<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LansiaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get("/", fn() => redirect("/login"));

// Auth
Route::get("/login", [AuthController::class, "showLogin"])->name("login");
Route::post("/login", [AuthController::class, "login"]);
Route::post("/logout", [AuthController::class, "logout"])->name("logout");

Route::middleware(["auth"])->group(function () {
    Route::get("/dashboard", [DashboardController::class, "index"])->name(
        "dashboard",
    );

    // Lansia resource
    Route::resource("lansia", LansiaController::class, [
        "parameters" => ["lansia" => "lansia"],
    ]);

    // Export & Import
    Route::get("lansia/export/excel", [
        LansiaController::class,
        "export",
    ])->name("lansia.export");
    Route::post("lansia/import", [LansiaController::class, "import"])->name(
        "lansia.import",
    );

    // API geocoding
    Route::post("api/geocode", [LansiaController::class, "geocode"])->name(
        "api.geocode",
    );
    Route::get("api/search-locality", [
        LansiaController::class,
        "searchLocality",
    ])->name("api.search-locality");

    // Konfirmasi — hanya admin
    Route::middleware("role:admin")->group(function () {
        Route::get("konfirmasi", [
            LansiaController::class,
            "konfirmasiIndex",
        ])->name("konfirmasi.index");
        Route::post("lansia/{lansia}/konfirmasi", [
            LansiaController::class,
            "konfirmasi",
        ])->name("lansia.konfirmasi");
        Route::post("lansia/{lansia}/tolak", [
            LansiaController::class,
            "tolak",
        ])->name("lansia.tolak");
        Route::post("lansia/{lansia}/meninggal", [
            LansiaController::class,
            "meninggal",
        ])->name("lansia.meninggal");

        Route::resource("users", UserController::class);
        Route::resource("roles", RoleController::class);
    });
});
