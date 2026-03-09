<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\ReportController;

Route::view('/', 'welcome')->name('home');

// Verification routes (requires auth but not verified)
Route::middleware(['auth'])->group(function () {
    Route::get('verify', [VerificationController::class, 'show'])->name('verification.show');
    Route::post('verify', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('verify/resend', [VerificationController::class, 'resend'])->name('verification.resend');
});

// Protected routes - requires both auth and our custom verification
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Staff management - admin only
    Route::get('staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('staff/register', [StaffController::class, 'create'])->name('staff.create');
    Route::post('staff/register', [StaffController::class, 'store'])->name('staff.store');
    Route::delete('staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
    Route::post('staff/{staff}/verify', [StaffController::class, 'toggleVerification'])->name('staff.toggleVerification');
    
    // Items management - admin only
    Route::get('items/create', [ItemController::class, 'create'])->name('items.create');
    Route::get('items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::apiResource('items', ItemController::class)->except(['show', 'edit', 'update']);
    
    // Customers management - admin and staff
    Route::get('customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::apiResource('customers', CustomerController::class)->except(['show', 'edit', 'update']);
    
    // Orders management - admin and staff
    Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}/payment', [OrderController::class, 'paymentForm'])->name('orders.payment');
    Route::post('orders/{order}/payment', [OrderController::class, 'recordPayment'])->name('orders.recordPayment');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
    Route::get('orders/{order}/receipt/pdf', [OrderController::class, 'downloadPdf'])->name('orders.receipt.pdf');
    Route::get('orders/{order}/receipt/whatsapp', [OrderController::class, 'generateReceiptForWhatsApp'])->name('orders.receipt.whatsapp');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    
    // Reports - admin only
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/branch/{branch}', [ReportController::class, 'branch'])->name('reports.branch');
});

require __DIR__.'/settings.php';
