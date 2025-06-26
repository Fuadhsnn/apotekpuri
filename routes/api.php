<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SatusehatController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// SatuSehat API Routes
Route::prefix('satusehat')->group(function () {
    Route::get('/medications', [SatusehatController::class, 'searchMedications']);
    Route::get('/medications/{id}', [SatusehatController::class, 'getMedicationDetail']);
    Route::post('/medications/import', [SatusehatController::class, 'importMedication']);
});
