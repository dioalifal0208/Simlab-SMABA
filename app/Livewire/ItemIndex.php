<?php

namespace App\Livewire;

use App\Models\Item;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class ItemIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $kondisi = '';
    public $tipe = '';
    public $sort = 'created_at';
    public $direction = 'desc';

    public $selectedItems = [];
    public $selectAll = false;

    protected $listeners = ['itemDeleted' => 'render'];

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

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedItems = Item::pluck('id')->map(fn ($id) => (string) $id);
        } else {
            $this->selectedItems = [];
        }
    }

    public function sortBy($field)
    {
        if ($this->sort === $field) {
            $this->direction = $this->direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->direction = 'asc';
        }
        $this->sort = $field;
    }

    public function deleteSelected()
    {
        if (!auth()->user()->can('is-admin')) {
            abort(403);
        }

        $items = Item::whereIn('id', $this->selectedItems)->get();
        $photoPaths = $items->pluck('photo')->filter()->all();

        if (!empty($photoPaths)) {
            Storage::disk('public')->delete($photoPaths);
        }

        Item::whereIn('id', $this->selectedItems)->delete();

        $this->selectedItems = [];
        $this->selectAll = false;

        session()->flash('success', count($items) . ' item berhasil dihapus.');
    }

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
