<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewTestimonialSubmitted;

class TestimonialController extends Controller
{
    /**
     * Daftar testimoni untuk admin (moderasi).
     */
    public function index()
    {
        Gate::authorize('is-admin');

        $pending = Testimonial::where('status', 'pending')->latest()->get();
        $approved = Testimonial::where('status', 'approved')->latest()->get();

        return view('admin.testimonials.index', compact('pending', 'approved'));
    }

    /**
     * Simpan testimoni baru (status pending).
     */
    public function store(Request $request)
    {
        $validated = $request->validateWithBag('testimonial', [
            'nama'         => 'required|string|max:100',
            'peran'        => 'nullable|string|max:50',
            'laboratorium' => 'nullable|in:Biologi,Fisika,Bahasa,Komputer 1,Komputer 2,Komputer 3,Komputer 4',
            'pesan'        => 'required|string|max:500',
        ]);

        Testimonial::create([
            'nama'         => $validated['nama'],
            'peran'        => $validated['peran'] ?? null,
            'laboratorium' => $validated['laboratorium'] ?? null,
            'pesan'        => $validated['pesan'],
            'status'       => 'pending',
        ]);

        // Beri tahu admin ada testimoni baru
        try {
            $admins = User::where('role', 'admin')->get();
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new NewTestimonialSubmitted($validated['nama']));
            }
        } catch (\Throwable $th) {
            // abaikan jika gagal kirim notifikasi
        }

        return back()->with('testimonial_submitted', 'Terima kasih, testimoni Anda sudah dikirim dan menunggu persetujuan admin.');
    }

    /**
     * Update status testimoni (approve/reject).
     */
    public function updateStatus(Request $request, Testimonial $testimonial)
    {
        Gate::authorize('is-admin');

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $testimonial->update(['status' => $validated['status']]);

        return back()->with('success', 'Status testimoni diperbarui.');
    }
}
