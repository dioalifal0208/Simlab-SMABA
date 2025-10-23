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
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Wrapper Konten Utama dengan Animasi --}}
            <div data-aos="fade-up" data-aos-duration="500" data-aos-once="true">
                {{-- Form Filter Status Otomatis --}}
                <div class="mb-6 bg-white overflow-hidden shadow-lg sm:rounded-xl">
                    <form action="{{ route('bookings.index') }}" method="GET" class="p-4 sm:p-6" id="filter-form">
                        <div class="flex items-center space-x-4">
                            <label for="status" class="text-sm font-medium text-gray-700">Filter Status:</label>
                            <select name="status" id="status" class="w-full sm:w-auto rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue text-sm">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                    </form>
                </div>

                {{-- Tabel Daftar Peminjaman (REDESIGNED) --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    @if (auth()->user()->role == 'admin')
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
                                    @endif
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tujuan Kegiatan</th>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                    <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 divide-y divide-gray-200">
                                @forelse ($bookings as $booking)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-4 px-6 text-sm font-medium text-gray-500">#{{ $booking->id }}</td>
                                        @if (auth()->user()->role == 'admin')
                                            <td class="py-4 px-6 text-sm font-semibold text-gray-900">{{ $booking->user->name }}</td>
                                        @endif
                                        <td class="py-4 px-6 text-sm font-medium text-gray-800">{{ Str::limit($booking->tujuan_kegiatan, 40) }}</td>
                                        <td class="py-4 px-6 text-sm">
                                            <div>{{ $booking->waktu_mulai->format('d M Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $booking->waktu_mulai->format('H:i') }} - {{ $booking->waktu_selesai->format('H:i') }}</div>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            @if($booking->status == 'pending')
                                                <span class="px-3 py-1 text-xs font-bold leading-none text-yellow-800 bg-yellow-100 rounded-full">Menunggu</span>
                                            @elseif($booking->status == 'approved')
                                                <span class="px-3 py-1 text-xs font-bold leading-none text-green-800 bg-green-100 rounded-full">Disetujui</span>
                                            @elseif($booking->status == 'rejected')
                                                <span class="px-3 py-1 text-xs font-bold leading-none text-red-800 bg-red-100 rounded-full">Ditolak</span>
                                            @elseif($booking->status == 'completed')
                                                <span class="px-3 py-1 text-xs font-bold leading-none text-gray-800 bg-gray-100 rounded-full">Selesai</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <a href="{{ route('bookings.show', $booking->id) }}" class="px-4 py-2 bg-smaba-dark-blue text-white rounded-md hover:bg-smaba-light-blue font-semibold text-xs shadow-sm transition-colors duration-300">
                                                Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->role == 'admin' ? '6' : '5' }}" class="py-8 text-center text-gray-500">
                                            <p class="font-semibold">Tidak Ada Data</p>
                                            <p class="text-sm mt-1">Belum ada data booking yang cocok dengan filter Anda.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-200">
                        {{ $bookings->withQueryString()->links() }}
                    </div>
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

                statusSelect.addEventListener('change', () => {
                    filterForm.submit();
                });
            });
        </script>
    @endpush
</x-app-layout>