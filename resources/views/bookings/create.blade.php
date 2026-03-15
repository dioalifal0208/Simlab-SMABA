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

                    <form action="{{ route('bookings.store') }}" method="POST" class="space-y-6" id="bookingForm" x-data="{ showSopModal: false, hasReadSop: false }">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Nama Guru Pengampu --}}
                            <div class="md:col-span-2">
                                <label for="guru_pengampu" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nama Guru Pengampu</label>
                                <input id="guru_pengampu" type="text" name="guru_pengampu" value="{{ old('guru_pengampu', Auth::user()->name) }}" required 
                                    class="w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 placeholder-gray-300"
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
                                    <option value="Komputer 1" @selected(old('laboratorium', $selectedLaboratorium) == 'Komputer 1')>Komputer 1</option>
                                    <option value="Komputer 2" @selected(old('laboratorium', $selectedLaboratorium) == 'Komputer 2')>Komputer 2</option>
                                    <option value="Komputer 3" @selected(old('laboratorium', $selectedLaboratorium) == 'Komputer 3')>Komputer 3</option>
                                    <option value="Komputer 4" @selected(old('laboratorium', $selectedLaboratorium) == 'Komputer 4')>Komputer 4</option>
                                </select>
                                @if(Auth::user()->role === 'guru')
                                    <p class="mt-1 text-xs text-gray-500">* Terkunci sesuai penugasan Anda.</p>
                                @endif
                            </div>

                            {{-- Jumlah Peserta --}}
                            <div>
                                <label for="jumlah_peserta" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Jumlah Peserta <span class="text-[10px] italic text-gray-300">(Opsional)</span></label>
                                <input id="jumlah_peserta" type="number" name="jumlah_peserta" value="{{ old('jumlah_peserta') }}" 
                                    class="w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 placeholder-gray-300"
                                    placeholder="Contoh: 30">
                            </div>

                            {{-- NIP/Nomor Induk --}}
                            <div>
                                <label for="nomor_induk" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">NIP/Nomor Induk</label>
                                <input id="nomor_induk" type="text" name="nomor_induk" value="{{ old('nomor_induk', Auth::user()->nomor_induk) }}" 
                                    class="w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 placeholder-gray-300"
                                    placeholder="Nomor Induk Pegawai">
                            </div>

                            {{-- Kelas / Jabatan --}}
                            <div>
                                <label for="kelas" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Kelas / Jabatan</label>
                                <input id="kelas" type="text" name="kelas" value="{{ old('kelas', Auth::user()->kelas) }}" 
                                    class="w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 placeholder-gray-300"
                                    placeholder="Contoh: XI IPA 1 / Guru Mapel">
                            </div>

                            {{-- No. HP / WA --}}
                            <div class="md:col-span-2">
                                <label for="phone_number" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">No. HP / WA</label>
                                <input id="phone_number" type="text" name="phone_number" value="{{ old('phone_number', Auth::user()->phone_number) }}" 
                                    class="w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 placeholder-gray-300"
                                    placeholder="0812xxxxxxx">
                            </div>

                            {{-- Mata Pelajaran --}}
                            <div class="md:col-span-2">
                                <label for="mata_pelajaran" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Mata Pelajaran <span class="text-[10px] italic text-gray-300">(Opsional)</span></label>
                                <input id="mata_pelajaran" type="text" name="mata_pelajaran" value="{{ old('mata_pelajaran') }}" 
                                    class="w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 placeholder-gray-300"
                                    placeholder="Contoh: Biologi Reproduksi">
                            </div>

                            {{-- Tujuan Kegiatan --}}
                            <div class="md:col-span-2">
                                <label for="tujuan_kegiatan" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tujuan Kegiatan / Judul Praktikum</label>
                                <textarea id="tujuan_kegiatan" name="tujuan_kegiatan" rows="3" required 
                                    class="w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 placeholder-gray-300"
                                    placeholder="Jelaskan secara ringkas kegiatan yang akan dilakukan...">{{ old('tujuan_kegiatan') }}</textarea>
                            </div>

                            {{-- Waktu Mulai --}}
                            <div>
                                <label for="waktu_mulai" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Waktu Mulai</label>
                                <input id="waktu_mulai" type="datetime-local" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required 
                                    class="w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
                            </div>

                            {{-- Waktu Selesai --}}
                            <div>
                                <label for="waktu_selesai" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Waktu Selesai</label>
                                <input id="waktu_selesai" type="datetime-local" name="waktu_selesai" value="{{ old('waktu_selesai') }}" required 
                                    class="w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
                            </div>
                        </div>

                        <div class="pt-6 flex items-center justify-end space-x-3 border-t border-gray-100">
                            <a href="{{ route('bookings.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold text-sm transition-colors">
                                Batal
                            </a>
                            <button type="button" @click="
                                if(document.getElementById('bookingForm').checkValidity()) {
                                    showSopModal = true;
                                    hasReadSop = false;
                                } else {
                                    document.getElementById('bookingForm').reportValidity();
                                }
                            " class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold text-sm shadow-sm transition-colors">
                                Kirim Pengajuan
                            </button>
                        </div>

                        {{-- SOP Modal --}}
                        <div x-cloak x-show="showSopModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                            <div x-show="showSopModal" 
                                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                    <div x-show="showSopModal" @click.away="showSopModal = false"
                                        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                        class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                                        
                                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                            <div class="sm:flex sm:items-start">
                                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                                    <i class="fas fa-file-contract text-blue-600"></i>
                                                </div>
                                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                                    <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Standar Operasional Prosedur (SOP) Laboratorium</h3>
                                                    <div class="mt-2 text-sm text-gray-500 mb-4">
                                                        <p>Silakan gulir (scroll) dan baca seluruh syarat dan ketentuan di bawah ini untuk dapat menyetujui jadwal lab.</p>
                                                    </div>
                                                    
                                                    {{-- Scrollable Content Box --}}
                                                    <div class="mt-4 bg-gray-50 border border-gray-200 rounded-md p-4 h-64 overflow-y-auto w-full text-sm text-gray-700 space-y-3"
                                                         @scroll="
                                                            const bottom = $event.target.scrollHeight - $event.target.scrollTop <= $event.target.clientHeight + 2;
                                                            if(bottom) hasReadSop = true;
                                                         ">
                                                        <h4 class="font-bold text-gray-900">1. Ketentuan Umum</h4>
                                                        <p>1.1. Peminjam bertanggung jawab penuh atas segala alat, bahan, dan ruangan laboratorium selama masa peminjaman.</p>
                                                        <p>1.2. Peminjaman laboratorium harus diajukan minimal 1 hari sebelum kegiatan (H-1).</p>
                                                        <p>1.3. Peminjam wajib memastikan ruangan dalam keadaan bersih dan rapi setelah kegiatan selesai dilaksanakan.</p>
                                                        
                                                        <h4 class="font-bold text-gray-900 mt-4">2. Kesehatan dan Keselamatan Kerja (K3)</h4>
                                                        <p>2.1. Pengguna laboratorium diwajibkan mematuhi standar K3 yang berlaku di masing-masing ruangan yang dipinjam.</p>
                                                        <p>2.2. Segala bentuk kecelakaan kerja di dalam laboratorium harus segera dilaporkan kepada Kepala Laboratorium / Petugas yang berwenang.</p>

                                                        <h4 class="font-bold text-gray-900 mt-4">3. Persetujuan</h4>
                                                        <p>Dengan mengajukan permohonan ini, Anda secara sadar telah membaca, memahami, dan menyetujui segala aturan yang berlaku di Laboratorium SMABA.</p>
                                                        <p class="text-xs text-gray-400 mt-6">(Scroll hingga baris ini untuk menyetujui)</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                            <button type="submit" 
                                                form="bookingForm"
                                                :class="hasReadSop ? 'bg-green-600 hover:bg-green-500' : 'bg-gray-300 cursor-not-allowed'"
                                                :disabled="!hasReadSop"
                                                class="inline-flex w-full justify-center rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto transition-colors">
                                                Saya Setuju & Simpan Jadwal
                                            </button>
                                            <button type="button" @click="showSopModal = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Blokir Sabtu, Minggu, dan Hari Libur pada Pemilihan Tanggal ---
            const dateInputs = document.querySelectorAll('input[type="datetime-local"]');
            
            // Daftar hari libur nasional Indonesia tahun 2026 (YYYY-MM-DD)
            const holidays = [
                '2026-01-01', '2026-02-17', '2026-03-20', '2026-03-22', '2026-03-23',
                '2026-04-03', '2026-05-01', '2026-05-14', '2026-05-29', '2026-06-01',
                '2026-06-18', '2026-08-17', '2026-08-27', '2026-12-25'
            ];

            dateInputs.forEach(input => {
                input.addEventListener('change', function(e) {
                    if (!this.value) return;
                    
                    const date = new Date(this.value);
                    
                    // 1. Cek Akhir Pekan (Sabtu/Minggu)
                    const day = date.getDay(); // 0 = Sunday, 6 = Saturday
                    if (day === 0 || day === 6) {
                        alert('Laboratorium tidak dapat dibooking pada hari Sabtu atau Minggu. Silakan pilih hari kerja (Senin-Jumat).');
                        this.value = ''; // Reset the input
                        return;
                    }

                    // 2. Cek Hari Libur Nasional
                    // Format ke YYYY-MM-DD untuk pencocokan
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const dateNum = String(date.getDate()).padStart(2, '0');
                    const dateString = `${year}-${month}-${dateNum}`;
                    
                    if (holidays.includes(dateString)) {
                        alert(`Laboratorium tidak dapat dibooking pada hari libur nasional (${dateString}). Silakan pilih tanggal lain.`);
                        this.value = ''; // Reset the input
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>

