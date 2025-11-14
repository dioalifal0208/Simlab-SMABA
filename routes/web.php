<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DamageReportController;
use App\Http\Controllers\PracticumModuleController;
use App\Http\Controllers\MaintenanceLogController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StockRequestController;
use App\Http\Controllers\AnnouncementController;

use Illuminate\Support\Facades\Route;


// Halaman Landing Page
Route::get('/', function () {
    return view('welcome');
})->middleware('no.cache')->name('welcome');

// Grup Rute yang hanya bisa diakses setelah login
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Fitur Utama (Resource Routes)
    Route::resource('items', ItemController::class);
    Route::resource('loans', LoanController::class);
    Route::resource('bookings', BookingController::class);
    Route::resource('practicum-modules', PracticumModuleController::class);

    // Kalender
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');

    // Notifikasi
    Route::get('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/notifications/summary', [NotificationController::class, 'summary'])->name('notifications.summary');

    // ===== Fitur Interaktif Pengguna =====
    Route::get('/items/{item}/report-damage', [DamageReportController::class, 'create'])->name('damage-reports.create');
    Route::post('/items/{item}/report-damage', [DamageReportController::class, 'store'])->name('damage-reports.store');
    Route::post('/items/{item}/request-stock', [StockRequestController::class, 'store'])->name('stock-requests.store');

    // ===== Pustaka Digital (Sebagian Publik, Sebagian Terproteksi) =====
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/{document}/preview', [DocumentController::class, 'preview'])->name('documents.preview');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    
    // Hanya admin & guru yang bisa unggah dan hapus dokumen
    Route::middleware('can:manage-documents')->group(function () {
        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    });

    // ===================================
    // =====     RUTE KHUSUS ADMIN     =====
    // ===================================
    Route::middleware('can:is-admin')->group(function () {
        
        // ==============================================
        // ## TAMBAHAN BARU ##
        // Route untuk menangani hapus massal (bulk delete)
        Route::delete('/items/delete-multiple', [ItemController::class, 'deleteMultiple'])->name('items.delete-multiple');
        // ==============================================

        // Manajemen User
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');

        // PENAMBAHAN: Route untuk Impor User
        Route::post('/users/import', [UserController::class, 'handleImport'])->name('users.import.store');

        // Manajemen Laporan Kerusakan
        Route::get('/damage-reports', [DamageReportController::class, 'index'])->name('damage-reports.index');
        Route::get('/damage-reports/{report}', [DamageReportController::class, 'show'])->name('damage-reports.show');
        Route::patch('/damage-reports/{report}', [DamageReportController::class, 'update'])->name('damage-reports.update');
        Route::delete('/damage-reports/{report}', [DamageReportController::class, 'destroy'])->name('damage-reports.destroy');

        // Manajemen Riwayat Perawatan (Maintenance)
        Route::get('/items/{item}/maintenance', [MaintenanceLogController::class, 'index'])->name('maintenance.index');
        Route::post('/items/{item}/maintenance', [MaintenanceLogController::class, 'store'])->name('maintenance.store');
        
        // Laporan & Analitik
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        
        // Manajemen Pengumuman Global
        Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

        // Impor Item
        Route::post('/items/import', [ItemController::class, 'handleImport'])->name('items.import.handle');
        // PERBAIKAN: Route untuk mengunduh template kosong
        Route::get('/items/import-template', [ItemController::class, 'exportTemplate'])->name('items.template.export');
        Route::get('/items/export-all', [ItemController::class, 'handleExport'])->name('items.export.all'); // Route untuk ekspor semua data
    });
});

require __DIR__ . '/auth.php';

// Rute penangkap semua (Catch-all Route) untuk 404
// Letakkan ini di bagian PALING BAWAH dari file web.php
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
