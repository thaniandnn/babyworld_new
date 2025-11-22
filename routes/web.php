<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;


// autentikasi
// akan nampilkan form reset password
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot.password');
// setelah selesai formnya akan diproses dmn logicnya diproses di authcontroller dengan nama method resetpass
Route::post('/forgot-password', [AuthController::class, 'resetPassword'])->name('reset.password');
Route::get('/login-register', [AuthController::class, 'loginRegister'])->name('login.register');
Route::get('/index', [AuthController::class, 'index'])->name('index');
Route::post('/login-register', [AuthController::class, 'loginProcess'])->name('login.process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

// halaman kalo berhasil login
Route::get('/shop', [ShopController::class, 'index'])->name('shop');

// halaman di nav container
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/accounts', [PageController::class, 'accounts'])->name('accounts');
Route::get('/compare', [PageController::class, 'compare'])->name('compare');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/wishlist', [PageController::class, 'wishlist'])->name('wishlist');
Route::get('/chat', [PageController::class, 'chat'])->name('chat');

// kelola akun 
Route::get('/accounts', [AccountController::class, 'index'])->name('accounts');
Route::post('/accounts/update-profile', [AccountController::class, 'updateProfile'])->name('accounts.updateProfile');
Route::post('/accounts/update-address', [AccountController::class, 'updateAddress'])->name('accounts.updateAddress');
Route::post('/accounts/change-password', [AccountController::class, 'changePassword'])->name('accounts.changePassword');


// cart 
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update'); // inc/dec quantity

Route::get('/shop', [ShopController::class, 'index'])->name('shop');

Route::get('/', function () {
    return redirect('/login-register');
});

