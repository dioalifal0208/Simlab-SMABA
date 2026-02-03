<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

#[Layout('layouts.guest')]
class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    // Custom properties for error handling
    public $emailError = '';

    public function updatedEmail()
    {
        $this->emailError = ''; 
        
        // Basic format validation first
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
             return;
        }

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            $this->emailError = 'Email tidak terdaftar dalam sistem kami.';
            $this->addError('email', $this->emailError);
        }
    }

    public function login(Request $request)
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            // Log failed login
            AuditLog::create([
                'user_id' => null,
                'action' => 'failed_login',
                'model' => 'Auth',
                'details' => ['email' => $this->email],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            $this->addError('base', trans('auth.failed'));
            return;
        }

        RateLimiter::clear($this->throttleKey());

        $user = Auth::user();

        // 2FA Check
        if ($this->shouldUseTwoFactor($user)) {
             session([
                '2fa:user:id' => $user->id,
                '2fa:remember' => $this->remember,
            ]);
            
            // Logout immediate to prevent full access until 2FA
            Auth::logout();
            
            return redirect()->route('two-factor.index');
        }

        session()->regenerate();
        $this->invalidateOtherSessions($user, session()->getId());
        $this->setCurrentSessionId($user, session()->getId());

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'login',
            'model' => 'Auth',
            'details' => ['method' => 'password'],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->intended(route('dashboard'));
    }

    protected function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'base' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey()
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }

    protected function shouldUseTwoFactor($user)
    {
        return in_array($user->role, ['admin', 'guru'], true)
            && $user->two_factor_enabled
            && $user->two_factor_secret;
    }

    protected function invalidateOtherSessions($user, $currentSessionId)
    {
        if (! $user || config('session.driver') !== 'database') {
            return;
        }

        DB::table(config('session.table', 'sessions'))
            ->where('user_id', $user->id)
            ->where('id', '<>', $currentSessionId)
            ->delete();
    }

    protected function setCurrentSessionId($user, $currentSessionId)
    {
        if (! $user) return;
        
        $user->forceFill([
            'current_session_id' => $currentSessionId,
        ])->save();
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
