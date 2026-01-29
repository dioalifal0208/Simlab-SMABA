<x-app-layout>
    {{-- Bagian Header Halaman --}}
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                    {{ __('Manajemen Pengguna') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola akun pengguna dan peran (role) mereka.</p>
            </div>
            
            @can('is-admin')
                <div class="flex items-center space-x-3 mt-3 sm:mt-0" x-data="{ showCreateUserModal: false }">
                    {{-- PENAMBAHAN: Tombol Impor User (membuka modal) --}}
                    <button @click="showImportModal = true" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold text-sm shadow-sm transition-colors">
                        <i class="fas fa-upload mr-2"></i> Import User
                    </button>

                    {{-- Tombol Tambah Pengguna Baru (Manual) --}}
                    <button @click="showCreateUserModal = true" class="px-5 py-2 bg-smaba-dark-blue text-white rounded-lg hover:bg-smaba-light-blue font-semibold text-sm shadow-md transition-colors">
                        <i class="fas fa-plus mr-2"></i> Tambah Pengguna
                    </button>

                    {{-- MODAL TAMBAH PENGGUNA --}}
                    <div x-show="showCreateUserModal" 
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" 
                        style="display: none;">
                        
                        <div @click.outside="showCreateUserModal = false" 
                             x-show="showCreateUserModal"
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-90"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-90"
                             class="w-full max-w-lg bg-white p-8 rounded-xl shadow-lg relative"
                             x-data="{ role: 'guru' }">

                            <button @click="showCreateUserModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>

                            <h2 class="text-2xl font-bold text-smaba-text mb-6">Tambah Pengguna Baru</h2>

                            <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
                                @csrf
                                {{-- Nama --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                    <input type="text" name="name" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" name="email" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                </div>

                                {{-- Password --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                    <input type="password" name="password" required minlength="8" class="w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                </div>

                                {{-- Role --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Peran (Role)</label>
                                    <select name="role" x-model="role" class="w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                        <option value="guru">Guru</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>

                                {{-- Laboratorium (Hanya jika Guru) --}}
                                <div x-show="role === 'guru'" x-transition>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Laboratorium</label>
                                    <select name="laboratorium" class="w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                        <option value="">Pilih Laboratorium</option>
                                        <option value="Biologi">Biologi</option>
                                        <option value="Fisika">Fisika</option>
                                        <option value="Bahasa">Bahasa</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">* Wajib dipilih untuk Guru Pengelola Lab.</p>
                                </div>

                                <div class="pt-4 flex justify-end">
                                    <button type="button" @click="showCreateUserModal = false" class="mr-3 px-4 py-2 text-gray-700 hover:text-gray-900 font-medium">Batal</button>
                                    <button type="submit" class="px-6 py-2 bg-smaba-dark-blue text-white rounded-md hover:bg-smaba-light-blue font-semibold shadow-md transition-colors">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">Sukses</p>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-once="true">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lab</th>
                                <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Peran (Role)</th>
                                <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 divide-y divide-gray-200">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm font-semibold text-gray-900">{{ $user->name }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $user->email }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-700">{{ $user->laboratorium ?? '-' }}</td>
                                    <td class="py-4 px-6 text-center">
                                        @if($user->role == 'admin')
                                            <span class="px-3 py-1 text-xs font-bold leading-none text-red-800 bg-red-100 rounded-full">Admin</span>
                                        @elseif($user->role == 'guru')
                                            <span class="px-3 py-1 text-xs font-bold leading-none text-blue-800 bg-blue-100 rounded-full">Guru</span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-bold leading-none text-gray-800 bg-gray-100 rounded-full">Peran lain</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <a href="{{ route('users.edit', $user->id) }}" class="inline-flex items-center justify-center bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded-lg shadow-sm text-xs transition-colors duration-300" title="Edit Peran">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-500">
                                        <p class="font-semibold">Tidak Ada Pengguna</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($users->hasPages())
                    <div class="p-4 border-t border-gray-200">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- =============================================== --}}
    {{-- ##       PENAMBAHAN: MODAL IMPOR PENGGUNA    ## --}}
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

            <h2 class="text-2xl font-bold text-smaba-text text-center">Impor Data Pengguna</h2>
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
                
                {{-- Instruksi Header --}}
                <div class="mt-4 text-xs text-gray-600">
                    <p class="font-semibold">Pastikan header file Anda sesuai (wajib):</p>
                    <p class="font-mono text-gray-500">nama, email, password, role, laboratorium</p>
                    <p class="text-gray-500 mt-1">*) Kolom `role` opsional, otomatis 'guru' jika dikosongkan. Kolom `laboratorium` opsional untuk guru (isi: Biologi/Fisika/Bahasa).</p>
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


    {{-- =============================================== --}}
    {{-- ##      PENAMBAHAN: SCRIPT MODAL IMPOR       ## --}}
    {{-- =============================================== --}}
    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                
                // --- Komponen Alpine.js untuk Modal Impor ---
                Alpine.data('importUploader', () => ({
                    isDragging: false,
                    isLoading: false,
                    file: null,
                    fileName: '',
                    message: '',
                    success: false,
                    formAction: '{{ route("users.import.store") }}', // <-- Mengarah ke route impor user

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
            });
        </script>
    @endpush
</x-app-layout>
