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
use App\Http\Controllers\Auth\TwoFactorSettingsController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\ItemRequestController;
use App\Models\Testimonial;
use App\Models\Booking;
use Carbon\Carbon;
use App\Http\Controllers\ContactAdminController;
use App\Http\Controllers\ContactConversationController;
use App\Http\Controllers\AdminContactConversationController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\GlobalSearchController;

use Illuminate\Support\Facades\Route;


// Pengaturan Bahasa
Route::get('/lang/{locale}', [\App\Http\Controllers\LanguageController::class, 'switch'])->name('lang.switch');
Route::get('/test-lang', function() { return view('test-lang'); });

use App\Models\Item;
use App\Models\User;
use App\Models\Loan;

// Halaman Landing Page
Route::get('/', function () {
    $testimonials = Testimonial::where('status', 'approved')
        ->latest()
        ->take(4)
        ->get();

    $today = Carbon::today();
    $todayBookings = Booking::where('status', 'approved')
        ->whereDate('waktu_mulai', $today)
        ->orderBy('waktu_mulai')
        ->take(5)
        ->get();

    // Stats Dynamic
    $inventoryCount = Item::sum('stok');
    $teacherCount = User::where('role', 'guru')->count();
    $activityCount = Booking::count() + Loan::count();

    return view('welcome', compact('testimonials', 'todayBookings', 'inventoryCount', 'teacherCount', 'activityCount'));
})->middleware('no.cache')->name('welcome');


Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
Route::post('/contact-admin', [ContactAdminController::class, 'store'])->name('contact.admin.store');
Route::get('/verify/booking/{booking}', [BookingController::class, 'verify'])->name('bookings.verify'); // Public Verification Route

// Grup Rute yang hanya bisa diakses setelah login
Route::middleware(['auth', 'single.session'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Global Search
    Route::get('/search', [GlobalSearchController::class, 'search'])->name('search.global');

    // Percakapan dengan Admin
    Route::get('/contact-conversations', [ContactConversationController::class, 'index'])->name('contact.conversations.index');
    Route::post('/contact-conversations', [ContactConversationController::class, 'store'])->name('contact.conversations.store');
    Route::get('/contact-conversations/messages', [ContactConversationController::class, 'messages'])->name('contact.conversations.messages');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Two Factor (Google Authenticator)
    Route::post('/two-factor/start', [TwoFactorSettingsController::class, 'start'])->name('two-factor.start');
    Route::post('/two-factor/confirm', [TwoFactorSettingsController::class, 'confirm'])->name('two-factor.confirm');
    Route::post('/two-factor/recovery-codes', [TwoFactorSettingsController::class, 'regenerateRecoveryCodes'])->name('two-factor.recovery');
    Route::delete('/two-factor', [TwoFactorSettingsController::class, 'disable'])->name('two-factor.disable');

    // Fitur Utama (Resource Routes)
    Route::resource('items', ItemController::class);
    Route::resource('loans', LoanController::class);

    
    // Custom route for Booking Letter must be before resource to avoid parameter conflict issues (though less likely here)
    Route::get('/bookings/{booking}/surat', [BookingController::class, 'printSurat'])->name('bookings.surat');
    Route::patch('/bookings/{booking}/return', [BookingController::class, 'storeReturnDetails'])->name('bookings.return'); 
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

    // Permintaan tambah alat/bahan (guru)
    Route::get('/item-requests/create', [ItemRequestController::class, 'create'])->name('item-requests.create');
    Route::post('/item-requests', [ItemRequestController::class, 'store'])->name('item-requests.store');

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
        // Menggunakan POST agar selaras dengan form HTML (tanpa spoofing method DELETE)
        Route::post('/items/delete-multiple', [ItemController::class, 'deleteMultiple'])->name('items.delete-multiple');
        // ==============================================

        // Manajemen User
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

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
        Route::post('/reports/export', [ReportController::class, 'export'])->name('reports.export'); // Export Booking Lab
        Route::post('/reports/export-loans', [ReportController::class, 'exportLoans'])->name('reports.export-loans'); // Export Peminjaman Alat

        // Lock Screen Unlock
        Route::post('/lock-screen/unlock', [\App\Http\Controllers\Auth\LockScreenController::class, 'unlock'])->name('lock-screen.unlock');
        
        // Manajemen Pengumuman Global
        Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

        // Impor Item
        Route::post('/items/import', [ItemController::class, 'handleImport'])->name('items.import.handle');
        // PERBAIKAN: Route untuk mengunduh template kosong (Prefix /admin untuk hindari konflik dengan items/{id})
        Route::get('/admin/items/import-template', [ItemController::class, 'exportTemplate'])->name('items.template.export');
        Route::get('/admin/items/export-all', [ItemController::class, 'handleExport'])->name('items.export.all'); // Route untuk ekspor semua data

        // Percakapan kontak admin (dari landing page)
        Route::get('/admin/contact-conversations', [AdminContactConversationController::class, 'index'])->name('admin.contact-conversations.index');
        Route::get('/admin/contact-conversations/{conversation}', [AdminContactConversationController::class, 'show'])->name('admin.contact-conversations.show');
        Route::post('/admin/contact-conversations/{conversation}/reply', [AdminContactConversationController::class, 'reply'])->name('admin.contact-conversations.reply');
        Route::get('/admin/contact-conversations-json', [AdminContactConversationController::class, 'listJson'])->name('admin.contact-conversations.json');
        Route::get('/admin/contact-conversations/{conversation}/messages-json', [AdminContactConversationController::class, 'messagesJson'])->name('admin.contact-conversations.messages');

        // Moderasi Testimoni
        Route::get('/admin/testimonials', [TestimonialController::class, 'index'])->name('admin.testimonials.index');
        Route::post('/admin/testimonials/{testimonial}/status', [TestimonialController::class, 'updateStatus'])->name('admin.testimonials.update-status');

        // Audit Trail / Log Aktivitas
        Route::get('/admin/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/admin/audit-logs/export', [AuditLogController::class, 'export'])->name('audit-logs.export');
        Route::get('/admin/audit-logs/{auditLog}', [AuditLogController::class, 'show'])->name('audit-logs.show');

        // Permintaan item dari guru
        Route::get('/admin/item-requests', [ItemRequestController::class, 'index'])->name('admin.item-requests.index');
        Route::get('/admin/item-requests/{itemRequest}', [ItemRequestController::class, 'show'])->name('admin.item-requests.show');
        Route::post('/admin/item-requests/{itemRequest}/approve', [ItemRequestController::class, 'approve'])->name('admin.item-requests.approve');
        Route::post('/admin/item-requests/{itemRequest}/reject', [ItemRequestController::class, 'reject'])->name('admin.item-requests.reject');
    });
});

require __DIR__ . '/auth.php';

// Rute penangkap semua (Catch-all Route) untuk 404
// Letakkan ini di bagian PALING BAWAH dari file web.php
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
