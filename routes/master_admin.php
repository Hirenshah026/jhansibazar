<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::any('/dashboard', [AdminController::class, 'dashboard']);
Route::get('/merchant-preview/{id}', [AdminController::class, 'show'])->name('merchant.preview');
Route::get('/merchant-id-preview/{id}', [AdminController::class, 'showPreview']);

// 3. Download ID ke liye (Same page but with print trigger)
Route::get('/download-id/{id}', [AdminController::class, 'downloadPreview']);