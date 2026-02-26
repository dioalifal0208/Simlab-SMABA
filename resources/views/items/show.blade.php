<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('items.details.title') }}: {{ $item->nama_alat }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('items.details.subtitle') }}</p>
            </div>
            <a href="{{ route('items.index') }}" class="mt-3 sm:mt-0 text-sm font-semibold text-indigo-500 hover:text-indigo-600 transition-colors">
                &larr; {{ __('items.details.back_to_list') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        {{-- 
            PENAMBAHAN: 
            Inisialisasi Alpine.js untuk mengelola modal foto.
            `showPhotoModal` akan mengontrol visibilitas modal.
            `activeImage` akan menyimpan URL gambar yang sedang aktif di galeri.
        --}}
        <div 
            class="max-w-7xl mx-auto sm:px-6 lg:px-8" 
            x-data="{ 
                showPhotoModal: false, 
                activeImage: '{{ $item->images->first() ? asset('storage/item-photos/thumbnails/medium/' . basename($item->images->first()->path)) : '' }}' 
            }"
        >
            {{-- Pesan Sukses/Error --}}
            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">{{ __('common.messages.success') }}</p>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Kolom Kiri: Sidebar Informasi & Aksi -->
                <aside class="lg:col-span-1 space-y-6">
                    <!-- Kartu Identitas Item -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl p-6" data-aos="fade-up" data-aos-once="true">
                        {{-- PERUBAHAN: Menampilkan galeri gambar --}}
                        <div class="mb-4">
                            <!-- Gambar Utama -->
                            <a href="#" @click.prevent="showPhotoModal = true" title="{{ __('items.details.photo_tip') }}" class="block">
                                @if($item->images->isNotEmpty())
                                    <img 
                                        :src="activeImage" 
                                        alt="{{ $item->nama_alat }}" 
                                        class="w-full h-56 object-cover rounded-lg border cursor-pointer transition-all duration-300"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="w-full h-56 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 border cursor-pointer">
                                        <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                                    </div>
                                @endif
                            </a>
                            <!-- Thumbnail Gallery -->
                            @if($item->images->count() > 1)
                            <div class="grid grid-cols-5 gap-2 mt-2">
                                @foreach($item->images as $image)
                                <img 
                                    @click="activeImage = '{{ asset('storage/item-photos/thumbnails/medium/' . basename($image->path)) }}'"
                                    src="{{ asset('storage/item-photos/thumbnails/small/' . basename($image->path)) }}" 
                                    alt="Thumbnail" 
                                    class="w-full h-12 object-cover rounded-md cursor-pointer border-2 transition-all"
                                    :class="{ 'border-indigo-500': activeImage === '{{ asset('storage/item-photos/thumbnails/medium/' . basename($image->path)) }}', 'border-transparent': activeImage !== '{{ asset('storage/item-photos/thumbnails/medium/' . basename($image->path)) }}' }"
                                    loading="lazy"
                                >
                                @endforeach
                            </div>
                            @else
                                <p class="text-xs text-center text-gray-400 mt-2">{{ __('items.details.single_photo') }}</p>
                            @endif
                        </div>

                        <span class="px-3 py-1 text-xs font-semibold text-indigo-800 bg-indigo-100 rounded-full">{{ $item->tipe }}</span>
                        <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $item->nama_alat }}</h1>
                        
                        <div class="mt-4 space-y-3 text-sm">
                            <div class="flex justify-between items-center"><span class="text-gray-500">{{ __('items.table.condition') }}</span> <span class="font-semibold">@if($item->kondisi == 'Baik')<span class="text-green-600">{{ __('items.status.good') }}</span>@elseif($item->kondisi == 'Kurang Baik')<span class="text-yellow-600">{{ __('items.status.fair') }}</span>@else<span class="text-red-600">{{ __('items.status.broken') }}</span>@endif</span></div>
                            <div class="flex justify-between items-center"><span class="text-gray-500">{{ __('items.table.location') }}</span> <span class="font-semibold">{{ $item->lokasi_penyimpanan }}</span></div>
                            <div class="flex justify-between items-center"><span class="text-gray-500">{{ __('items.table.lab') }}</span> <span class="font-semibold">{{ $item->laboratorium }}</span></div>
                            <div class="flex justify-between items-center"><span class="text-gray-500">{{ __('items.table.stock') }}</span> <span class="font-semibold">{{ $item->jumlah }} {{ $item->satuan }}</span></div>
                        </div>

                        {{-- PERUBAHAN: Barcode sekarang bisa diklik untuk menampilkan foto dan berisi URL --}}
                        <div class="mt-6 border-t pt-4 flex flex-col items-center" title="{{ __('items.details.photo_tip') }}">
                            <a href="#" @click.prevent="showPhotoModal = true" class="block cursor-pointer group">
                                <div class="p-2 border rounded-lg transition-transform duration-300 group-hover:scale-110">
                                    {{-- PERBAIKAN: Barcode sekarang meng-encode URL lengkap ke halaman ini --}}
                                    {!! DNS1D::getBarcodeHTML(route('items.show', $item->id), 'C128', 2, 60) !!}
                                </div>
                            </a>
                            <p class="text-xs text-gray-500 mt-2 text-center">{{ __('items.details.scan_tip') }}</p>
                        </div>

                        {{-- PENAMBAHAN: Modal untuk menampilkan foto --}}
                        <div x-show="showPhotoModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4" style="display: none;">
                            <div @click.outside="showPhotoModal = false" class="relative max-w-3xl max-h-full">
                                @if ($item->images->isNotEmpty())
                                    <img 
                                        :src="activeImage.replace('/thumbnails/medium/', '/original/')" 
                                        alt="Detail foto {{ $item->nama_alat }}" 
                                        class="w-full h-auto object-contain rounded-lg shadow-2xl" 
                                        style="max-height: 90vh;"
                                    >
                                @else
                                    <div class="w-96 h-96 bg-white rounded-lg flex items-center justify-center text-gray-500"><p>{{ __('items.details.no_photo') }}</p></div>
                                @endif
                                <button @click="showPhotoModal = false" class="absolute -top-3 -right-3 h-8 w-8 flex items-center justify-center bg-white rounded-full text-gray-700 hover:bg-gray-200 transition-colors shadow-lg" title="{{ __('items.actions.close') }}">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu Aksi Pengguna -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl p-6" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                        <h3 class="text-base font-bold text-gray-900 mb-4">{{ __('items.details.user_actions') }}</h3>
                        <div class="space-y-3">
                            @if($item->jumlah > 0)
                                <a href="{{ route('loans.create', ['item_id' => $item->id]) }}" class="w-full flex items-center justify-center px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 font-semibold text-sm shadow-md transition-colors">
                                    <i class="fas fa-hand-holding mr-2"></i> {{ __('items.actions.request_loan') }}
                                </a>
                            @elseif($item->tipe == 'Bahan Habis Pakai')
                                <form action="{{ route('stock-requests.store', $item->id) }}" method="POST"> @csrf <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-semibold text-sm shadow-md transition-colors"><i class="fas fa-box-open mr-2"></i> {{ __('items.actions.request_stock') }}</button></form>
                            @else
                                <button class="w-full flex items-center justify-center px-4 py-3 bg-gray-300 text-gray-500 rounded-lg font-semibold text-sm cursor-not-allowed"><i class="fas fa-hand-holding mr-2"></i> {{ __('items.status.unavailable') }}</button>
                            @endif
                            <a href="{{ route('damage-reports.create', $item->id) }}" class="w-full flex items-center justify-center px-4 py-3 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 font-semibold text-sm transition-colors border border-red-200">
                                <i class="fas fa-exclamation-triangle mr-2"></i> {{ __('items.actions.report_damage') }}
                            </a>
                        </div>
                    </div>

                    <!-- Kartu Aksi Admin -->
                    @can('is-admin')
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl p-6" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                        <h3 class="text-base font-bold text-gray-900 mb-4">{{ __('items.details.admin_actions') }}</h3>
                        <div class="space-y-3">
                            <a href="{{ route('items.edit', $item->id) }}" class="w-full flex items-center justify-center py-2 px-3 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-md transition-colors text-sm">{{ __('items.actions.edit') }}</a>
                            <a href="{{ route('maintenance.index', $item->id) }}" class="w-full flex items-center justify-center py-2 px-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition-colors text-sm">{{ __('items.actions.maintenance_log') }}</a>
                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-center py-2 px-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md transition-colors text-sm">{{ __('items.actions.delete') }}</button>
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
                            <h3 class="text-xl font-bold text-gray-900">{{ __('items.details.specs_title') }}</h3>
                            <p class="mt-4 text-md text-gray-700 whitespace-pre-wrap">{{ $item->deskripsi ?? __('items.details.no_description') }}</p>
                            
                            <div class="border-t mt-6 pt-6">
                                <dl class="text-sm space-y-3">
                                    <div class="flex justify-between"><dt class="text-gray-500">{{ __('items.details.inventory_code') }}</dt><dd class="font-mono text-gray-800">{{ $item->kode_inventaris ?? '-' }}</dd></div>
                                    <div class="flex justify-between"><dt class="text-gray-500">{{ __('items.details.procurement_year') }}</dt><dd class="font-semibold text-gray-800">{{ $item->tahun_pengadaan ?? '-' }}</dd></div>
                                    @if($item->tipe == 'Bahan Habis Pakai')<div class="flex justify-between"><dt class="text-gray-500">{{ __('items.details.min_stock') }}</dt><dd class="font-semibold text-gray-800">{{ $item->stok_minimum ?? '-' }} {{ $item->satuan }}</dd></div>@endif
                                    <div class="flex justify-between"><dt class="text-gray-500">{{ __('items.details.created_at') }}</dt><dd class="text-gray-800">{{ $item->created_at->format('d M Y') }}</dd></div>
                                    <div class="flex justify-between"><dt class="text-gray-500">{{ __('items.details.updated_at') }}</dt><dd class="text-gray-800">{{ $item->updated_at->format('d M Y') }}</dd></div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu Riwayat Peminjaman -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                        <div class="p-6 max-h-96 overflow-y-auto">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('items.details.loan_history') }}</h3>
                            <p class="text-sm text-center text-gray-500 py-8">{{ __('items.details.loan_history_soon') }}</p>
                        </div>
                    </div>

                    <!-- Kartu Riwayat Perawatan -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                        <div class="p-6 max-h-96 overflow-y-auto">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('items.details.maintenance_history') }}</h3>
                            @forelse($item->maintenanceLogs as $log)
                                <div class="p-3 rounded-lg bg-gray-50 border mb-3"><p class="text-sm font-semibold text-gray-800">{{ $log->activity }}</p><p class="text-xs text-gray-500">{{ __('items.details.done_by', ['name' => $log->user->name, 'date' => $log->created_at->format('d M Y')]) }}</p>@if($log->notes)<p class="text-sm text-gray-600 mt-1 italic">"{{ $log->notes }}"</p>@endif</div>
                            @empty
                                <p class="text-sm text-center text-gray-500 py-8">{{ __('items.details.no_maintenance') }}</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Kartu Modul Terkait -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="300" data-aos-once="true">
                        <div class="p-6 max-h-96 overflow-y-auto">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('items.details.usage_modules') }}</h3>
                            @forelse($item->practicumModules as $module)
                                <a href="{{ route('practicum-modules.show', $module->id) }}" class="block p-3 rounded-lg hover:bg-gray-50 transition-colors mb-2">
                                    <p class="text-sm font-semibold text-indigo-600 hover:underline">{{ $module->title }}</p>
                                    <p class="text-xs text-gray-500">{{ __('items.details.created_by') }} {{ $module->user->name ?? 'N/A' }}</p>
                                </a>
                            @empty
                                <p class="text-sm text-center text-gray-500 py-8">{{ __('items.details.no_modules') }}</p>
                            @endforelse
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</x-app-layout>

