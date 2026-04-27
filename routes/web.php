<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ItemStepController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;

// ─────────────────────────────────────────────
// Cache & View Clear Utilities
// ─────────────────────────────────────────────
Route::get('/view-clear', function () {
    Artisan::call('view:clear');
    return redirect('/');
});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    File::cleanDirectory(storage_path('logs'));
    return redirect('/');
});

// user route 
Route::post('/login-check', [UserController::class, 'ajaxLogin'])->name('login.check');
Route::post('/save-mobile', [UserController::class, 'save_mobile'])->name('save.mobile');
Route::any('/user-profile', [UserController::class, 'dashboard'])->name('user.dashboard');
Route::any('/user-logout', [UserController::class, 'logout']);

// Follow/Unfollow Route
Route::post('/follow-user', [UserController::class, 'toggleFollow'])->name('follow.user');
Route::post('/spin/decrement', [FrontController::class, 'decrementSpin'])->name('spin.decrement');

// ─────────────────────────────────────────────
// Public Routes
// ─────────────────────────────────────────────
Route::get('/',                          [FrontController::class, 'home'])->name('home');
Route::get('/home',                      [FrontController::class, 'home'])->name('home1');
Route::get('/rozana',                    [FrontController::class, 'rozana'])->name('rozana');
Route::get('/spin',                      [FrontController::class, 'spin'])->name('spin');
Route::get('/spin/{slug}',               [FrontController::class, 'spin']);

Route::get('/wallet',                    [FrontController::class, 'wallet'])->name('wallet');
Route::get('/shopprofile',               [FrontController::class, 'shopprofile'])->name('shopprofile');
Route::get('/shopprofile/{slug}',        [FrontController::class, 'shopprofile']);
Route::get('/shopprofile-details/{slug}',[FrontController::class, 'shopprofile_details'])->name('shopprofile_details');
Route::get('/notifications',             [FrontController::class, 'notifications'])->name('notifications');
Route::get('/healthcard',                [FrontController::class, 'healthcard'])->name('healthcard');
Route::get('/shop-register',             [FrontController::class, 'add_shop'])->name('add_shop');
Route::get('/login',                     [FrontController::class, 'shop_login'])->name('shop_login');
Route::get('/set-pin-shop',                     [FrontController::class, 'shop_set_pin'])->name('shop_set_pin');
Route::post('/update-shop-pin', [FrontController::class, 'shop_updatePin'])->name('settings.update-pin');
// Shop ID Card view karne ke liye
Route::get('/shop/id-card/{id}', [ShopController::class, 'showIdCard'])->name('shops.idcard');
Route::get('/shop/scanner', [ShopController::class, 'scanner'])->name('shops.scanner');
// ─────────────────────────────────────────────
// Auth Routes
// ─────────────────────────────────────────────
Route::any('/shop-login-ajax',  [FrontController::class, 'shop_login_ajax'])->name('shop_login_ajax');
Route::any('/shop-logout',      [FrontController::class, 'shop_logout'])->name('shop_logout');

// ─────────────────────────────────────────────
// Shop Registration Steps
// ─────────────────────────────────────────────
Route::post('/save-step1',    [ShopController::class, 'saveStep1'])->name('shop.step1');
Route::post('/save-step2',    [ShopController::class, 'saveStep2'])->name('shop.step2');
Route::post('/final-submit',  [ShopController::class, 'finalSubmit'])->name('shop.final');

Route::post('/shops/update', [ShopController::class, 'update'])->name('shops.update');
// ─────────────────────────────────────────────
// Shop Photo Delete Routes
// ─────────────────────────────────────────────
Route::post('/shop/delete-item-photo', [ShopController::class, 'deleteItemPhoto'])->name('shop.photo.item.delete');
Route::post('/shop/delete/{id}',       [ShopController::class, 'deleteShop'])->name('shop.delete');
Route::post('/shop-review/store', [FrontController::class, 'storeReview'])->name('shop.review.store');


// ─────────────────────────────────────────────
// Authenticated Routes (shop user only)
// ─────────────────────────────────────────────
Route::middleware(['authcheckuser'])->group(function () {
    Route::get('/account',                   [FrontController::class, 'account'])->name('account');
    Route::get('/service-register',          [FrontController::class, 'add_service'])->name('add_service');
    Route::get('/item-register',             [FrontController::class, 'add_item'])->name('add_item');

     // Spinner Offers Routes
    Route::get('/shop/offers', [ShopController::class, 'manageOffers'])->name('shop.offers.manage');
    Route::post('/shop/offers/save', [ShopController::class, 'saveOffer'])->name('shop.offers.save');
    Route::post('/shop/offers/delete', [ShopController::class, 'deleteOffer'])->name('shop.offers.delete');

    // ─────────────────────────────────────────────
    // Item & Service Routes
    // ─────────────────────────────────────────────
    Route::post('/item/save-step',       [ItemStepController::class, 'saveStep']);
    Route::delete('/item-delete/{id}',   [FrontController::class, 'deleteItem'])->name('item.delete');
    Route::post('/services/bulk-store',  [ServiceController::class, 'bulkStore'])->name('services.bulk-store');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::post('/categories/update', [CategoryController::class, 'update'])->name('categories.update'); // Ye missing tha
    Route::post('/categories/delete', [CategoryController::class, 'destroy'])->name('categories.delete');

    Route::any('/services/list', [ServiceController::class, 'list_show']);
    Route::get('/api/services-list', [ServiceController::class, 'getServices'])->name('services.api.list');
    Route::get('/services/fetch/{id}', [ServiceController::class, 'fetch']);
    Route::post('/services/update/{id}', [ServiceController::class, 'update']);
    Route::delete('/services/delete/{id}', [ServiceController::class, 'destroy']);

    //logo
    Route::post('/shop/update-banner', [ShopController::class, 'updateBanner'])->name('shop.update.banner.local');

    // Logo update karne ke liye
    Route::post('/shop/update-logo', [ShopController::class, 'updateLogo'])->name('shop.update.logo.local');
    Route::post('/shop-photos-update', [ShopController::class, 'updateShopPhotos'])->name('shop.photos.update');
    Route::post('/shop-photos-delete', [ShopController::class, 'deleteShopPhoto'])->name('shop.photos.delete');
});

