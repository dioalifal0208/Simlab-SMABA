<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\AdminContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ContactAdminController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validateWithBag('contactAdmin', [
            'nama'   => 'required|string|max:100',
            'email'  => 'required|email|max:150',
            'pesan'  => 'required|string|max:500',
        ]);

        try {
            $admins = User::where('role', 'admin')->get();
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new AdminContactMessage(
                    $validated['nama'],
                    $validated['email'],
                    $validated['pesan']
                ));
            }
        } catch (\Throwable $th) {
            // Diamkan jika notifikasi gagal dikirim
        }

        return back()->with('contact_submitted', 'Pesan Anda sudah dikirim ke admin lab.');
    }
}
