<?php

use App\Http\Controllers\Receptionist\DashboardController;
use App\Http\Controllers\Apps\CustomerController;
use App\Http\Controllers\Apps\OrderController;
use App\Http\Controllers\Apps\StitchingOrderController;
use App\Http\Controllers\Apps\MeasurementController;
use App\Http\Controllers\Apps\TailorController;
use App\Http\Controllers\Apps\PaymentController;
use App\Http\Controllers\Apps\InvoiceController;
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
    Route::get('orders/create/select-type', [OrderController::class, 'selectOrderType'])->name('orders.select-type');
    Route::post('orders/create/summary', [OrderController::class, 'orderSummary'])->name('orders.summary');
    Route::post('orders/create/payment', [OrderController::class, 'paymentStep'])->name('orders.payment');
    Route::post('orders/{order}/record-payment', [OrderController::class, 'recordPayment'])->name('orders.record-payment');
    Route::post('orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('orders/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.invoice');
    Route::post('orders/{order}/print-invoice', [OrderController::class, 'printInvoice'])->name('orders.print-invoice');

    // Stitching Orders
    Route::resource('stitching-orders', StitchingOrderController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update']);
    Route::post('stitching-orders/{stitchingOrder}/assign-tailor', [StitchingOrderController::class, 'assignTailor'])->name('stitching-orders.assign-tailor');
    Route::post('stitching-orders/{stitchingOrder}/update-status', [StitchingOrderController::class, 'updateStatus'])->name('stitching-orders.update-status');
    Route::get('stitching-orders/{stitchingOrder}/invoice', [StitchingOrderController::class, 'downloadInvoice'])->name('stitching-orders.invoice');

    // Measurements
    Route::resource('measurements', MeasurementController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::post('measurements/{measurement}/set-default', [MeasurementController::class, 'setDefault'])->name('measurements.set-default');
    Route::post('measurements/{measurement}/duplicate', [MeasurementController::class, 'duplicate'])->name('measurements.duplicate');
    Route::get('customers/{customer}/measurements', [MeasurementController::class, 'customerMeasurements'])->name('measurements.customer-history');

    // Tailors
    Route::get('tailors/assign-form', [TailorController::class, 'assignForm'])->name('tailors.assign-form');
    Route::post('tailors/assign-order', [TailorController::class, 'assignOrder'])->name('tailors.assign-order');
    Route::resource('tailors', TailorController::class)->only(['index', 'show']);
    Route::get('tailors/{stitchingOrder}/assignment-details', [TailorController::class, 'showAssignment'])->name('tailors.show-assignment');
    Route::get('tailors/{tailor}/workload', [TailorController::class, 'getWorkload'])->name('tailors.workload');
    Route::get('tailors/{tailor}/availability', [TailorController::class, 'getAvailability'])->name('tailors.availability');
    Route::post('tailors/{stitchingOrder}/reassign', [TailorController::class, 'reassignOrder'])->name('tailors.reassign-order');
    Route::get('tailors/{tailor}/orders', [TailorController::class, 'getTailorOrders'])->name('tailors.tailor-orders');
    Route::get('tailors/{tailor}/dashboard', [TailorController::class, 'viewDashboard'])->name('tailors.view-dashboard');

    // Payments
    Route::resource('payments', PaymentController::class)->only(['index', 'show']);
    Route::post('payments/{payment}/collect', [PaymentController::class, 'collectPayment'])->name('payments.collect');
    Route::post('payments/{payment}/mark-paid', [PaymentController::class, 'markPaid'])->name('payments.mark-paid');
    Route::post('orders/{order}/record-payment', [PaymentController::class, 'recordPayment'])->name('orders.record-payment');
    Route::get('payments/summary/{order}', [PaymentController::class, 'getPaymentSummary'])->name('payments.summary');
    Route::get('payments/export', [PaymentController::class, 'exportSummary'])->name('payments.export');

    // Invoices
    Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('', [\App\Http\Controllers\Receptionist\ReportController::class, 'index'])->name('index');
        Route::get('daily-orders', [\App\Http\Controllers\Receptionist\ReportController::class, 'dailyOrdersReport'])->name('daily-orders');
        Route::get('monthly-sales', [\App\Http\Controllers\Receptionist\ReportController::class, 'monthlySalesReport'])->name('monthly-sales');
        Route::get('pending-stitching', [\App\Http\Controllers\Receptionist\ReportController::class, 'pendingStitchingReport'])->name('pending-stitching');
        Route::get('completed-orders', [\App\Http\Controllers\Receptionist\ReportController::class, 'completedOrdersReport'])->name('completed-orders');
        Route::get('payment-collection', [\App\Http\Controllers\Receptionist\ReportController::class, 'paymentCollectionReport'])->name('payment-collection');
    });
});
