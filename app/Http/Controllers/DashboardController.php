<?php
namespace App\Http\Controllers;

use App\Models\Lansia;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            "total_lansia" => Lansia::count(),
            "lansia_today" => Lansia::whereDate("created_at", today())->count(),
            "petugas_active" => User::role("petugas")->count(),
        ];

        return view("dashboard.index", compact("stats"));
    }
}
