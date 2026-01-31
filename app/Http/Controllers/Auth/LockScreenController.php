<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LockScreenController extends Controller
{
    /**
     * Unlock the screen with password validation.
     */
    public function unlock(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = Auth::user();

        if (Hash::check($request->password, $user->password)) {
            // Reset session activity timestamp if needed, but for now just return success
            return response()->json(['success' => true]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Password yang Anda masukkan salah.'
        ], 422);
    }
}
