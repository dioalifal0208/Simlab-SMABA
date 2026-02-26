<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                    @if (auth()->user()->role == 'admin')
                        {{ __('bookings.title_admin') }}
                    @else
                        {{ __('bookings.title_user') }}
                    @endif
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    @if (auth()->user()->role == 'admin')
                        {{ __('bookings.subtitle_admin') }}
                    @else
                        {{ __('bookings.subtitle_user') }}
                    @endif
                </p>
            </div>
            
            <button @click="$dispatch('open-booking-modal')" class="mt-3 sm:mt-0 px-6 py-2.5 bg-green-700 text-white rounded-lg hover:bg-green-800 font-bold text-sm shadow-lg transition-all border-2 border-green-800 flex items-center gap-2">
                <i class="fas fa-plus"></i> {{ __('bookings.actions.create_new') }}
            </button>
        </div>
    </x-slot>

    <div class="py-12" 
         x-data="{ showModal: {{ ($errors->any() || request('create')) ? 'true' : 'false' }} }" 
         @open-booking-modal.window="showModal = true; $dispatch('modal-state-changed', {open: true})"
         x-init="$watch('showModal', value => $dispatch('modal-state-changed', {open: value}))">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">{{ __('common.messages.success') }}</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div data-aos="fade-up" data-aos-duration="500" data-aos-once="true">
                {{-- Form Filter Status Otomatis --}}
                <div class="mb-6 bg-white overflow-hidden border border-gray-100 shadow-sm sm:rounded-xl">
                    <form action="{{ route('bookings.index') }}" method="GET" class="p-4 sm:p-6 space-y-4" id="filter-form">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 space-y-4 sm:space-y-0">
                            <div class="flex items-center space-x-3">
                                <label for="status" class="text-sm font-medium text-gray-700">{{ __('common.labels.status') }}:</label>
                                <select name="status" id="status" class="w-full sm:w-48 rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue text-sm">
                                    <option value="">{{ __('bookings.filters.all') }}</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('common.status.pending') }}</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('common.status.approved') }}</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('common.status.rejected') }}</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('common.status.completed') }}</option>
                                </select>
                            </div>
                            <div class="flex items-center space-x-3">
                                <label for="laboratorium" class="text-sm font-medium text-gray-700">{{ __('common.nav.lab_services') }}:</label>
                                <select name="laboratorium" id="laboratorium" class="w-full sm:w-48 rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue text-sm">
                                    <option value="">{{ __('bookings.filters.all_labs') }}</option>
                                    <option value="Biologi" {{ request('laboratorium') == 'Biologi' ? 'selected' : '' }}>Biologi</option>
                                    <option value="Fisika" {{ request('laboratorium') == 'Fisika' ? 'selected' : '' }}>Fisika</option>
                                    <option value="Bahasa" {{ request('laboratorium') == 'Bahasa' ? 'selected' : '' }}>Bahasa</option>
                                </select>
                            </div>
                            <i id="loading-spinner" class="fas fa-spinner fa-spin text-gray-500 hidden"></i>
                        </div>
                    </form>
                </div>

                {{-- Daftar Booking Cards --}}
                <div class="space-y-4">
                    @forelse ($bookings as $booking)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 border-l-4 transition-all duration-200 hover:-translate-y-1 hover:shadow-md 
                            @if($booking->status == 'approved') border-l-green-500 @elseif($booking->status == 'pending') border-l-yellow-500 @elseif($booking->status == 'rejected') border-l-red-500 @else border-l-gray-400 @endif">
                            <div class="p-4 sm:p-6">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex-grow">
                                        <div class="flex items-center space-x-3 flex-wrap gap-2">
                                            <span class="px-3 py-1 text-xs font-bold leading-none rounded-full
                                                @if($booking->status == 'pending') text-yellow-800 bg-yellow-100
                                                @elseif($booking->status == 'approved') text-green-800 bg-green-100
                                                @elseif($booking->status == 'rejected') text-red-800 bg-red-100
                                                @else text-gray-800 bg-gray-100 @endif">
                                                {{ match($booking->status) {
                                                    'pending' => __('common.status.pending'),
                                                    'approved' => __('common.status.approved'),
                                                    'rejected' => __('common.status.rejected'),
                                                    'completed' => __('common.status.completed'),
                                                    default => ucfirst($booking->status)
                                                } }}
                                            </span>
                                            <p class="text-sm font-semibold text-smaba-dark-blue">{{ $booking->tujuan_kegiatan }}</p>
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-50 text-green-700">
                                                {{ $booking->laboratorium }}
                                            </span>
                                        </div>
                                        @if (auth()->user()->role == 'admin')
                                            <p class="mt-2 text-sm text-gray-600">
                                                <i class="fas fa-user-circle fa-fw mr-1 text-gray-400"></i> {{ __('bookings.labels.diajukan_oleh') }}: <span class="font-medium">{{ $booking->user->name }}</span>
                                            </p>
                                        @endif
                                    </div>
                                    <div class="mt-4 sm:mt-0 sm:ml-4 flex-shrink-0">
                                        <a href="{{ route('bookings.show', $booking->id) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-smaba-dark-blue text-white rounded-md hover:bg-smaba-light-blue font-semibold text-xs shadow-sm transition-colors duration-300">
                                            <i class="fas fa-eye mr-2"></i> {{ __('common.buttons.details') }}
                                        </a>
                                    </div>
                                </div>
                                <div class="mt-4 pt-4 border-t border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt fa-fw mr-2 text-gray-400"></i>
                                        <span class="font-medium">{{ $booking->waktu_mulai->translatedFormat('l, d F Y') }}</span>
                                    </div>
                                    <div class="mt-2 sm:mt-0 flex items-center">
                                        <i class="fas fa-clock fa-fw mr-2 text-gray-400"></i>
                                        <span>{{ $booking->waktu_mulai->format('H:i') }} - {{ $booking->waktu_selesai->format('H:i') }} WIB</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 bg-white rounded-xl border border-gray-100 shadow-sm">
                            <div class="w-20 h-20 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                <i class="fas fa-calendar-times text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ __('bookings.empty.title') }}</h3>
                            <p class="text-sm text-gray-500 mb-4">{{ __('bookings.empty.description') }}</p>
                            <button @click="showModal = true" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                                <i class="fas fa-plus"></i> {{ __('bookings.empty.action') }}
                            </button>
                        </div>
                    @endforelse
                </div>

                {{-- Paginasi --}}
                <div class="mt-6">
                    {{ $bookings->withQueryString()->links() }}
                </div>
            </div>
        </div>

    {{-- Modal Booking Baru --}}
    <div x-show="showModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-cloak
         @keydown.escape.window="showModal = false">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            {{-- Background Overlay --}}
            <div x-show="showModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 transition-opacity" 
                 aria-hidden="true" 
                 @click="showModal = false">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            {{-- Centered Modal Content --}}
            <span class="hidden sm:inline-block sm:align-middle sm:min-h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-xl leading-6 font-bold text-gray-900" id="modal-title">
                                    {{ __('Formulir Pengajuan Booking Lab') }}
                                </h3>
                                <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            
                            @if ($errors->any())
                                <div class="mb-6 bg-red-50 border-l-4 border-red-400 text-red-700 p-4 text-sm rounded-lg" role="alert">
                                    <p class="font-bold">Oops! Terjadi kesalahan.</p>
                                    <ul class="mt-2 list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('bookings.store') }}" method="POST">
                                @csrf
                                <div class="space-y-5">
                                    <div>
                                        <label for="guru_pengampu" class="block font-semibold text-xs text-gray-500 uppercase tracking-wider mb-1">Nama Guru Pengampu</label>
                                        <input type="text" name="guru_pengampu" id="guru_pengampu" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="{{ old('guru_pengampu') }}" placeholder="Contoh: Budi Santoso, S.Pd." required>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <label for="laboratorium_modal" class="block font-semibold text-xs text-gray-500 uppercase tracking-wider mb-1">Laboratorium</label>
                                            <select name="laboratorium" id="laboratorium_modal" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required {{ auth()->user()->role === 'admin' ? '' : 'disabled' }}>
                                                <option value="">-- Pilih Lab --</option>
                                                <option value="Biologi" @selected(old('laboratorium', $selectedLaboratorium ?? '') === 'Biologi')>Lab Biologi</option>
                                                <option value="Fisika" @selected(old('laboratorium', $selectedLaboratorium ?? '') === 'Fisika')>Lab Fisika</option>
                                                <option value="Bahasa" @selected(old('laboratorium', $selectedLaboratorium ?? '') === 'Bahasa')>Lab Bahasa</option>
                                            </select>
                                            @if(auth()->user()->role !== 'admin')
                                                <input type="hidden" name="laboratorium" value="{{ old('laboratorium', $selectedLaboratorium) }}">
                                            @endif
                                        </div>
                                        <div>
                                            <label for="jumlah_peserta" class="block font-semibold text-xs text-gray-500 uppercase tracking-wider mb-1">Jumlah Peserta <span class="text-[10px] lowercase italic font-normal text-gray-400">(opsional)</span></label>
                                            <input type="number" name="jumlah_peserta" id="jumlah_peserta" min="1" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="{{ old('jumlah_peserta') }}" placeholder="Contoh: 30">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                        <div>
                                            <label for="nomor_induk" class="block font-semibold text-xs text-gray-500 uppercase tracking-wider mb-1">NIP/Nomor Induk</label>
                                            <input type="text" name="nomor_induk" id="nomor_induk" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="{{ old('nomor_induk', auth()->user()->nomor_induk) }}" placeholder="Nomor Induk Pegawai">
                                        </div>
                                        <div>
                                            <label for="kelas" class="block font-semibold text-xs text-gray-500 uppercase tracking-wider mb-1">Kelas / Jabatan</label>
                                            <input type="text" name="kelas" id="kelas" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="{{ old('kelas', auth()->user()->kelas) }}" placeholder="Contoh: XI IPA 1 / Guru Mapel">
                                        </div>
                                        <div>
                                            <label for="phone_number" class="block font-semibold text-xs text-gray-500 uppercase tracking-wider mb-1">No. HP / WA</label>
                                            <input type="text" name="phone_number" id="phone_number" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="{{ old('phone_number', auth()->user()->phone_number) }}" placeholder="0812xxxxxx">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="mata_pelajaran" class="block font-semibold text-xs text-gray-500 uppercase tracking-wider mb-1">Mata Pelajaran <span class="text-[10px] lowercase italic font-normal text-gray-400">(opsional)</span></label>
                                        <input type="text" name="mata_pelajaran" id="mata_pelajaran" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="{{ old('mata_pelajaran') }}" placeholder="Contoh: Biologi Reproduksi">
                                    </div>

                                    <div>
                                        <label for="tujuan_kegiatan" class="block font-semibold text-xs text-gray-500 uppercase tracking-wider mb-1">Tujuan Kegiatan / Judul Praktikum</label>
                                        <textarea name="tujuan_kegiatan" id="tujuan_kegiatan" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Jelaskan secara ringkas kegiatan yang akan dilakukan..." required>{{ old('tujuan_kegiatan') }}</textarea>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <label for="waktu_mulai" class="block font-semibold text-xs text-gray-500 uppercase tracking-wider mb-1">Waktu Mulai</label>
                                            <input type="datetime-local" name="waktu_mulai" id="waktu_mulai" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="{{ old('waktu_mulai') }}" required>
                                        </div>
                                        <div>
                                            <label for="waktu_selesai" class="block font-semibold text-xs text-gray-500 uppercase tracking-wider mb-1">Waktu Selesai</label>
                                            <input type="datetime-local" name="waktu_selesai" id="waktu_selesai" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="{{ old('waktu_selesai') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-8 flex justify-end gap-3 pt-5 border-t border-gray-100">
                                    <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-bold text-sm transition-all">Batal</button>
                                    <button type="submit" class="px-7 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold text-sm shadow-md shadow-blue-200 transition-all">Kirim Pengajuan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const filterForm = document.getElementById('filter-form');
                const statusSelect = document.getElementById('status');
                const labSelect = document.getElementById('laboratorium');

                const submitFilters = () => {
                    filterForm.submit();
                    const spinner = document.getElementById('loading-spinner');
                    if (spinner) spinner.classList.remove('hidden');
                };

                if (statusSelect) {
                    statusSelect.addEventListener('change', submitFilters);
                }

                if (labSelect) {
                    labSelect.addEventListener('change', submitFilters);
                }
            });
        </script>
    @endpush
    </div>
</x-app-layout>
