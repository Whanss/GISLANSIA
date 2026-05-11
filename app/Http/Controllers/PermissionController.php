<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:role.view')->only(['index']);
        $this->middleware('permission:role.create')->only(['store']);
        $this->middleware('permission:role.delete')->only(['destroy']);
    }

    public function index()
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($p) {
            return explode('.', $p->name)[0]; // group by resource (lansia, user, role)
        });
        return view('admin.permissions.index', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ], [
            'name.required' => 'Nama izin wajib diisi.',
            'name.unique'   => 'Nama izin sudah ada.',
        ]);

        Permission::create(['name' => $validated['name']]);

        return redirect()->route('permissions.index')->with('success', 'Izin berhasil ditambahkan');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Izin berhasil dihapus');
    }
}
