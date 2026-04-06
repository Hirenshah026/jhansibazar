<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FrontController;

use App\Http\Controllers\ShopController;
use App\Http\Controllers\ItemStepController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CategoryController;
///////============///////

Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
     return redirect("/");
});
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('view:clear');
    File::cleanDirectory(storage_path('logs'));
    return redirect("/");
});


// Route::any('/', [FrontController::class, 'index']);
Route::get('/', [FrontController::class, 'home'])->name('home');
Route::delete('/item-delete/{id}', [FrontController::class, 'deleteItem'])->name('item.delete');
Route::get('/home', [FrontController::class, 'home'])->name('home1');
Route::get('/rozana', [FrontController::class, 'rozana'])->name('rozana');
Route::get('/spin', [FrontController::class, 'spin'])->name('spin');
Route::get('/spin/{slug}', [FrontController::class, 'spin']);
Route::get('/account', [FrontController::class, 'account'])->name('account');
Route::get('/wallet', [FrontController::class, 'wallet'])->name('wallet');
Route::get('/shopprofile', [FrontController::class, 'shopprofile'])->name('shopprofile');
Route::get('/shopprofile/{slug}', [FrontController::class, 'shopprofile'])->name('shopprofile');
Route::get('/shopprofile-details/{slug}', [FrontController::class, 'shopprofile_details'])->name('shopprofile_details');
Route::get('/notifications', [FrontController::class, 'notifications'])->name('notifications'); 
Route::get('/healthcard', [FrontController::class, 'healthcard'])->name('healthcard');
Route::get('/shop-register', [FrontController::class, 'add_shop'])->name('add_shop');
Route::get('/login', [FrontController::class, 'shop_login'])->name('shop_login');
Route::get('/service-register', [FrontController::class, 'add_service'])->name('add_service');
Route::get('/item-register', [FrontController::class, 'add_item'])->name('add_item');
Route::any('/shop-login-ajax', [FrontController::class, 'shop_login_ajax'])->name('shop_login_ajax');
Route::any('/shop-logout', [FrontController::class, 'shop_logout'])->name('shop_logout');

Route::post('/save-step1', [ShopController::class, 'saveStep1']);
Route::post('/save-step2', [ShopController::class, 'saveStep2']);
Route::post('/final-submit', [ShopController::class, 'finalSubmit']);

Route::post('/item/save-step', [ItemStepController::class, 'saveStep']);


Route::post('/services/bulk-store', [ServiceController::class, 'bulkStore'])->name('services.bulk-store');

Route::middleware(['authcheckuser'])->group(function () {
    // Spinner Offers Routes
    Route::get('/shop/offers', [ShopController::class, 'manageOffers'])->name('shop.offers.manage');
    Route::post('/shop/offers/save', [ShopController::class, 'saveOffer'])->name('shop.offers.save');
    Route::post('/shop/offers/delete', [ShopController::class, 'deleteOffer'])->name('shop.offers.delete');

    // Category Management Routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::post('/categories/update', [CategoryController::class, 'update'])->name('categories.update'); // Ye missing tha
    Route::post('/categories/delete', [CategoryController::class, 'destroy'])->name('categories.delete');
});
