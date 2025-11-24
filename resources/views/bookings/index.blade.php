<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            {{-- Judul dan Sub-judul Halaman --}}
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                    @if (auth()->user()->role == 'admin')
                        {{ __('Kelola Booking Lab') }}
                    @else
                        {{ __('Riwayat Booking Lab Saya') }}
                    @endif
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    @if (auth()->user()->role == 'admin')
                        Lihat dan proses semua pengajuan jadwal penggunaan lab.
                    @else
                        Ajukan jadwal dan lacak status booking lab Anda.
                    @endif
                </p>
            </div>
            
            {{-- Tombol Aksi "Ajukan Booking Baru" --}}
            <a href="{{ route('bookings.create') }}" class="mt-3 sm:mt-0 px-5 py-2 bg-smaba-dark-blue text-white rounded-lg hover:bg-smaba-light-blue font-semibold text-sm shadow-md transition-colors duration-300 ease-in-out transform hover:-translate-y-0.5">
                <i class="fas fa-plus mr-2"></i> Ajukan Booking Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Pesan Sukses (Flash Message) --}}
            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">Sukses</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            {{-- Wrapper Konten Utama dengan Animasi --}}
            <div data-aos="fade-up" data-aos-duration="500" data-aos-once="true">
                {{-- Form Filter Status Otomatis --}}
                <div class="mb-6 bg-white overflow-hidden border border-gray-100 shadow-sm sm:rounded-xl">
                    <form action="{{ route('bookings.index') }}" method="GET" class="p-4 sm:p-6 space-y-4" id="filter-form">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 space-y-4 sm:space-y-0">
                            <div class="flex items-center space-x-3">
                                <label for="status" class="text-sm font-medium text-gray-700">Status:</label>
                                <select name="status" id="status" class="w-full sm:w-48 rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue text-sm">
                                    <option value="">Semua</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                            <div class="flex items-center space-x-3">
                                <label for="laboratorium" class="text-sm font-medium text-gray-700">Laboratorium:</label>
                                <select name="laboratorium" id="laboratorium" class="w-full sm:w-48 rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue text-sm">
                                    <option value="">Semua Lab</option>
                                    <option value="Biologi" {{ request('laboratorium') == 'Biologi' ? 'selected' : '' }}>Biologi</option>
                                    <option value="Fisika" {{ request('laboratorium') == 'Fisika' ? 'selected' : '' }}>Fisika</option>
                                    <option value="Bahasa" {{ request('laboratorium') == 'Bahasa' ? 'selected' : '' }}>Bahasa</option>
                                </select>
                            </div>
                            {{-- Indikator Loading --}}
                            <i id="loading-spinner" class="fas fa-spinner fa-spin text-gray-500 hidden"></i>
                        </div>
                    </form>
                </div>

                {{-- Daftar Booking (REDESIGNED with Cards) --}}
                <div class="space-y-4">
                    @forelse ($bookings as $booking)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-gray-200 transition-all duration-200 hover:-translate-y-1 hover:shadow-md 
                            @if($booking->status == 'approved') border-green-500 @elseif($booking->status == 'pending') border-yellow-500 @elseif($booking->status == 'rejected') border-red-500 @else border-gray-400 @endif">
                            <div class="p-4 sm:p-6">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                    {{-- Kolom Kiri: Info Utama --}}
                                    <div class="flex-grow">
                                        <div class="flex items-center space-x-3 flex-wrap gap-2">
                                            {{-- Status Badge --}}
                                            <span class="px-3 py-1 text-xs font-bold leading-none rounded-full
                                                @if($booking->status == 'pending') text-yellow-800 bg-yellow-100
                                                @elseif($booking->status == 'approved') text-green-800 bg-green-100
                                                @elseif($booking->status == 'rejected') text-red-800 bg-red-100
                                                @else text-gray-800 bg-gray-100 @endif">
                                                {{-- Menggunakan pemetaan untuk teks yang lebih ramah pengguna --}}
                                                {{ match($booking->status) {
                                                    'pending' => 'Menunggu',
                                                    'approved' => 'Disetujui',
                                                    'rejected' => 'Ditolak',
                                                    'completed' => 'Selesai',
                                                    default => ucfirst($booking->status)
                                                } }}
                                            </span>
                                            <p class="text-sm font-semibold text-smaba-dark-blue">{{ $booking->tujuan_kegiatan }}</p>
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700">
                                                {{ $booking->laboratorium }}
                                            </span>
                                        </div>
                                        @if (auth()->user()->role == 'admin')
                                            <p class="mt-2 text-sm text-gray-600">
                                                <i class="fas fa-user-circle fa-fw mr-1 text-gray-400"></i> Diajukan oleh: <span class="font-medium">{{ $booking->user->name }}</span>
                                            </p>
                                        @endif
                                    </div>
                                    {{-- Kolom Kanan: Aksi --}}
                                    <div class="mt-4 sm:mt-0 sm:ml-4 flex-shrink-0">
                                        <a href="{{ route('bookings.show', $booking->id) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-smaba-dark-blue text-white rounded-md hover:bg-smaba-light-blue font-semibold text-xs shadow-sm transition-colors duration-300">
                                            <i class="fas fa-eye mr-2"></i> Lihat Detail
                                        </a>
                                    </div>
                                </div>
                                {{-- Footer Kartu: Info Tanggal --}}
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
                        <div class="text-center py-12 bg-white rounded-lg shadow-md">
                            <i class="fas fa-folder-open text-4xl text-gray-300"></i>
                            <p class="mt-4 font-semibold text-gray-700">Tidak Ada Data Booking</p>
                            <p class="text-sm mt-1 text-gray-500">Belum ada data booking yang cocok dengan filter Anda.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Paginasi --}}
                <div class="mt-6">
                    {{ $bookings->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk Auto-Filter --}}
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

                if (statusSelect) { // Pastikan elemen ada
                    // PERBAIKAN: Menambahkan spinner saat form disubmit
                    statusSelect.addEventListener('change', submitFilters);
                }

                if (labSelect) {
                    labSelect.addEventListener('change', submitFilters);
                }
            });
        </script>
    @endpush
</x-app-layout>
