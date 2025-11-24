<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorLoginController extends Controller
{
    /**
     * Tampilkan form input OTP.
     */
    public function show(Request $request): RedirectResponse|View
    {
        if (!$request->session()->has('2fa:user:id')) {
            return redirect()->route('login.create');
        }

        return view('auth.two-factor-challenge');
    }

    /**
     * Verifikasi OTP dan login.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $userId = $request->session()->get('2fa:user:id');
        $remember = $request->session()->get('2fa:remember', false);

        $user = $userId ? User::find($userId) : null;

        if (!$user || !$user->two_factor_secret) {
            return redirect()->route('login.create')->withErrors([
                'email' => 'Sesi login tidak valid atau sudah kadaluarsa. Silakan login ulang.',
            ]);
        }

        $google2fa = new Google2FA();
        $secret = decrypt($user->two_factor_secret);

        $code = preg_replace('/\s+/', '', $request->code);
        // window 8 = toleransi drift waktu lebih lebar (sekitar 4 interval ke depan/belakang)
        $totpValid = $google2fa->verifyKey($secret, $code, 8);

        $recoveryValid = false;
        if (!$totpValid && $user->two_factor_recovery_codes) {
            $codes = is_array($user->two_factor_recovery_codes)
                ? $user->two_factor_recovery_codes
                : json_decode($user->two_factor_recovery_codes, true) ?? [];

            foreach ($codes as $index => $hashed) {
                if (Hash::check($code, $hashed)) {
                    $recoveryValid = true;
                    // hapus kode yang sudah dipakai
                    unset($codes[$index]);
                    $user->forceFill([
                        'two_factor_recovery_codes' => json_encode(array_values($codes)),
                    ])->save();
                    break;
                }
            }
        }

        if (!$totpValid && !$recoveryValid) {
            throw ValidationException::withMessages([
                'code' => 'Kode tidak valid. Pastikan OTP dari aplikasi atau recovery code benar.',
            ]);
        }

        $this->clearSession($request, $user);

        Auth::login($user, $remember);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Bersihkan jejak OTP dari sesi dan database.
     */
    protected function clearSession(Request $request, User $user): void
    {
        $request->session()->forget(['2fa:user:id', '2fa:remember']);

        $user->forceFill([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ])->save();
    }
}
