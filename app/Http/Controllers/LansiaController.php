<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lansia;
use App\Exports\LansiaExport;
use App\Imports\LansiaImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LansiaController extends Controller
{
    public function index(Request $request)
    {
        $query = Lansia::latest();

        // Search by name or NIK
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('nama', 'like', "%$search%")
                  ->orWhere('nik', 'like', "%$search%");
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $lansia = $query->paginate(10);
        return view("lansia.index", compact("lansia"));
    }

    public function export()
    {
        return Excel::download(new LansiaExport, 'data_lansia_' . now()->format('Y-m-d_H-i-s') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new LansiaImport, $request->file('file'));
            return redirect()->route('lansia.index')->with('success', 'Import data berhasil! Silahkan refresh halaman.');
        } catch (\Exception $e) {
            return redirect()->route('lansia.index')->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }
    public function create()
    {
        return view("lansia.create");
    }

    public function store(Request $request)
    {
        // Validation & storage logic
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
        // Update logic
    }

    public function destroy(Lansia $lansia)
    {
        $lansia->delete();
        return redirect()->route('lansia.index')->with('success', 'Data berhasil dihapus');
    }
}

