<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                {{ __('Buat Modul Praktikum / SOP Baru') }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">Isi detail modul dan pilih alat/bahan yang dibutuhkan.</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-once="true">
                <div class="p-6 md:p-8 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-400 text-red-700 p-4 text-sm rounded-lg" role="alert">
                             <p class="font-bold">Oops! Ada yang salah:</p>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('practicum-modules.store') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            {{-- Judul Modul --}}
                            <div>
                                <label for="title" class="block font-medium text-sm text-gray-700">Judul Modul / SOP</label>
                                <input type="text" name="title" id="title" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600" value="{{ old('title') }}" required autofocus>
                            </div>

                            {{-- Deskripsi / Langkah-langkah --}}
                            <div>
                                <label for="description" class="block font-medium text-sm text-gray-700">Deskripsi / Langkah-langkah (Opsional)</label>
                                <textarea name="description" id="description" rows="5" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600" placeholder="Tuliskan langkah-langkah praktikum atau SOP di sini...">{{ old('description') }}</textarea>
                            </div>

                            {{-- Pemilihan Item --}}
                            <div>
                                <label class="block font-medium text-sm text-gray-700 mb-2">Pilih Alat / Bahan yang Dibutuhkan (Opsional)</label>
                                <div class="border rounded-lg p-4 bg-gray-50 max-h-72 overflow-y-auto">
                                    <input type="text" id="item-search-module" placeholder="Ketik untuk mencari item..." class="w-full mb-4 pl-4 pr-4 py-2 rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 text-sm">
                                    <div class="space-y-2" id="item-list-module">
                                        @forelse ($items as $item)
                                            <div class="flex items-center item-entry-module">
                                                <input type="checkbox" name="items[]" value="{{ $item->id }}" id="module_item_{{ $item->id }}" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ in_array($item->id, old('items', [])) ? 'checked' : '' }}>
                                                <label for="module_item_{{ $item->id }}" class="ms-3 block text-sm text-gray-800 item-name-module">{{ $item->nama_alat }} <span class="text-xs text-gray-500">({{ $item->tipe }})</span></label>
                                            </div>
                                        @empty
                                            <p class="text-gray-500 text-sm">Tidak ada item inventaris yang tersedia.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('practicum-modules.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-semibold text-sm transition-colors">Batal</a>
                            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold text-sm shadow-sm transition-colors">Simpan Modul</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Script untuk Live Search Item --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('item-search-module');
                const itemList = document.getElementById('item-list-module');
                const items = itemList.querySelectorAll('.item-entry-module');

                searchInput.addEventListener('keyup', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    items.forEach(item => {
                        const itemName = item.querySelector('.item-name-module').textContent.toLowerCase();
                        if (itemName.includes(searchTerm)) {
                            item.style.display = 'flex';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
