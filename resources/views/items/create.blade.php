<x-app-layout>
    <x-slot name="header">
        {{-- Header Halaman Didesain Ulang --}}
        <div>
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                {{ __('items.add_item') }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('items.create_subtitle') }}</p>
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

                    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Nama Alat --}}
                            <div class="md:col-span-2">
                                <label for="nama_alat" class="block text-sm font-medium text-gray-700">{{ __('items.form.name_label') }}</label>
                                <input id="nama_alat" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" type="text" name="nama_alat" value="{{ old('nama_alat') }}" required autofocus />
                            </div>

                            {{-- PENAMBAHAN: Field untuk Tipe Item --}}
                            <div class="md:col-span-2">
                                <label for="tipe" class="block text-sm font-medium text-gray-700">{{ __('items.form.type') }}</label>
                                <select name="tipe" id="tipe" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="Alat" @selected(old('tipe', 'Alat') == 'Alat')>{{ __('items.types.alat') }}</option>
                                    <option value="Bahan Habis Pakai" @selected(old('tipe') == 'Bahan Habis Pakai')>{{ __('items.types.bahan') }}</option>
                                </select>
                            </div>

                            {{-- PERUBAHAN: Grup Jumlah, Stok Min, dan Satuan digabung menjadi 3 kolom --}}
                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="jumlah" class="block text-sm font-medium text-gray-700">{{ __('items.form.stock_label') }}</label>
                                    <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah') }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label for="stok_minimum" class="block text-sm font-medium text-gray-700">{{ __('items.form.min_stock') }} <span class="text-xs text-gray-400">{{ __('items.form.optional') }}</span></label>
                                    <input type="number" name="stok_minimum" id="stok_minimum" value="{{ old('stok_minimum') }}" placeholder="{{ __('items.form.min_stock_placeholder') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label for="satuan" class="block text-sm font-medium text-gray-700">{{ __('items.form.unit') }}</label>
                                    <input type="text" name="satuan" id="satuan" value="{{ old('satuan') }}" placeholder="{{ __('items.form.unit_placeholder') }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                            </div>
                            
                            {{-- Kondisi --}}
                            <div>
                                <label for="kondisi" class="block text-sm font-medium text-gray-700">{{ __('items.form.condition') }}</label>
                                <select name="kondisi" id="kondisi" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                                    <option value="baik" @selected(old('kondisi') == 'baik')>{{ __('items.status.good') }}</option>
                                    <option value="kurang baik" @selected(old('kondisi') == 'kurang baik')>{{ __('items.status.fair') }}</option>
                                    <option value="Rusak" @selected(old('kondisi') == 'Rusak')>{{ __('items.status.broken') }}</option>
                                </select>
                            </div>

                            {{-- Lokasi Penyimpanan --}}
                            <div>
                                <label for="lokasi_penyimpanan" class="block text-sm font-medium text-gray-700">{{ __('items.form.location') }}</label>
                                <input id="lokasi_penyimpanan" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" type="text" name="lokasi_penyimpanan" value="{{ old('lokasi_penyimpanan') }}" required />
                            </div>

                            {{-- Kode Inventaris & Tahun Pengadaan --}}
                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="kode_inventaris" class="block text-sm font-medium text-gray-700">{{ __('items.form.kode_inventaris') }}</label>
                                    <input id="kode_inventaris" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" type="text" name="kode_inventaris" value="{{ old('kode_inventaris') }}" required />
                                </div>
                                <div>
                                    <label for="tahun_pengadaan" class="block text-sm font-medium text-gray-700">{{ __('items.form.tahun_pengadaan') }}</label>
                                    <input id="tahun_pengadaan" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" type="number" min="1900" max="{{ date('Y') + 1 }}" name="tahun_pengadaan" value="{{ old('tahun_pengadaan', date('Y')) }}" required />
                                </div>
                            </div>

                            {{-- Laboratorium --}}
                            <div>
                                <label for="laboratorium" class="block text-sm font-medium text-gray-700">{{ __('items.form.lab') }}</label>
                                <select name="laboratorium" id="laboratorium" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                                    <option value="Biologi" @selected(old('laboratorium', 'Biologi') === 'Biologi')>{{ __('items.form.lab_prefix') }} Biologi</option>
                                    <option value="Fisika" @selected(old('laboratorium') === 'Fisika')>{{ __('items.form.lab_prefix') }} Fisika</option>
                                    <option value="Bahasa" @selected(old('laboratorium') === 'Bahasa')>{{ __('items.form.lab_prefix') }} Bahasa</option>
                                    <option value="Komputer 1" @selected(old('laboratorium') === 'Komputer 1')>{{ __('items.form.lab_prefix') }} Komputer 1</option>
                                    <option value="Komputer 2" @selected(old('laboratorium') === 'Komputer 2')>{{ __('items.form.lab_prefix') }} Komputer 2</option>
                                    <option value="Komputer 3" @selected(old('laboratorium') === 'Komputer 3')>{{ __('items.form.lab_prefix') }} Komputer 3</option>
                                    <option value="Komputer 4" @selected(old('laboratorium') === 'Komputer 4')>{{ __('items.form.lab_prefix') }} Komputer 4</option>
                                </select>
                            </div>
                            
                            {{-- Foto Item --}}
                            <div class="md:col-span-2">
                                <label for="photos" class="block text-sm font-medium text-gray-700">{{ __('items.form.photos_label') }}</label>
                                <input id="photos" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200" type="file" name="photos[]" multiple>
                            </div>

                            {{-- Dokumen Pendukung (Manual Book / SOP / MSDS) --}}
                            <div class="md:col-span-2 p-4 bg-gray-50 border border-gray-200 rounded-lg space-y-4" x-data="{ itemTipe: document.getElementById('tipe').value }" x-init="document.getElementById('tipe').addEventListener('change', (e) => { itemTipe = e.target.value })">
                                <h4 class="text-sm font-bold text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-file-pdf text-red-500"></i> {{ __('items.form.doc_section_title') }}
                                </h4>
                                <p class="text-xs text-gray-500" x-show="itemTipe === 'Alat'">{{ __('items.form.doc_hint_alat') }}</p>
                                <p class="text-xs text-gray-500" x-show="itemTipe === 'Bahan Habis Pakai'">{{ __('items.form.doc_hint_bahan') }}</p>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="dokumen_tipe" class="block text-sm font-medium text-gray-700">{{ __('items.form.doc_type_label') }} <span class="text-xs text-gray-400">{{ __('items.form.optional') }}</span></label>
                                        <select name="dokumen_tipe" id="dokumen_tipe" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                            <option value="">-- {{ __('items.form.doc_type_placeholder') }} --</option>
                                            <option value="manual_book" @selected(old('dokumen_tipe') == 'manual_book')>📘 {{ __('items.form.doc_types.manual_book') }}</option>
                                            <option value="sop_ik" @selected(old('dokumen_tipe') == 'sop_ik')>📋 {{ __('items.form.doc_types.sop_ik') }}</option>
                                            <option value="msds" @selected(old('dokumen_tipe') == 'msds')>⚠️ {{ __('items.form.doc_types.msds') }}</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="dokumen" class="block text-sm font-medium text-gray-700">{{ __('items.form.doc_file_label') }} <span class="text-xs text-gray-400">PDF, {{ __('items.form.doc_max_size') }}</span></label>
                                        <input id="dokumen" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100" type="file" name="dokumen" accept=".pdf">
                                    </div>
                                </div>
                            </div>

                            {{-- Deskripsi (Keterangan) --}}
                            <div class="md:col-span-2">
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700">{{ __('items.form.description_optional') }}</label>
                                <textarea name="deskripsi" id="deskripsi" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('deskripsi') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('items.index') }}" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition-colors text-sm shadow-sm">{{ __('common.buttons.cancel') }}</a>
                            <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 font-bold text-sm shadow-sm transition-colors hover:-translate-y-0.5 transform">{{ __('items.actions.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

