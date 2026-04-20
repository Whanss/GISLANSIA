<?php
namespace App\Http\Controllers;

use App\Models\Lansia;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Stats utama ───────────────────────────────────────────────
        $totalLansia = Lansia::count();
        $totalDikonfirmasi = Lansia::where("status", "dikonfirmasi")->count();
        $totalPending = Lansia::where("status", "pending")->count();
        $totalDitolak = Lansia::where("status", "ditolak")->count();
        $totalMeninggal = Lansia::where("status", "meninggal")->count();
        $totalBerKoordinat = Lansia::whereNotNull("latitude")
            ->whereNotNull("longitude")
            ->count();
        $totalPetugas = User::count();
        $tambahHariIni = Lansia::whereDate("created_at", today())->count();
        $tambahBulanIni = Lansia::whereMonth("created_at", now()->month)
            ->whereYear("created_at", now()->year)
            ->count();

        $stats = [
            "total_lansia" => $totalLansia,
            "total_dikonfirmasi" => $totalDikonfirmasi,
            "total_pending" => $totalPending,
            "total_ditolak" => $totalDitolak,
            "total_meninggal" => $totalMeninggal,
            "total_berkoordinat" => $totalBerKoordinat,
            "total_petugas" => $totalPetugas,
            "tambah_hari_ini" => $tambahHariIni,
            "tambah_bulan_ini" => $tambahBulanIni,
            "persen_koordinat" =>
                $totalLansia > 0
                    ? round(($totalBerKoordinat / $totalLansia) * 100)
                    : 0,
        ];

        // ── Semua data untuk peta + search (semua status dikonfirmasi) ─────────
        $allLansiaForMap = Lansia::where("status", "dikonfirmasi")
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

        // ── 5 data terbaru ────────────────────────────────────────────
        $recentLansia = Lansia::with("user")->latest()->take(5)->get();

        // ── Distribusi per kecamatan (top 7) ─────────────────────────
        $distribusiKecamatan = Lansia::select(
            "kecamatan",
            DB::raw("count(*) as total"),
        )
            ->whereNotNull("kecamatan")
            ->where("kecamatan", "!=", "")
            ->groupBy("kecamatan")
            ->orderByDesc("total")
            ->take(7)
            ->get();

        return view(
            "dashboard.index",
            compact(
                "stats",
                "allLansiaForMap",
                "recentLansia",
                "distribusiKecamatan",
            ),
        );
    }
}
