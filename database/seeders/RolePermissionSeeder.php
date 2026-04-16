<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        Role::create(["name" => "admin"]);
        Role::create(["name" => "petugas"]);
    }
}
