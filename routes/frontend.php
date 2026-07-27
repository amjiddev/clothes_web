<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\TailoringServiceController;
use App\Http\Controllers\Frontend\CartController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/product/{identifier}', [HomeController::class, 'productDetail'])->name('product.detail');
Route::get('/tailoring', [HomeController::class, 'tailoring'])->name('tailoring');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Tailoring Service Routes
Route::get('/tailoring-service', [TailoringServiceController::class, 'show'])->name('tailoring.service');
Route::post('/tailoring-service/submit', [TailoringServiceController::class, 'store'])->name('tailoring.store')->middleware('auth');
Route::get('/tailoring-service/success', [TailoringServiceController::class, 'success'])->name('tailoring.success');
Route::get('/api/measurements', [TailoringServiceController::class, 'getMeasurements'])->middleware('auth');
Route::get('/api/measurement/{id}', [TailoringServiceController::class, 'loadMeasurement'])->middleware('auth');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{cartKey}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{cartKey}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'getCount'])->name('cart.count');
Route::get('/cart/summary', [CartController::class, 'getSummary'])->name('cart.summary');

// Checkout routes
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [CartController::class, 'store'])->name('order.store');
    Route::get('/order/{orderId}/confirmation', [CartController::class, 'confirmation'])->name('order.confirmation');
});
