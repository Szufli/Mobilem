<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnnouncementController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('announcements')->group(function () {
    Route::get('/', [AnnouncementController::class, 'index']); 
    Route::post('/', [AnnouncementController::class, 'store']); 
    Route::put('/{id}', [AnnouncementController::class, 'update']); 
    Route::delete('/{id}', [AnnouncementController::class, 'destroy']); 
});
