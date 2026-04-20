<?php

namespace App\Http\Controllers;

use App\Notifications\VerifyNotificationEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class NotificationEmailController extends Controller
{
    /**
     * Tampilkan form setup notification email.
     * (Sekarang ditangani oleh Modal di Layout, fungsi ini opsional sebagai fallback)
     */
    public function setup(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedNotificationEmail()) {
            return redirect()->route('dashboard');
        }

        return view('notification-email.setup', [
            'email' => $user->notification_email,
        ]);
    }

    /**
     * Simpan notification email dan kirim verifikasi.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'notification_email' => [
                'required',
                'email',
                'max:255',
                \Illuminate\Validation\Rule::unique('users', 'notification_email')
                    ->ignore($request->user()->id),
            ],
        ], [
            'notification_email.required' => 'Email notifikasi wajib diisi.',
            'notification_email.email' => 'Format email tidak valid.',
            'notification_email.unique' => 'Email ini sudah digunakan oleh user lain.',
        ]);

        $user = $request->user();

        // Jika email berubah, reset verifikasi
        if ($user->notification_email !== $validated['notification_email']) {
            $user->forceFill([
                'notification_email' => $validated['notification_email'],
                'notification_email_verified_at' => null,
            ])->save();
        }

        // Kirim email verifikasi via smtp-auth
        try {
            $user->notify(new VerifyNotificationEmail);
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Gagal mengirim email: ' . $e->getMessage()], 500);
            }
            return back()->withErrors(['notification_email' => 'Gagal mengirim email verifikasi.']);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Email verifikasi telah dikirim ke ' . $validated['notification_email']]);
        }

        return redirect()->route('notification-email.setup')
            ->with('status', 'Email verifikasi telah dikirim ke ' . $validated['notification_email']);
    }

    /**
     * Verifikasi notification email via signed URL.
     */
    public function verify(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedNotificationEmail()) {
            return redirect()->route('dashboard')
                ->with('success', 'Email notifikasi sudah terverifikasi sebelumnya.');
        }

        $user->forceFill([
            'notification_email_verified_at' => now(),
        ])->save();

        return redirect()->route('dashboard')
            ->with('success', 'Email notifikasi berhasil diverifikasi!');
    }

    /**
     * Kirim ulang email verifikasi (throttled).
     */
    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedNotificationEmail()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Email sudah terverifikasi.'], 400);
            }
            return redirect()->route('dashboard');
        }

        if (!$user->notification_email) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Silakan isi email notifikasi terlebih dahulu.'], 400);
            }
            return redirect()->route('notification-email.setup')
                ->withErrors(['notification_email' => 'Silakan isi email notifikasi terlebih dahulu.']);
        }

        // Rate limiting manual: max 3 per menit (jika middleware throttle tidak cukup)
        $key = 'verify-notif-email:' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            if ($request->expectsJson()) {
                return response()->json(['message' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik."], 429);
            }
            return back()->withErrors([
                'notification_email' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.",
            ]);
        }
        RateLimiter::hit($key, 60);

        try {
            $user->notify(new VerifyNotificationEmail);
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Gagal mengirim ulang email.'], 500);
            }
            return back()->withErrors(['notification_email' => 'Gagal mengirim ulang email.']);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Email verifikasi telah dikirim ulang.']);
        }

        return back()->with('status', 'Email verifikasi telah dikirim ulang.');
    }

    /**
     * Skip setup (user bisa lanjut tanpa notification email).
     */
    public function skip(Request $request)
    {
        $request->session()->put('notification_email_skipped', true);

        if ($request->expectsJson()) {
            return response()->json(['status' => 'skipped']);
        }

        return redirect()->route('dashboard');
    }
}
