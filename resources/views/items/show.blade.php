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
            
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8" data-aos="fade-in" data-aos-once="true">

                {{-- Kolom Kiri (Konten Utama - Lebar 3/5) --}}
                <div class="lg:col-span-3 space-y-6">
                    {{-- Kartu Gambar --}}
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                        @if ($item->photo)
                            <img src="{{ Storage::url($item->photo) }}" alt="{{ $item->nama_alat }}" class="w-full h-64 object-cover">
                        @else
                            <div class="w-full h-64 bg-gray-200 flex items-center justify-center text-gray-400">
                                <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                            </div>
                        @endif
                    </div>
                    
                    {{-- Kartu Detail Informasi --}}
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                        <div class="p-6">
                            <span class="px-3 py-1 text-xs font-semibold text-indigo-800 bg-indigo-100 rounded-full">{{ $item->tipe }}</span>
                            <h1 class="text-3xl font-bold text-smaba-text mt-2">{{ $item->nama_alat }}</h1>
                            <dl class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Kondisi</dt>
                                    <dd class="mt-1">
                                        @if($item->kondisi == 'Baik') <span class="px-3 py-1 text-sm font-semibold text-green-800 bg-green-100 rounded-full">Baik</span>
                                        @elseif($item->kondisi == 'Kurang Baik') <span class="px-3 py-1 text-sm font-semibold text-yellow-800 bg-yellow-100 rounded-full">Kurang Baik</span>
                                        @else <span class="px-3 py-1 text-sm font-semibold text-red-800 bg-red-100 rounded-full">Rusak</span>
                                        @endif
                                    </dd>
                                </div>
                                <div><dt class="text-sm font-medium text-gray-500">Jumlah & Satuan</dt><dd class="mt-1 text-md text-gray-900 font-semibold">{{ $item->jumlah }} {{ $item->satuan }}</dd></div>
                                @if($item->tipe == 'Bahan Habis Pakai')
                                <div><dt class="text-sm font-medium text-gray-500">Stok Minimum</dt><dd class="mt-1 text-md text-gray-900 font-semibold">{{ $item->stok_minimum ?? '-' }} {{ $item->satuan }}</dd></div>
                                @endif
                                <div><dt class="text-sm font-medium text-gray-500">Lokasi Penyimpanan</dt><dd class="mt-1 text-md text-gray-900 font-semibold">{{ $item->lokasi_penyimpanan }}</dd></div>
                                <div class="col-span-2"><dt class="text-sm font-medium text-gray-500">Deskripsi / Keterangan</dt><dd class="mt-1 text-md text-gray-700 whitespace-pre-wrap">{{ $item->keterangan ?? 'Tidak ada keterangan.' }}</dd></div>
                            </dl>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="400" data-aos-once="true"> 
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-smaba-text mb-4">Digunakan Dalam Modul</h3>
                            <div class="space-y-2 max-h-48 overflow-y-auto">
                                @forelse($item->practicumModules as $module)
                                    <a href="{{ route('practicum-modules.show', $module->id) }}" class="block p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                        <p class="text-sm font-semibold text-smaba-dark-blue hover:underline">{{ $module->title }}</p>
                                        <p class="text-xs text-gray-500">Dibuat oleh {{ $module->user->name ?? 'N/A' }}</p> 
                                    </a>
                                @empty
                                    <p class="text-sm text-gray-500">Item ini belum ditautkan ke modul praktikum manapun.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="500" data-aos-once="true">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-smaba-text mb-4">Informasi Tambahan</h3>
                            <dl class="text-sm space-y-3">
                                <div class="flex justify-between"><dt class="text-gray-500">Kode Inventaris</dt><dd class="font-semibold text-gray-800">{{ $item->kode_inventaris ?? '-' }}</dd></div>
                                <div class="flex justify-between"><dt class="text-gray-500">Tahun Pengadaan</dt><dd class="font-semibold text-gray-800">{{ $item->tahun_pengadaan ?? '-' }}</dd></div>
                                <div class="flex justify-between"><dt class="text-gray-500">Dibuat pada</dt><dd class="font-semibold text-gray-800">{{ $item->created_at->format('d M Y') }}</dd></div>
                                <div class="flex justify-between"><dt class="text-gray-500">Diperbarui pada</dt><dd class="font-semibold text-gray-800">{{ $item->updated_at->format('d M Y') }}</dd></div>
                            </dl>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan (Sidebar Aksi - Lebar 2/5) --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- PERUBAHAN: KARTU STATUS --}}
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-smaba-text mb-4">Status Ketersediaan</h3>
                            <div class="p-4 rounded-lg text-center font-bold text-lg {{ $item->jumlah > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $item->jumlah > 0 ? 'Tersedia' : 'Stok Habis' }}
                            </div>
                        </div>
                    </div>
                    
                    {{-- PERUBAHAN: KARTU AKSI PENGGUNA (DENGAN LOGIKA STOK) --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-smaba-text mb-4">Aksi Pengguna</h3>
                        <div class="space-y-3">

                            {{-- Logika Tombol --}}
                            @if($item->jumlah > 0)
                                {{-- JIKA STOK ADA: Tampilkan tombol "Ajukan Peminjaman" --}}
                                <a href="{{ route('loans.create', ['item_id' => $item->id]) }}" class="w-full flex items-center justify-center px-4 py-3 bg-smaba-dark-blue text-white rounded-lg hover:bg-smaba-light-blue font-semibold text-sm shadow-md transition-colors">
                                    <i class="fas fa-hand-holding mr-2"></i> Ajukan Peminjaman
                                </a>
                            @elseif($item->tipe == 'Bahan Habis Pakai')
                                {{-- JIKA STOK HABIS & TIPE-NYA BAHAN: Tampilkan tombol "Minta Restock" --}}
                                <form action="{{ route('stock-requests.store', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-semibold text-sm shadow-md transition-colors">
                                        <i class="fas fa-box-open mr-2"></i> Minta Restock
                                    </button>
                                </form>
                            @else
                                {{-- JIKA STOK HABIS & TIPE-NYA ALAT (bukan bahan): Tampilkan non-aktif --}}
                                <button class="w-full flex items-center justify-center px-4 py-3 bg-gray-300 text-gray-500 rounded-lg font-semibold text-sm cursor-not-allowed">
                                    <i class="fas fa-hand-holding mr-2"></i> Item Tidak Tersedia
                                </button>
                            @endif

                            {{-- Tombol Lapor Rusak (selalu ada) --}}
                            <a href="{{ route('damage-reports.create', $item->id) }}" class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold text-sm shadow-md transition-colors">
                                <i class="fas fa-exclamation-triangle mr-2"></i> Laporkan Kerusakan
                            </a>
                        </div>
                    </div>
                </div>
                    
                    @can('is-admin')
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="300" data-aos-once="true"> {{-- Delay diubah --}}
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-smaba-text mb-4">Aksi Admin</h3>
                            <div class="flex space-x-2">
                                <a href="{{ route('items.edit', $item->id) }}" class="flex-1 text-center py-2 px-3 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-md transition-colors text-sm">Edit</a>
                                <a href="{{ route('maintenance.index', $item->id) }}" class="flex-1 text-center py-2 px-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition-colors text-sm">Riwayat</a>
                                <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="delete-form flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-center py-2 px-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md transition-colors text-sm">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endcan

                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="400" data-aos-once="true"> {{-- Delay diubah --}}
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-smaba-text mb-4">QR Code Item</h3>
                            <div class="flex justify-center p-4 bg-white rounded-lg">
                                {!!
                                    QrCode::format('svg')
                                        ->size(150)
                                        ->generate(route('items.show', $item->id))
                                !!}
                            </div>
                            <p class="text-center text-xs text-gray-500 mt-4">
                                Pindai Barcode ini untuk membuka halaman detail item secara langsung.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
