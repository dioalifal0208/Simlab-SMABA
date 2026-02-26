<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    Detail Laporan Kerusakan #{{ $report->id }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Laporan untuk item: <span class="font-semibold">{{ $report->item->nama_alat }}</span></p>
            </div>
            <a href="{{ route('damage-reports.index') }}" class="mt-3 sm:mt-0 text-sm font-semibold text-indigo-500 hover:text-indigo-600 transition-colors">
                &larr; Kembali ke Daftar Laporan
            </a>
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

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8" data-aos="fade-in" data-aos-once="true">

                {{-- Kolom Kiri: Detail Laporan & Foto --}}
                <div class="lg:col-span-3 space-y-6">
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Detail Laporan</h3>
                            <dl class="space-y-4">
                                <div><dt class="text-sm font-medium text-gray-500">Item yang Dilaporkan</dt><dd class="mt-1 font-semibold text-indigo-600 hover:underline"><a href="{{ route('items.show', $report->item->id) }}">{{ $report->item->nama_alat }}</a></dd></div>
                                <div><dt class="text-sm font-medium text-gray-500">Dilaporkan oleh</dt><dd class="mt-1 font-semibold text-gray-800">{{ $report->user->name }}</dd></div>
                                <div><dt class="text-sm font-medium text-gray-500">Tanggal Laporan</dt><dd class="mt-1 font-semibold text-gray-800">{{ $report->created_at->format('d F Y, H:i') }}</dd></div>
                                <div><dt class="text-sm font-medium text-gray-500">Deskripsi Kerusakan</dt><dd class="mt-1 text-gray-700 bg-gray-50 p-3 rounded-md whitespace-pre-wrap">{{ $report->description }}</dd></div>
                            </dl>
                        </div>
                    </div>
                    @if($report->photo)
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                         <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Foto Kerusakan</h3>
                            <img src="{{ Storage::url($report->photo) }}" alt="Foto Kerusakan" class="w-full h-auto object-cover rounded-lg shadow-md">
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Kolom Kanan: Status & Aksi Admin --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Status Laporan</h3>
                             <div class="text-center">
                                @if($report->status == 'Dilaporkan') <span class="px-4 py-2 text-sm font-bold leading-none text-yellow-800 bg-yellow-100 rounded-full">Dilaporkan</span>
                                @elseif($report->status == 'Diverifikasi') <span class="px-4 py-2 text-sm font-bold leading-none text-green-800 bg-green-100 rounded-full">Diverifikasi</span>
                                @else <span class="px-4 py-2 text-sm font-bold leading-none text-green-800 bg-green-100 rounded-full">Diperbaiki</span>
                                @endif
                            </div>

                            @if($report->status != 'Diperbaiki')
                                <form action="{{ route('damage-reports.update', $report->id) }}" method="POST" class="mt-6 border-t pt-6">
                                    @csrf @method('PATCH')
                                    <h4 class="font-semibold text-gray-700 mb-2">Ubah Status Laporan</h4>
                                    <div class="flex items-center space-x-3">
                                        <select name="status" id="status" class="flex-grow rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 text-sm">
                                            @if($report->status == 'Dilaporkan') <option value="Diverifikasi">Ubah ke: Diverifikasi</option> @endif
                                            <option value="Diperbaiki">Ubah ke: Selesai Diperbaiki</option>
                                        </select>
                                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500 font-semibold text-sm shadow-sm transition-colors">Simpan</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>

                    {{-- PENAMBAHAN: Kartu Aksi Lainnya (Termasuk Tombol Hapus) --}}
                    @can('is-admin')
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Lainnya</h3>
                            <form action="{{ route('damage-reports.destroy', $report->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-center py-2 px-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md transition-colors text-sm">Hapus Laporan Ini</button>
                            </form>
                        </div>
                    </div>
                    @endcan

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
