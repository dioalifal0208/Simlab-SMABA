<?php

namespace App\Providers;

use App\Models\Loan;
use App\Observers\LoanObserver;
use App\Models\Announcement;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Menjalankan logika ini setiap kali view 'layouts.navigation' dan 'layouts.app' dimuat
        View::composer(['layouts.navigation', 'layouts.app'], function ($view) {
            
            // --- PENAMBAHAN: Ambil pengumuman yang sedang aktif ---
            // Kita cache hasilnya selama 10 menit agar tidak query database di setiap halaman
            $activeAnnouncement = cache()->remember('active_announcement', 600, function () {
                return Announcement::where('status', 'active')->first();
            });
            // ----------------------------------------------------

            if (Auth::check()) {
                /** @var User $user */
                $user = Auth::user();

                // Gunakan relasi notifications() lalu filter unread
                $notifications = $user
                    ->notifications()
                    ->whereNull('read_at')
                    ->latest()
                    ->limit(5)
                    ->get();

                $unreadCount = $user
                    ->notifications()
                    ->whereNull('read_at')
                    ->count();

                // Kirim semua data ke view
                $view->with(compact('notifications', 'unreadCount', 'activeAnnouncement'));
            } else {
                // Jika user belum login, kirim data kosong dan pengumuman
                $view->with([
                    'notifications' => collect(), 
                    'unreadCount' => 0,
                    'activeAnnouncement' => $activeAnnouncement
                ]);
            }
        });
    }
}
