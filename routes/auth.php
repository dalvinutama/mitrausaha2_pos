<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;

// ====================================================================
// AKSES GUEST (BELUM LOGIN)
// ====================================================================
Route::middleware('guest')->group(function () {
    
    // Hanya buka akses untuk halaman Login (Register & Forgot Password dimatikan)
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    
});

// ====================================================================
// AKSES AUTH (SUDAH LOGIN)
// ====================================================================
Route::middleware('auth')->group(function () {
    
    // Konfirmasi Sandi (Biasanya dipakai Laravel sebelum user melakukan aksi sangat sensitif)
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Update Sandi (Memungkinkan user mengubah sandinya sendiri via menu Profil)
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // Proses Keluar (Logout)
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
                
});