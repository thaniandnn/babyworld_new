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
| AUTH VIEWS
|--------------------------------------------------------------------------
*/

// Show login-register page
Route::get('/login-register', function () {
    return view('login-register');
})->name('login-register.page');

/*
|--------------------------------------------------------------------------
| REGISTER
|--------------------------------------------------------------------------
*/

// Register user (RegisterController)
Route::post('/register', [RegisterController::class, 'process'])
    ->name('register.process');


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

// Login process (AuthController)
Route::post('/login', [AuthController::class, 'loginProcess'])
    ->name('login.process');

// Logout
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
| NAVBAR PAGES
|--------------------------------------------------------------------------
*/

Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/index', [PageController::class, 'index'])->name('index');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/accounts', [PageController::class, 'accounts'])->name('accounts');
Route::get('/compare', [PageController::class, 'compare'])->name('compare');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/wishlist', [PageController::class, 'wishlist'])->name('wishlist');
Route::get('/chat', [PageController::class, 'chat'])->name('chat');


/*
|--------------------------------------------------------------------------
| ACCOUNT MANAGEMENT (AFTER LOGIN)
|--------------------------------------------------------------------------
*/

Route::post('/accounts/update-profile', [AccountController::class, 'updateProfile'])
    ->name('accounts.updateProfile');

Route::post('/accounts/update-address', [AccountController::class, 'updateAddress'])
    ->name('accounts.updateAddress');

Route::post('/accounts/change-password', [AccountController::class, 'changePassword'])
    ->name('accounts.changePassword');


/*
|--------------------------------------------------------------------------
| CART
|--------------------------------------------------------------------------
*/

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
