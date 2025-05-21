<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\Auth\LoginController;

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
