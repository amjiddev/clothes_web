<?php

use App\Http\Controllers\Tailor\DashboardController;
use App\Http\Controllers\Tailor\StitchingOrderController;
use App\Http\Controllers\Tailor\StitchingStatusController;
use App\Http\Controllers\Tailor\MeasurementController;
use App\Http\Controllers\Tailor\DesignController;
use App\Http\Controllers\Tailor\CompletedOrderController;
use App\Http\Controllers\Tailor\ProfileController;
use App\Http\Controllers\Tailor\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tailor Routes
|--------------------------------------------------------------------------
|
| Routes for tailor panel with role-based access control
| Only authenticated users with tailor role can access these routes
*/

Route::middleware(['auth', 'verified', 'tailor.only'])->prefix('tailor')->name('tailor.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Assigned Stitching Orders
    Route::get('/stitching-orders', [StitchingOrderController::class, 'index'])->name('stitching-orders.index');
    Route::get('/stitching-orders/{id}', [StitchingOrderController::class, 'show'])->name('stitching-orders.show');
    Route::post('/stitching-orders/{id}/update-status', [StitchingOrderController::class, 'updateStatus'])->name('stitching-orders.update-status');
    Route::post('/stitching-orders/{id}/add-notes', [StitchingOrderController::class, 'addNotes'])->name('stitching-orders.add-notes');

    // Measurement Details
    Route::get('/measurements', [MeasurementController::class, 'index'])->name('measurements.index');
    Route::get('/measurements/{id}', [MeasurementController::class, 'show'])->name('measurements.show');

    // Design Gallery (View Only)
    Route::get('/designs/gallery', [DesignController::class, 'gallery'])->name('designs.gallery');
    Route::get('/designs/{id}', [DesignController::class, 'show'])->name('designs.show');

    // Stitching Status
    Route::get('/status', [StitchingStatusController::class, 'index'])->name('status.index');
    Route::get('/status/{id}', [StitchingStatusController::class, 'show'])->name('status.show');
    Route::post('/status/{id}/update', [StitchingStatusController::class, 'updateStatus'])->name('status.update');
    Route::get('/status/{id}/timeline', [StitchingStatusController::class, 'getStatusTimeline'])->name('status.timeline');
    Route::get('/status/{id}/transitions', [StitchingStatusController::class, 'getAvailableTransitions'])->name('status.transitions');

    // Completed Orders
    Route::get('/completed-orders', [CompletedOrderController::class, 'index'])->name('completed-orders.index');
    Route::get('/completed-orders/{id}', [CompletedOrderController::class, 'show'])->name('completed-orders.show');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [NotificationController::class, 'getUnread'])->name('notifications.unread');
    Route::get('/notifications/stats', [NotificationController::class, 'getStats'])->name('notifications.stats');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-multiple-read', [NotificationController::class, 'markMultipleAsRead'])->name('notifications.mark-multiple-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{id}/delete', [NotificationController::class, 'delete'])->name('notifications.delete');
    Route::post('/notifications/clear-all', [NotificationController::class, 'clearAll'])->name('notifications.clear-all');

    // Profile Settings
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('profile.change-password');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password.store');
});
