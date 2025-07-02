<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\QRCodeController;

// Rute autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rute untuk kasir
Route::middleware(['auth'])->group(function () {
    Route::get('/kasir', [KasirController::class, 'index'])
        ->middleware('role:kasir')
        ->name('kasir.index');

    Route::get('/kasir/search', [KasirController::class, 'search'])
        ->middleware('role:kasir')
        ->name('kasir.search');

    Route::get('/kasir/obat/{id}', [KasirController::class, 'getObat'])
        ->middleware('role:kasir')
        ->name('kasir.getObat');
});

// Rute default
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect('/admin')
            : redirect('/kasir');
    }
    return redirect('/login');
});

// Route untuk admin (Filament)
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Route admin sudah ditangani oleh Filament
});
Route::get('/kasir/search', [KasirController::class, 'search'])->name('kasir.search');
Route::get('/kasir/obat/{id}', [KasirController::class, 'getObat'])->name('kasir.getObat');

// Route untuk pelanggan
Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
Route::get('/pelanggan/obat', [PelangganController::class, 'getObat'])->name('pelanggan.obat');
Route::get('/pelanggan/obat/{id}', [App\Http\Controllers\PelangganController::class, 'show'])->name('pelanggan.obat.show');
Route::get('/contoh', function () {
    return view('contoh-kasir');
});
Route::post('/kasir/checkout', [KasirController::class, 'checkout'])->name('kasir.checkout')->middleware('auth');

// Route untuk QR Code
Route::get('/qrcode', [QRCodeController::class, 'generateQRCode'])->name('qrcode.generate');

// Route untuk print struk
Route::get('/kasir/print/{id}', [KasirController::class, 'printStruk'])->name('kasir.print')->middleware('auth');
