<x-app-layout>
    <x-slot name="header">
        {{-- Header Halaman Didesain Ulang --}}
        <div>
            <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                {{ __('Edit Item Inventaris') }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">Perbarui detail untuk alat atau bahan: <span class="font-semibold">{{ $item->nama_alat }}</span></p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Desain Kartu Disesuaikan --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-once="true">
                <div class="p-6 md:p-8 text-gray-900">
                    
                    {{-- Pesan Error Validasi (jika ada) --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border-l-4 border-red-400 text-red-700 p-4 text-sm rounded-lg" role="alert">
                            <p class="font-bold">Oops! Ada yang salah:</p>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- Method untuk update --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Nama Alat --}}
                            <div class="md:col-span-2">
                                <label for="nama_alat" class="block text-sm font-medium text-gray-700">Nama Alat / Bahan</label>
                                <input id="nama_alat" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" type="text" name="nama_alat" value="{{ old('nama_alat', $item->nama_alat) }}" required autofocus />
                            </div>

                            {{-- Field untuk Tipe Item --}}
                            <div class="md:col-span-2">
                                <label for="tipe" class="block text-sm font-medium text-gray-700">Tipe Item</label>
                                <select name="tipe" id="tipe" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                    <option value="Alat" @selected(old('tipe', $item->tipe) == 'Alat')>Alat (Tidak Habis Pakai)</option>
                                    <option value="Bahan Habis Pakai" @selected(old('tipe', $item->tipe) == 'Bahan Habis Pakai')>Bahan Habis Pakai (Consumable)</option>
                                </select>
                            </div>

                            {{-- Grup Jumlah, Stok Min, dan Satuan --}}
                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah/Stok</label>
                                    <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', $item->jumlah) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                </div>
                                <div>
                                    <label for="stok_minimum" class="block text-sm font-medium text-gray-700">Stok Minimum <span class="text-xs text-gray-400">(Opsional)</span></label>
                                    <input type="number" name="stok_minimum" id="stok_minimum" value="{{ old('stok_minimum', $item->stok_minimum) }}" placeholder="Contoh: 10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                </div>
                                <div>
                                    <label for="satuan" class="block text-sm font-medium text-gray-700">Satuan</label>
                                    <input type="text" name="satuan" id="satuan" value="{{ old('satuan', $item->satuan) }}" placeholder="Contoh: Pcs, Gram, Liter" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                </div>
                            </div>
                            
                            {{-- Kondisi --}}
                            <div>
                                <label for="kondisi" class="block text-sm font-medium text-gray-700">Kondisi</label>
                                <select name="kondisi" id="kondisi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" required>
                                    <option value="Baik" @selected(old('kondisi', $item->kondisi) == 'Baik')>Baik</option>
                                    <option value="Kurang Baik" @selected(old('kondisi', $item->kondisi) == 'Kurang Baik')>Kurang Baik</option>
                                    <option value="Rusak" @selected(old('kondisi', $item->kondisi) == 'Rusak')>Rusak</option>
                                </select>
                            </div>

                            {{-- Lokasi Penyimpanan --}}
                            <div>
                                <label for="lokasi_penyimpanan" class="block text-sm font-medium text-gray-700">Lokasi Penyimpanan</label>
                                <input id="lokasi_penyimpanan" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" type="text" name="lokasi_penyimpanan" value="{{ old('lokasi_penyimpanan', $item->lokasi_penyimpanan) }}" required />
                            </div>
                            
                            {{-- Foto Item --}}
                            <div class="md:col-span-2">
                                <label for="photo" class="block text-sm font-medium text-gray-700">Ganti Foto Item (Opsional)</label>
                                <input id="photo" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200" type="file" name="photo">
                                @if ($item->photo)
                                    <div class="mt-4">
                                        <p class="text-sm text-gray-600 mb-2">Foto Saat Ini:</p>
                                        <img src="{{ Storage::url($item->photo) }}" alt="{{ $item->nama_alat }}" class="h-24 w-24 object-cover rounded-md shadow-md">
                                    </div>
                                @endif
                            </div>

                            {{-- Deskripsi (Keterangan) --}}
                            <div class="md:col-span-2">
                                <label for="keterangan" class="block text-sm font-medium text-gray-700">Deskripsi / Keterangan (Opsional)</label>
                                <textarea name="keterangan" id="keterangan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">{{ old('keterangan', $item->keterangan) }}</textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('items.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 font-semibold text-sm transition-colors">Batal</a>
                            <button type="submit" class="ms-4 px-6 py-2 bg-smaba-dark-blue text-white rounded-md hover:bg-smaba-light-blue font-semibold text-sm shadow-md transition-colors">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>