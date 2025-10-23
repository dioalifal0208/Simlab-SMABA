<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                    {{ __('Formulir Pengajuan Peminjaman') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Isi detail dan pilih item yang ingin Anda pinjam.</p>
            </div>
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
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('loans.store') }}" method="POST">
                        @csrf
                        <div class="space-y-6">

                            {{-- Tanggal Pinjam & Kembali --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="tanggal_pinjam" class="block font-medium text-sm text-gray-700">Tanggal Rencana Peminjaman</label>
                                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" value="{{ old('tanggal_pinjam') }}" required>
                                </div>
                                <div>
                                    <label for="tanggal_estimasi_kembali" class="block font-medium text-sm text-gray-700">Estimasi Tanggal Kembali</label>
                                    <input type="date" name="tanggal_estimasi_kembali" id="tanggal_estimasi_kembali" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" value="{{ old('tanggal_estimasi_kembali') }}" required>
                                </div>
                            </div>

                            {{-- Daftar Item dengan Live Search --}}
                            <div>
                                <label class="block font-medium text-sm text-gray-700 mb-2">Pilih Alat / Bahan</label>
                                <div class="border rounded-lg p-4 bg-gray-50">
                                    {{-- Input untuk Live Search --}}
                                    <div class="relative mb-4">
                                        <input type="text" id="item-search" placeholder="Ketik untuk mencari item..." class="w-full pl-10 pr-4 py-2 rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue text-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>

                                    {{-- Daftar Item --}}
                                    <div class="space-y-3 border-t pt-4 max-h-72 overflow-y-auto" id="item-list">
                                        @forelse ($items as $item)
                                            <div class="p-2 rounded-md hover:bg-gray-100 item-entry">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <input type="checkbox" name="items[]" value="{{ $item->id }}" id="item_{{ $item->id }}" 
                                                        class="h-4 w-4 rounded border-gray-300 text-smaba-dark-blue focus:ring-smaba-light-blue"
                                                        {{-- PERBAIKAN DI SINI: Pastikan variabel $selectedItemIds sudah di-define --}}
                                                        {{ isset($selectedItemIds) && in_array($item->id, $selectedItemIds) ? 'checked' : '' }}>
                                                        <div class="ms-3">
                                                            <label for="item_{{ $item->id }}" class="block text-sm font-semibold text-gray-800 item-name">{{ $item->nama_alat }}</label>
                                                            <span class="text-xs text-gray-500">Stok Tersedia: {{ $item->jumlah }} {{ $item->satuan }}</span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label for="jumlah_{{ $item->id }}" class="sr-only">Jumlah</label>
                                                        <input type="number" name="jumlah[{{ $item->id }}]" id="jumlah_{{ $item->id }}" min="1" max="{{ $item->jumlah }}" placeholder="Jml" class="w-20 text-sm rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-center text-gray-500 py-4">Tidak ada alat yang tersedia untuk dipinjam.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            {{-- Catatan --}}
                            <div>
                                <label for="catatan" class="block font-medium text-sm text-gray-700">Catatan (Opsional)</label>
                                <textarea name="catatan" id="catatan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" placeholder="Contoh: Untuk praktikum kelas XI IPA 1">{{ old('catatan') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <button type="button" onclick="window.history.back();" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 font-semibold text-sm transition-colors">Batal</button>
                            <button type="submit" class="px-6 py-2 bg-smaba-dark-blue text-white rounded-md hover:bg-smaba-light-blue font-semibold text-sm shadow-md transition-colors">Ajukan Peminjaman</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // --- Script Live Search (dari sebelumnya) ---
                const searchInput = document.getElementById('item-search');
                const itemList = document.getElementById('item-list');
                const items = itemList.querySelectorAll('.item-entry');

                searchInput.addEventListener('keyup', function(e) {
                    const searchTerm = e.target.value.toLowerCase();

                    items.forEach(item => {
                        const itemName = item.querySelector('.item-name').textContent.toLowerCase();
                        if (itemName.includes(searchTerm)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
                
                // --- PENAMBAHAN: Kustomisasi Pesan Error Validasi Stok ---
                
                // 1. Ambil semua input jumlah di dalam daftar item
                const itemInputs = document.querySelectorAll('#item-list input[type="number"]');

                itemInputs.forEach(input => {
                    
                    // 2. Saat browser mendeteksi input tidak valid (misal, melebihi max)
                    input.addEventListener('invalid', function(event) {
                        // Cek apakah errornya karena angka terlalu besar
                        if (event.target.validity.rangeOverflow) {
                            // Atur pesan error kustom dalam Bahasa Indonesia
                            const max = event.target.max;
                            event.target.setCustomValidity('Jumlah tidak boleh lebih dari stok yang tersedia (Maks: ' + max + ').');
                        }
                    });

                    // 3. Saat pengguna mulai mengetik lagi, hapus pesan error kustom
                    //    (Ini penting agar form bisa disubmit saat sudah benar)
                    input.addEventListener('input', function(event) {
                        event.target.setCustomValidity('');
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>