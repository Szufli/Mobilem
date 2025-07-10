<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnnouncementController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('announcements')->group(function () {
    Route::get('/', [AnnouncementController::class, 'index']); // lista
    Route::post('/', [AnnouncementController::class, 'store']); // tworzenie
    Route::get('/{id}', [AnnouncementController::class, 'show']); // pobranie pojedynczego
    Route::put('/{id}', [AnnouncementController::class, 'update']); // aktualizacja
    Route::delete('/{id}', [AnnouncementController::class, 'destroy']); // usunięcie
});