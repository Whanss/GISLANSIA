<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        // Admin user — firstOrCreate agar aman dijalankan ulang
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('admin123'),
            ]
        );
        $admin->syncRoles(['super admin']);

        // Petugas user
        $petugas = User::firstOrCreate(
            ['email' => 'petugas@gmail.com'],
            [
                'name'     => 'Petugas',
                'password' => Hash::make('petugas123'),
            ]
        );
        $petugas->syncRoles(['petugas']);
    }
}
