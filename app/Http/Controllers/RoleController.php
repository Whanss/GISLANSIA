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
        $this->middleware('permission:role.view')->only(['index']);
        $this->middleware('permission:role.create')->only(['create', 'store']);
        $this->middleware('permission:role.edit')->only(['edit', 'update']);
        $this->middleware('permission:role.delete')->only(['destroy']);
    }

    public function index()
    {
        $roles = Role::with("permissions")->get();
        $users = \App\Models\User::with('roles')->orderBy('name')->get();
        return view("admin.roles.index", compact("roles", "users"));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get();
        return view("admin.roles.create", compact("permissions"));
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
        $permissions = Permission::orderBy('name')->get();
        $users       = \App\Models\User::with('roles')->orderBy('name')->get();

        // Permission role.* dan permission.* yang dimiliki user saat ini — tidak boleh dicabut dari role sendiri
        $currentUserRolePermissions = auth()->user()->getAllPermissions()
            ->filter(fn($p) => str_starts_with($p->name, 'role.') || str_starts_with($p->name, 'permission.'))
            ->pluck('id');

        // Cek apakah role yang diedit adalah role milik user yang sedang login
        $isOwnRole = auth()->user()->roles->contains('id', $role->id);

        return view("admin.roles.edit", compact("role", "permissions", "users", "currentUserRolePermissions", "isOwnRole"));
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

        $submittedPermissions = collect($validated['permissions'] ?? []);

        // Protect: jika user mengedit role miliknya sendiri,
        // permission role.* dan permission.* yang dia miliki tidak boleh dicabut
        $isOwnRole = auth()->user()->roles->contains('id', $role->id);
        if ($isOwnRole) {
            $lockedPermissions = auth()->user()->getAllPermissions()
                ->filter(fn($p) => str_starts_with($p->name, 'role.') || str_starts_with($p->name, 'permission.'))
                ->pluck('id');
            $submittedPermissions = $submittedPermissions->merge($lockedPermissions)->unique();
        }

        $permissions = Permission::whereIn('id', $submittedPermissions)->get();
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')->with('success', 'Peran berhasil diperbarui');
    }

    public function destroy(Role $role)
    {
        // Protect: tidak bisa hapus role milik sendiri
        if (auth()->user()->roles->contains('id', $role->id)) {
            return redirect()->route('roles.index')->with('error', 'Tidak dapat menghapus peran yang sedang Anda gunakan.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Peran berhasil dihapus');
    }

    public function assignUserRole(Request $request, \App\Models\User $user)
    {
        $request->validate([
            'role' => 'nullable|string|exists:roles,name',
        ]);

        // Prevent mengubah role diri sendiri
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Tidak dapat mengubah peran akun sendiri.');
        }

        if (empty($request->role)) {
            // Hapus dari role — tidak bisa hapus diri sendiri (sudah dicek di atas)
            $user->syncRoles([]);
        } else {
            // Jika user sudah punya role lain, tolak — harus hapus dulu
            $existingRole = $user->roles->first();
            if ($existingRole && $existingRole->name !== $request->role) {
                return redirect()->back()->with('error',
                    $user->name . ' sudah memiliki peran "' . ucfirst($existingRole->name) . '". Hapus peran tersebut terlebih dahulu.'
                );
            }
            $user->syncRoles([$request->role]);
        }

        return redirect()->back()->with('success', 'Peran ' . $user->name . ' berhasil diperbarui.');
    }
}
