<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
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
                    <form action="{{ route('loans.store') }}" method="POST" id="loanForm" x-data="{ showSopModal: false, hasReadSop: false }">
                        @csrf
                        <div class="space-y-6">
                            {{-- Pilih Laboratorium --}}
                            <div>
                                <label for="laboratorium" class="block font-medium text-sm text-gray-700">Laboratorium</label>
                                <select name="laboratorium" id="laboratorium" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600" required {{ auth()->user()->role === 'admin' ? '' : 'disabled' }}>
                                    <option value="Biologi" @selected(old('laboratorium', $selectedLaboratorium ?? 'Biologi') === 'Biologi')>Lab Biologi</option>
                                    <option value="Fisika" @selected(old('laboratorium', $selectedLaboratorium ?? '') === 'Fisika')>Lab Fisika</option>
                                    <option value="Bahasa" @selected(old('laboratorium', $selectedLaboratorium ?? '') === 'Bahasa')>Lab Bahasa</option>
                                    <option value="Komputer 1" @selected(old('laboratorium', $selectedLaboratorium ?? '') === 'Komputer 1')>Lab Komputer 1</option>
                                    <option value="Komputer 2" @selected(old('laboratorium', $selectedLaboratorium ?? '') === 'Komputer 2')>Lab Komputer 2</option>
                                    <option value="Komputer 3" @selected(old('laboratorium', $selectedLaboratorium ?? '') === 'Komputer 3')>Lab Komputer 3</option>
                                    <option value="Komputer 4" @selected(old('laboratorium', $selectedLaboratorium ?? '') === 'Komputer 4')>Lab Komputer 4</option>
                                </select>
                                @if(auth()->user()->role !== 'admin')
                                    <input type="hidden" name="laboratorium" value="{{ old('laboratorium', $selectedLaboratorium) }}">
                                    <p class="text-xs text-gray-500 mt-1">Lab dikunci sesuai penugasan Anda.</p>
                                @else
                                    <p class="text-xs text-gray-500 mt-1">Item di bawah difilter berdasarkan lab yang dipilih.</p>
                                @endif
                            </div>

                            {{-- Detail Kegiatan & Booking Lab --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 bg-gray-50 border border-gray-200 rounded-xl">
                                <div class="md:col-span-2 mb-1">
                                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b pb-2 border-gray-200"><i class="fas fa-clipboard-list mr-2 text-indigo-500"></i> Detail Penggunaan Lab</h3>
                                </div>
                                
                                {{-- Nama Guru Pengampu --}}
                                <div class="md:col-span-2">
                                    <label for="guru_pengampu" class="block font-medium text-sm text-gray-700">Nama Guru Pengampu</label>
                                    <input id="guru_pengampu" type="text" name="guru_pengampu" value="{{ old('guru_pengampu', Auth::user()->name) }}" required 
                                        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 placeholder-gray-300"
                                        placeholder="Contoh: Budi Santoso, S.Pd.">
                                </div>

                                {{-- Jumlah Peserta --}}
                                <div>
                                    <label for="jumlah_peserta" class="block font-medium text-sm text-gray-700">Jumlah Peserta <span class="text-[10px] italic text-gray-500">(Opsional)</span></label>
                                    <input id="jumlah_peserta" type="number" name="jumlah_peserta" value="{{ old('jumlah_peserta') }}" 
                                        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 placeholder-gray-300"
                                        placeholder="Contoh: 30">
                                </div>

                                {{-- Mata Pelajaran --}}
                                <div>
                                    <label for="mata_pelajaran" class="block font-medium text-sm text-gray-700">Mata Pelajaran <span class="text-[10px] italic text-gray-500">(Opsional)</span></label>
                                    <input id="mata_pelajaran" type="text" name="mata_pelajaran" value="{{ old('mata_pelajaran') }}" 
                                        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 placeholder-gray-300"
                                        placeholder="Contoh: Biologi Reproduksi">
                                </div>

                                {{-- Tujuan Kegiatan --}}
                                <div class="md:col-span-2">
                                    <label for="tujuan_kegiatan" class="block font-medium text-sm text-gray-700">Tujuan Kegiatan / Judul Praktikum</label>
                                    <textarea id="tujuan_kegiatan" name="tujuan_kegiatan" rows="2" required 
                                        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 placeholder-gray-300"
                                        placeholder="Jelaskan secara ringkas kegiatan yang akan dilakukan...">{{ old('tujuan_kegiatan') }}</textarea>
                                </div>

                                {{-- Waktu Mulai --}}
                                <div>
                                    <label for="waktu_mulai" class="block font-medium text-sm text-gray-700">Waktu Mulai</label>
                                    <input id="waktu_mulai" type="datetime-local" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required 
                                        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
                                </div>

                                {{-- Waktu Selesai --}}
                                <div>
                                    <label for="waktu_selesai" class="block font-medium text-sm text-gray-700">Waktu Selesai</label>
                                    <input id="waktu_selesai" type="datetime-local" name="waktu_selesai" value="{{ old('waktu_selesai') }}" required 
                                        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
                                </div>
                            </div>

                            {{-- Daftar Item dengan Live Search --}}
                            <div>
                                <label class="block font-medium text-sm text-gray-700 mb-2">Pilih Alat / Bahan</label>
                                <div class="border rounded-lg p-4 bg-gray-50">
                                    {{-- Input untuk Live Search --}}
                                    <div class="relative mb-4">
                                        <input type="text" id="item-search" placeholder="Ketik untuk mencari item..." class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 text-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>

                                    {{-- Daftar Item --}}
                                    <div class="space-y-3 border-t pt-4 max-h-72 overflow-y-auto" id="item-list">
                                        @forelse ($items as $item)
                                            <div class="p-2 rounded-md hover:bg-gray-100 item-entry" data-lab="{{ $item->laboratorium }}">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <input type="checkbox" name="items[]" value="{{ $item->id }}" id="item_{{ $item->id }}" 
                                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                        {{-- PERBAIKAN DI SINI: Pastikan variabel $selectedItemIds sudah di-define --}}
                                                        {{ isset($selectedItemIds) && in_array($item->id, $selectedItemIds) ? 'checked' : '' }}>
                                                        <div class="ms-3">
                                                            <label for="item_{{ $item->id }}" class="block text-sm font-semibold text-gray-800 item-name">{{ $item->nama_alat }}</label>
                                                            <span class="text-xs text-gray-500">Stok Tersedia: {{ $item->jumlah }} {{ $item->satuan }} • {{ $item->laboratorium }}</span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label for="jumlah_{{ $item->id }}" class="sr-only">Jumlah</label>
                                                        <input type="number" name="jumlah[{{ $item->id }}]" id="jumlah_{{ $item->id }}" min="1" max="{{ $item->jumlah }}" placeholder="Jml" class="w-20 text-sm rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
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
                                <textarea name="catatan" id="catatan" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600" placeholder="Contoh: Untuk praktikum kelas XI IPA 1">{{ old('catatan') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <button type="button" onclick="window.history.back();" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-semibold text-sm transition-colors">Batal</button>
                            <button type="button" @click="
                                // Validasi HTML5 dasar form sebelum nampilin modal
                                if(document.getElementById('loanForm').checkValidity()) {
                                    showSopModal = true;
                                    // Reset status baca saat modal dibuka ulang
                                    hasReadSop = false;
                                } else {
                                    document.getElementById('loanForm').reportValidity();
                                }
                            " class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold text-sm shadow-sm transition-colors">Ajukan Peminjaman</button>
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
                                                        <p>Silakan gulir (scroll) dan baca seluruh syarat dan ketentuan di bawah ini untuk dapat menyetujui dan meminjam laboratorium/alat.</p>
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
                                                        
                                                        <h4 class="font-bold text-gray-900 mt-4">2. Penggunaan Alat & Bahan</h4>
                                                        <p>2.1. Alat-alat yang dipinjam harus dikembalikan dalam kondisi baik dan lengkap, sesuai dengan data pada saat peminjaman.</p>
                                                        <p>2.2. Kerusakan atau kehilangan alat akan menjadi tanggung jawab penuh pihak peminjam, baik untuk memperbaiki maupun mengganti alat tersebut.</p>
                                                        <p>2.3. Penggunaan bahan praktikum harus sesuai dengan porsi yang wajar dan di bawah pengawasan Guru Pengampu.</p>

                                                        <h4 class="font-bold text-gray-900 mt-4">3. Kesehatan dan Keselamatan Kerja (K3)</h4>
                                                        <p>3.1. Pengguna laboratorium diwajibkan mematuhi standar K3 (menggunakan jas lab, tidak makan/minum di dalam lab, dsb).</p>
                                                        <p>3.2. Segala bentuk kecelakaan kerja di dalam laboratorium harus segera dilaporkan kepada Kepala Laboratorium / Petugas yang berwenang.</p>

                                                        <h4 class="font-bold text-gray-900 mt-4">4. Persetujuan</h4>
                                                        <p>Dengan mengajukan permohonan ini, Anda secara sadar telah membaca, memahami, dan menyetujui segala aturan yang berlaku di Laboratorium SMABA.</p>
                                                        <p class="text-xs text-gray-400 mt-6">(Scroll hingga baris ini untuk menyetujui)</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                            <button type="submit" 
                                                form="loanForm"
                                                :class="hasReadSop ? 'bg-green-600 hover:bg-green-500' : 'bg-gray-300 cursor-not-allowed'"
                                                :disabled="!hasReadSop"
                                                class="inline-flex w-full justify-center rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto transition-colors">
                                                Saya Setuju & Pinjam
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
                // --- Script Live Search (dari sebelumnya) ---
                const searchInput = document.getElementById('item-search');
                const itemList = document.getElementById('item-list');
                const items = itemList.querySelectorAll('.item-entry');
                const labSelect = document.getElementById('laboratorium');

                searchInput.addEventListener('keyup', function(e) {
                    const searchTerm = e.target.value.toLowerCase();

                    items.forEach(item => {
                        const itemName = item.querySelector('.item-name').textContent.toLowerCase();
                        if (itemName.includes(searchTerm) && item.dataset.lab === labSelect.value) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });

                // Filter berdasarkan lab
                function filterByLab() {
                    items.forEach(item => {
                        const matchLab = item.dataset.lab === labSelect.value;
                        const itemName = item.querySelector('.item-name').textContent.toLowerCase();
                        const matchSearch = searchInput.value === '' || itemName.includes(searchInput.value.toLowerCase());
                        item.style.display = matchLab && matchSearch ? 'block' : 'none';
                    });
                }
                labSelect.addEventListener('change', filterByLab);
                filterByLab();
                
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
                        // Format ke YYYY-MM-DD untuk pencocokan dengan timezone lokal
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

