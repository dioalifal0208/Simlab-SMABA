<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ $module->title }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Modul dibuat oleh: <span class="font-semibold">{{ $module->user->name }}</span> pada {{ $module->created_at->format('d M Y') }}</p>
            </div>
            <a href="{{ route('practicum-modules.index') }}" class="mt-3 sm:mt-0 text-sm font-semibold text-indigo-500 hover:text-indigo-600 transition-colors">
                &larr; Kembali ke Daftar Modul
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8" data-aos="fade-in" data-aos-once="true">

                {{-- Kolom Kiri: Deskripsi Modul --}}
                <div class="lg:col-span-3 space-y-6">
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Deskripsi / Langkah-langkah</h3>
                            @if($module->description)
                                <div class="prose max-w-none text-gray-700">
                                    {!! nl2br(e($module->description)) !!} {{-- nl2br untuk menghormati baris baru --}}
                                </div>
                            @else
                                <p class="text-gray-500 italic">Tidak ada deskripsi untuk modul ini.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Daftar Item & Aksi --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Alat & Bahan Dibutuhkan</h3>
                             @if($module->items->isNotEmpty())
                                <ul class="list-disc list-inside space-y-2 text-sm text-gray-800 mb-6">
                                    @foreach($module->items as $item)
                                        <li>
                                            <a href="{{ route('items.show', $item->id) }}" class="font-semibold hover:underline">{{ $item->nama_alat }}</a>
                                            <span class="text-xs text-gray-500">({{ $item->tipe }})</span>
                                        </li>
                                    @endforeach
                                </ul>

                                {{-- Tombol Ajaib! --}}
                                <a href="{{ route('loans.create', ['module_items' => $module->items->pluck('id')->implode(',')]) }}" 
                                   class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold text-sm shadow-md transition-colors">
                                    <i class="fas fa-hand-holding mr-2"></i> Ajukan Peminjaman Semua Item
                                </a>
                             @else
                                <p class="text-center text-gray-500 text-sm py-4">Tidak ada alat atau bahan yang ditautkan ke modul ini.</p>
                             @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
