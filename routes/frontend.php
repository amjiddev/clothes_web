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
Route::get('/new-in', [HomeController::class, 'newIn'])->name('new-in');
Route::get('/brands-page', [HomeController::class, 'brandsPage'])->name('brands-page');
Route::get('/brands', [\App\Http\Controllers\Frontend\BrandController::class, 'index'])->name('brands');
Route::get('/collections', [HomeController::class, 'collections'])->name('collections');
Route::get('/collections/best-sellers', [HomeController::class, 'bestSellers'])->name('collections.best-sellers');
Route::get('/collections/summer-2026', [HomeController::class, 'summer2026'])->name('collections.summer-2026');
Route::get('/collections/featured', [HomeController::class, 'featured'])->name('collections.featured');
Route::get('/product/{identifier}', [HomeController::class, 'productDetail'])->name('product.detail');
Route::get('/tailoring', [HomeController::class, 'tailoring'])->name('tailoring');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/disclaimer', [HomeController::class, 'disclaimer'])->name('disclaimer');
Route::get('/return-exchange', [HomeController::class, 'returnExchange'])->name('return-exchange');
Route::get('/shipping-policy', [HomeController::class, 'shippingPolicy'])->name('shipping-policy');
Route::get('/track-order', [HomeController::class, 'trackOrder'])->name('track-order');
Route::get('/feedback-survey', [HomeController::class, 'feedbackSurvey'])->name('feedback-survey');
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms-of-service', [HomeController::class, 'termsOfService'])->name('terms-of-service');

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
