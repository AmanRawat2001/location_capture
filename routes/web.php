<?php

use App\Http\Controllers\API\V1\LocationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LocationController::class, 'index'])->name('location.form');
Route::post('/submit-location', [LocationController::class, 'store'])->name('location.store');
Route::get('/download-pdf', [LocationController::class, 'downloadPdf'])->name('location.download');
Route::get('/success', [LocationController::class, 'success'])->name('location.success');
