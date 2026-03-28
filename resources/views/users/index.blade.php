<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">Manajemen Pengguna</h2>
                <p class="text-sm font-medium text-slate-500 mt-1">Kelola akun pengguna, peran (role), dan akses laboratorium.</p>
            </div>
            
            @can('is-admin')
            <div class="flex items-center gap-3" x-data="{ showCreateUserModal: false }" x-init="$watch('showCreateUserModal', value => $dispatch('modal-state-changed', {open: value}))">
                <button @click="showImportModal = true" class="px-4 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 font-bold text-sm shadow-sm transition-all hover:shadow">
                    <i class="fas fa-upload mr-2 text-slate-400"></i> Import
                </button>
                <button @click="showCreateUserModal = true" class="px-4 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-500 font-bold text-sm shadow-[0_4px_14px_0_rgba(79,70,229,0.39)] hover:shadow-[0_6px_20px_rgba(79,70,229,0.23)] transition-all hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i> Tambah Pengguna
                </button>

                {{-- CREATE USER MODAL --}}
                <template x-teleport="body">
                    <div x-show="showCreateUserModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
                        <div x-show="showCreateUserModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showCreateUserModal = false"></div>
                        <div x-show="showCreateUserModal" x-transition:enter="ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95" class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-lg w-full relative z-10 overflow-hidden" x-data="{ role: 'guru' }">
                            
                            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                                <h3 class="font-extrabold text-lg text-slate-900 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-sm text-indigo-600"><i class="fas fa-user-plus"></i></div>
                                    Tambah Pengguna Baru
                                </h3>
                                <button @click="showCreateUserModal = false" class="text-slate-400 hover:text-slate-600 transition-colors"><i class="fas fa-times"></i></button>
                            </div>
                            
                            <form action="{{ route('users.store') }}" method="POST" class="p-6 space-y-5">
                                @csrf
                                <x-form.input name="name" label="Nama Lengkap" placeholder="Masukkan nama lengkap" icon="fa-user" required />
                                <x-form.input name="email" type="email" label="Alamat Email" placeholder="nama@email.com" icon="fa-envelope" required />
                                <x-form.input name="password" type="password" label="Password" placeholder="Min. 8 karakter" icon="fa-lock" required />
                                
                                <x-form.select name="role" label="Peran (Role)" icon="fa-shield" x-model="role" required>
                                    <option value="guru">Guru</option>
                                    <option value="admin">Admin</option>
                                </x-form.select>

                                <div x-show="role === 'guru'" x-transition>
                                    <x-form.select name="laboratorium" label="Laboratorium" icon="fa-flask" helper="Wajib dipilih untuk peran Guru.">
                                        <option value="">Pilih Laboratorium</option>
                                        <option value="Biologi">Biologi</option>
                                        <option value="Fisika">Fisika</option>
                                        <option value="Bahasa">Bahasa</option>
                                        <option value="Komputer 1">Komputer 1</option>
                                        <option value="Komputer 2">Komputer 2</option>
                                        <option value="Komputer 3">Komputer 3</option>
                                        <option value="Komputer 4">Komputer 4</option>
                                    </x-form.select>
                                </div>

                                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                    <button type="button" @click="showCreateUserModal = false" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-colors text-sm">Batal</button>
                                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-lg hover:bg-indigo-500 transition-all">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </template>
            </div>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- SUCCESS TOAST --}}
            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-xl shadow-sm flex items-center gap-4" data-aos="fade-in" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0"><i class="fas fa-check"></i></div>
                    <span class="text-sm font-bold text-emerald-800">{{ session('success') }}</span>
                </div>
            @endif

            {{-- 1. SUMMARY CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center gap-4 group hover:-translate-y-0.5 transition-all duration-300" data-aos="fade-up">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-xl border border-indigo-100 group-hover:scale-110 transition-transform"><i class="fas fa-users"></i></div>
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Total Pengguna</p>
                        <h3 class="text-2xl font-extrabold text-slate-800">{{ number_format($totalUsers) }}</h3>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center gap-4 group hover:-translate-y-0.5 transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center text-xl border border-rose-100 group-hover:scale-110 transition-transform"><i class="fas fa-user-shield"></i></div>
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Administrator</p>
                        <h3 class="text-2xl font-extrabold text-slate-800">{{ number_format($adminCount) }}</h3>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center gap-4 group hover:-translate-y-0.5 transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl border border-emerald-100 group-hover:scale-110 transition-transform"><i class="fas fa-chalkboard-teacher"></i></div>
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Guru</p>
                        <h3 class="text-2xl font-extrabold text-slate-800">{{ number_format($guruCount) }}</h3>
                    </div>
                </div>
            </div>

            {{-- 2. FILTER BAR --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 sm:p-5 flex flex-col sm:flex-row items-stretch sm:items-center gap-3" data-aos="fade-up">
                <form method="GET" action="{{ route('users.index') }}" class="flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400"><i class="fas fa-magnifying-glass text-sm"></i></div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="block w-full rounded-xl text-sm pl-10 pr-4 py-2.5 border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 hover:bg-white focus:bg-white transition-all duration-200 placeholder-slate-400">
                    </div>
                    <select name="role" onchange="this.form.submit()" class="rounded-xl text-sm px-4 py-2.5 border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 hover:bg-white focus:bg-white transition-all duration-200 font-bold text-slate-600 appearance-none">
                        <option value="">Semua Peran</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                    </select>
                    <button type="submit" class="px-5 py-2.5 bg-slate-800 text-white rounded-xl font-bold text-sm hover:bg-slate-700 transition-colors shadow-sm"><i class="fas fa-search mr-2"></i>Cari</button>
                    @if(request('search') || request('role'))
                        <a href="{{ route('users.index') }}" class="px-4 py-2.5 border border-slate-200 text-slate-500 rounded-xl font-bold text-sm hover:bg-slate-50 transition-colors text-center">Reset</a>
                    @endif
                </form>
            </div>

            {{-- 3. USER TABLE --}}
            <div class="bg-white overflow-hidden shadow-sm border border-slate-100 sm:rounded-2xl" x-data="{ showDeleteModal: false, deleteUserId: null, deleteUserName: '' }" data-aos="fade-up">
                <div class="overflow-x-auto">
                    @if($users->count() > 0)
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-left">Pengguna</th>
                                <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-left">Lab</th>
                                <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-center">Peran</th>
                                <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($users as $user)
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    {{-- Avatar + Name + Email --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0 shadow-sm border
                                                {{ $user->role == 'admin' ? 'bg-rose-50 border-rose-200 text-rose-700' : 'bg-emerald-50 border-emerald-200 text-emerald-700' }}">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-sm font-bold text-slate-800 truncate">{{ $user->name }}</div>
                                                <div class="text-[11px] font-medium text-slate-400 truncate">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Lab --}}
                                    <td class="px-6 py-4 text-sm text-slate-600 font-medium">{{ $user->laboratorium ?? '-' }}</td>

                                    {{-- Role Badge --}}
                                    <td class="px-6 py-4 text-center">
                                        @if($user->role == 'admin')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold bg-rose-50 text-rose-600 tracking-wide border border-rose-100">
                                                <i class="fas fa-shield-alt text-[9px]"></i> ADMIN
                                            </span>
                                        @elseif($user->role == 'guru')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-600 tracking-wide border border-emerald-100">
                                                <i class="fas fa-chalkboard-teacher text-[9px]"></i> GURU
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold bg-slate-100 text-slate-600 tracking-wide border border-slate-200">
                                                {{ strtoupper($user->role) }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Actions Dropdown --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="relative inline-block text-left" x-data="{ open: false }">
                                            <button @click="open = !open" @click.outside="open = false" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors focus:outline-none">
                                                <i class="fas fa-ellipsis-vertical"></i>
                                            </button>
                                            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 z-20 mt-2 w-44 origin-top-right bg-white rounded-xl shadow-lg ring-1 ring-black/5 border border-slate-100 overflow-hidden" style="display: none;">
                                                <a href="{{ route('users.edit', $user->id) }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">
                                                    <i class="fas fa-pen-to-square w-4 text-slate-400"></i> Edit
                                                </a>
                                                @if(auth()->id() !== $user->id)
                                                <button @click="deleteUserId = {{ $user->id }}; deleteUserName = '{{ addslashes($user->name) }}'; showDeleteModal = true; open = false;" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors w-full text-left">
                                                    <i class="fas fa-trash-can w-4"></i> Hapus
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                        {{-- Empty State --}}
                        <div class="text-center py-16 px-4">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                <i class="fas fa-users-slash text-2xl text-slate-300"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-700">Tidak Ada Pengguna Ditemukan</h3>
                            <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Coba ubah filter pencarian atau tambah pengguna baru.</p>
                        </div>
                    @endif
                </div>

                @if ($users->hasPages())
                    <div class="p-6 border-t border-slate-50 bg-slate-50/50">
                        {{ $users->links() }}
                    </div>
                @endif

                {{-- DELETE CONFIRMATION MODAL --}}
                <template x-teleport="body">
                    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
                        <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showDeleteModal = false"></div>
                        <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95" class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full relative z-10 overflow-hidden">
                            
                            <div class="p-6 text-center">
                                <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4 border border-red-100">
                                    <i class="fas fa-triangle-exclamation text-2xl text-red-500"></i>
                                </div>
                                <h3 class="font-extrabold text-lg text-slate-900 mb-2">Konfirmasi Hapus Pengguna</h3>
                                <p class="text-sm text-slate-500 font-medium leading-relaxed">Apakah Anda yakin ingin menghapus <strong class="text-slate-800" x-text="deleteUserName"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
                            </div>
                            
                            <div class="flex items-center justify-center gap-3 px-6 py-4 bg-slate-50 border-t border-slate-100">
                                <button type="button" @click="showDeleteModal = false" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-colors text-sm">Batal</button>
                                <form :action="'/users/' + deleteUserId" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-5 py-2.5 bg-red-600 text-white rounded-xl font-bold text-sm shadow-lg hover:bg-red-500 transition-all">Ya, Hapus Pengguna</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

        </div>
    </div>

    {{-- IMPORT USER MODAL --}}
    <div x-show="showImportModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showImportModal = false"></div>
        <div @click.outside="showImportModal = false" x-show="showImportModal" x-transition:enter="ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="w-full max-w-lg bg-white rounded-2xl shadow-xl relative z-10 overflow-hidden border border-slate-100" x-data="importUploader()">
            
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-extrabold text-lg text-slate-900 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-sm text-emerald-600"><i class="fas fa-file-import"></i></div>
                    Impor Data Pengguna
                </h3>
                <button @click="showImportModal = false" class="text-slate-400 hover:text-slate-600 transition-colors"><i class="fas fa-times"></i></button>
            </div>

            <div class="p-6">
                <p class="text-sm text-slate-500 font-medium mb-4">Unggah file .csv atau .xlsx Anda.</p>

                <div x-show="message" :class="{ 'bg-emerald-50 border-emerald-200 text-emerald-700': success, 'bg-red-50 border-red-200 text-red-700': !success }" class="hidden mb-4 border p-4 text-sm rounded-xl" role="alert">
                    <p class="font-bold" x-text="success ? 'Sukses' : 'Oops! Terjadi kesalahan'"></p>
                    <div x-html="message"></div>
                </div>

                <form :action="formAction" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm">
                    @csrf
                    <div @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false" @drop.prevent="handleDrop" @click="$refs.fileInput.click()" :class="{ 'border-indigo-500 bg-indigo-50/50 ring-4 ring-indigo-500/20': isDragging }" class="relative flex flex-col items-center justify-center w-full h-44 border-2 border-slate-200 border-dashed rounded-xl cursor-pointer bg-slate-50 hover:bg-white transition-all duration-200">
                        <div class="flex flex-col items-center justify-center text-center">
                            <i class="fas fa-cloud-arrow-up text-3xl text-slate-300 mb-3"></i>
                            <p class="text-sm text-slate-500" x-show="!fileName"><span class="font-bold text-indigo-600">Klik untuk memilih file</span> atau seret ke sini</p>
                            <p class="text-sm font-bold text-indigo-600" x-show="fileName" x-text="fileName"></p>
                            <p class="text-xs text-slate-400 mt-1">CSV, XLS, atau XLSX (Maks. 5MB)</p>
                        </div>
                        <input x-ref="fileInput" name="file" type="file" class="hidden" @change="handleFileSelect" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                    </div>
                    
                    <div class="mt-4 bg-amber-50 border border-amber-100 rounded-xl p-3 text-xs text-amber-700">
                        <p class="font-bold mb-1"><i class="fas fa-info-circle mr-1"></i>Format Header Wajib:</p>
                        <code class="text-amber-800 font-mono">nama, email, password, role, laboratorium</code>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" :disabled="isLoading" :class="{ 'opacity-50 cursor-not-allowed': isLoading }" class="px-6 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-500 font-bold text-sm shadow-lg transition-all">
                            <span x-show="!isLoading"><i class="fas fa-upload mr-2"></i>Mulai Impor</span>
                            <span x-show="isLoading"><i class="fas fa-spinner-third fa-spin mr-2"></i>Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('importUploader', () => ({
                isDragging: false, isLoading: false, file: null, fileName: '', message: '', success: false,
                formAction: '{{ route("users.import.store") }}',
                handleFileSelect(event) {
                    if (event.target.files.length > 0) { this.file = event.target.files[0]; this.fileName = this.file.name; this.message = ''; }
                },
                handleDrop(event) {
                    this.isDragging = false;
                    if (event.dataTransfer.files.length > 0) {
                        const allowedTypes = ['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
                        if (allowedTypes.includes(event.dataTransfer.files[0].type)) { this.file = event.dataTransfer.files[0]; this.fileName = this.file.name; this.message = ''; }
                        else { this.file = null; this.fileName = ''; this.success = false; this.message = 'Tipe file tidak valid.'; }
                    }
                },
                async submitForm() {
                    if (!this.file) { this.success = false; this.message = 'Silakan pilih file.'; return; }
                    this.isLoading = true; this.message = '';
                    const formData = new FormData(); formData.append('file', this.file);
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                    try {
                        const response = await fetch(this.formAction, { method: 'POST', body: formData, headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                        const data = await response.json();
                        if (!response.ok) { this.success = false; this.message = (response.status === 422 && data.errors) ? '<ul>' + data.errors.map(err => `<li>${err}</li>`).join('') + '</ul>' : data.message || 'Error.'; }
                        else { this.success = true; this.message = data.message + ' Halaman akan dimuat ulang...'; setTimeout(() => window.location.reload(), 2000); }
                    } catch (error) { this.success = false; this.message = 'Gagal terhubung ke server.'; }
                    finally { this.isLoading = false; }
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>
