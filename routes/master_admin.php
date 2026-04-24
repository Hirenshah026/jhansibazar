<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/login-post', [AdminController::class, 'login']);
Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

Route::any('/dashboard', [AdminController::class, 'dashboard']);
Route::any('/add-shop', [AdminController::class, 'add_shop']);
Route::get('/merchant-preview/{id}', [AdminController::class, 'show'])->name('merchant.preview');
Route::get('/merchant-id-preview/{id}', [AdminController::class, 'showPreview']);

// 3. Download ID ke liye (Same page but with print trigger)
Route::get('/download-id/{id}', [AdminController::class, 'downloadPreview']);
Route::post('/store-shop', [AdminController::class, 'shop_store']);