<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                    {{ __('Formulir Pengajuan Booking Lab') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Isi detail jadwal untuk mengajukan penggunaan laboratorium.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden border border-gray-100 shadow-sm sm:rounded-xl" data-aos="fade-up" data-aos-once="true">
                <div class="p-6 md:p-8 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-400 text-red-700 p-4 text-sm rounded-lg" role="alert">
                            <p class="font-bold">Oops! Terjadi kesalahan.</p>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('bookings.store') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            
                            <div>
                                <label for="guru_pengampu" class="block font-medium text-sm text-gray-700">Nama Guru Pengampu</label>
                                <input type="text" name="guru_pengampu" id="guru_pengampu" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" value="{{ old('guru_pengampu') }}" required>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="laboratorium" class="block font-medium text-sm text-gray-700">Pilih Laboratorium</label>
                                    <select name="laboratorium" id="laboratorium" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" required {{ auth()->user()->role === 'admin' ? '' : 'disabled' }}>
                                        <option value="">-- Pilih Lab --</option>
                                        <option value="Biologi" @selected(old('laboratorium', $selectedLaboratorium ?? '') === 'Biologi')>Lab Biologi</option>
                                        <option value="Fisika" @selected(old('laboratorium', $selectedLaboratorium ?? '') === 'Fisika')>Lab Fisika</option>
                                        <option value="Bahasa" @selected(old('laboratorium', $selectedLaboratorium ?? '') === 'Bahasa')>Lab Bahasa</option>
                                    </select>
                                    @if(auth()->user()->role !== 'admin')
                                        <input type="hidden" name="laboratorium" value="{{ old('laboratorium', $selectedLaboratorium) }}">
                                        <p class="text-xs text-gray-500 mt-1">Lab dikunci sesuai penugasan Anda.</p>
                                    @endif
                                </div>
                                <div>
                                    <label for="jumlah_peserta" class="block font-medium text-sm text-gray-700">Jumlah Peserta <span class="text-xs text-gray-400">(Opsional)</span></label>
                                    <input type="number" name="jumlah_peserta" id="jumlah_peserta" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" value="{{ old('jumlah_peserta') }}">
                                </div>
                            </div>

                            <div>
                                <label for="mata_pelajaran" class="block font-medium text-sm text-gray-700">Mata Pelajaran <span class="text-xs text-gray-400">(Opsional)</span></label>
                                <input type="text" name="mata_pelajaran" id="mata_pelajaran" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" value="{{ old('mata_pelajaran') }}" placeholder="Contoh: Biologi, Fisika Dasar">
                            </div>

                            <div>
                                <label for="tujuan_kegiatan" class="block font-medium text-sm text-gray-700">Tujuan Kegiatan / Judul Praktikum</label>
                                <textarea name="tujuan_kegiatan" id="tujuan_kegiatan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" required>{{ old('tujuan_kegiatan') }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="waktu_mulai" class="block font-medium text-sm text-gray-700">Waktu Mulai</label>
                                    <input type="datetime-local" name="waktu_mulai" id="waktu_mulai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" value="{{ old('waktu_mulai') }}" required>
                                </div>
                                <div>
                                    <label for="waktu_selesai" class="block font-medium text-sm text-gray-700">Waktu Selesai</label>
                                    <input type="datetime-local" name="waktu_selesai" id="waktu_selesai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" value="{{ old('waktu_selesai') }}" required>
                                </div>
                            </div>

                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 font-semibold text-sm transition-colors">Batal</a>
                            <button type="submit" class="px-6 py-2 bg-smaba-dark-blue text-white rounded-md hover:bg-smaba-light-blue font-semibold text-sm shadow-md transition-colors">Ajukan Booking</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
