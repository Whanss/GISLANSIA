<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lansia;
use App\Exports\LansiaExport;
use App\Imports\LansiaImport;
use App\Services\GeocodingService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LansiaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:lansia.view')->only(['index', 'show']);
        $this->middleware('permission:lansia.create')->only(['create', 'store']);
        $this->middleware('permission:lansia.edit')->only(['edit', 'update']);
        $this->middleware('permission:lansia.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = Lansia::with("user")->latest();

        if ($request->filled("search")) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where("nama", "like", "%$search%")->orWhere(
                    "nik",
                    "like",
                    "%$search%",
                );
            });
        }

        if ($request->filled("status")) {
            $query->where("status", $request->status);
        }

        $lansia = $query->paginate(10)->withQueryString();

        $allLansiaForMap = Lansia::whereNotNull("latitude")
            ->whereNotNull("longitude")
            ->select(
                "id",
                "nama",
                "nik",
                "umur",
                "alamat",
                "desa",
                "kecamatan",
                "latitude",
                "longitude",
                "status",
            )
            ->get();

        return view("lansia.index", compact("lansia", "allLansiaForMap"));
    }

    public function create()
    {
        return view("lansia.create");
    }

    public function store(Request $request)
    {
        $canAutoConfirm = auth()->user()->can('lansia.auto_confirm');
        $canSetStatus   = auth()->user()->can('lansia.set_status');

        $rules = [
            "nama"          => "required|string|max:255",
            "nik"           => "required|string|unique:lansias,nik|max:16",
            "tanggal_lahir" => "required|date",
            "umur"          => "required|integer",
            "alamat"        => "required|string",
            "desa"          => "required|string",
            "kecamatan"     => "required|string",
            "kabupaten"     => "required|string",
            "provinsi"      => "required|string",
            "rt"            => "required|string",
            "rw"            => "required|string",
            "note"          => "nullable|string",
            "latitude"      => "nullable|numeric",
            "longitude"     => "nullable|numeric",
        ];

        if ($canSetStatus) {
            $rules["status"] = "required|in:pending,dikonfirmasi,ditolak,meninggal";
        }

        $validated = $request->validate($rules);

        if ($canSetStatus) {
            // Bisa pilih status manual
            $validated["status"] = $validated["status"] ?? "dikonfirmasi";
        } elseif ($canAutoConfirm) {
            // Langsung dikonfirmasi tanpa pilih status
            $validated["status"] = "dikonfirmasi";
        } else {
            // Default: pending, tunggu konfirmasi
            $validated["status"] = "pending";
        }

        $validated["user_id"] = auth()->id();
        $validated["pendata"] = auth()->user()->name;

        Lansia::create($validated);

        $msg = $validated["status"] === "pending"
            ? "Data berhasil dikirim dan menunggu konfirmasi."
            : "Data Lansia berhasil ditambahkan!";

        return redirect()->route("lansia.index")->with("success", $msg);
    }

    public function show(Lansia $lansia)
    {
        return view("lansia.show", compact("lansia"));
    }

    public function edit(Lansia $lansia)
    {
        return view("lansia.edit", compact("lansia"));
    }

    public function update(Request $request, Lansia $lansia)
    {
        $canSetStatus = auth()->user()->can('lansia.set_status');

        $rules = [
            "nama"          => "required|string|max:255",
            "nik"           => "required|string|max:16|unique:lansias,nik," . $lansia->id . ",id",
            "tanggal_lahir" => "required|date",
            "umur"          => "required|integer",
            "alamat"        => "required|string",
            "desa"          => "required|string",
            "kecamatan"     => "required|string",
            "kabupaten"     => "required|string",
            "provinsi"      => "required|string",
            "rt"            => "required|string",
            "rw"            => "required|string",
            "note"          => "nullable|string",
            "latitude"      => "nullable|numeric",
            "longitude"     => "nullable|numeric",
            "status"        => "required|in:pending,dikonfirmasi,ditolak,meninggal",
        ];

        $validated = $request->validate($rules);

        // Hanya yang punya lansia.set_status yang bisa ubah status
        if (!$canSetStatus) {
            unset($validated["status"]);
        }

        // Jika status diubah ke ditolak, hapus koordinat dari peta
        if (isset($validated["status"]) && $validated["status"] === "ditolak") {
            $validated["latitude"] = null;
            $validated["longitude"] = null;
        }

        $lansia->update($validated);

        return redirect()->route("lansia.index")->with("success", "Data Lansia berhasil diperbarui!");
    }

    public function destroy(Lansia $lansia)
    {
        $lansia->delete();
        return redirect()
            ->route("lansia.index")
            ->with("success", "Data berhasil dihapus");
    }

    // ── KONFIRMASI (admin only) ───────────────────────────────────────────────

    public function konfirmasiIndex()
    {
        abort_unless(auth()->user()->can('lansia.set_status'), 403);

        $pending = Lansia::with("user")
            ->where("status", "pending")
            ->latest()
            ->paginate(15);

        $totalPending = Lansia::where("status", "pending")->count();

        return view("lansia.konfirmasi", compact("pending", "totalPending"));
    }

    public function konfirmasi(Lansia $lansia)
    {
        abort_unless(auth()->user()->can('lansia.set_status'), 403);
        $lansia->update(["status" => "dikonfirmasi"]);
        return back()->with(
            "success",
            "Data " . $lansia->nama . " berhasil dikonfirmasi.",
        );
    }

    public function tolak(Lansia $lansia)
    {
        abort_unless(auth()->user()->can('lansia.set_status'), 403);
        // Hapus koordinat agar tidak muncul di peta
        $lansia->update([
            "status" => "ditolak",
            "latitude" => null,
            "longitude" => null,
        ]);
        return back()->with(
            "success",
            "Data " . $lansia->nama . " telah ditolak dan dihapus dari peta.",
        );
    }

    public function meninggal(Lansia $lansia)
    {
        abort_unless(auth()->user()->can('lansia.set_status'), 403);
        $lansia->update(["status" => "meninggal"]);
        return back()->with(
            "success",
            "Status " . $lansia->nama . " diubah menjadi Meninggal.",
        );
    }

    // ── GEOCODE API ───────────────────────────────────────────────────────────

    public function geocode(Request $request)
    {
        $request->validate([
            "desa" => "required|string",
            "kecamatan" => "required|string",
            "kabupaten" => "required|string",
            "provinsi" => "nullable|string",
        ]);

        $coordinates = GeocodingService::geocodeWithLocality(
            $request->desa,
            $request->kecamatan,
            $request->kabupaten,
            $request->provinsi ?? "Nusa Tenggara Barat",
        );

        if ($coordinates) {
            return response()->json([
                "success" => true,
                "latitude" => $coordinates["latitude"],
                "longitude" => $coordinates["longitude"],
                "display_name" => $coordinates["display_name"] ?? null,
                "strategy" => $coordinates["strategy"] ?? null,
            ]);
        }

        return response()->json(
            [
                "success" => false,
                "message" =>
                    "Alamat tidak ditemukan. Pastikan Desa, Kecamatan, dan Kabupaten sudah benar.",
            ],
            404,
        );
    }

    public function searchLocality(Request $request)
    {
        $request->validate(["query" => "required|string"]);
        $coordinates = GeocodingService::geocodeAddress($request->query);
        return response()->json([
            "success" => (bool) $coordinates,
            "data" => $coordinates,
        ]);
    }

    public function export()
    {
        return Excel::download(
            new LansiaExport(),
            "data_lansia_" . now()->format("Y-m-d_H-i-s") . ".xlsx",
        );
    }

    public function import(Request $request)
    {
        $request->validate(["file" => "required|mimes:xlsx,xls,csv"]);
        try {
            Excel::import(new LansiaImport(), $request->file("file"));
            return redirect()
                ->route("lansia.index")
                ->with("success", "Import data berhasil!");
        } catch (\Exception $e) {
            return redirect()
                ->route("lansia.index")
                ->with("error", "Import gagal: " . $e->getMessage());
        }
    }
}
