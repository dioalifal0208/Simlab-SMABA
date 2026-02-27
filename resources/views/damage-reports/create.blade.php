{{-- resources/views/damage-reports/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                {{ __('Laporkan Kerusakan Alat') }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">Anda melaporkan kerusakan untuk: <span class="font-semibold">{{ $item->nama_alat }}</span></p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-once="true">
                <div class="p-6 md:p-8 text-gray-900">
                    <form action="{{ route('damage-reports.store', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-6">
                            {{-- Deskripsi Kerusakan --}}
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Kerusakan</label>
                                <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600" required>{{ old('description') }}</textarea>
                                <p class="mt-2 text-xs text-gray-500">Jelaskan sedetail mungkin kerusakan yang Anda temukan.</p>
                                @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Unggah Foto --}}
                            <div>
                                <label for="photo" class="block text-sm font-medium text-gray-700">Foto Kerusakan (Opsional)</label>
                                <input id="photo" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200" type="file" name="photo">
                                @error('photo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-semibold text-sm transition-colors">Batal</a>
                            <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold text-sm shadow-sm transition-colors">Kirim Laporan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
