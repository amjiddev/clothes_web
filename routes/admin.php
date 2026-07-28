<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\TailorController;
use App\Http\Controllers\Admin\ReceptionistController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'admin.only'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Inventory Management
    Route::resource('inventory', InventoryController::class)->only(['index', 'show', 'edit', 'update']);
    Route::post('inventory/{inventory}/adjust-stock', [InventoryController::class, 'adjustStock'])->name('inventory.adjust-stock');
    Route::post('inventory/{inventory}/add-stock', [InventoryController::class, 'addStock'])->name('inventory.add-stock');
    Route::post('inventory/{inventory}/remove-stock', [InventoryController::class, 'removeStock'])->name('inventory.remove-stock');

    // Tailor Management
    Route::resource('tailors', TailorController::class);

    // Receptionist Management
    Route::resource('receptionists', ReceptionistController::class);
    Route::post('receptionists/{receptionist}/activate', [ReceptionistController::class, 'activate'])->name('receptionists.activate');
    Route::post('receptionists/{receptionist}/deactivate', [ReceptionistController::class, 'deactivate'])->name('receptionists.deactivate');

    // Customer Management
    Route::resource('customers', CustomerController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
    Route::post('customers/{customer}/block', [CustomerController::class, 'block'])->name('customers.block');
    Route::post('customers/{customer}/unblock', [CustomerController::class, 'unblock'])->name('customers.unblock');

    // Coupon Management
    Route::resource('coupons', CouponController::class);

    // Payment Management
    Route::resource('payments', PaymentController::class)->only(['index', 'show']);
    Route::post('payments/{payment}/update-status', [PaymentController::class, 'updateStatus'])->name('payments.update-status');

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/sales', [ReportController::class, 'salesReport'])->name('reports.sales');
    Route::get('reports/orders', [ReportController::class, 'orderReport'])->name('reports.orders');
    Route::get('reports/stitching', [ReportController::class, 'stitchingReport'])->name('reports.stitching');
    Route::get('reports/customers', [ReportController::class, 'customerReport'])->name('reports.customers');
    Route::get('reports/tailors', [ReportController::class, 'tailorReport'])->name('reports.tailors');
    Route::get('reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export');
    Route::get('reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');

    // Settings
    Route::resource('settings', SettingsController::class)->only(['index']);
    Route::post('settings/shop/update', [SettingsController::class, 'updateShop'])->name('settings.updateShop');
    Route::post('settings/payment/update', [SettingsController::class, 'updatePayment'])->name('settings.updatePayment');
    Route::post('settings/email/update', [SettingsController::class, 'updateEmail'])->name('settings.updateEmail');
    Route::post('settings/colors/update', [SettingsController::class, 'updateColors'])->name('settings.updateColors');
    Route::get('settings/tab/{tab}', [SettingsController::class, 'getTab'])->name('settings.getTab');

    // Product Management (Redirect to existing routes if needed)
    Route::resource('products', \App\Http\Controllers\Apps\ProductController::class);
    Route::resource('categories', \App\Http\Controllers\Apps\CategoryController::class);
    
    // Order Management (Redirect to existing routes)
    Route::resource('orders', \App\Http\Controllers\Apps\OrderController::class);
    Route::post('orders/{order}/update-status', [\App\Http\Controllers\Apps\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('orders/{order}/stats', [\App\Http\Controllers\Apps\OrderController::class, 'getStats'])->name('orders.stats');
    
    // Stitching Order Management
    Route::resource('stitching-orders', \App\Http\Controllers\Apps\StitchingOrderController::class);
    Route::get('stitching-orders/{stitchingOrder}/assign-tailor', [\App\Http\Controllers\Apps\StitchingOrderController::class, 'assignTailor'])->name('stitching-orders.assign-tailor');
    Route::post('stitching-orders/{stitchingOrder}/assign', [\App\Http\Controllers\Apps\StitchingOrderController::class, 'updateAssignment'])->name('stitching-orders.update-assignment');
    Route::put('stitching-orders/{stitchingOrder}/status', [\App\Http\Controllers\Apps\StitchingOrderController::class, 'updateStatus'])->name('stitching-orders.update-status');
    Route::get('stitching-orders/{stitchingOrder}/stats', [\App\Http\Controllers\Apps\StitchingOrderController::class, 'getStats'])->name('stitching-orders.stats');
    
    // User Management
    Route::prefix('user-management')->name('user-management.')->group(function () {
        Route::resource('users', \App\Http\Controllers\Apps\UserManagementController::class);
        Route::resource('roles', \App\Http\Controllers\Apps\RoleManagementController::class);
        Route::resource('permissions', \App\Http\Controllers\Apps\PermissionManagementController::class);
    });

    // Website Management - Frontend Pages
    Route::prefix('website-management')->name('website-management.')->group(function () {
        Route::get('/contact', [\App\Http\Controllers\Admin\WebsiteManagementController::class, 'contact'])->name('contact');
        Route::post('/contact/store', [\App\Http\Controllers\Admin\WebsiteManagementController::class, 'storeContact'])->name('contact.store');
        Route::put('/contact/{id}/update', [\App\Http\Controllers\Admin\WebsiteManagementController::class, 'updateContact'])->name('contact.update');
        Route::delete('/contact/{id}/delete', [\App\Http\Controllers\Admin\WebsiteManagementController::class, 'deleteContact'])->name('contact.delete');
    });
});
