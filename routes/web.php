<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get("/", fn () => redirect()->route("login"));

// Guest (belum login) - FR-01
Route::middleware("guest")->group(function () {
    Route::get("register", [RegisteredUserController::class, "create"])->name("register");
    Route::post("register", [RegisteredUserController::class, "store"]);
    Route::get("login", [AuthenticatedSessionController::class, "create"])->name("login");
    Route::post("login", [AuthenticatedSessionController::class, "store"]);
});

// Wajib login
Route::middleware("auth")->group(function () {
    Route::post("logout", [AuthenticatedSessionController::class, "destroy"])->name("logout");

    Route::get("dashboard", [DashboardController::class, "index"])->name("dashboard");

    // FR-02: profil
    Route::get("profile", [ProfileController::class, "edit"])->name("profile.edit");
    Route::patch("profile", [ProfileController::class, "update"])->name("profile.update");

    // FR-04: cari & lihat material (semua user login bisa lihat, aksi ajukan hanya utk demand)
    Route::get("materials", [MaterialController::class, "index"])->name("materials.index");
    Route::get("materials/{material}", [MaterialController::class, "show"])->name("materials.show");

    // FR-03: kelola material - khusus Supply
    Route::middleware("role:supply")->group(function () {
        Route::get("my-materials", [MaterialController::class, "myMaterials"])->name("materials.my");
        Route::get("my-materials/create", [MaterialController::class, "create"])->name("materials.create");
        Route::post("my-materials", [MaterialController::class, "store"])->name("materials.store");
        Route::get("my-materials/{material}/edit", [MaterialController::class, "edit"])->name("materials.edit");
        Route::put("my-materials/{material}", [MaterialController::class, "update"])->name("materials.update");
        Route::delete("my-materials/{material}", [MaterialController::class, "destroy"])->name("materials.destroy");
    });

    // FR-06/08: Demand mengajukan transaksi atas sebuah material
    Route::post("materials/{material}/transactions", [TransactionController::class, "store"])
        ->middleware("role:demand")->name("transactions.store");

    // FR-10/12: riwayat & detail transaksi (Supply & Demand)
    Route::get("transactions", [TransactionController::class, "index"])->name("transactions.index");
    Route::get("transactions/{transaction}", [TransactionController::class, "show"])->name("transactions.show");

    // FR-09: approve/reject - khusus Supply pemilik transaksi (dicek di controller)
    Route::post("transactions/{transaction}/approve", [TransactionController::class, "approve"])->name("transactions.approve");
    Route::post("transactions/{transaction}/reject", [TransactionController::class, "reject"])->name("transactions.reject");

    // FR-11: konfirmasi oleh kedua pihak
    Route::post("transactions/{transaction}/confirm", [TransactionController::class, "confirm"])->name("transactions.confirm");
    Route::post("transactions/{transaction}/cancel", [TransactionController::class, "cancel"])->name("transactions.cancel");

    // FR-07: notifikasi
    Route::get("notifications", [NotificationController::class, "index"])->name("notifications.index");
});