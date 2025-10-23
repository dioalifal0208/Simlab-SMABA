<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-6"> {{-- Padding py-6 yang lebih padat --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">Sukses</p>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- =================================== --}}
            {{-- ======= TAMPILAN UNTUK ADMIN ======= --}}
            {{-- =================================== --}}
            @if (auth()->user()->role === 'admin')
                <div class="space-y-6" data-aos="fade-in" data-aos-duration="500" data-aos-once="true">
                    
                    {{-- Welcome Banner (Tetap Sama) --}}
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-once="true">
                        <div class="p-6 md:p-8">
                            <h3 class="text-2xl font-bold text-smaba-text">Selamat datang kembali, {{ Auth::user()->name }}!</h3>
                            <p class="mt-1 text-gray-600">Berikut adalah ringkasan aktivitas laboratorium hari ini. Apa yang ingin Anda lakukan?</p>
                            <div class="mt-4 flex flex-wrap gap-3">
                                <a href="{{ route('items.create') }}" class="px-4 py-2 bg-smaba-dark-blue text-white text-xs font-semibold rounded-lg hover:bg-smaba-light-blue shadow-sm transition-colors">
                                    + Tambah Item Baru
                                </a>
                                <a href="{{ route('loans.index', ['status' => 'pending']) }}" class="px-4 py-2 bg-gray-200 text-gray-800 text-xs font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                                    Proses Peminjaman
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    @if(isset($lowStockItems) && $lowStockItems->isNotEmpty())
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded-lg" data-aos="fade-up" data-aos-once="true">
                            <p class="font-bold">Perhatian: Stok Menipis</p>
                            <ul class="list-disc list-inside mt-2 text-sm">
                                @foreach($lowStockItems as $item)
                                    <li>Stok untuk <a href="{{ route('items.show', $item->id) }}" class="font-semibold underline hover:text-yellow-900">{{ $item->nama_alat }}</a> tersisa {{ $item->jumlah }}.</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- ============================================== --}}
                    {{-- ## PERUBAHAN: KARTU STATISTIK DIKONSOLIDASI ## --}}
                    {{-- ============================================== --}}
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-smaba-text mb-4">Ringkasan Status Laboratorium</h3>
                            {{-- Menggunakan grid untuk menampilkan daftar statistik --}}
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                
                                {{-- 1. Peminjaman Pending --}}
                                <a href="{{ route('loans.index', ['status' => 'pending']) }}" class="block p-4 bg-blue-50 rounded-lg hover:shadow-md transition-shadow">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-blue-100"><i class="fas fa-clock text-blue-600"></i></div>
                                        <div>
                                            <p class="text-3xl font-bold text-smaba-text">{{ $pendingLoansCount ?? 0 }}</p>
                                            <h4 class="font-semibold text-gray-500 text-sm">Peminjaman Pending</h4>
                                        </div>
                                    </div>
                                </a>
                                
                                {{-- 2. Booking Pending --}}
                                <a href="{{ route('bookings.index', ['status' => 'pending']) }}" class="block p-4 bg-blue-50 rounded-lg hover:shadow-md transition-shadow">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-blue-100"><i class="fas fa-calendar-check text-blue-600"></i></div>
                                        <div>
                                            <p class="text-3xl font-bold text-smaba-text">{{ $pendingBookingsCount ?? 0 }}</p>
                                            <h4 class="font-semibold text-gray-500 text-sm">Booking Pending</h4>
                                        </div>
                                    </div>
                                </a>
                                
                                {{-- 3. Item Rusak --}}
                                <a href="{{ route('items.index', ['kondisi' => 'Rusak']) }}" class="block p-4 bg-red-50 rounded-lg hover:shadow-md transition-shadow">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-red-100"><i class="fas fa-tools text-red-600"></i></div>
                                        <div>
                                            <p class="text-3xl font-bold text-smaba-text">{{ $brokenItemsCount ?? 0 }}</p>
                                            <h4 class="font-semibold text-gray-500 text-sm">Item Kondisi Rusak</h4>
                                        </div>
                                    </div>
                                </a>

                                {{-- 4. Laporan Kerusakan --}}
                                <a href="{{ route('damage-reports.index', ['status' => 'Dilaporkan']) }}" class="block p-4 bg-yellow-50 rounded-lg hover:shadow-md transition-shadow">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-yellow-100"><i class="fas fa-exclamation-triangle text-yellow-600"></i></div>
                                        <div>
                                            <p class="text-3xl font-bold text-smaba-text">{{ $newDamageReportsCount ?? 0 }}</p>
                                            <h4 class="font-semibold text-gray-500 text-sm">Laporan Kerusakan</h4>
                                        </div>
                                    </div>
                                </a>

                                {{-- 5. Peminjaman Terlambat --}}
                                <a href="{{ route('loans.index', ['status' => 'Terlambat']) }}" class="block p-4 bg-red-50 rounded-lg hover:shadow-md transition-shadow">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-red-100"><i class="fas fa-hourglass-end text-red-600"></i></div>
                                        <div>
                                            <p class="text-3xl font-bold text-smaba-text">{{ $overdueLoansCount ?? 0 }}</p>
                                            <h4 class="font-semibold text-gray-500 text-sm">Peminjaman Terlambat</h4>
                                        </div>
                                    </div>
                                </a>

                                {{-- 6. Jadwal Terdekat --}}
                                <a href="{{ route('calendar.index') }}" class="block p-4 bg-gray-50 rounded-lg hover:shadow-md transition-shadow">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-gray-200"><i class="fas fa-calendar-day text-gray-600"></i></div>
                                        <div>
                                            <p class="text-3xl font-bold text-smaba-text">{{ $upcomingBookingsCount ?? 0 }}</p>
                                            <h4 class="font-semibold text-gray-500 text-sm">Jadwal Lab Terdekat</h4>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    {{-- ============================================== --}}
                    {{-- ##          AKHIR DARI KARTU KONSOLIDASI          ## --}}
                    {{-- ============================================== --}}


                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 min-h-0">
                        {{-- Tab Aktivitas & Dokumen (Tetap Sama) --}}
                        <div x-data="{ activeTab: 'aktivitas' }" class="lg:col-span-2 bg-white shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                            <div class="border-b border-gray-200"><nav class="flex -mb-px"><button @click="activeTab = 'aktivitas'" :class="{ 'border-smaba-dark-blue text-smaba-dark-blue': activeTab === 'aktivitas', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'aktivitas' }" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors">Aktivitas Terbaru</button><button @click="activeTab = 'dokumen'" :class="{ 'border-smaba-dark-blue text-smaba-dark-blue': activeTab === 'dokumen', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'dokumen' }" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors">Dokumen Terbaru</button></nav></div>
                            <div class="p-6">
                                <div x-show="activeTab === 'aktivitas'" x-transition><div class="space-y-4 overflow-auto max-h-96">@forelse ($recentActivities ?? []->take(5) as $activity)<div class="flex items-start space-x-3"><div class="flex-shrink-0">@if ($activity instanceof \App\Models\Loan) <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-100"><svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/></svg></span> @else <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100"><svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg></span> @endif</div><div class="text-sm flex-grow"><p class="text-gray-800"><span class="font-semibold">{{ $activity->user->name }}</span> @if ($activity instanceof \App\Models\Loan) mengajukan peminjaman alat. @else mengajukan booking lab untuk "{{ Str::limit($activity->tujuan_kegiatan, 30) }}". @endif</p><p class="text-xs text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p></div><div class="text-sm text-right">@if ($activity instanceof \App\Models\Loan) <a href="{{ route('loans.show', $activity->id) }}" class="text-indigo-600 hover:text-indigo-900">Lihat</a> @else <a href="{{ route('bookings.show', $activity->id) }}" class="text-indigo-600 hover:text-indigo-900">Lihat</a> @endif</div></div>@empty<p class="text-center text-gray-500">Tidak ada aktivitas terbaru.</p>@endforelse</div></div>
                                <div x-show="activeTab === 'dokumen'" x-transition style="display: none;"><div class="overflow-x-auto max-h-96"><table class="min-w-full bg-white"><tbody>@forelse (($recentDocuments ?? [])->take(5) as $document)<tr class="hover:bg-gray-50"><td class="py-3 px-4 border-b"><p class="font-semibold">{{ $document->title }}</p><p class="text-xs text-gray-500">Diunggah oleh {{ $document->user->name }}</p></td><td class="py-3 px-4 border-b text-right space-x-3"><a href="#" onclick="openModal('{{ route('documents.preview', $document) }}', '{{ e($document->title) }}'); return false;" class="text-sm text-indigo-600 hover:text-indigo-900">Lihat</a></td></tr>@empty<tr><td class="py-4 text-center text-gray-500">Belum ada dokumen di pustaka digital.</td></tr>@endforelse</tbody></table></div><a href="{{ route('documents.index') }}" class="block bg-gray-50 mt-4 px-6 py-3 text-sm font-medium text-indigo-600 hover:text-indigo-800 text-center rounded-b-lg">Lihat Semua Dokumen &rarr;</a></div>
                            </div>
                        </div>
                        
                        {{-- Kartu Chart (Tetap Sama) --}}
                        <div class="bg-white p-6 rounded-xl shadow-lg min-h-0" data-aos="fade-up" data-aos-delay="600" data-aos-once="true" id="chart-container"><h3 class="font-semibold text-gray-700 mb-4">Proporsi Kondisi Item</h3><div class="h-64 w-full"><canvas id="itemConditionsChart"></canvas></div></div>
                    </div>
                </div>
            
            @else
                {{-- ======================================= --}}
                {{-- ======= TAMPILAN SISWA / GURU (REDESIGN) ======= --}}
                {{-- ======================================= --}}
                <div class="space-y-6" data-aos="fade-in" data-aos-duration="500" data-aos-once="true">
                    
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-once="true">
                        <div class="p-6 md:p-8 flex items-center space-x-4">
                            <div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-smaba-dark-blue"><span class="text-xl font-medium leading-none text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span></span></div>
                            <div><h3 class="text-2xl font-bold text-smaba-text">Selamat Datang, {{ auth()->user()->name }}!</h3><p class="mt-1 text-gray-600">Siap untuk praktikum? Ajukan peminjaman atau booking lab di bawah ini.</p></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <a href="{{ route('loans.create') }}" class="group block p-6 bg-white border-2 border-transparent rounded-xl shadow-lg hover:border-smaba-dark-blue hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                            <div class="flex items-center space-x-4"><div class="flex-shrink-0 h-12 w-12 flex items-center justify-center rounded-lg bg-green-100"><i class="fas fa-flask text-xl text-green-600"></i></div><div><h3 class="font-bold text-lg text-smaba-text group-hover:text-smaba-light-blue">Ajukan Peminjaman Alat</h3><p class="text-sm text-gray-600 mt-1">Pinjam alat dan bahan praktikum.</p></div></div>
                        </a>
                        <a href="{{ route('bookings.create') }}" class="group block p-6 bg-white border-2 border-transparent rounded-xl shadow-lg hover:border-smaba-dark-blue hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                             <div class="flex items-center space-x-4"><div class="flex-shrink-0 h-12 w-12 flex items-center justify-center rounded-lg bg-blue-100"><i class="fas fa-calendar-alt text-xl text-blue-600"></i></div><div><h3 class="font-bold text-lg text-smaba-text group-hover:text-smaba-light-blue">Ajukan Booking Lab</h3><p class="text-sm text-gray-600 mt-1">Reservasi jadwal dan ruangan.</p></div></div>
                        </a>
                    </div>
                    
                    @if(isset($activeLoans) && $activeLoans->isNotEmpty())
                        <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="300" data-aos-once="true">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-smaba-text mb-4">Item yang Sedang Anda Pinjam</h3>
                                <div class="space-y-4">
                                    @foreach($activeLoans as $loan)
                                        <div class="p-4 border rounded-lg bg-gray-50">
                                            <div class="flex justify-between items-center mb-2"><span class="font-semibold text-smaba-dark-blue">Pengajuan: {{ $loan->created_at->format('d M Y') }}</span><span class="text-xs text-gray-500">Estimasi Kembali: {{ $loan->tanggal_estimasi_kembali->format('d M Y') }}</span></div>
                                            <ul class="list-disc list-inside text-sm text-gray-700">
                                                @foreach($loan->items as $item)
                                                    <li>{{ $item->nama_alat }} ({{ $item->pivot->jumlah }} {{ $item->satuan }})</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(isset($overdueLoans) && $overdueLoans->isNotEmpty())
                        <div class="bg-red-50 border-2 border-red-200 overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="300" data-aos-once="true">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-red-700 mb-4">Peminjaman Terlambat!</h3>
                                <div class="space-y-4">
                                    @foreach($overdueLoans as $loan)
                                        <div class="p-4 border rounded-lg bg-white shadow-sm">
                                            <div class="flex justify-between items-center mb-2"><span class="font-semibold text-red-600">Pengajuan: {{ $loan->created_at->format('d M Y') }}</span><span class="text-xs text-red-500 font-semibold">Seharusnya Kembali: {{ $loan->tanggal_estimasi_kembali->format('d M Y') }}</span></div>
                                            <ul class="list-disc list-inside text-sm text-gray-700">
                                                @foreach($loan->items as $item)
                                                    <li>{{ $item->nama_alat }} ({{ $item->pivot->jumlah }} {{ $item->satuan }})</li>
                                                @endforeach
                                            </ul>
                                            <p class="text-sm font-medium text-red-700 mt-3">Harap segera kembalikan item ini ke laboratorium.</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                        <div class="lg:col-span-2 bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="300" data-aos-once="true">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-smaba-text mb-4">Jadwal Mendatang</h3>
                                @if($nextBooking)
                                    <div class="space-y-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 flex flex-col items-center justify-center h-10 w-10 rounded-lg bg-gray-100 text-smaba-dark-blue"><span class="text-xs font-bold uppercase">{{ $nextBooking->waktu_mulai->format('M') }}</span><span class="text-lg font-bold">{{ $nextBooking->waktu_mulai->format('d') }}</span></div>
                                            <div>
                                                <p class="font-semibold text-gray-800">{{ $nextBooking->tujuan_kegiatan }}</p>
                                                <p class="text-sm text-gray-600">{{ $nextBooking->waktu_mulai->format('H:i') }} - {{ $nextBooking->waktu_selesai->format('H:i') }}</p>
                                                <a href="{{ route('bookings.show', $nextBooking->id) }}" class="text-xs text-smaba-light-blue hover:underline mt-1 inline-block">Lihat Detail &rarr;</a>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-center text-gray-500 py-4 text-sm">Tidak ada jadwal booking yang akan datang.</p>
                                @endif
                            </div>
                        </div>

                        <div class="lg:col-span-3 bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="400" data-aos-once="true">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-smaba-text mb-4">Aktivitas Peminjaman Saya</h3>
                                <div class="space-y-4 overflow-auto max-h-96">
                                    @forelse ($recentUserLoans as $loan)
                                        <div class="flex items-center space-x-3 p-2 rounded-md hover:bg-gray-50">
                                            <div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-100"><i class="fas fa-flask text-green-600 text-sm"></i></span></div>
                                            <div class="flex-grow text-sm">
                                                <p class="font-semibold text-gray-800">Pengajuan Peminjaman</p>
                                                <p class="text-xs text-gray-500">Diajukan pada {{ \Carbon\Carbon::parse($loan->tanggal_pinjam ?? $loan->created_at)->format('d M Y') }}</p>
                                            </div>
                                            <div class="flex-shrink-0 text-sm text-right space-y-1">
                                                @php $s = $loan->status; @endphp
                                                @if($s == 'pending') <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-200 rounded-full">Menunggu</span>
                                                @elseif($s == 'approved') <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">Disetujui</span>
                                                @elseif($s == 'rejected') <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full">Ditolak</span>
                                                @elseif($s == 'completed') <span class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded-full">Selesai</span>
                                                @elseif($s == 'Terlambat') <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full">Terlambat</span>
                                                @endif
                                                <a href="{{ route('loans.show', $loan->id) }}" class="block text-xs text-smaba-light-blue hover:underline">Lihat Detail</a>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-center text-gray-500 py-4 text-sm">Belum ada riwayat peminjaman.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Pratinjau Dokumen --}}
    <div id="documentModal" class="hidden fixed inset-0 z-50 bg-black/60 items-center justify-center">
        <div class="bg-white w-11/12 max-w-4xl rounded-lg shadow-lg overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 id="modalTitle" class="text-lg font-semibold">Pratinjau Dokumen</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-800 text-xl font-bold">&times;</button>
            </div>
            <div class="p-4">
                <iframe id="documentFrame" class="w-full h-[80vh] border rounded" src=""></iframe>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @php
        $__labels = $chartLabels ?? collect();
        $__data   = $chartData   ?? collect();
        $__colors = $chartColors ?? ['rgb(75, 192, 192)','rgb(255, 205, 86)','rgb(255, 99, 132)'];
    @endphp
    <script>
        function openModal(url, title) {
            const modal = document.getElementById('documentModal');
            if (!modal) return;
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('documentFrame').src = url;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        function closeModal() {
            const modal = document.getElementById('documentModal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('documentFrame').src = '';
            document.body.style.overflow = '';
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Logika Animasi Chart (hanya untuk admin)
            const chartContainer = document.getElementById('chart-container');
            if (chartContainer) {
                const observer = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const chartCanvas = document.getElementById('itemConditionsChart');
                            if (!chartCanvas) return;
                            if (Chart.getChart(chartCanvas)) { return; } // Mencegah duplikasi
                            const labels = @json($__labels);
                            const data   = @json($__data);
                            const colors = @json($__colors);
                            if (labels.length === 0) return;
                            new Chart(chartCanvas.getContext('2d'), {
                                type: 'pie',
                                data: { labels, datasets: [{ data, backgroundColor: colors, hoverOffset: 4 }] },
                                options: { responsive: true, maintainAspectRatio: false, animation: { animateScale: true, animateRotate: true } }
                            });
                            observer.unobserve(chartContainer);
                        }
                    });
                }, { threshold: 0.5 });
                observer.observe(chartContainer);
            }
        });
    </script>
</x-app-layout>