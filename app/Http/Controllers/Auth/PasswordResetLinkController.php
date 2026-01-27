<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse; // <-- PENAMBAHAN

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     * (File ini mungkin sudah tidak Anda gunakan, tapi tidak masalah)
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse|JsonResponse // <-- PENAMBAHAN JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Logika untuk mengirim link reset password (tetap sama)
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // --- PERUBAHAN LOGIKA RESPON ---
        
        // Jika link BERHASIL dikirim
        if ($status == Password::RESET_LINK_SENT) {
            // Jika ini adalah request AJAX (dari popup kita)
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __($status) // Kirim pesan sukses sebagai JSON
                ], 200);
            }
            // Jika ini request browser biasa
            return back()->with('status', __($status));
        }

        // Jika GAGAL (misal: email tidak ditemukan)
        
        // Jika ini adalah request AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'message' => __($status) // Kirim pesan error sebagai JSON
            ], 422); // 422 Unprocessable Entity
        }

        // Jika ini request browser biasa
        return back()->withInput($request->only('email'))
                     ->withErrors(['email' => __($status)]);
    }
}