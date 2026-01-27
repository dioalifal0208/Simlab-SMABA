<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response|RedirectResponse|JsonResponse // <-- Tambahkan JsonResponse
{
    try {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'guru',
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();
        $user->forceFill([
            'current_session_id' => $request->session()->getId(),
        ])->save();

        // JIKA PERMINTAAN DATANG DARI AJAX
        if ($request->expectsJson()) {
            return response()->json(['status' => 'success'], 201); // 201 Created
        }

        // Jika dari browser biasa
        return redirect()->intended(route('dashboard'));
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        // JIKA PERMINTAAN DATANG DARI AJAX DAN VALIDASI GAGAL
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $e->validator->errors()->first(),
            ], 422); // 422 Unprocessable Entity
        }

        // Jika dari browser biasa
        throw $e;
    }
}
}
