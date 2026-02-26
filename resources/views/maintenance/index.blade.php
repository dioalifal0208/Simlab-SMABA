<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    Riwayat Perawatan: {{ $item->nama_alat }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Catat dan lihat semua riwayat perbaikan dan perawatan untuk item ini.</p>
            </div>
            <a href="{{ route('items.show', $item->id) }}" class="mt-3 sm:mt-0 text-sm font-semibold text-indigo-500 hover:text-indigo-600 transition-colors">
                &larr; Kembali ke Detail Item
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">Sukses</p>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border-l-4 border-red-400 text-red-700 p-4 text-sm rounded-lg" role="alert">
                    <p class="font-bold">Oops! Ada yang salah:</p>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-once="true">
                <div class="p-6 md:p-8 text-gray-900">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Tambah Catatan Perawatan Baru</h3>
                    <form action="{{ route('maintenance.store', $item->id) }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div><label for="tanggal_perawatan" class="block font-medium text-sm text-gray-700">Tanggal Perawatan</label><input type="date" name="tanggal_perawatan" id="tanggal_perawatan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600" value="{{ old('tanggal_perawatan', date('Y-m-d')) }}" required></div>
                                <div><label for="hasil" class="block font-medium text-sm text-gray-700">Hasil Perawatan</label><input type="text" name="hasil" id="hasil" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600" value="{{ old('hasil') }}" placeholder="Contoh: Selesai dibersihkan, Komponen diganti" required></div>
                            </div>
                            <div><label for="masalah_ditemukan" class="block font-medium text-sm text-gray-700">Masalah Ditemukan</label><textarea name="masalah_ditemukan" id="masalah_ditemukan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600" required>{{ old('masalah_ditemukan') }}</textarea></div>
                            <div><label for="tindakan_perbaikan" class="block font-medium text-sm text-gray-700">Tindakan Perbaikan</label><textarea name="tindakan_perbaikan" id="tindakan_perbaikan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600" required>{{ old('tindakan_perbaikan') }}</textarea></div>
                            <div><label for="biaya" class="block font-medium text-sm text-gray-700">Biaya (Rp) <span class="text-xs text-gray-400">(Opsional)</span></label><input type="number" name="biaya" id="biaya" min="0" placeholder="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600" value="{{ old('biaya') }}"></div>
                        </div>
                        <div class="mt-8 flex justify-end"><button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500 font-semibold text-sm shadow-md transition-colors">Simpan Log</button></div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                <div class="p-6"><h3 class="text-xl font-bold text-gray-900">Daftar Riwayat</h3></div>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hasil</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Masalah & Tindakan</th>
                                <th class="py-3 px-6 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Biaya</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 divide-y divide-gray-200">
                            @forelse ($maintenanceLogs as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm whitespace-nowrap">{{ $log->tanggal_perawatan->format('d M Y') }}</td>
                                    <td class="py-4 px-6 text-sm font-semibold">{{ $log->hasil }}</td>
                                    <td class="py-4 px-6 text-sm">
                                        <p><strong class="font-medium text-gray-500">Masalah:</strong> {{ $log->masalah_ditemukan }}</p>
                                        <p class="mt-1"><strong class="font-medium text-gray-500">Tindakan:</strong> {{ $log->tindakan_perbaikan }}</p>
                                    </td>
                                    <td class="py-4 px-6 text-sm text-right whitespace-nowrap">@if($log->biaya > 0) Rp {{ number_format($log->biaya, 0, ',', '.') }} @else - @endif</td>
                                    <td class="py-4 px-6 text-sm">{{ $log->user->name }}</td>
                                </tr>
                            @empty
                            <tr><td colspan="5" class="py-8 text-center text-gray-500"><p class="font-semibold">Belum ada riwayat perawatan untuk item ini.</p></td></tr>
                        @endforelse {{-- <-- INI YANG BENAR --}}
                        </tbody>
                    </table>
                </div>
                 @if ($maintenanceLogs->hasPages())
                    <div class="p-4 border-t border-gray-200">{{ $maintenanceLogs->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
