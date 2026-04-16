<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LansiaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return redirect("/login");
});

// Auth Routes
Route::get("/login", [AuthController::class, "showLogin"])->name("login");
Route::post("/login", [AuthController::class, "login"]);
Route::post("/logout", [AuthController::class, "logout"])->name("logout");

// Protected Routes
Route::middleware(["auth"])->group(function () {
    Route::get("/dashboard", [DashboardController::class, "index"])->name(
        "dashboard",
    );
    Route::resource("lansia", LansiaController::class);

    // Lansia export & import
    Route::get('lansia/export/excel', [LansiaController::class, 'export'])->name('lansia.export');
    Route::post('lansia/import', [LansiaController::class, 'import'])->name('lansia.import');

    // Admin Only
    Route::middleware("role:admin")->group(function () {
        Route::resource("users", UserController::class);
        Route::resource("roles", RoleController::class);
    });
});
