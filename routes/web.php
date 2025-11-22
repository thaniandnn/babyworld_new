<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

// SHOW login & register page
Route::get('/login-register', [AuthController::class, 'loginRegister'])
    ->name('login-register.page');

// REGISTER
Route::post('/register', [RegisterController::class, 'process'])
    ->name('register.process');

// LOGIN
Route::post('/login', [AuthController::class, 'loginProcess'])
    ->name('login.process');

// LOGOUT
Route::get('/logout', [AuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| FORGOT PASSWORD
|--------------------------------------------------------------------------
*/
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])
    ->name('forgot.password');

Route::post('/forgot-password', [AuthController::class, 'resetPassword'])
    ->name('reset.password');


/*
|--------------------------------------------------------------------------
| PUBLIC PAGES (NO LOGIN REQUIRED)
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/index', [PageController::class, 'index'])->name('index');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/compare', [PageController::class, 'compare'])->name('compare');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/wishlist', [PageController::class, 'wishlist'])->name('wishlist');
Route::get('/chat', [PageController::class, 'chat'])->name('chat');


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (USER MUST BE LOGGED IN)
|--------------------------------------------------------------------------
*/
Route::middleware([])->group(function () {

    // Account Dashboard (READ)
    Route::get('/accounts', [AccountController::class, 'index'])
        ->name('accounts');

    // Update Profile (UPDATE)
    Route::post('/accounts/update-profile', [AccountController::class, 'updateProfile'])
        ->name('accounts.updateProfile');

    // Update Address (UPDATE)
    Route::post('/accounts/update-address', [AccountController::class, 'updateAddress'])
        ->name('accounts.updateAddress');

    // Change Password (UPDATE)
    Route::post('/accounts/change-password', [AccountController::class, 'changePassword'])
        ->name('accounts.changePassword');

    // Delete Account (DELETE)
    Route::delete('/accounts/delete', [AccountController::class, 'deleteAccount'])
        ->name('accounts.delete');
});


/*
|--------------------------------------------------------------------------
| CART ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
