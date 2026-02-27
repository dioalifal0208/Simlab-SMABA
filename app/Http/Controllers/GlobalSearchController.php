<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Document;
use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GlobalSearchController extends Controller
{
    /**
     * Navigasi cepat yang tersedia untuk semua user.
     */
    protected function navItems(): array
    {
        $user = Auth::user();
        $nav  = [
            ['title' => 'Dashboard',         'subtitle' => 'Halaman utama',            'url' => route('dashboard'),           'icon' => 'fa-gauge-high'],
            ['title' => 'Inventaris',         'subtitle' => 'Daftar alat & bahan lab', 'url' => route('items.index'),         'icon' => 'fa-boxes-stacked'],
            ['title' => 'Peminjaman Alat',    'subtitle' => 'Ajukan atau lihat pinjaman', 'url' => route('loans.index'),      'icon' => 'fa-hand-holding'],
            ['title' => 'Booking Lab',        'subtitle' => 'Jadwal penggunaan lab',   'url' => route('bookings.index'),      'icon' => 'fa-calendar-check'],
            ['title' => 'Kalender',           'subtitle' => 'Jadwal lab sebulan penuh', 'url' => route('calendar.index'),    'icon' => 'fa-calendar-days'],
            ['title' => 'Dokumen Digital',    'subtitle' => 'Pustaka & file lab',      'url' => route('documents.index'),     'icon' => 'fa-file-lines'],
            ['title' => 'Modul Praktikum',   'subtitle' => 'Panduan kegiatan lab',    'url' => route('practicum-modules.index'), 'icon' => 'fa-flask'],
            ['title' => 'Profil Saya',        'subtitle' => 'Pengaturan akun',         'url' => route('profile.edit'),        'icon' => 'fa-circle-user'],
        ];

        if ($user->role === 'guru') {
            $nav[] = ['title' => 'Ajukan Alat/Bahan', 'subtitle' => 'Request pengadaan ke admin', 'url' => route('item-requests.create'), 'icon' => 'fa-paper-plane'];
        }

        if ($user->role === 'admin') {
            $nav[] = ['title' => 'Manajemen User',    'subtitle' => 'Kelola akun guru',      'url' => route('users.index'),                        'icon' => 'fa-users-gear'];
            $nav[] = ['title' => 'Laporan',           'subtitle' => 'Rekap & ekspor data',   'url' => route('reports.index'),                      'icon' => 'fa-chart-bar'];
            $nav[] = ['title' => 'Laporan Kerusakan', 'subtitle' => 'Alat rusak & tindakan', 'url' => route('damage-reports.index'),               'icon' => 'fa-triangle-exclamation'];
            $nav[] = ['title' => 'Pengumuman',        'subtitle' => 'Kelola pengumuman lab',  'url' => route('announcements.index'),                'icon' => 'fa-bullhorn'];
            $nav[] = ['title' => 'Log Aktivitas',     'subtitle' => 'Audit trail sistem',    'url' => route('audit-logs.index'),                   'icon' => 'fa-shield-halved'];
            $nav[] = ['title' => 'Permintaan Alat',   'subtitle' => 'Review permintaan guru', 'url' => route('admin.item-requests.index'),         'icon' => 'fa-inbox'];
        }

        return $nav;
    }

    /**
     * Global search endpoint — returns JSON grouped results.
     */
    public function search(Request $request): JsonResponse
    {
        $q    = trim($request->get('q', ''));
        $user = Auth::user();

        // Jika query kosong, kembalikan hanya navigasi cepat
        if (mb_strlen($q) < 2) {
            return response()->json([
                'nav'       => $this->navItems(),
                'items'     => [],
                'documents' => [],
                'bookings'  => [],
                'loans'     => [],
                'empty'     => true,
            ]);
        }

        $like      = "%{$q}%";
        $isAdmin   = $user->role === 'admin';
        $userLab   = $user->laboratorium;

        // ── 1. Alat / Bahan ─────────────────────────────────────
        $itemQuery = Item::query()
            ->where(function ($sub) use ($like) {
                $sub->where('nama_alat',        'like', $like)
                    ->orWhere('kode_inventaris', 'like', $like)
                    ->orWhere('deskripsi',       'like', $like)
                    ->orWhere('tipe',            'like', $like);
            });

        if (!$isAdmin && $userLab) {
            $itemQuery->where('laboratorium', $userLab);
        }

        $items = $itemQuery->limit(5)->get()->map(fn($item) => [
            'id'       => $item->id,
            'title'    => $item->nama_alat,
            'subtitle' => ($item->kode_inventaris ? $item->kode_inventaris . ' · ' : '') . $item->laboratorium . ' · ' . $item->kondisi,
            'url'      => route('items.show', $item->id),
            'icon'     => $item->tipe === 'Bahan' ? 'fa-flask' : 'fa-wrench',
            'category' => 'items',
        ]);

        // ── 2. Dokumen Digital ───────────────────────────────────
        $documents = Document::query()
            ->visibleTo($user)
            ->where(function ($sub) use ($like) {
                $sub->where('title',       'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhere('file_name',   'like', $like);
            })
            ->limit(5)
            ->get()
            ->map(fn($doc) => [
                'id'       => $doc->id,
                'title'    => $doc->title,
                'subtitle' => $doc->file_name . ' · ' . strtoupper($doc->file_type ?? '-'),
                'url'      => route('documents.preview', $doc->id),
                'icon'     => 'fa-file-pdf',
                'category' => 'documents',
            ]);

        // ── 3. Booking Lab ───────────────────────────────────────
        $bookingQuery = Booking::query()
            ->where(function ($sub) use ($like) {
                $sub->where('tujuan_kegiatan', 'like', $like)
                    ->orWhere('mata_pelajaran',  'like', $like)
                    ->orWhere('guru_pengampu',   'like', $like)
                    ->orWhere('laboratorium',    'like', $like);
            });

        if (!$isAdmin) {
            // Guru hanya melihat booking yang dibuat oleh dirinya atau di lab-nya
            $bookingQuery->where(function ($sub) use ($user, $userLab) {
                $sub->where('user_id', $user->id);
                if ($userLab) {
                    $sub->orWhere('laboratorium', $userLab);
                }
            });
        }

        $bookings = $bookingQuery->with('user')->latest()->limit(5)->get()->map(fn($b) => [
            'id'       => $b->id,
            'title'    => $b->tujuan_kegiatan,
            'subtitle' => $b->laboratorium . ' · ' . $b->waktu_mulai->format('d M Y') . ' · ' . ucfirst($b->status),
            'url'      => route('bookings.show', $b->id),
            'icon'     => 'fa-calendar-check',
            'category' => 'bookings',
        ]);

        // ── 4. Peminjaman Alat ───────────────────────────────────
        $loanQuery = Loan::query()
            ->where(function ($sub) use ($like) {
                $sub->where('catatan', 'like', $like)
                    ->orWhereHas('items', fn($i) => $i->where('nama_alat', 'like', $like));
            });

        if (!$isAdmin) {
            // Guru hanya melihat peminjaman dari lab-nya atau miliknya sendiri
            $loanQuery->where(function ($sub) use ($user, $userLab) {
                $sub->where('user_id', $user->id);
                if ($userLab) {
                    $sub->orWhere('laboratorium', $userLab);
                }
            });
        }

        $loans = $loanQuery->with('items')->latest()->limit(5)->get()->map(fn($loan) => [
            'id'       => $loan->id,
            'title'    => $loan->items->pluck('nama_alat')->take(2)->join(', ') ?: 'Peminjaman #' . $loan->id,
            'subtitle' => 'Pinjam ' . $loan->tanggal_pinjam->format('d M Y') . ' · ' . ucfirst($loan->status),
            'url'      => route('loans.show', $loan->id),
            'icon'     => 'fa-hand-holding',
            'category' => 'loans',
        ]);

        // ── 5. Navigasi (filter statis berdasarkan query) ──────
        $filtered = array_filter($this->navItems(), fn($nav) =>
            str_contains(mb_strtolower($nav['title']), mb_strtolower($q))
            || str_contains(mb_strtolower($nav['subtitle']), mb_strtolower($q))
        );

        return response()->json([
            'items'     => $items,
            'documents' => $documents,
            'bookings'  => $bookings,
            'loans'     => $loans,
            'nav'       => array_values($filtered),
            'empty'     => false,
        ]);
    }
}
