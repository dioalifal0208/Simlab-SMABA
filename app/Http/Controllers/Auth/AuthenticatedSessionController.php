<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request)
{
    // Jika URL saat ini adalah /register, set mode ke 'signup'
    $mode = $request->routeIs('register') ? 'signup' : 'login';

    return view('auth.login', ['mode' => $mode]);
}

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): Response|RedirectResponse|JsonResponse // <-- Tambahkan JsonResponse
{
    try {
        $request->authenticate();

        $request->session()->regenerate();

        // JIKA PERMINTAAN DATANG DARI AJAX
        if ($request->expectsJson()) {
            return response()->json(['status' => 'success'], 200);
        }

        // Jika dari browser biasa
        return redirect()->intended(route('dashboard'));

    } catch (\Illuminate\Validation\ValidationException $e) {
        // JIKA PERMINTAAN DATANG DARI AJAX DAN VALIDASI GAGAL (Email/Password salah)
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $e->validator->errors()->first(),
            ], 422); // 422 Unprocessable Entity
        }
        
        // Jika dari browser biasa
        throw $e;
    }
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
{
    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    // PASTIKAN BARIS INI MENGARAH KE '/'
    return redirect('/'); 
}
}
