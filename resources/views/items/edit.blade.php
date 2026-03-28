<x-app-layout>
    <x-slot name="header">
        {{-- Header Halaman Didesain Ulang --}}
        <div>
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                {{ __('items.edit_item') }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">{!! __('items.edit_subtitle_param', ['name' => '<span class="font-semibold">' . $item->nama_alat . '</span>']) !!}</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Desain Kartu Disesuaikan --}}
            <div class="bg-white overflow-hidden border border-slate-200 shadow-sm sm:rounded-xl" data-aos="fade-up" data-aos-once="true">
                <div class="p-6 md:p-8 text-slate-800">
                    
                    {{-- Pesan Error Validasi (jika ada) --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-white border-l-4 border-l-red-500 p-4 text-sm rounded-lg shadow-sm" role="alert">
                            <p class="font-bold text-red-700">{{ __('common.messages.error_title') }}:</p>
                            <ul class="mt-2 list-disc list-inside text-slate-600">
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
                                <input id="nama_alat" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" type="text" name="nama_alat" value="{{ old('nama_alat', $item->nama_alat) }}" required autofocus />
                            </div>

                            {{-- Field untuk Tipe Item --}}
                            <div class="md:col-span-2">
                                <label for="tipe" class="block text-sm font-medium text-gray-700">{{ __('items.form.type') }}</label>
                                <select name="tipe" id="tipe" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="Alat" @selected(old('tipe', $item->tipe) == 'Alat')>{{ __('items.types.alat') }}</option>
                                    <option value="Bahan Habis Pakai" @selected(old('tipe', $item->tipe) == 'Bahan Habis Pakai')>{{ __('items.types.bahan') }}</option>
                                </select>
                            </div>

                            {{-- Grup Jumlah, Stok Min, dan Satuan --}}
                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="jumlah" class="block text-sm font-medium text-gray-700">{{ __('items.form.stock_label') }}</label>
                                    <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', $item->jumlah) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label for="stok_minimum" class="block text-sm font-medium text-gray-700">{{ __('items.form.min_stock') }} <span class="text-xs text-gray-400">{{ __('items.form.optional') }}</span></label>
                                    <input type="number" name="stok_minimum" id="stok_minimum" value="{{ old('stok_minimum', $item->stok_minimum) }}" placeholder="{{ __('items.form.min_stock_placeholder') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label for="satuan" class="block text-sm font-medium text-gray-700">{{ __('items.form.unit') }}</label>
                                    <input type="text" name="satuan" id="satuan" value="{{ old('satuan', $item->satuan) }}" placeholder="{{ __('items.form.unit_placeholder') }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                            </div>
                            
                            {{-- Kondisi --}}
                            <div>
                                <label for="kondisi" class="block text-sm font-medium text-gray-700">{{ __('items.form.condition') }}</label>
                                <select name="kondisi" id="kondisi" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                                    <option value="baik" @selected(old('kondisi', $item->kondisi) == 'baik')>{{ __('items.status.good') }}</option>
                                    <option value="kurang baik" @selected(old('kondisi', $item->kondisi) == 'kurang baik')>{{ __('items.status.fair') }}</option>
                                    <option value="Rusak" @selected(old('kondisi', $item->kondisi) == 'Rusak')>{{ __('items.status.broken') }}</option>
                                </select>
                            </div>

                            {{-- Lokasi Penyimpanan --}}
                            <div>
                                <label for="lokasi_penyimpanan" class="block text-sm font-medium text-gray-700">{{ __('items.form.location') }}</label>
                                <input id="lokasi_penyimpanan" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" type="text" name="lokasi_penyimpanan" value="{{ old('lokasi_penyimpanan', $item->lokasi_penyimpanan) }}" required />
                            </div>

                            {{-- Laboratorium --}}
                            <div>
                                <label for="laboratorium" class="block text-sm font-medium text-gray-700">{{ __('items.form.lab') }}</label>
                                <select name="laboratorium" id="laboratorium" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                                    <option value="Biologi" @selected(old('laboratorium', $item->laboratorium) === 'Biologi')>{{ __('items.form.lab_prefix') }} Biologi</option>
                                    <option value="Fisika" @selected(old('laboratorium', $item->laboratorium) === 'Fisika')>{{ __('items.form.lab_prefix') }} Fisika</option>
                                    <option value="Bahasa" @selected(old('laboratorium', $item->laboratorium) === 'Bahasa')>{{ __('items.form.lab_prefix') }} Bahasa</option>
                                    <option value="Komputer 1" @selected(old('laboratorium', $item->laboratorium) === 'Komputer 1')>{{ __('items.form.lab_prefix') }} Komputer 1</option>
                                    <option value="Komputer 2" @selected(old('laboratorium', $item->laboratorium) === 'Komputer 2')>{{ __('items.form.lab_prefix') }} Komputer 2</option>
                                    <option value="Komputer 3" @selected(old('laboratorium', $item->laboratorium) === 'Komputer 3')>{{ __('items.form.lab_prefix') }} Komputer 3</option>
                                    <option value="Komputer 4" @selected(old('laboratorium', $item->laboratorium) === 'Komputer 4')>{{ __('items.form.lab_prefix') }} Komputer 4</option>
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

                            {{-- Dokumen Pendukung (Manual Book / SOP / MSDS) --}}
                            <div class="md:col-span-2 p-4 bg-gray-50 border border-gray-200 rounded-lg space-y-4">
                                <h4 class="text-sm font-bold text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-file-pdf text-red-500"></i> {{ __('items.form.doc_section_title') }}
                                </h4>

                                {{-- Menampilkan dokumen yang sudah ada --}}
                                @if($item->dokumen_path)
                                    <div class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-lg">
                                        <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center text-red-600 shrink-0">
                                            <i class="fas fa-file-pdf text-lg"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-800 truncate">
                                                @if($item->dokumen_tipe === 'manual_book') 📘 {{ __('items.form.doc_types.manual_book') }}
                                                @elseif($item->dokumen_tipe === 'sop_ik') 📋 {{ __('items.form.doc_types.sop_ik') }}
                                                @elseif($item->dokumen_tipe === 'msds') ⚠️ {{ __('items.form.doc_types.msds') }}
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-500">{{ __('items.form.doc_current') }}</p>
                                        </div>
                                        <a href="{{ Storage::url($item->dokumen_path) }}" target="_blank" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                                            <i class="fas fa-external-link-alt mr-1"></i> {{ __('items.form.doc_view') }}
                                        </a>
                                    </div>
                                @endif

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="dokumen_tipe" class="block text-sm font-medium text-gray-700">{{ __('items.form.doc_type_label') }} <span class="text-xs text-gray-400">{{ __('items.form.optional') }}</span></label>
                                        <select name="dokumen_tipe" id="dokumen_tipe" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                            <option value="">-- {{ __('items.form.doc_type_placeholder') }} --</option>
                                            <option value="manual_book" @selected(old('dokumen_tipe', $item->dokumen_tipe) == 'manual_book')>📘 {{ __('items.form.doc_types.manual_book') }}</option>
                                            <option value="sop_ik" @selected(old('dokumen_tipe', $item->dokumen_tipe) == 'sop_ik')>📋 {{ __('items.form.doc_types.sop_ik') }}</option>
                                            <option value="msds" @selected(old('dokumen_tipe', $item->dokumen_tipe) == 'msds')>⚠️ {{ __('items.form.doc_types.msds') }}</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="dokumen" class="block text-sm font-medium text-gray-700">{{ $item->dokumen_path ? __('items.form.doc_replace_label') : __('items.form.doc_file_label') }} <span class="text-xs text-gray-400">PDF, {{ __('items.form.doc_max_size') }}</span></label>
                                        <input id="dokumen" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100" type="file" name="dokumen" accept=".pdf">
                                    </div>
                                </div>
                            </div>

                            {{-- Deskripsi (Keterangan) --}}
                            <div class="md:col-span-2">
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700">{{ __('items.form.description_optional') }}</label>
                                <textarea name="deskripsi" id="deskripsi" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('deskripsi', $item->deskripsi) }}</textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('items.index') }}" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition-colors text-sm shadow-sm">{{ __('common.buttons.cancel') }}</a>
                            <button type="submit" class="ms-4 px-6 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 font-bold text-sm shadow-sm transition-colors hover:-translate-y-0.5 transform">{{ __('common.buttons.update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

