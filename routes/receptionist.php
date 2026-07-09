<?php

use App\Http\Controllers\Receptionist\DashboardController;
use App\Http\Controllers\Apps\CustomerController;
use App\Http\Controllers\Apps\OrderController;
use App\Http\Controllers\Apps\StitchingOrderController;
use App\Http\Controllers\Apps\MeasurementController;
use App\Http\Controllers\Apps\TailorController;
use App\Http\Controllers\Apps\PaymentController;
use App\Http\Controllers\Apps\InvoiceController;
use App\Http\Controllers\Apps\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Receptionist Routes
|--------------------------------------------------------------------------
|
| Routes for receptionist panel with role-based access control
*/

Route::middleware(['auth', 'verified', 'receptionist.only'])->prefix('receptionist')->name('receptionist.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Customers
    Route::resource('customers', CustomerController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update']);
    Route::get('customers/{customer}/measurements', [CustomerController::class, 'showMeasurements'])->name('customers.measurements');
    Route::get('customers/{customer}/orders', [CustomerController::class, 'showOrders'])->name('customers.orders');

    // Orders
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update']);
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('orders/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.invoice');
    Route::post('orders/{order}/print-invoice', [OrderController::class, 'printInvoice'])->name('orders.print-invoice');

    // Stitching Orders
    Route::resource('stitching-orders', StitchingOrderController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update']);
    Route::post('stitching-orders/{stitchingOrder}/assign-tailor', [StitchingOrderController::class, 'assignTailor'])->name('stitching-orders.assign-tailor');
    Route::post('stitching-orders/{stitchingOrder}/update-status', [StitchingOrderController::class, 'updateStatus'])->name('stitching-orders.update-status');
    Route::get('stitching-orders/{stitchingOrder}/invoice', [StitchingOrderController::class, 'downloadInvoice'])->name('stitching-orders.invoice');

    // Measurements
    Route::resource('measurements', MeasurementController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update']);
    Route::post('measurements/{measurement}/use-as-template', [MeasurementController::class, 'useAsTemplate'])->name('measurements.use-as-template');

    // Tailors
    Route::resource('tailors', TailorController::class)->only(['index', 'show']);
    Route::get('tailors/{tailor}/availability', [TailorController::class, 'availability'])->name('tailors.availability');

    // Payments
    Route::resource('payments', PaymentController::class)->only(['index', 'show']);
    Route::post('payments/{payment}/collect', [PaymentController::class, 'collectPayment'])->name('payments.collect');
    Route::post('payments/{payment}/mark-paid', [PaymentController::class, 'markPaid'])->name('payments.mark-paid');

    // Invoices
    Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');

    // Reports
    Route::get('reports', [ReportController::class, 'receptionistReports'])->name('reports.index');
    Route::get('reports/orders', [ReportController::class, 'orderReport'])->name('reports.orders');
    Route::get('reports/stitching', [ReportController::class, 'stitchingReport'])->name('reports.stitching');
    Route::get('reports/customers', [ReportController::class, 'customerReport'])->name('reports.customers');
    Route::get('reports/payments', [ReportController::class, 'paymentReport'])->name('reports.payments');
    Route::post('reports/export', [ReportController::class, 'export'])->name('reports.export');
});
