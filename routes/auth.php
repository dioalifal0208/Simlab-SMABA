<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Rute GET untuk register (diperbaiki controllernya)
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register.create'); // Nama diubah agar tidak konflik

    // Rute POST untuk register (yang digunakan AJAX)
    Route::post('register', [RegisteredUserController::class, 'store'])
                ->name('register'); // <-- Nama 'register' dipindah ke sini

    // Rute GET untuk login
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login.create'); // Nama diubah agar tidak konflik

    // Rute POST untuk login (yang digunakan AJAX)
    Route::post('login', [AuthenticatedSessionController::class, 'store'])
                ->name('login'); // <-- Nama 'login' dipindah ke sini

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    // PERBAIKAN: Mengganti nama 'password.store' menjadi 'password.update' agar konsisten
    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});