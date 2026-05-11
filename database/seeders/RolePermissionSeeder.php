<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Buat semua permission
        $permissions = [
            // Lansia
            'lansia.view',
            'lansia.create',
            'lansia.edit',
            'lansia.delete',
            'lansia.auto_confirm',  // data yg diinput langsung dikonfirmasi
            'lansia.set_status',    // bisa ubah status (konfirmasi/tolak/meninggal)
            // User
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',
            // Role
            'role.view',
            'role.create',
            'role.edit',
            'role.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Admin dapat semua permission
        $admin = Role::firstOrCreate(['name' => 'super admin']);
        $admin->syncPermissions(Permission::all());

        // Petugas hanya bisa lihat & input lansia
        $petugas = Role::firstOrCreate(['name' => 'petugas']);
        $petugas->syncPermissions(['lansia.view', 'lansia.create', 'lansia.edit']);
    }
}
