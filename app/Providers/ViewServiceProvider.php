<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth; // <-- Import Auth
use Illuminate\Support\Facades\View; // <-- Import View
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
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
        // Menjalankan logika ini setiap kali view 'layouts.navigation' di-render
        View::composer('layouts.navigation', function ($view) {
            if (Auth::check()) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                
                // Ambil 5 notifikasi terbaru yang belum dibaca
                $notifications = $user->unreadNotifications()->take(5)->get();
                
                // Hitung SEMUA notifikasi yang belum dibaca
                $unreadCount = $user->unreadNotifications()->count();
                
                // Kirim variabel $notifications dan $unreadCount ke view
                $view->with(compact('notifications', 'unreadCount'));
            } else {
                // Jika user belum login, kirim data kosong agar tidak error
                $view->with(['notifications' => collect(), 'unreadCount' => 0]);
            }
        });
    }
}