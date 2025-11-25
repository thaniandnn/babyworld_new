<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Auth\RegisterController;


/*
|--------------------------------------------------------------------------
| HOME → REDIRECT KE LOGIN-REGISTER.PAGE
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login-register.page');
})->name('home');


/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

// Login + Register Page (nama route yang kamu minta!)
Route::get('/login-register', [AuthController::class, 'loginRegister'])
    ->name('login-register.page');

// Register
Route::post('/register', [RegisterController::class, 'process'])
    ->name('register.process');

// Login
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
| PUBLIC PAGES
|--------------------------------------------------------------------------
*/

Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/compare', [PageController::class, 'compare'])->name('compare');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/wishlist', [PageController::class, 'wishlist'])->name('wishlist');
Route::get('/chat', [PageController::class, 'chat'])->name('chat');


/*
|--------------------------------------------------------------------------
| CART
|--------------------------------------------------------------------------
*/

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');

// Apply voucher (dari versi temanmu)
Route::post('/cart/apply-voucher', [CartController::class, 'applyVoucher'])
    ->name('cart.applyVoucher');


/*
|--------------------------------------------------------------------------
| ACCOUNT + CHECKOUT (Optional Login → Sesuai Versi Temanmu)
|--------------------------------------------------------------------------
*/

Route::middleware([])->group(function () {

    /*
    |------------------------------
    | CHECKOUT
    |------------------------------
    */
    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout.index');

    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])
        ->name('checkout.place');


    /*
    |------------------------------
    | ACCOUNT
    |------------------------------
    */
    Route::get('/accounts', [AccountController::class, 'index'])
        ->name('accounts');

    Route::post('/accounts/update-profile', [AccountController::class, 'updateProfile'])
        ->name('accounts.updateProfile');

    Route::post('/accounts/update-address', [AccountController::class, 'updateAddress'])
        ->name('accounts.updateAddress');

    Route::post('/accounts/change-password', [AccountController::class, 'changePassword'])
        ->name('accounts.changePassword');

    Route::delete('/accounts/delete', [AccountController::class, 'deleteAccount'])
        ->name('accounts.delete');
});
