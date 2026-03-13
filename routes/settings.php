<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingsController;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', [SettingsController::class, 'profile'])->name('profile.edit');
    Route::post('settings/profile', [SettingsController::class, 'updateProfile'])->name('user-profile-information.update');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('settings/password', [SettingsController::class, 'password'])->name('user-password.edit');
    Route::post('settings/password', [SettingsController::class, 'updatePassword'])->name('user-password.update');
    
    Route::get('settings/appearance', [SettingsController::class, 'appearance'])->name('appearance.edit');
    
    Route::get('settings/two-factor', [SettingsController::class, 'twoFactor'])->name('two-factor.show');
    Route::post('settings/two-factor/enable', [SettingsController::class, 'enableTwoFactor'])->name('two-factor.enable');
    Route::delete('settings/two-factor/disable', [SettingsController::class, 'disableTwoFactor'])->name('two-factor.disable');
});
