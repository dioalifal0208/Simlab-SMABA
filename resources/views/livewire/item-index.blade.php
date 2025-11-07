<div>
    {{-- Komponen Livewire hanya berisi konten utama tabel & filter --}}
    <div class="py-12">
        {{-- PENAMBAHAN: Wrapper untuk animasi AOS --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" data-aos="fade-up" data-aos-duration="500" data-aos-once="true">
            
            {{-- Pesan Sukses --}}
            @if (session()->has('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">Sukses</p>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl p-6">
                <!-- Filter & Search -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <input 
                        wire:model.debounce.500ms="search" 
                        type="text" 
                        placeholder="Cari nama alat..." 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                    
                    <select wire:model="kondisi" class="w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                        <option value="">Semua Kondisi</option>
                        <option value="Baik">Baik</option>
                        <option value="Kurang Baik">Kurang Baik</option>
                        <option value="Rusak">Rusak</option>
                    </select>

                    <select wire:model="tipe" class="w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                        <option value="">Semua Tipe</option>
                        <option value="Alat">Alat</option>
                        <option value="Bahan Habis Pakai">Bahan Habis Pakai</option>
                    </select>

                    @can('is-admin')
                    <div class="flex items-center space-x-2">
                        <button 
                            wire:click="deleteSelected" 
                            wire:loading.attr="disabled"
                            @if(empty($selectedItems)) disabled @endif
                            class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg font-semibold text-sm shadow-md transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed hover:bg-red-700">
                            <i class="fas fa-trash-alt mr-2"></i> Hapus ({{ count($selectedItems) }})
                        </button>
                    </div>
                    @endcan
                </div>

                <!-- Tabel Data -->
                <div class="overflow-x-auto">
                    <div wire:loading.flex class="w-full items-center justify-center p-4">
                        <i class="fas fa-spinner fa-spin text-2xl text-smaba-light-blue"></i>
                        <span class="ml-2 text-gray-600">Memuat data...</span>
                    </div>
                    <table wire:loading.remove class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                @can('is-admin')
                                <th scope="col" class="p-4">
                                    <input type="checkbox" wire:model="selectAll" class="rounded border-gray-300 text-smaba-dark-blue shadow-sm focus:ring-smaba-light-blue">
                                </th>
                                @endcan
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('nama_alat')">
                                    Nama Alat
                                    @if($sort === 'nama_alat') <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i> @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('jumlah')">
                                    Stok
                                    @if($sort === 'jumlah') <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i> @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kondisi
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Lokasi
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Aksi</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($items as $item)
                                <tr wire:key="item-{{ $item->id }}" class="hover:bg-gray-50">
                                    @can('is-admin')
                                    <td class="p-4">
                                        <input type="checkbox" wire:model="selectedItems" value="{{ $item->id }}" class="rounded border-gray-300 text-smaba-dark-blue shadow-sm focus:ring-smaba-light-blue">
                                    </td>
                                    @endcan
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if ($item->photo)
                                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($item->photo) }}" alt="{{ $item->nama_alat }}">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">
                                                        <i class="fas fa-image"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->nama_alat }}</div>
                                                <div class="text-sm text-gray-500">{{ $item->tipe }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $item->jumlah }} {{ $item->satuan }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->kondisi == 'Baik')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Baik</span>
                                        @elseif($item->kondisi == 'Kurang Baik')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Kurang Baik</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rusak</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->lokasi_penyimpanan }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($item->activeLoans && $item->activeLoans->isNotEmpty())
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Dipinjam</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Tersedia</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('items.show', $item->id) }}" class="text-smaba-light-blue hover:text-smaba-dark-blue">Lihat</a>
                                        @can('is-admin')
                                            <a href="{{ route('items.edit', $item->id) }}" class="ml-4 text-yellow-600 hover:text-yellow-900">Edit</a>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <i class="fas fa-search text-4xl text-gray-300"></i>
                                        <p class="mt-4 text-gray-500">Tidak ada item yang cocok dengan pencarian Anda.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginasi -->
                <div class="mt-6">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- =============================================== --}}
    {{-- ##       PENYESUAIAN: MODAL IMPOR ITEM       ## --}}
    {{-- =============================================== --}}
    @can('is-admin')
    <div x-show="showImportModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" 
             x-cloak>
        
        {{-- Konten Modal --}}
        <div @click.outside="showImportModal = false" 
             x-show="showImportModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90"
             class="w-full max-w-lg bg-white p-8 rounded-xl shadow-lg relative"
             x-data="importUploader()"> {{-- Init Alpine component untuk uploader --}}

            <button @click="showImportModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            <h2 class="text-2xl font-bold text-smaba-text text-center">Impor Data Item</h2>
            <p class="text-sm text-gray-500 text-center mt-2 mb-4">Unggah file .csv atau .xlsx Anda.</p>

            {{-- Area Pesan Error/Sukses --}}
            <div x-show="message" :class="{ 'bg-green-100 border-green-500 text-green-700': success, 'bg-red-50 border-red-400 text-red-700': !success }" class="hidden mb-4 border-l-4 p-4 text-sm rounded-lg" role="alert">
                <p class="font-bold" x-text="success ? 'Sukses' : 'Oops! Terjadi kesalahan'"></p>
                <div x-html="message"></div>
            </div>

            <form :action="formAction" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm">
                @csrf
                {{-- Area Drag & Drop --}}
                <div 
                    @dragover.prevent="isDragging = true"
                    @dragleave.prevent="isDragging = false"
                    @drop.prevent="handleDrop"
                    :class="{ 'border-smaba-dark-blue bg-gray-100': isDragging }"
                    class="relative flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors"
                    onclick="document.getElementById('file-upload-item').click()">
                    
                    <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center">
                        <i class="fas fa-file-excel text-4xl text-gray-400"></i>
                        <p class="mb-2 text-sm text-gray-500" x-show="!fileName">
                            <span class="font-semibold">Klik untuk memilih file</span> atau tarik dan lepas (drag & drop)
                        </p>
                        <p class="text-sm font-semibold text-smaba-dark-blue" x-show="fileName" x-text="fileName"></p>
                        <p class="text-xs text-gray-500">CSV, XLS, atau XLSX (Maks. 5MB)</p>
                    </div>
                    <input id="file-upload-item" name="file" type="file" class="hidden" @change="handleFileSelect" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                </div>
                
                {{-- Instruksi Header --}}
                <div class="mt-4 text-xs text-gray-600">
                    <p class="font-semibold">Pastikan header file Anda sesuai (wajib):</p>
                    <p class="font-mono text-gray-500">nama_alat, tipe, jumlah, stok_minimum, satuan, kondisi, lokasi_penyimpanan, deskripsi</p>
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
    @endcan
</div>

{{-- =============================================== --}}
{{-- ##      PENAMBAHAN: SCRIPT MODAL IMPOR       ## --}}
{{-- =============================================== --}}
@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            
            // --- Komponen Alpine.js untuk Modal Impor ---
            // Didefinisikan di sini agar tidak konflik dengan yang ada di halaman user
            if (typeof Alpine.data('importUploader') === 'undefined') {
                Alpine.data('importUploader', () => ({
                    isDragging: false,
                    isLoading: false,
                    file: null,
                    fileName: '',
                    message: '',
                    success: false,
                    formAction: '{{ route("items.import") }}', // <-- Mengarah ke route impor item

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
                            const allowedTypes = [ 'text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ];
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
                                let errorList = '';
                                if (response.status === 422 && data.errors) {
                                    // Menangani format error dari Maatwebsite/Excel
                                    if (Array.isArray(data.errors)) {
                                        errorList = '<ul>' + data.errors.map(err => `<li>${err}</li>`).join('') + '</ul>';
                                    } else {
                                        errorList = '<ul>' + Object.values(data.errors).flat().map(err => `<li>${err}</li>`).join('') + '</ul>';
                                    }
                                    this.message = (data.message || 'Validasi gagal.') + errorList;
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
            }
        });
    </script>
@endpush
