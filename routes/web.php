<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RazorpayController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});


Route::get('/login',[AuthController::class,'login'])->name("login");
Route::get('/register',[AuthController::class,'register'])->name("register");

Route::post('/register-user',[AuthController::class,'registerUser'])->name("register.user");
Route::post('/login-user',[AuthController::class,'loginUser'])->name("login.user");

Route::get('/product-view',[AuthController::class,'getProduct'])->name("product.list");
Route::post('/razorpay/payment', [RazorpayController::class, 'payment'])->name('razorpay.payment');
Route::post('/razorpay/success', [RazorpayController::class, 'success'])->name('razorpay.success');

Route::middleware([AuthMiddleware::class])->group(function () {
Route::get('/product',[ProductController::class,'index'])->name("product.view");
Route::post('/product-store',[ProductController::class,'store'])->name("product.store");
Route::get('/product-get',[ProductController::class,'show'])->name("product.get");
Route::get('/product-edit',[ProductController::class,'edit'])->name("product.edit");
Route::get('/product-delete',[ProductController::class,'destroy'])->name("product.delete");

Route::get('/logout', function () {
    session()->forget('IS_ADMIN');
    session()->forget('USER_ID');
    session()->forget('USER_NAME');

    return view('index');
});

});



