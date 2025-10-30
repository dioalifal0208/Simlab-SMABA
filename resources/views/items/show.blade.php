{{-- Menambahkan 'use' statement untuk QrCode di paling atas file --}}
@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                    Detail Item: {{ $item->nama_alat }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Informasi lengkap, riwayat, dan aksi untuk item inventaris.</p>
            </div>
            <a href="{{ route('items.index') }}" class="mt-3 sm:mt-0 text-sm font-semibold text-smaba-light-blue hover:text-smaba-dark-blue transition-colors">
                &larr; Kembali ke Daftar Inventaris
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Pesan Sukses/Error --}}
            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">Sukses</p>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Kolom Kiri: Sidebar Informasi & Aksi -->
                <aside class="lg:col-span-1 space-y-6">
                    <!-- Kartu Identitas Item -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl p-6" data-aos="fade-up" data-aos-once="true">
                        @if ($item->photo)
                            <img src="{{ Storage::url($item->photo) }}" alt="{{ $item->nama_alat }}" class="w-full h-48 object-cover rounded-lg border mb-4">
                        @else
                            <div class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 border mb-4">
                                <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                            </div>
                        @endif
                        <span class="px-3 py-1 text-xs font-semibold text-indigo-800 bg-indigo-100 rounded-full">{{ $item->tipe }}</span>
                        <h1 class="text-2xl font-bold text-smaba-text mt-2">{{ $item->nama_alat }}</h1>
                        
                        <div class="mt-4 space-y-3 text-sm">
                            <div class="flex justify-between items-center"><span class="text-gray-500">Kondisi</span> <span class="font-semibold">@if($item->kondisi == 'Baik')<span class="text-green-600">Baik</span>@elseif($item->kondisi == 'Kurang Baik')<span class="text-yellow-600">Kurang Baik</span>@else<span class="text-red-600">Rusak</span>@endif</span></div>
                            <div class="flex justify-between items-center"><span class="text-gray-500">Lokasi</span> <span class="font-semibold">{{ $item->lokasi_penyimpanan }}</span></div>
                            <div class="flex justify-between items-center"><span class="text-gray-500">Stok Tersedia</span> <span class="font-semibold">{{ $item->jumlah }} {{ $item->satuan }}</span></div>
                        </div>
                    </div>

                    <!-- Kartu Aksi Pengguna -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl p-6" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                        <h3 class="text-base font-bold text-smaba-text mb-4">Aksi Pengguna</h3>
                        <div class="space-y-3">
                            @if($item->jumlah > 0)
                                <a href="{{ route('loans.create', ['item_id' => $item->id]) }}" class="w-full flex items-center justify-center px-4 py-3 bg-smaba-dark-blue text-white rounded-lg hover:bg-smaba-light-blue font-semibold text-sm shadow-md transition-colors">
                                    <i class="fas fa-hand-holding mr-2"></i> Ajukan Peminjaman
                                </a>
                            @elseif($item->tipe == 'Bahan Habis Pakai')
                                <form action="{{ route('stock-requests.store', $item->id) }}" method="POST"> @csrf <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-semibold text-sm shadow-md transition-colors"><i class="fas fa-box-open mr-2"></i> Minta Restock</button></form>
                            @else
                                <button class="w-full flex items-center justify-center px-4 py-3 bg-gray-300 text-gray-500 rounded-lg font-semibold text-sm cursor-not-allowed"><i class="fas fa-hand-holding mr-2"></i> Item Tidak Tersedia</button>
                            @endif
                            <a href="{{ route('damage-reports.create', $item->id) }}" class="w-full flex items-center justify-center px-4 py-3 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 font-semibold text-sm transition-colors border border-red-200">
                                <i class="fas fa-exclamation-triangle mr-2"></i> Laporkan Kerusakan
                            </a>
                        </div>
                    </div>

                    <!-- Kartu Aksi Admin -->
                    @can('is-admin')
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl p-6" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                        <h3 class="text-base font-bold text-smaba-text mb-4">Aksi Admin</h3>
                        <div class="space-y-3">
                            <a href="{{ route('items.edit', $item->id) }}" class="w-full flex items-center justify-center py-2 px-3 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-md transition-colors text-sm">Edit Item</a>
                            <a href="{{ route('maintenance.index', $item->id) }}" class="w-full flex items-center justify-center py-2 px-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition-colors text-sm">Catat Perawatan</a>
                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-center py-2 px-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md transition-colors text-sm">Hapus Item</button>
                            </form>
                        </div>
                    </div>
                    @endcan
                </aside>

                <!-- Kolom Kanan: Konten Detail yang bisa di-scroll -->
                <main class="lg:col-span-2 space-y-6">
                    <!-- Kartu Deskripsi & Spesifikasi -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-once="true">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-smaba-text">Deskripsi & Spesifikasi</h3>
                            <p class="mt-4 text-md text-gray-700 whitespace-pre-wrap">{{ $item->deskripsi ?? 'Tidak ada keterangan.' }}</p>
                            
                            <div class="border-t mt-6 pt-6">
                                <dl class="text-sm space-y-3">
                                    <div class="flex justify-between"><dt class="text-gray-500">Kode Inventaris</dt><dd class="font-mono text-gray-800">{{ $item->kode_inventaris ?? '-' }}</dd></div>
                                    <div class="flex justify-between"><dt class="text-gray-500">Tahun Pengadaan</dt><dd class="font-semibold text-gray-800">{{ $item->tahun_pengadaan ?? '-' }}</dd></div>
                                    @if($item->tipe == 'Bahan Habis Pakai')<div class="flex justify-between"><dt class="text-gray-500">Stok Minimum</dt><dd class="font-semibold text-gray-800">{{ $item->stok_minimum ?? '-' }} {{ $item->satuan }}</dd></div>@endif
                                    <div class="flex justify-between"><dt class="text-gray-500">Dibuat pada</dt><dd class="text-gray-800">{{ $item->created_at->format('d M Y') }}</dd></div>
                                    <div class="flex justify-between"><dt class="text-gray-500">Diperbarui pada</dt><dd class="text-gray-800">{{ $item->updated_at->format('d M Y') }}</dd></div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu Riwayat Peminjaman -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                        <div class="p-6 max-h-96 overflow-y-auto">
                            <h3 class="text-xl font-bold text-smaba-text mb-4">Riwayat Peminjaman</h3>
                            <p class="text-sm text-center text-gray-500 py-8">Fitur riwayat peminjaman akan segera tersedia.</p>
                        </div>
                    </div>

                    <!-- Kartu Riwayat Perawatan -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                        <div class="p-6 max-h-96 overflow-y-auto">
                            <h3 class="text-xl font-bold text-smaba-text mb-4">Riwayat Perawatan</h3>
                            @forelse($item->maintenanceLogs as $log)
                                <div class="p-3 rounded-lg bg-gray-50 border mb-3"><p class="text-sm font-semibold text-gray-800">{{ $log->activity }}</p><p class="text-xs text-gray-500">Dilakukan oleh {{ $log->user->name }} pada {{ $log->created_at->format('d M Y') }}</p>@if($log->notes)<p class="text-sm text-gray-600 mt-1 italic">"{{ $log->notes }}"</p>@endif</div>
                            @empty
                                <p class="text-sm text-center text-gray-500 py-8">Belum ada riwayat perawatan untuk item ini.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Kartu Modul Terkait -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="300" data-aos-once="true">
                        <div class="p-6 max-h-96 overflow-y-auto">
                            <h3 class="text-xl font-bold text-smaba-text mb-4">Digunakan Dalam Modul</h3>
                            @forelse($item->practicumModules as $module)
                                <a href="{{ route('practicum-modules.show', $module->id) }}" class="block p-3 rounded-lg hover:bg-gray-50 transition-colors mb-2">
                                    <p class="text-sm font-semibold text-smaba-dark-blue hover:underline">{{ $module->title }}</p>
                                    <p class="text-xs text-gray-500">Dibuat oleh {{ $module->user->name ?? 'N/A' }}</p>
                                </a>
                            @empty
                                <p class="text-sm text-center text-gray-500 py-8">Item ini belum ditautkan ke modul praktikum manapun.</p>
                            @endforelse
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</x-app-layout>
