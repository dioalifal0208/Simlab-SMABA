<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('Formulir Pengajuan Booking Lab') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Silakan isi formulir di bawah ini untuk mengajukan peminjaman laboratorium.</p>
            </div>
            <a href="{{ route('bookings.index') }}" class="mt-3 sm:mt-0 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold text-sm shadow-sm transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden border border-gray-100 shadow-sm sm:rounded-xl" data-aos="fade-up" data-aos-once="true">
                <div class="p-6 md:p-8">
                    
                    {{-- Alert Error --}}
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

                    <form action="{{ route('bookings.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Nama Guru Pengampu --}}
                            <div class="md:col-span-2">
                                <label for="guru_pengampu" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nama Guru Pengampu</label>
                                <input id="guru_pengampu" type="text" name="guru_pengampu" value="{{ old('guru_pengampu', Auth::user()->name) }}" required 
                                    class="w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-gray-300"
                                    placeholder="Contoh: Budi Santoso, S.Pd.">
                            </div>

                            {{-- Laboratorium --}}
                            <div>
                                <label for="laboratorium" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Laboratorium</label>
                                <select id="laboratorium" name="laboratorium" required 
                                    class="w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 {{ Auth::user()->role === 'guru' ? 'bg-gray-50' : '' }}"
                                    {{ Auth::user()->role === 'guru' ? 'readonly' : '' }}>
                                    <option value="">-- Pilih Lab --</option>
                                    <option value="Biologi" @selected(old('laboratorium', $selectedLaboratorium) == 'Biologi')>Biologi</option>
                                    <option value="Fisika" @selected(old('laboratorium', $selectedLaboratorium) == 'Fisika')>Fisika</option>
                                    <option value="Bahasa" @selected(old('laboratorium', $selectedLaboratorium) == 'Bahasa')>Bahasa</option>
                                </select>
                                @if(Auth::user()->role === 'guru')
                                    <p class="mt-1 text-xs text-gray-500">* Terkunci sesuai penugasan Anda.</p>
                                @endif
                            </div>

                            {{-- Jumlah Peserta --}}
                            <div>
                                <label for="jumlah_peserta" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Jumlah Peserta <span class="text-[10px] italic text-gray-300">(Opsional)</span></label>
                                <input id="jumlah_peserta" type="number" name="jumlah_peserta" value="{{ old('jumlah_peserta') }}" 
                                    class="w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-gray-300"
                                    placeholder="Contoh: 30">
                            </div>

                            {{-- NIP/Nomor Induk --}}
                            <div>
                                <label for="nomor_induk" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">NIP/Nomor Induk</label>
                                <input id="nomor_induk" type="text" name="nomor_induk" value="{{ old('nomor_induk', Auth::user()->nomor_induk) }}" 
                                    class="w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-gray-300"
                                    placeholder="Nomor Induk Pegawai">
                            </div>

                            {{-- Kelas / Jabatan --}}
                            <div>
                                <label for="kelas" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Kelas / Jabatan</label>
                                <input id="kelas" type="text" name="kelas" value="{{ old('kelas', Auth::user()->kelas) }}" 
                                    class="w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-gray-300"
                                    placeholder="Contoh: XI IPA 1 / Guru Mapel">
                            </div>

                            {{-- No. HP / WA --}}
                            <div class="md:col-span-2">
                                <label for="phone_number" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">No. HP / WA</label>
                                <input id="phone_number" type="text" name="phone_number" value="{{ old('phone_number', Auth::user()->phone_number) }}" 
                                    class="w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-gray-300"
                                    placeholder="0812xxxxxxx">
                            </div>

                            {{-- Mata Pelajaran --}}
                            <div class="md:col-span-2">
                                <label for="mata_pelajaran" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Mata Pelajaran <span class="text-[10px] italic text-gray-300">(Opsional)</span></label>
                                <input id="mata_pelajaran" type="text" name="mata_pelajaran" value="{{ old('mata_pelajaran') }}" 
                                    class="w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-gray-300"
                                    placeholder="Contoh: Biologi Reproduksi">
                            </div>

                            {{-- Tujuan Kegiatan --}}
                            <div class="md:col-span-2">
                                <label for="tujuan_kegiatan" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tujuan Kegiatan / Judul Praktikum</label>
                                <textarea id="tujuan_kegiatan" name="tujuan_kegiatan" rows="3" required 
                                    class="w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-gray-300"
                                    placeholder="Jelaskan secara ringkas kegiatan yang akan dilakukan...">{{ old('tujuan_kegiatan') }}</textarea>
                            </div>

                            {{-- Waktu Mulai --}}
                            <div>
                                <label for="waktu_mulai" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Waktu Mulai</label>
                                <input id="waktu_mulai" type="datetime-local" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required 
                                    class="w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            {{-- Waktu Selesai --}}
                            <div>
                                <label for="waktu_selesai" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Waktu Selesai</label>
                                <input id="waktu_selesai" type="datetime-local" name="waktu_selesai" value="{{ old('waktu_selesai') }}" required 
                                    class="w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="pt-6 flex items-center justify-end space-x-3 border-t border-gray-100">
                            <a href="{{ route('bookings.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold text-sm transition-colors">
                                Batal
                            </a>
                            <button type="submit" class="px-8 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold text-sm shadow-md transition-all hover:shadow-lg transform active:scale-95">
                                Kirim Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

