<x-app-layout>
    {{-- Bagian Header Halaman --}}
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                    {{ __('Daftar Inventaris') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola semua alat dan bahan laboratorium.</p>
            </div>
            
            @can('is-admin')
                <div class="flex items-center space-x-3 mt-3 sm:mt-0">
                    <button @click="showImportModal = true" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold text-sm shadow-sm transition-colors">
                        <i class="fas fa-upload mr-2"></i> Impor Data
                    </button>

                    <a href="{{ route('items.create') }}" class="px-5 py-2 bg-smaba-dark-blue text-white rounded-lg hover:bg-smaba-light-blue font-semibold text-sm shadow-md transition-colors duration-300 ease-in-out transform hover:-translate-y-0.5">
                        <i class="fas fa-plus mr-2"></i> Tambah Alat / Item Baru
                    </a>
                </div>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Pesan Sukses (Flash Message) --}}
            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">Sukses</p>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div data-aos="fade-up" data-aos-duration="500" data-aos-once="true">
                {{-- Form untuk Search dan Filter --}}
                <div class="mb-6 bg-white overflow-hidden shadow-lg sm:rounded-xl">
                    <form action="{{ route('items.index') }}" method="GET" class="p-6" id="filter-form">
                        <div class="flex flex-col md:flex-row items-center gap-4">
                            <div class="flex-grow w-full">
                                <label for="search" class="sr-only">Cari</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                                    </div>
                                    <input type="text" name="search" id="search" placeholder="Ketik untuk mencari alat..." 
                                           class="w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="w-full md:w-auto">
                                <label for="kondisi" class="sr-only">Kondisi</label>
                                <select name="kondisi" id="kondisi" class="w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                    <option value="">Semua Kondisi</option>
                                    <option value="Baik" @selected(request('kondisi') == 'Baik')>Baik</option>
                                    <option value="Kurang Baik" @selected(request('kondisi') == 'Kurang Baik')>Kurang Baik</option>
                                    <option value="Rusak" @selected(request('kondisi') == 'Rusak')>Rusak</option>
                                </select>
                            </div>
                            <div class="w-full md:w-auto hidden">
                                <button type="submit" class="w-full justify-center px-6 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 font-semibold text-sm h-full transition-colors duration-300">
                                    Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Tabel Daftar Inventaris --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ route('items.index', ['sort' => 'nama_alat', 'direction' => $sort == 'nama_alat' && $direction == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center">
                                            Nama Alat
                                            @if($sort == 'nama_alat')<svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $direction == 'asc' ? 'm5 15l7-7l7 7' : 'm19 9l-7 7l-7-7' }}"></path></svg>@endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ route('items.index', ['sort' => 'jumlah', 'direction' => $sort == 'jumlah' && $direction == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center">
                                            Jumlah
                                            @if($sort == 'jumlah')<svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $direction == 'asc' ? 'm5 15l7-7l7 7' : 'm19 9l-7 7l-7-7' }}"></path></svg>@endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi</th>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Lokasi</th>
                                    @can('is-admin')
                                        <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 divide-y divide-gray-200">
                                @forelse ($items as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-4 px-6 text-sm">{{ $items->firstItem() + $loop->index }}</td>
                                        <td class="py-4 px-6">
                                            @if ($item->photo)
                                                <img src="{{ Storage::url($item->photo) }}" alt="{{ $item->nama_alat }}" class="h-12 w-12 object-cover rounded-md shadow">
                                            @else
                                                <div class="h-12 w-12 bg-gray-200 rounded-md flex items-center justify-center text-gray-400">
                                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6">
                                            <a href="{{ route('items.show', $item->id) }}" class="text-smaba-dark-blue hover:text-smaba-light-blue hover:underline font-bold">
                                                {{ $item->nama_alat }}
                                            </a>
                                        </td>
                                        <td class="py-4 px-6 text-sm">{{ $item->jumlah }} {{ $item->satuan }}</td>
                                        <td class="py-4 px-6 text-center">
                                            @if($item->kondisi == 'Baik')
                                                <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Baik</span>
                                            @elseif($item->kondisi == 'Kurang Baik')
                                                <span class="px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Kurang Baik</span>
                                            @else
                                                <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Rusak</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-sm hidden md:table-cell">{{ $item->lokasi_penyimpanan }}</td>
                                        @can('is-admin')
                                            <td class="py-4 px-6">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <a href="{{ route('items.edit', $item->id) }}" class="flex items-center justify-center bg-yellow-500 hover:bg-yellow-600 text-white font-bold p-2 rounded-lg shadow-md transition-colors duration-300" title="Edit">
                                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                                                    </a>
                                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="flex items-center justify-center bg-red-600 hover:bg-red-700 text-white font-bold p-2 rounded-lg shadow-md transition-colors duration-300" title="Hapus">
                                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endcan
                                    </tr>
                                @empty
                                    <tr><td colspan="{{ auth()->user()->role == 'admin' ? '8' : '6' }}" class="py-8 text-center text-gray-500">
                                        <p class="font-semibold">Tidak Ada Data</p>
                                        <p class="text-sm mt-1">Data inventaris tidak ditemukan. Coba ubah filter pencarian Anda.</p>
                                    </td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-200">
                        {{ $items->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =============================================== --}}
    {{-- ##       MODAL IMPOR DATA (KODE UTAMA)       ## --}}
    {{-- =============================================== --}}
    <div x-show="showImportModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" 
         style="display: none;">
        
        <div @click.outside="showImportModal = false" 
             x-show="showImportModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90"
             class="w-full max-w-lg bg-white p-8 rounded-xl shadow-lg relative"
             x-data="importUploader()">
             
            <button @click="showImportModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            <h2 class="text-2xl font-bold text-smaba-text text-center">Impor Data Item</h2>
            <p class="text-sm text-gray-500 text-center mt-2 mb-4">Unggah file .csv atau .xlsx Anda.</p>

            <div x-show="message" :class="{ 'bg-green-100 border-green-500 text-green-700': success, 'bg-red-50 border-red-400 text-red-700': !success }" class="hidden mb-4 border-l-4 p-4 text-sm rounded-lg" role="alert">
                <p class="font-bold" x-text="success ? 'Sukses' : 'Oops! Terjadi kesalahan'"></p>
                <div x-html="message"></div>
            </div>

            <form :action="formAction" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm">
                @csrf
                <div 
                    @dragover.prevent="isDragging = true"
                    @dragleave.prevent="isDragging = false"
                    @drop.prevent="handleDrop"
                    :class="{ 'border-smaba-dark-blue bg-gray-100': isDragging }"
                    class="relative flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                    
                    <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center">
                        <i class="fas fa-file-excel text-4xl text-gray-400"></i>
                        <p class="mb-2 text-sm text-gray-500" x-show="!fileName">
                            <span class="font-semibold">Klik untuk memilih file</span> atau tarik dan lepas (drag & drop)
                        </p>
                        <p class="text-sm font-semibold text-smaba-dark-blue" x-show="fileName" x-text="fileName"></p>
                        <p class="text-xs text-gray-500">CSV, XLS, atau XLSX (Maks. 5MB)</p>
                    </div>
                    <input id="file-upload" name="file" type="file" class="hidden" @change="handleFileSelect" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                </div>
                
                <div class="mt-4 text-xs text-gray-600">
                    <p class="font-semibold">Pastikan header file Anda sesuai:</p>
                    <p class="font-mono text-gray-500">nama_alat, tipe, jumlah, satuan, kondisi, lokasi_penyimpanan, stok_minimum, deskripsi</p>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" 
                            :disabled="isLoading"
                            :class="{ 'opacity-50 cursor-not-allowed': isLoading }"
                            class="px-6 py-2 bg-smaba-dark-blue text-white rounded-md hover:bg-smaba-light-blue font-semibold text-sm shadow-md transition-colors">
                        <span x-show="!isLoading">Mulai Impor Data</span>
                        <span x-show="isLoading">Memproses...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- Script untuk Popup Konfirmasi Hapus & LIVE SEARCH & MODAL IMPOR --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            {{-- PERBAIKAN: Menggabungkan semua script ke dalam satu listener 'alpine:init' --}}
            document.addEventListener('alpine:init', () => {
                
                // --- Komponen Alpine.js untuk Modal Impor ---
                Alpine.data('importUploader', () => ({
                    isDragging: false,
                    isLoading: false,
                    file: null,
                    fileName: '',
                    message: '',
                    success: false,
                    formAction: '{{ route("items.import.store") }}',

                    handleFileSelect(event) {
                        if (event.target.files.length > 0) {
                            this.file = event.target.files[0];
                            this.fileName = this.file.name;
                            this.message = ''; 
                        }
                    },
                    
                    handleDrop(event) {
                        this.isDragging = false;
                        if (event.dataTransfer.files.length > 0) {
                            const allowedTypes = [
                                'text/csv', 
                                'application/vnd.ms-excel', 
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                            ];
                            if (allowedTypes.includes(event.dataTransfer.files[0].type)) {
                                this.file = event.dataTransfer.files[0];
                                this.fileName = this.file.name;
                                this.message = '';
                            } else {
                                this.file = null;
                                this.fileName = '';
                                this.success = false;
                                this.message = 'Tipe file tidak valid. Harap gunakan .csv atau .xlsx.';
                            }
                        }
                    },

                    async submitForm() {
                        if (!this.file) {
                            this.success = false;
                            this.message = 'Silakan pilih file untuk diunggah.';
                            return;
                        }

                        this.isLoading = true;
                        this.message = '';
                        
                        const formData = new FormData();
                        formData.append('file', this.file);
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                        try {
                            const response = await fetch(this.formAction, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            });

                            const data = await response.json();

                            if (!response.ok) {
                                this.success = false;
                                if (response.status === 422 && data.errors) {
                                    this.message = '<ul>' + data.errors.map(err => `<li>${err}</li>`).join('') + '</ul>';
                                } else {
                                    this.message = data.message || 'Terjadi kesalahan yang tidak diketahui.';
                                }
                            } else {
                                this.success = true;
                                this.message = data.message + ' Halaman akan dimuat ulang...';
                                setTimeout(() => {
                                    window.location.reload();
                                }, 2000);
                            }

                        } catch (error) {
                            console.error('Import error:', error);
                            this.success = false;
                            this.message = 'Gagal terhubung ke server. Periksa koneksi Anda.';
                        } finally {
                            this.isLoading = false;
                        }
                    }
                }));

                // --- Script SweetAlert2 untuk konfirmasi hapus ---
                const deleteForms = document.querySelectorAll('.delete-form');
                deleteForms.forEach(form => {
                    form.addEventListener('submit', function (event) {
                        event.preventDefault(); 
                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "Data yang dihapus tidak dapat dikembalikan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });

                // --- Script untuk Live Search dan Auto-Filter ---
                const filterForm = document.getElementById('filter-form');
                const searchInput = document.getElementById('search');
                const kondisiSelect = document.getElementById('kondisi');
                let debounceTimer;

                if (filterForm) {
                    searchInput.addEventListener('keyup', () => {
                        clearTimeout(debounceTimer);
                        debounceTimer = setTimeout(() => {
                            filterForm.submit();
                        }, 500); 
                    });

                    kondisiSelect.addEventListener('change', () => {
                        filterForm.submit();
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>