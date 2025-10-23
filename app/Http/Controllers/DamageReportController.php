<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\DamageReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DamageReportController extends Controller
{
    use AuthorizesRequests;

    /**
     * Menampilkan form untuk membuat laporan kerusakan baru.
     */
    public function create(Item $item)
    {
        return view('damage-reports.create', compact('item'));
    }

    /**
     * Menyimpan laporan kerusakan yang baru disubmit.
     */
    public function store(Request $request, Item $item)
    {
        $request->validate([
            'description' => 'required|string|min:10',
            'photo' => 'nullable|image|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('damage_reports', 'public');
        }

        DamageReport::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'description' => $request->description,
            'photo' => $photoPath,
            'status' => 'Dilaporkan',
        ]);

        $item->update(['kondisi' => 'Rusak']);

        return redirect()->route('items.show', $item->id)
            ->with('success', 'Laporan kerusakan berhasil dikirim. Kondisi item telah diupdate menjadi "Rusak".');
    }

    /**
     * Menampilkan daftar semua laporan kerusakan (untuk Admin).
     */
    public function index(Request $request)
    {
        $this->authorize('is-admin');
        $query = DamageReport::with(['user', 'item'])->latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $reports = $query->paginate(15);
        return view('damage-reports.index', ['reports' => $reports]);
    }

    /**
     * Menampilkan detail satu laporan kerusakan (untuk Admin).
     */
    public function show(DamageReport $report)
    {
        $this->authorize('is-admin');
        $report->load(['item', 'user']);
        return view('damage-reports.show', compact('report'));
    }

    /**
     * Mengupdate status laporan kerusakan (untuk Admin).
     */
    public function update(Request $request, DamageReport $report)
    {
        $this->authorize('is-admin');

        $request->validate([
            'status' => 'required|in:Diverifikasi,Diperbaiki',
        ]);

        $report->update([
            'status' => $request->status,
        ]);

        if ($request->status == 'Diperbaiki') {
            $report->load('item');
            $report->item->update(['kondisi' => 'Baik']);
            return back()->with('success', 'Status laporan telah diupdate. Kondisi item terkait telah dikembalikan menjadi "Baik".');
        }

        return back()->with('success', 'Status laporan berhasil diupdate.');
    }

    /**
     * PENAMBAHAN: Menghapus laporan kerusakan.
     */
    public function destroy(DamageReport $report)
    {
        // Otorisasi: Hanya admin
        $this->authorize('is-admin');

        // 1. Hapus foto terkait jika ada dari storage 'public'
        if ($report->photo && Storage::disk('public')->exists($report->photo)) {
            Storage::disk('public')->delete($report->photo);
        }

        // 2. Hapus laporan dari database
        $report->delete();

        // 3. Redirect ke halaman daftar laporan dengan pesan sukses
        return redirect()->route('damage-reports.index')
               ->with('success', 'Laporan kerusakan berhasil dihapus.');
    }
}