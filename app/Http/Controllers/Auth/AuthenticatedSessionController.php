<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\AuditLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use App\Models\User;
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
            // Validasi rate limit
            $request->ensureIsNotRateLimited();

            $credentials = $request->only('email', 'password');
            $remember = $request->boolean('remember');

            // Cek kredensial tanpa login penuh (untuk alur 2FA)
            if (!Auth::validate($credentials)) {
                RateLimiter::hit($request->throttleKey());
                
                // Log failed login attempt
                AuditLog::create([
                    'user_id' => null,
                    'action' => 'failed_login',
                    'model' => 'Auth',
                    'details' => ['email' => $request->email],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                throw ValidationException::withMessages([
                    'email' => trans('auth.failed'),
                ]);
            }

            RateLimiter::clear($request->throttleKey());

            $user = User::where('email', $request->email)->firstOrFail();

            if ($this->shouldUseTwoFactor($user)) {
                session([
                    '2fa:user:id' => $user->id,
                    '2fa:remember' => $remember,
                ]);

                // Respon untuk AJAX
                if ($request->expectsJson()) {
                    return response()->json(['status' => 'otp_required'], 202);
                }

                return redirect()->route('two-factor.index');
            }

            // Jika 2FA tidak diwajibkan, login biasa
            Auth::attempt($credentials, $remember);

            $request->session()->regenerate();
            $this->invalidateOtherSessions(Auth::user(), $request);
            $this->setCurrentSessionId(Auth::user(), $request);
            
            // Log successful login
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'login',
                'model' => 'Auth',
                'details' => ['method' => 'password'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

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
    $user = $request->user();

    if ($user) {
        // Log logout before destroying session
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'logout',
            'model' => 'Auth',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        $user->forceFill(['current_session_id' => null])->save();
    }

    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    // PASTIKAN BARIS INI MENGARAH KE '/'
    return redirect('/'); 
}

    /**
     * Tentukan apakah user wajib 2FA (berdasarkan role).
     */
    protected function shouldUseTwoFactor(User $user): bool
    {
        return in_array($user->role, ['admin', 'guru'], true)
            && $user->two_factor_enabled
            && $user->two_factor_secret;
    }

    /**
     * Hapus sesi lain milik user agar hanya satu sesi yang aktif.
     */
    protected function invalidateOtherSessions(?User $user, Request $request): void
    {
        if (! $user || config('session.driver') !== 'database') {
            return;
        }

        $sessionTable = config('session.table', 'sessions');

        DB::table($sessionTable)
            ->where('user_id', $user->id)
            ->where('id', '<>', $request->session()->getId())
            ->delete();
    }

    /**
     * Simpan ID sesi yang sedang aktif pada user.
     */
    protected function setCurrentSessionId(?User $user, Request $request): void
    {
        if (! $user) {
            return;
        }

        $user->forceFill([
            'current_session_id' => $request->session()->getId(),
        ])->save();
    }
}
