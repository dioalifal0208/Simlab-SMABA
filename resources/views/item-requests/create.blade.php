<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">Ajukan Penambahan Alat/Bahan</h2>
                <p class="text-sm text-gray-600 mt-1">Kirim permintaan penambahan alat/bahan tanpa perlu akses penuh ke inventaris.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 space-y-6">
                    <div class="bg-smaba-dark-blue/5 border border-smaba-dark-blue/10 text-sm text-gray-700 p-4 rounded-lg">
                        <p class="font-semibold text-smaba-dark-blue">Catatan</p>
                        <p class="mt-1">Permintaan Anda akan ditinjau admin. Setelah disetujui, item dibuat di inventaris oleh admin.</p>
                    </div>

                    <form method="POST" action="{{ route('item-requests.store') }}" class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama alat/bahan</label>
                                <input name="nama_alat" value="{{ old('nama_alat') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" />
                                <x-input-error :messages="$errors->get('nama_alat')" class="mt-2" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis/Tipe (opsional)</label>
                                <input name="tipe" value="{{ old('tipe') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" />
                                <x-input-error :messages="$errors->get('tipe')" class="mt-2" />
                            </div>
                        </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                                    <input type="number" min="1" name="jumlah" value="{{ old('jumlah', 1) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" />
                                    <x-input-error :messages="$errors->get('jumlah')" class="mt-2" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Satuan</label>
                                <input name="satuan" value="{{ old('satuan', 'unit') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" />
                                <x-input-error :messages="$errors->get('satuan')" class="mt-2" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Laboratorium</label>
                                <select name="laboratorium" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" required {{ auth()->user()->role === 'admin' ? '' : 'disabled' }}>
                                    <option value="Biologi" @selected(old('laboratorium', auth()->user()->laboratorium ?? 'Biologi') === 'Biologi')>Biologi</option>
                                    <option value="Fisika" @selected(old('laboratorium', auth()->user()->laboratorium ?? '') === 'Fisika')>Fisika</option>
                                    <option value="Bahasa" @selected(old('laboratorium', auth()->user()->laboratorium ?? '') === 'Bahasa')>Bahasa</option>
                                </select>
                                @if(auth()->user()->role !== 'admin')
                                    <input type="hidden" name="laboratorium" value="{{ old('laboratorium', auth()->user()->laboratorium) }}">
                                    <p class="text-xs text-gray-500 mt-1">Lab dikunci sesuai penugasan Anda.</p>
                                @endif
                                <x-input-error :messages="$errors->get('laboratorium')" class="mt-2" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Urgensi</label>
                                <select name="urgensi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                    <option value="normal" {{ old('urgensi') === 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="mendesak" {{ old('urgensi') === 'mendesak' ? 'selected' : '' }}>Mendesak</option>
                                </select>
                                <x-input-error :messages="$errors->get('urgensi')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Deskripsi kebutuhan</label>
                            <textarea name="deskripsi" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">{{ old('deskripsi') }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alasan urgensi (opsional)</label>
                            <textarea name="alasan_urgent" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">{{ old('alasan_urgent') }}</textarea>
                            <x-input-error :messages="$errors->get('alasan_urgent')" class="mt-2" />
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-5 py-2 bg-smaba-dark-blue text-white text-sm font-semibold rounded-md shadow-sm hover:bg-smaba-light-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-smaba-dark-blue">
                                Kirim Permintaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
