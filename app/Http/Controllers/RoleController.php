<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware("role:admin");
    }

    public function index()
    {
        $roles = Role::with("permissions")->get();
        return view("admin.roles.index", compact("roles"));
    }

    public function create()
    {
        return view("admin.roles.create");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array'
        ], [
            'name.required' => 'Nama Peran wajib diisi.',
            'name.unique' => 'Nama Peran sudah ada.',
        ]);

        $role = Role::create(['name' => $validated['name']]);

        if (!empty($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Peran berhasil dibuat');
    }

    public function edit(Role $role)
    {
        return view("admin.roles.edit", compact("role"));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ], [
            'name.required' => 'Nama Peran wajib diisi.',
            'name.unique' => 'Nama Peran sudah ada.',
        ]);

        $role->update(['name' => $validated['name']]);

        if (!empty($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('roles.index')->with('success', 'Peran berhasil diperbarui');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'admin' || $role->name === 'user') {
            return redirect()->route('roles.index')->with('error', 'Tidak dapat menghapus peran bawaan sistem');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Peran berhasil dihapus');
    }
}

