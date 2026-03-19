<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight leading-tight">
                    {{ __('items.title') }}
                </h2>
                <p class="text-sm font-medium text-slate-500 mt-1">{{ __('items.subtitle') }}</p>
            </div>
            <div class="mt-3 sm:mt-0 flex items-center space-x-3">
                @can('is-admin')
                    {{-- Tombol Impor Item (membuka modal) --}}
                    <button @click="showImportModal = true" class="px-4 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 font-bold text-sm shadow-sm transition-colors">
                        <i class="fas fa-upload mr-2"></i> {{ __('items.actions.import') }}
                    </button>

                    {{-- Tombol Tambah Item --}}
                    <a href="{{ route('items.create') }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-500 shadow-[0_4px_14px_0_rgba(16,185,129,0.39)] transition-all hover:-translate-y-0.5">
                        <i class="fas fa-plus mr-2"></i> {{ __('items.actions.add') }}
                    </a>
                @else
                    {{-- Guru/Staf: Ajukan penambahan item --}}
                    <a href="{{ route('item-requests.create') }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-500 shadow-[0_4px_14px_0_rgba(16,185,129,0.39)] transition-all hover:-translate-y-0.5">
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
                <div class="mb-6 bg-emerald-50 border border-emerald-100 p-4 rounded-xl shadow-sm flex items-center gap-3" role="alert">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0"><i class="fas fa-check-circle"></i></div>
                    <div><p class="font-bold text-emerald-800 text-sm">{{ __('common.messages.success') }}</p><span class="text-emerald-700 text-sm">{{ session('success') }}</span></div>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-100 p-4 rounded-xl shadow-sm flex items-center gap-3" role="alert">
                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 flex-shrink-0"><i class="fas fa-exclamation-triangle"></i></div>
                    <div><p class="font-bold text-red-800 text-sm">{{ __('common.messages.error') }}</p><span class="text-red-700 text-sm">{{ session('error') }}</span></div>
                </div>
            @endif

            {{-- PERUBAHAN: Desain Ulang Area Filter dan Tombol Hapus Massal --}}
            <div class="mb-8" data-aos="fade-up">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 relative overflow-hidden">
                    {{-- Decorative top border --}}
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-emerald-400"></div>
                    
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div class="flex flex-col">
                            <h3 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">
                                <i class="fas fa-filter text-slate-300 mr-1.5 inline-block"></i> Filter Data
                            </h3>
                        </div>
                        
                        {{-- Form Filter --}}
                        <form action="{{ route('items.index') }}" method="GET" id="filter-form" class="w-full md:flex-grow">
                            <div class="flex flex-wrap items-center justify-end gap-3">
                                {{-- Input Pencarian dengan ikon --}}
                                <div class="relative flex-grow max-w-md">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                    <input type="text" name="search" id="search" placeholder="{{ __('items.filters.search') }}" value="{{ request('search') }}" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 pl-10 py-2.5 text-sm transition-all bg-slate-50 hover:bg-white focus:bg-white text-slate-800 placeholder-slate-400 font-medium">
                                </div>
                                {{-- Dropdown Filter --}}
                                <select name="tipe" id="tipe" class="w-auto min-w-[140px] rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 py-2.5 text-sm transition-all text-slate-600 bg-slate-50 hover:bg-white cursor-pointer appearance-none font-medium">
                                    <option value="">{{ __('items.filters.type') }}</option>
                                    <option value="Alat" @selected(request('tipe') == 'Alat')>{{ __('items.categories.alat') }}</option>
                                    <option value="Bahan Habis Pakai" @selected(request('tipe') == 'Bahan Habis Pakai')>{{ __('items.categories.bahan') }}</option>
                                </select>
                                <select name="kondisi" id="kondisi" class="w-auto min-w-[140px] rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 py-2.5 text-sm transition-all text-slate-600 bg-slate-50 hover:bg-white cursor-pointer appearance-none font-medium">
                                    <option value="">{{ __('items.filters.condition') }}</option>
                                    <option value="baik" @selected(request('kondisi') == 'baik')>{{ __('items.conditions.baik') }}</option>
                                    <option value="kurang baik" @selected(request('kondisi') == 'kurang baik')>{{ __('items.conditions.kurang_baik') }}</option>
                                    <option value="Rusak" @selected(request('kondisi') == 'Rusak')>{{ __('items.conditions.rusak') }}</option>
                                </select>
                                @php
                                    $isAdmin = auth()->user()?->role === 'admin';
                                    $lockedLab = auth()->user()?->laboratorium;
                                @endphp
                                <div class="flex items-center gap-2">
                                    <select name="laboratorium" id="laboratorium" class="w-auto min-w-[140px] rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 py-2.5 text-sm transition-all text-slate-600 bg-slate-50 hover:bg-white cursor-pointer appearance-none font-medium" {{ $isAdmin ? '' : 'disabled' }}>
                                        <option value="">{{ __('items.filters.all_labs') }}</option>
                                        <option value="Biologi" @selected(request('laboratorium', $lockedLab) == 'Biologi')>{{ __('common.labs.biologi') }}</option>
                                        <option value="Fisika" @selected(request('laboratorium', $lockedLab) == 'Fisika')>{{ __('common.labs.fisika') }}</option>
                                        <option value="Bahasa" @selected(request('laboratorium', $lockedLab) == 'Bahasa')>{{ __('common.labs.bahasa') }}</option>
                                        <option value="Komputer 1" @selected(request('laboratorium', $lockedLab) == 'Komputer 1')>Lab Komputer 1</option>
                                        <option value="Komputer 2" @selected(request('laboratorium', $lockedLab) == 'Komputer 2')>Lab Komputer 2</option>
                                        <option value="Komputer 3" @selected(request('laboratorium', $lockedLab) == 'Komputer 3')>Lab Komputer 3</option>
                                        <option value="Komputer 4" @selected(request('laboratorium', $lockedLab) == 'Komputer 4')>Lab Komputer 4</option>
                                    </select>
                                    @unless($isAdmin)
                                        <input type="hidden" name="laboratorium" value="{{ request('laboratorium', $lockedLab) }}">
                                        <span class="text-[10px] uppercase font-bold text-slate-400 ml-1">{{ __('items.filters.locked_lab') }}</span>
                                    @endunless
                                </div>
                                {{-- Tombol Reset --}}
                                <a href="{{ route('items.index') }}" class="px-4 py-2.5 bg-slate-100/80 text-slate-600 rounded-xl hover:bg-slate-200 hover:text-slate-900 font-semibold text-sm transition-colors border border-slate-200 group flex items-center shadow-sm" title="{{ __('items.actions.reset_filters') }}">
                                    <i class="fas fa-sync-alt group-hover:rotate-180 transition-transform duration-300"></i>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Tombol Aksi Hapus Massal (Muncul saat ada item terpilih) --}}
                <div x-show="selectedItems.length > 0" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="mt-4 bg-red-50/80 backdrop-blur-sm border border-red-200 rounded-2xl p-4 flex justify-between items-center shadow-md relative overflow-hidden" style="display: none;">
                    {{-- Warning stripes background --}}
                    <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, #ef4444 10px, #ef4444 20px);"></div>
                    
                    <div class="flex items-center gap-3 relative z-10">
                        <div class="bg-red-100 text-red-600 p-2 rounded-xl border border-red-200 font-bold">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <span class="text-sm font-bold text-red-800 tracking-tight">
                            <span x-text="selectedItems.length" class="text-lg"></span> Item Terpilih
                        </span>
                    </div>
                    
                    <form id="bulk-delete-form" action="{{ route('items.delete-multiple') }}" method="POST" class="relative z-10">
                        @csrf
                        {{-- Input tersembunyi untuk menampung ID item --}}
                        <template x-for="id in selectedItems" :key="id">
                            <input type="hidden" name="item_ids[]" :value="id">
                        </template>
                        <button type="button" @click="confirmBulkDelete(selectedItems)" class="px-5 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 font-bold text-sm shadow-[0_4px_14px_0_rgb(239,68,68,0.39)] hover:shadow-[0_6px_20px_rgba(239,68,68,0.23)] hover:-translate-y-0.5 transition-all outline-none">
                            <i class="fas fa-trash-alt mr-2"></i> {{ __('items.actions.bulk_delete') }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- Tabel Daftar Item (AJAX Container) --}}
            <div id="table-container" class="bg-white border border-slate-100 overflow-hidden shadow-sm sm:rounded-2xl" data-aos="fade-up" data-aos-delay="100">
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

