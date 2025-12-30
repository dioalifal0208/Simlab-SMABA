<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureSingleSession
{
    /**
     * Pastikan hanya sesi yang dicatat di users.current_session_id yang boleh aktif.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user) {
            $currentSessionId = $request->session()->getId();

            if ($user->current_session_id !== $currentSessionId) {
                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $message = 'Sesi Anda telah digantikan oleh login di perangkat lain.';

                if ($request->expectsJson()) {
                    return response()->json(['message' => $message], 401);
                }

                return redirect()->route('login.create')->withErrors([
                    'email' => $message,
                ]);
            }
        }

        return $next($request);
    }
}
