<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination; // <-- Penting untuk paginasi AJAX

class ItemIndex extends Component
{
    use WithPagination;

    // 1. PROPERTI UNTUK STATE
    // Properti ini akan secara otomatis terhubung dengan input di frontend
    public $search = '';
    public $kondisi = '';
    public $tipe = '';
    public $sort = 'created_at';
    public $direction = 'desc';

    // Properti untuk fitur hapus massal
    public $selectedItems = [];
    public $selectAll = false;

    // 2. LISTENER UNTUK EVENT
    // Jika ada event 'itemDeleted', panggil method 'render' untuk refresh data
    protected $listeners = [
        'itemDeleted' => 'render',
        'itemImported' => 'render' // <-- TAMBAHAN: Dengarkan event setelah impor sukses
    ];

    // 3. LIFECYCLE HOOKS
    // Method ini akan berjalan setiap kali properti $search, $kondisi, atau $tipe diubah.
    // Fungsinya adalah untuk mereset paginasi ke halaman pertama.
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingKondisi()
    {
        $this->resetPage();
    }

    public function updatingTipe()
    {
        $this->resetPage();
    }

    // Method ini berjalan setiap kali properti $selectAll diubah
    public function updatedSelectAll($value)
    {
        if ($value) {
            // Jika "Select All" dicentang, isi $selectedItems dengan semua ID item di halaman saat ini
            $this->selectedItems = Item::pluck('id')->map(fn ($id) => (string) $id);
        } else {
            // Jika tidak, kosongkan
            $this->selectedItems = [];
        }
    }

    // 4. METHOD UNTUK AKSI
    // Method ini akan dipanggil saat header tabel di-klik
    public function sortBy($field)
    {
        if ($this->sort === $field) {
            $this->direction = $this->direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->direction = 'asc';
        }
        $this->sort = $field;
    }
    // Method untuk menghapus item yang dipilih
    public function deleteSelected()
    {
        // Otorisasi: pastikan hanya admin
        if (Gate::denies('is-admin')) {
            abort(403);
        }

        $items = Item::whereIn('id', $this->selectedItems)->get();
        $items = Item::whereIn('id', $this->selectedItems)->get();
        $photoPaths = $items->pluck('photo')->filter()->all();

        if (!empty($photoPaths)) {
            Storage::disk('public')->delete($photoPaths);
        }

        Item::whereIn('id', $this->selectedItems)->delete();

        // Reset state setelah hapus
        $this->selectedItems = [];
        $this->selectAll = false;

        // Kirim pesan sukses
        session()->flash('success', count($items) . ' item berhasil dihapus.');
    }

    // 5. METHOD RENDER (UTAMA)
    // Method ini yang akan menampilkan data ke view
    public function render()
    {
        $query = Item::with(['user', 'activeLoans'])
            ->when($this->search, fn ($q) => $q->where('nama_alat', 'like', '%' . $this->search . '%'))
            ->when($this->kondisi, fn ($q) => $q->where('kondisi', $this->kondisi))
            ->when($this->tipe, fn ($q) => $q->where('tipe', $this->tipe));

        $items = $query->orderBy($this->sort, $this->direction)->paginate(12);

        return view('livewire.item-index', [
            'items' => $items,
        ]);
    }
}

?>