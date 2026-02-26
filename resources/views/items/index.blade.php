<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                    {{ __('items.title') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('items.subtitle') }}</p>
            </div>
            <div class="mt-3 sm:mt-0 flex items-center space-x-3">
                @can('is-admin')
                    {{-- Tombol Impor Item (membuka modal) --}}
                    <button @click="showImportModal = true" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold text-sm shadow-sm transition-colors">
                        <i class="fas fa-upload mr-2"></i> {{ __('items.actions.import') }}
                    </button>

                    {{-- Tombol Tambah Item --}}
                    <a href="{{ route('items.create') }}" class="inline-block px-4 py-2 bg-smaba-dark-green text-white text-sm font-semibold rounded-md hover:bg-smaba-light-green shadow-sm transition-colors">
                        + {{ __('items.actions.add') }}
                    </a>
                @else
                    {{-- Guru/Staf: Ajukan penambahan item --}}
                    <a href="{{ route('item-requests.create') }}" class="inline-flex items-center px-4 py-2 bg-smaba-dark-green text-white text-sm font-semibold rounded-md hover:bg-smaba-light-green shadow-sm transition-colors">
                        <i class="fas fa-plus mr-2"></i> {{ __('items.actions.request_add') }}
                    </a>
                @endcan
            </div>
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
                    <p class="font-bold">{{ __('common.messages.success') }}</p>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">{{ __('common.messages.error') }}</p>
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
                                <input type="text" name="search" id="search" placeholder="{{ __('items.filters.search') }}" value="{{ request('search') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue pl-10 text-sm">
                            </div>
                            {{-- Dropdown Filter --}}
                            <select name="tipe" id="tipe" class="w-auto rounded-lg border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue text-sm">
                                <option value="">{{ __('items.filters.type') }}</option>
                                <option value="Alat" @selected(request('tipe') == 'Alat')>{{ __('items.categories.alat') }}</option>
                                <option value="Bahan Habis Pakai" @selected(request('tipe') == 'Bahan Habis Pakai')>{{ __('items.categories.bahan') }}</option>
                            </select>
                            <select name="kondisi" id="kondisi" class="w-auto rounded-lg border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue text-sm">
                                <option value="">{{ __('items.filters.condition') }}</option>
                                <option value="Baik" @selected(request('kondisi') == 'Baik')>{{ __('items.conditions.baik') }}</option>
                                <option value="Kurang Baik" @selected(request('kondisi') == 'Kurang Baik')>{{ __('items.conditions.rusak_ringan') }}</option>
                                <option value="Rusak" @selected(request('kondisi') == 'Rusak')>{{ __('items.conditions.rusak_berat') }}</option>
                            </select>
                            @php
                                $isAdmin = auth()->user()?->role === 'admin';
                                $lockedLab = auth()->user()?->laboratorium;
                            @endphp
                            <div class="flex items-center gap-2">
                                <select name="laboratorium" id="laboratorium" class="w-auto rounded-lg border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue text-sm" {{ $isAdmin ? '' : 'disabled' }}>
                                    <option value="">{{ __('items.filters.all_labs') }}</option>
                                    <option value="Biologi" @selected(request('laboratorium', $lockedLab) == 'Biologi')>{{ __('common.labs.biologi') }}</option>
                                    <option value="Fisika" @selected(request('laboratorium', $lockedLab) == 'Fisika')>{{ __('common.labs.fisika') }}</option>
                                    <option value="Bahasa" @selected(request('laboratorium', $lockedLab) == 'Bahasa')>{{ __('common.labs.bahasa') }}</option>
                                </select>
                                @unless($isAdmin)
                                    <input type="hidden" name="laboratorium" value="{{ request('laboratorium', $lockedLab) }}">
                                    <span class="text-xs text-gray-500">{{ __('items.filters.locked_lab') }}</span>
                                @endunless
                            </div>
                            {{-- Tombol Reset --}}
                            <a href="{{ route('items.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold text-sm shadow-sm transition-colors" title="{{ __('items.actions.reset_filters') }}">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Tombol Aksi Hapus Massal (Muncul saat ada item terpilih) --}}
                <div x-show="selectedItems.length > 0" x-transition class="mt-4 bg-white border border-red-300 rounded-lg p-3 flex justify-between items-center" style="display: none;">
                    <span class="text-sm font-semibold text-gray-700">
                        <span x-text="selectedItems.length"></span> {{ __('items.status.selected_count', ['count' => '']) }}
                    </span>
                    <form id="bulk-delete-form" action="{{ route('items.delete-multiple') }}" method="POST">
                        @csrf
                        {{-- Input tersembunyi untuk menampung ID item --}}
                        <template x-for="id in selectedItems" :key="id">
                            <input type="hidden" name="item_ids[]" :value="id">
                        </template>
                        <button type="button" @click="confirmBulkDelete(selectedItems)" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold text-sm shadow-md transition-colors">
                            <i class="fas fa-trash-alt mr-2"></i> {{ __('items.actions.bulk_delete') }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- Tabel Daftar Item (AJAX Container) --}}
            <div id="table-container" class="bg-white border border-gray-100 overflow-hidden shadow-sm sm:rounded-xl" data-aos="fade-up" data-aos-delay="100">
                @include('items.partials.item-table')
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
        {{-- Product Tour CSS & JS --}}
        <link rel="stylesheet" href="{{ asset('css/dashboard-tour.css') }}">
        <script src="{{ asset('js/items-tour.js') }}"></script>
        
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // --- Logika untuk Filter Otomatis ---
                const filterForm = document.getElementById('filter-form');
                const tipeSelect = document.getElementById('tipe');
                const kondisiSelect = document.getElementById('kondisi');
                const labSelect = document.getElementById('laboratorium');
                const searchInput = document.getElementById('search');

                const tableContainer = document.getElementById('table-container');

                // Fungsi utama untuk fetch data via AJAX
                function fetchItems(url) {
                    // Tampilkan indikator loading (opsional, bisa nambah spinner)
                    tableContainer.style.opacity = '0.5';

                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        tableContainer.innerHTML = html;
                        tableContainer.style.opacity = '1';
                        
                        // Update URL browser tanpa reload
                        window.history.pushState(null, '', url);
                        
                        // Re-initialize event listener untuk pagination yang baru
                        initPaginationListeners();
                    })
                    .catch(error => {
                        console.error('Error fetching items:', error);
                        tableContainer.style.opacity = '1';
                    });
                }

                // Event listener untuk filter change
                function handleFilterChange() {
                    const params = new URLSearchParams(new FormData(filterForm)).toString();
                    const url = `${filterForm.action}?${params}`;
                    fetchItems(url);
                }

                if(tipeSelect) tipeSelect.addEventListener('change', handleFilterChange);
                if(kondisiSelect) kondisiSelect.addEventListener('change', handleFilterChange);
                if(labSelect && !labSelect.disabled) labSelect.addEventListener('change', handleFilterChange);

                // Fungsi Debounce untuk delay submit
                function debounce(func, wait) {
                    let timeout;
                    return function(...args) {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => func.apply(this, args), wait);
                    };
                }

                // Auto-search saat mengetik
                if (searchInput) {
                    searchInput.addEventListener('input', debounce(() => {
                        handleFilterChange();
                    }, 500)); // Delay dikurangi jadi 500ms karena lebih responsif
                }
                
                // Mencegah submit form biasa saat enter ditekan di search
                filterForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    handleFilterChange();
                });

                // Inisialisasi listener pagination awal
                function initPaginationListeners() {
                    const paginationLinks = tableContainer.querySelectorAll('a.page-link, .pagination a');
                    paginationLinks.forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            fetchItems(this.href);
                        });
                    });
                }
                
                initPaginationListeners();
            });

            // --- Logika untuk Konfirmasi Hapus Massal dengan Alpine & SweetAlert ---

            function confirmBulkDelete(selectedItems) {
                // Validasi: pastikan ada item yang dipilih
                if (!selectedItems || selectedItems.length === 0) {
                    Swal.fire("{{ __('items.messages.no_items_selected_title') }}", "{{ __('items.messages.no_items_selected_text') }}", 'info');
                    return;
                }

                Swal.fire({
                    title: `{{ __('items.messages.delete_bulk_title', ['count' => '${selectedItems.length}']) }}`,
                    text: "{{ __('items.messages.delete_bulk_text') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: "{{ __('items.messages.delete_bulk_confirm') }}",
                    cancelButtonText: "{{ __('items.messages.delete_bulk_cancel') }}"
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
