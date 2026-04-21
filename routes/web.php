<?php

use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// Authentication routes
Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

// Registration routes
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Logout route
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

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
    Route::get('orders/{order}/receipt/sms', [OrderController::class, 'sendReceiptSms'])->name('orders.receipt.sms');
    Route::get('orders/{order}/details', [OrderController::class, 'getOrderDetails'])->name('orders.details');
    Route::get('orders/{order}/whatsapp', [OrderController::class, 'getWhatsAppUrl'])->name('orders.whatsapp');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Reports - admin only
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/branch/{branch}', [ReportController::class, 'branch'])->name('reports.branch');

    // Expenses
    Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::put('expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
});

require __DIR__.'/settings.php';
