<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

// ---------- Publik ----------
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/pesan', [OrderController::class, 'create'])->name('order.create');
Route::post('/pesan/estimasi', [OrderController::class, 'estimate'])
    ->middleware('throttle:60,1')
    ->name('order.estimate');
Route::post('/pesan', [OrderController::class, 'store'])
    ->middleware('throttle:5,10') // maks 5 order per 10 menit per IP (anti-spam)
    ->name('order.store');
Route::get('/pesan/sukses/{code}', [OrderController::class, 'success'])->name('order.success');

Route::get('/cek-progress', [TrackingController::class, 'form'])->name('track.form');
Route::post('/cek-progress', [TrackingController::class, 'check'])
    ->middleware('throttle:10,1')
    ->name('track.check');
Route::get('/cek-progress/{code}', [TrackingController::class, 'show'])->name('track.show');

Route::post('/order/{code}/review', [ReviewController::class, 'store'])
    ->middleware('throttle:5,10')
    ->name('review.store');

// ---------- Admin ----------
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [Admin\LoginController::class, 'form'])->name('login');
    Route::post('/login', [Admin\LoginController::class, 'login'])
        ->middleware('throttle:10,1')
        ->name('login.attempt');

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [Admin\LoginController::class, 'logout'])->name('logout');
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/orders', [Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.status');
        Route::patch('/orders/{order}/payment', [Admin\OrderController::class, 'updatePayment'])->name('orders.payment');
        Route::delete('/orders/{order}', [Admin\OrderController::class, 'destroy'])->name('orders.destroy');
        Route::post('/orders/{order}/restore', [Admin\OrderController::class, 'restore'])->name('orders.restore');
        Route::delete('/orders/{order}/force', [Admin\OrderController::class, 'forceDestroy'])->name('orders.force-destroy');

        Route::get('/subjects', [Admin\SubjectController::class, 'index'])->name('subjects.index');
        Route::post('/subjects', [Admin\SubjectController::class, 'store'])->name('subjects.store');
        Route::patch('/subjects/{subject}', [Admin\SubjectController::class, 'update'])->name('subjects.update');
        Route::delete('/subjects/{subject}', [Admin\SubjectController::class, 'destroy'])->name('subjects.destroy');

        Route::get('/pricing', [Admin\PricingController::class, 'index'])->name('pricing.index');
        Route::post('/pricing/multipliers', [Admin\PricingController::class, 'storeMultiplier'])->name('pricing.multipliers.store');
        Route::patch('/pricing/multipliers/{multiplier}', [Admin\PricingController::class, 'updateMultiplier'])->name('pricing.multipliers.update');
        Route::post('/pricing/addons', [Admin\PricingController::class, 'storeAddon'])->name('pricing.addons.store');
        Route::patch('/pricing/addons/{addon}', [Admin\PricingController::class, 'updateAddon'])->name('pricing.addons.update');

        Route::get('/reviews', [Admin\ReviewController::class, 'index'])->name('reviews.index');
        Route::patch('/reviews/{review}/toggle', [Admin\ReviewController::class, 'toggle'])->name('reviews.toggle');
    });
});
