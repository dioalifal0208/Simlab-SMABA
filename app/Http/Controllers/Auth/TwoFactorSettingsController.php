<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorSettingsController extends Controller
{
    /**
     * Mulai setup: buat secret sementara dan simpan di session.
     */
    public function start(Request $request): RedirectResponse
    {
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();

        $request->session()->put('2fa:setup:secret', $secret);

        return Redirect::route('profile.edit')->with('status', 'Kode rahasia dibuat. Scan QR dan masukkan OTP untuk mengaktifkan.');
    }

    /**
     * Konfirmasi OTP dari Google Authenticator, simpan secret + recovery codes.
     */
    public function confirm(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $secret = $request->session()->get('2fa:setup:secret');

        if (!$secret) {
            return Redirect::route('profile.edit')->withErrors([
                'code' => 'Mulai dulu dengan membuat QR kode.',
            ]);
        }

        $google2fa = new Google2FA();
        $code = preg_replace('/\s+/', '', $request->code);
        // window 8 memberikan toleransi waktu lebih lebar (sekitar 4 interval ke depan/belakang)
        $valid = $google2fa->verifyKey($secret, $code, 8); 

        if (!$valid) {
            return Redirect::route('profile.edit')->withErrors([
                'code' => 'Kode OTP tidak valid.',
            ]);
        }

        $recoveryPlain = $this->generateRecoveryCodes();
        $hashedRecovery = collect($recoveryPlain)->map(fn($code) => Hash::make($code))->all();

        $request->user()->forceFill([
            'two_factor_enabled' => true,
            'two_factor_secret' => encrypt($secret),
            'two_factor_recovery_codes' => json_encode($hashedRecovery),
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ])->save();

        $request->session()->forget('2fa:setup:secret');
        $request->session()->flash('two_factor_recovery_plain', $recoveryPlain);

        return Redirect::route('profile.edit')->with('status', 'Google Authenticator diaktifkan.');
    }

    /**
     * Nonaktifkan 2FA.
     */
    public function disable(Request $request): RedirectResponse
    {
        $request->user()->forceFill([
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ])->save();

        $request->session()->forget('2fa:setup:secret');

        return Redirect::route('profile.edit')->with('status', 'Google Authenticator dimatikan.');
    }

    /**
     * Regenerasi recovery codes baru (tampilkan sekali).
     */
    public function regenerateRecoveryCodes(Request $request): RedirectResponse
    {
        if (!$request->user()->two_factor_enabled || !$request->user()->two_factor_secret) {
            return Redirect::route('profile.edit')->withErrors([
                'recovery' => 'Aktifkan 2FA dulu sebelum membuat recovery codes.',
            ]);
        }

        $recoveryPlain = $this->generateRecoveryCodes();
        $hashedRecovery = collect($recoveryPlain)->map(fn($code) => Hash::make($code))->all();

        $request->user()->forceFill([
            'two_factor_recovery_codes' => json_encode($hashedRecovery),
        ])->save();

        $request->session()->flash('two_factor_recovery_plain', $recoveryPlain);

        return Redirect::route('profile.edit')->with('status', 'Recovery codes diperbarui.');
    }

    /**
     * Buat 8 kode recovery acak.
     */
    protected function generateRecoveryCodes(): array
    {
        return collect(range(1, 8))->map(function () {
            return strtoupper(bin2hex(random_bytes(4)));
        })->all();
    }
}
