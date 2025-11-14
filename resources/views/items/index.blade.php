<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                    {{ __('Inventaris Laboratorium') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Daftar semua alat dan bahan yang tersedia di laboratorium.</p>
            </div>
            @can('is-admin')
                <div class="mt-3 sm:mt-0 flex items-center space-x-3">
                    {{-- Tombol Impor Item (membuka modal) --}}
                    <button @click="showImportModal = true" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold text-sm shadow-sm transition-colors">
                        <i class="fas fa-upload mr-2"></i> Import Item
                    </button>

                    {{-- Tombol Tambah Item --}}
                    <a href="{{ route('items.create') }}" class="inline-block px-4 py-2 bg-smaba-dark-blue text-white text-sm font-semibold rounded-md hover:bg-smaba-light-blue shadow-sm transition-colors">
                        + Tambah Item
                    </a>
                </div>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        {{-- 
            PENAMBAHAN: 
            Inisialisasi Alpine.js untuk mengelola state modal impor dan item yang dipilih untuk hapus massal.
        --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ showImportModal: false, selectedItems: [] }">

            {{-- Pesan Sukses/Error --}}
            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">Sukses</p>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">Error</p>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- PERUBAHAN: Desain Ulang Area Filter dan Tombol Hapus Massal --}}
            <div class="mb-6" data-aos="fade-up">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    {{-- Form Filter --}}
                    <form action="{{ route('items.index') }}" method="GET" id="filter-form" class="w-full flex-grow">
                        <div class="flex items-center gap-3">
                            {{-- Input Pencarian dengan ikon --}}
                            <div class="relative flex-grow">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" name="search" id="search" placeholder="Cari nama alat..." value="{{ request('search') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue pl-10 text-sm">
                            </div>
                            {{-- Dropdown Filter --}}
                            <select name="tipe" id="tipe" class="w-auto rounded-lg border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue text-sm">
                                <option value="">Tipe</option>
                                <option value="Alat" @selected(request('tipe') == 'Alat')>Alat</option>
                                <option value="Bahan Habis Pakai" @selected(request('tipe') == 'Bahan Habis Pakai')>Bahan</option>
                            </select>
                            <select name="kondisi" id="kondisi" class="w-auto rounded-lg border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue text-sm">
                                <option value="">Kondisi</option>
                                <option value="Baik" @selected(request('kondisi') == 'Baik')>Baik</option>
                                <option value="Kurang Baik" @selected(request('kondisi') == 'Kurang Baik')>Kurang Baik</option>
                                <option value="Rusak" @selected(request('kondisi') == 'Rusak')>Rusak</option>
                            </select>
                            {{-- Tombol Reset --}}
                            <a href="{{ route('items.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold text-sm shadow-sm transition-colors" title="Reset Filter">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Tombol Aksi Hapus Massal (Muncul saat ada item terpilih) --}}
                <div x-show="selectedItems.length > 0" x-transition class="mt-4 bg-white border border-red-300 rounded-lg p-3 flex justify-between items-center" style="display: none;">
                    <span class="text-sm font-semibold text-gray-700">
                        <span x-text="selectedItems.length"></span> item terpilih
                    </span>
                    <form id="bulk-delete-form" action="{{ route('items.delete-multiple') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        {{-- Input tersembunyi untuk menampung ID item --}}
                        <template x-for="id in selectedItems" :key="id">
                            <input type="hidden" name="item_ids[]" :value="id">
                        </template>
                        <button type="button" @click="confirmBulkDelete" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold text-sm shadow-md transition-colors">
                            <i class="fas fa-trash-alt mr-2"></i> Hapus Terpilih
                        </button>
                    </form>
                </div>
            </div>

            {{-- Tabel Daftar Item --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="100">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-50">
                            <tr>
                                {{-- PENAMBAHAN: Checkbox "Pilih Semua" --}}
                                <th class="py-4 px-4">
                                    {{-- 
                                        PERBAIKAN: 
                                        Logika untuk memilih semua item dipindahkan langsung ke sini agar berjalan dalam lingkup Alpine.js.
                                        Saat dicentang, ia akan mengisi `selectedItems` dengan semua ID item yang ada di halaman.
                                        Saat centang dihilangkan, ia akan mengosongkan array `selectedItems`.
                                    --}}
                                    <input type="checkbox" @click="selectedItems = $event.target.checked ? Array.from(document.querySelectorAll('#item-list input[type=\'checkbox\']')).map(cb => cb.value) : []" class="rounded border-gray-300 text-smaba-dark-blue shadow-sm focus:ring-smaba-light-blue">
                                </th>
                                <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Alat/Bahan</th>
                                <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Tipe</th>
                                <th class="py-4 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                <th class="py-4 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Kondisi</th>
                                <th class="py-4 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 divide-y divide-gray-200" id="item-list">
                            @forelse ($items as $item)
                                <tr class="hover:bg-gray-50" :class="{'bg-blue-50': selectedItems.includes('{{ $item->id }}')}">
                                    {{-- PENAMBAHAN: Checkbox per baris --}}
                                    <td class="py-4 px-4">
                                        <input type="checkbox" x-model="selectedItems" value="{{ $item->id }}" class="rounded border-gray-300 text-smaba-dark-blue shadow-sm focus:ring-smaba-light-blue">
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                @if($item->photo)
                                                    <img
                                                        class="h-12 w-12 rounded-md object-cover"
                                                        src="{{ asset('storage/' . $item->photo) }}"
                                                        alt="{{ $item->nama_alat }}"
                                                    >
                                                @else
                                                    <div class="h-12 w-12 rounded-md bg-gray-100 flex items-center justify-center text-gray-400">
                                                        <i class="fas fa-image"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $item->nama_alat }}</div>
                                                <div class="text-xs text-gray-500 md:hidden">{{ $item->tipe }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-sm hidden md:table-cell">{{ $item->tipe }}</td>
                                    <td class="py-4 px-6 text-sm text-center">
                                        {{ $item->jumlah }} {{ $item->satuan }}
                                        @if($item->stok_minimum && $item->jumlah < $item->stok_minimum)
                                            <span class="block text-xs text-red-600 font-semibold">Stok Rendah</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center hidden sm:table-cell">
                                        @if($item->kondisi == 'Baik')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Baik</span>
                                        @elseif($item->kondisi == 'Kurang Baik')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Kurang Baik</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rusak</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <a href="{{ route('items.show', $item->id) }}" class="px-4 py-2 bg-smaba-dark-blue text-white rounded-md hover:bg-smaba-light-blue font-semibold text-xs shadow-sm transition-colors duration-300">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-500">
                                        <p class="font-semibold">Tidak Ada Item Ditemukan</p>
                                        <p class="text-sm mt-1">Coba ubah filter pencarian Anda atau tambahkan item baru.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Paginasi --}}
                <div class="p-4 border-t border-gray-200">
                    {{ $items->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- =============================================== --}}
    {{-- ##       PENAMBAHAN: MODAL IMPOR ITEM        ## --}}
    {{-- =============================================== --}}
    @can('is-admin')
        {{-- Latar belakang gelap modal --}}
        <div x-show="showImportModal" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" 
            style="display: none;">
            
            {{-- Kontainer konten modal --}}
            <div @click.outside="showImportModal = false" 
                class="w-full max-w-lg bg-white p-6 sm:p-8 rounded-xl shadow-lg relative">
                @include('items.modal-import')
            </div>
        </div>
    @endcan

    {{-- =================================================================== --}}
    {{-- ## PENAMBAHAN: SCRIPT UNTUK FILTER OTOMATIS & HAPUS MASSAL       ## --}}
    {{-- =================================================================== --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // --- Logika untuk Filter Otomatis ---
                const filterForm = document.getElementById('filter-form');
                const tipeSelect = document.getElementById('tipe');
                const kondisiSelect = document.getElementById('kondisi');

                if(tipeSelect) tipeSelect.addEventListener('change', () => filterForm.submit());
                if(kondisiSelect) kondisiSelect.addEventListener('change', () => filterForm.submit());
            });

            // --- Logika untuk Konfirmasi Hapus Massal dengan Alpine & SweetAlert ---

            function confirmBulkDelete() {
                // `selectedItems` adalah properti dari x-data Alpine.js
                if (this.selectedItems.length === 0) {
                    Swal.fire('Tidak Ada Item', 'Silakan pilih item yang ingin dihapus.', 'info');
                    return;
                }

                Swal.fire({
                    title: `Hapus ${this.selectedItems.length} item?`,
                    text: "Tindakan ini tidak dapat dibatalkan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, hapus semua!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form hapus massal
                        document.getElementById('bulk-delete-form').submit();
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
