<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
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
}
