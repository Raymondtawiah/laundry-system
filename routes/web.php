<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Staff management - admin only
    Route::get('staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('staff/register', [StaffController::class, 'create'])->name('staff.create');
    Route::post('staff/register', [StaffController::class, 'store'])->name('staff.store');
    Route::delete('staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
    
    // Items management - admin only
    Route::get('items/create', [ItemController::class, 'create'])->name('items.create');
    Route::apiResource('items', ItemController::class)->except(['show', 'edit', 'update']);
    
    // Customers management - admin and staff
    Route::get('customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::apiResource('customers', CustomerController::class)->except(['show', 'edit', 'update']);
    
    // Orders management - admin and staff
    Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}/payment', [OrderController::class, 'paymentForm'])->name('orders.payment');
    Route::post('orders/{order}/payment', [OrderController::class, 'recordPayment'])->name('orders.recordPayment');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
});

require __DIR__.'/settings.php';
