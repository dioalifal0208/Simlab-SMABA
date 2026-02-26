<x-app-layout>
    <x-slot name="header">
        {{-- Header Halaman Didesain Ulang --}}
        <div>
            <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                {{ __('items.edit_item') }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">{!! __('items.edit_subtitle_param', ['name' => '<span class="font-semibold">' . $item->nama_alat . '</span>']) !!}</p>
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
                            <p class="font-bold">{{ __('common.messages.error_title') }}:</p>
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
                                <label for="nama_alat" class="block text-sm font-medium text-gray-700">{{ __('items.form.name_label') }}</label>
                                <input id="nama_alat" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" type="text" name="nama_alat" value="{{ old('nama_alat', $item->nama_alat) }}" required autofocus />
                            </div>

                            {{-- Field untuk Tipe Item --}}
                            <div class="md:col-span-2">
                                <label for="tipe" class="block text-sm font-medium text-gray-700">{{ __('items.form.type') }}</label>
                                <select name="tipe" id="tipe" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                    <option value="Alat" @selected(old('tipe', $item->tipe) == 'Alat')>{{ __('items.types.alat') }}</option>
                                    <option value="Bahan Habis Pakai" @selected(old('tipe', $item->tipe) == 'Bahan Habis Pakai')>{{ __('items.types.bahan') }}</option>
                                </select>
                            </div>

                            {{-- Grup Jumlah, Stok Min, dan Satuan --}}
                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="jumlah" class="block text-sm font-medium text-gray-700">{{ __('items.form.stock_label') }}</label>
                                    <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', $item->jumlah) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                </div>
                                <div>
                                    <label for="stok_minimum" class="block text-sm font-medium text-gray-700">{{ __('items.form.min_stock') }} <span class="text-xs text-gray-400">{{ __('items.form.optional') }}</span></label>
                                    <input type="number" name="stok_minimum" id="stok_minimum" value="{{ old('stok_minimum', $item->stok_minimum) }}" placeholder="{{ __('items.form.min_stock_placeholder') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                </div>
                                <div>
                                    <label for="satuan" class="block text-sm font-medium text-gray-700">{{ __('items.form.unit') }}</label>
                                    <input type="text" name="satuan" id="satuan" value="{{ old('satuan', $item->satuan) }}" placeholder="{{ __('items.form.unit_placeholder') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                </div>
                            </div>
                            
                            {{-- Kondisi --}}
                            <div>
                                <label for="kondisi" class="block text-sm font-medium text-gray-700">{{ __('items.form.condition') }}</label>
                                <select name="kondisi" id="kondisi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" required>
                                    <option value="Baik" @selected(old('kondisi', $item->kondisi) == 'Baik')>{{ __('items.status.good') }}</option>
                                    <option value="Kurang Baik" @selected(old('kondisi', $item->kondisi) == 'Kurang Baik')>{{ __('items.status.fair') }}</option>
                                    <option value="Rusak" @selected(old('kondisi', $item->kondisi) == 'Rusak')>{{ __('items.status.broken') }}</option>
                                </select>
                            </div>

                            {{-- Lokasi Penyimpanan --}}
                            <div>
                                <label for="lokasi_penyimpanan" class="block text-sm font-medium text-gray-700">{{ __('items.form.location') }}</label>
                                <input id="lokasi_penyimpanan" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" type="text" name="lokasi_penyimpanan" value="{{ old('lokasi_penyimpanan', $item->lokasi_penyimpanan) }}" required />
                            </div>

                            {{-- Laboratorium --}}
                            <div>
                                <label for="laboratorium" class="block text-sm font-medium text-gray-700">{{ __('items.form.lab') }}</label>
                                <select name="laboratorium" id="laboratorium" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" required>
                                    <option value="Biologi" @selected(old('laboratorium', $item->laboratorium) === 'Biologi')>{{ __('items.form.lab_prefix') }} Biologi</option>
                                    <option value="Fisika" @selected(old('laboratorium', $item->laboratorium) === 'Fisika')>{{ __('items.form.lab_prefix') }} Fisika</option>
                                    <option value="Bahasa" @selected(old('laboratorium', $item->laboratorium) === 'Bahasa')>{{ __('items.form.lab_prefix') }} Bahasa</option>
                                </select>
                            </div>
                            
                            {{-- Foto Item --}}
                            <div class="md:col-span-2">
                                <label for="photos" class="block text-sm font-medium text-gray-700">{{ __('items.form.add_photo_label') }}</label>
                                <input id="photos" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200" type="file" name="photos[]" multiple>
                                {{-- Menampilkan gambar yang sudah ada --}}
                                @if ($item->images->isNotEmpty())
                                    <div class="mt-4">
                                        <p class="text-sm text-gray-600 mb-2">{{ __('items.form.current_gallery') }}</p>
                                        <div class="flex flex-wrap gap-2">@foreach($item->images as $image)<img src="{{ Storage::url($image->path) }}" alt="{{ $item->nama_alat }}" class="h-20 w-20 object-cover rounded-md shadow-md">@endforeach</div>
                                    </div>
                                @endif
                            </div>

                            {{-- Deskripsi (Keterangan) --}}
                            <div class="md:col-span-2">
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700">{{ __('items.form.description_optional') }}</label>
                                <textarea name="deskripsi" id="deskripsi" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">{{ old('deskripsi', $item->deskripsi) }}</textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('items.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 font-semibold text-sm transition-colors">{{ __('common.buttons.cancel') }}</a>
                            <button type="submit" class="ms-4 px-6 py-2 bg-smaba-dark-blue text-white rounded-md hover:bg-smaba-light-blue font-semibold text-sm shadow-md transition-colors">{{ __('common.buttons.update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
