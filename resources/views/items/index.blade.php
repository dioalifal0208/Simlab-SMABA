<x-app-layout x-data="{ showImportModal: false }">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                    {{ __('Inventaris Laboratorium') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola semua alat dan bahan yang tersedia di laboratorium.</p>
            </div>
            @can('is-admin')
                <div class="flex items-center space-x-3 mt-3 sm:mt-0">
                    {{-- PERBAIKAN: Menggunakan variabel `showImportModal` untuk membuka modal, bukan $dispatch --}}
                    <button @click="showImportModal = true" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold text-sm shadow-sm transition-colors">
                        <i class="fas fa-upload mr-2"></i> Import Item
                    </button>
                    <a href="{{ route('items.create') }}" class="px-5 py-2 bg-smaba-dark-blue text-white rounded-lg hover:bg-smaba-light-blue font-semibold text-sm shadow-md transition-colors">
                        <i class="fas fa-plus mr-2"></i> Tambah Item
                    </a>
                </div>
            @endcan
        </div>
    </x-slot>
    
    {{-- Komponen Livewire dipanggil di sini --}}
    @livewire('item-index')
</x-app-layout>
