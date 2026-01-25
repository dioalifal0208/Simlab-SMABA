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
                    
                    {{-- Quick Stats Summary Bar --}}
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-4 shadow-lg" data-aos="fade-down" data-aos-once="true">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Total Items --}}
                            <div class="flex items-center gap-4 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-lg">
                                <div class="flex-shrink-0 p-3 bg-white/20 rounded-lg">
                                    <i class="fas fa-flask text-2xl text-white"></i>
                                </div>
                                <div>
                                    <p class="text-blue-100 text-xs font-medium uppercase tracking-wider">Total Item</p>
                                    <p class="text-white text-2xl font-bold">{{ number_format($totalItemsCount ?? 0) }}</p>
                                </div>
                            </div>
                            
                            {{-- Total Users --}}
                            <div class="flex items-center gap-4 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-lg">
                                <div class="flex-shrink-0 p-3 bg-white/20 rounded-lg">
                                    <i class="fas fa-users text-2xl text-white"></i>
                                </div>
                                <div>
                                    <p class="text-blue-100 text-xs font-medium uppercase tracking-wider">Total Pengguna</p>
                                    <p class="text-white text-2xl font-bold">{{ number_format($totalUsersCount ?? 0) }}</p>
                                </div>
                            </div>
                            
                            {{-- Monthly Transactions --}}
                            <div class="flex items-center gap-4 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-lg">
                                <div class="flex-shrink-0 p-3 bg-white/20 rounded-lg">
                                    <i class="fas fa-exchange-alt text-2xl text-white"></i>
                                </div>
                                <div>
                                    <p class="text-blue-100 text-xs font-medium uppercase tracking-wider">Transaksi Bulan Ini</p>
                                    <p class="text-white text-2xl font-bold">{{ number_format($monthlyTransactionsCount ?? 0) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Welcome Banner (Clean Style) --}}
                    <div class="bg-white border border-gray-200 rounded-xl p-6 md:p-8" data-aos="fade-up" data-aos-once="true">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">Selamat datang, {{ Auth::user()->name }}! 👋</h3>
                                <p class="mt-1 text-gray-500">Berikut ringkasan aktivitas laboratorium hari ini.</p>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('items.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                    + Tambah Item
                                </a>
                                <a href="{{ route('loans.index', ['status' => 'pending']) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors">
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
                    {{-- ============================================== --}}
                    {{-- ## ENHANCED: GRADIENT STATISTICS CARDS ## --}}
                    {{-- ============================================== --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                        {{-- Peminjaman Pending --}}
                        <a href="{{ route('loans.index', ['status' => 'pending']) }}" class="group bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:border-blue-500 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Peminjaman Pending</h3>
                                <div class="p-2 bg-blue-50 rounded-lg text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            </div>
                            <p class="text-3xl font-bold text-gray-900">{{ $pendingLoansCount ?? 0 }}</p>
                            <p class="text-xs text-blue-600 mt-1 font-medium">Perlu persetujuan</p>
                        </a>

                        {{-- Booking Pending --}}
                        <a href="{{ route('bookings.index', ['status' => 'pending']) }}" class="group bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:border-purple-500 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Booking Pending</h3>
                                <div class="p-2 bg-purple-50 rounded-lg text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            </div>
                            <p class="text-3xl font-bold text-gray-900">{{ $pendingBookingsCount ?? 0 }}</p>
                            <p class="text-xs text-purple-600 mt-1 font-medium">Jadwal tertunda</p>
                        </a>

                        {{-- Item Rusak --}}
                        <a href="{{ route('items.index', ['kondisi' => 'Rusak']) }}" class="group bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:border-red-500 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Item Rusak</h3>
                                <div class="p-2 bg-red-50 rounded-lg text-red-600 group-hover:bg-red-600 group-hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                            </div>
                            <p class="text-3xl font-bold text-gray-900">{{ $brokenItemsCount ?? 0 }}</p>
                            <p class="text-xs text-red-600 mt-1 font-medium">Perlu perbaikan</p>
                        </a>

                        {{-- Laporan Kerusakan --}}
                        <a href="{{ route('damage-reports.index', ['status' => 'Dilaporkan']) }}" class="group bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:border-orange-500 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Laporan Baru</h3>
                                <div class="p-2 bg-orange-50 rounded-lg text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                            </div>
                            <p class="text-3xl font-bold text-gray-900">{{ $newDamageReportsCount ?? 0 }}</p>
                            <p class="text-xs text-orange-600 mt-1 font-medium">Verifikasi laporan</p>
                        </a>

                        {{-- Peminjaman Terlambat --}}
                        <a href="{{ route('loans.index', ['status' => 'Terlambat']) }}" class="group bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:border-rose-500 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Terlambat</h3>
                                <div class="p-2 bg-rose-50 rounded-lg text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            </div>
                            <p class="text-3xl font-bold text-gray-900">{{ $overdueLoansCount ?? 0 }}</p>
                            <p class="text-xs text-rose-600 mt-1 font-medium">Segera tindak lanjuti</p>
                        </a>

                        {{-- Jadwal Terdekat --}}
                        <a href="{{ route('calendar.index') }}" class="group bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:border-emerald-500 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Minggu Ini</h3>
                                <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            </div>
                            <p class="text-3xl font-bold text-gray-900">{{ $upcomingBookingsCount ?? 0 }}</p>
                            <p class="text-xs text-emerald-600 mt-1 font-medium">Jadwal praktikum</p>
                        </a>
                    </div>
                    {{-- ============================================== --}}
                    {{-- ##    END ENHANCED GRADIENT CARDS           ## --}}
                    {{-- ============================================== --}}


                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 min-h-0">
                        {{-- Tab Aktivitas & Dokumen (Tetap Sama) --}}
                        <div x-data="{ activeTab: 'aktivitas' }" class="lg:col-span-2 bg-white border border-gray-100 shadow-sm sm:rounded-xl transition-all duration-200 hover:shadow-md" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                            <div class="border-b border-gray-200"><nav class="flex -mb-px"><button @click="activeTab = 'aktivitas'" :class="{ 'border-smaba-dark-blue text-smaba-dark-blue': activeTab === 'aktivitas', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'aktivitas' }" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors">Aktivitas Terbaru</button><button @click="activeTab = 'dokumen'" :class="{ 'border-smaba-dark-blue text-smaba-dark-blue': activeTab === 'dokumen', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'dokumen' }" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors">Dokumen Terbaru</button></nav></div>
                            <div class="p-6">
                                <div x-show="activeTab === 'aktivitas'" x-transition><div class="space-y-4 overflow-auto max-h-96">@forelse ($recentActivities ?? []->take(5) as $activity)<div class="flex items-start space-x-3"><div class="flex-shrink-0">@if ($activity instanceof \App\Models\Loan) <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-100"><svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/></svg></span> @else <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100"><svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg></span> @endif</div><div class="text-sm flex-grow"><p class="text-gray-800"><span class="font-semibold">{{ $activity->user->name }}</span> @if ($activity instanceof \App\Models\Loan) mengajukan peminjaman alat. @else mengajukan booking lab untuk "{{ Str::limit($activity->tujuan_kegiatan, 30) }}". @endif</p><p class="text-xs text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p></div><div class="text-sm text-right">@if ($activity instanceof \App\Models\Loan) <a href="{{ route('loans.show', $activity->id) }}" class="text-indigo-600 hover:text-indigo-900">Lihat</a> @else <a href="{{ route('bookings.show', $activity->id) }}" class="text-indigo-600 hover:text-indigo-900">Lihat</a> @endif</div></div>@empty<p class="text-center text-gray-500">Tidak ada aktivitas terbaru.</p>@endforelse</div></div>
                                <div x-show="activeTab === 'dokumen'" x-transition style="display: none;"><div class="overflow-x-auto max-h-96"><table class="min-w-full bg-white"><tbody>@forelse (($recentDocuments ?? [])->take(5) as $document)<tr class="hover:bg-gray-50"><td class="py-3 px-4 border-b"><p class="font-semibold">{{ $document->title }}</p><p class="text-xs text-gray-500">Diunggah oleh {{ $document->user->name }}</p></td><td class="py-3 px-4 border-b text-right space-x-3"><a href="#" onclick="openModal('{{ route('documents.preview', $document) }}', '{{ e($document->title) }}'); return false;" class="text-sm text-indigo-600 hover:text-indigo-900">Lihat</a></td></tr>@empty<tr><td class="py-4 text-center text-gray-500">Belum ada dokumen di pustaka digital.</td></tr>@endforelse</tbody></table></div><a href="{{ route('documents.index') }}" class="block bg-gray-50 mt-4 px-6 py-3 text-sm font-medium text-indigo-600 hover:text-indigo-800 text-center rounded-b-lg">Lihat Semua Dokumen &rarr;</a></div>
                            </div>
                        </div>
                        
                        {{-- Kartu Chart (Tetap Sama) --}}
                        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-1 min-h-0" data-aos="fade-up" data-aos-delay="600" data-aos-once="true" id="chart-container"><h3 class="font-semibold text-gray-700 mb-4">Proporsi Kondisi Item</h3><div class="h-64 w-full"><canvas id="itemConditionsChart"></canvas></div></div>
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

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <a href="{{ route('loans.create') }}" class="group block p-6 bg-white border border-gray-100 rounded-xl shadow-sm hover:border-smaba-dark-blue/40 hover:shadow-md transition-all duration-200 transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                            <div class="flex items-center space-x-4"><div class="flex-shrink-0 h-12 w-12 flex items-center justify-center rounded-lg bg-green-100"><i class="fas fa-flask text-xl text-green-600"></i></div><div><h3 class="font-bold text-lg text-smaba-text group-hover:text-smaba-light-blue">Ajukan Peminjaman Alat</h3><p class="text-sm text-gray-600 mt-1">Pinjam alat dan bahan praktikum.</p></div></div>
                        </a>
                        <a href="{{ route('bookings.create') }}" class="group block p-6 bg-white border border-gray-100 rounded-xl shadow-sm hover:border-smaba-dark-blue/40 hover:shadow-md transition-all duration-200 transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                             <div class="flex items-center space-x-4"><div class="flex-shrink-0 h-12 w-12 flex items-center justify-center rounded-lg bg-blue-100"><i class="fas fa-calendar-alt text-xl text-blue-600"></i></div><div><h3 class="font-bold text-lg text-smaba-text group-hover:text-smaba-light-blue">Ajukan Booking Lab</h3><p class="text-sm text-gray-600 mt-1">Reservasi jadwal dan ruangan.</p></div></div>
                        </a>
                        <a href="{{ route('item-requests.create') }}" class="group block p-6 bg-white border border-gray-100 rounded-xl shadow-sm hover:border-smaba-dark-blue/40 hover:shadow-md transition-all duration-200 transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="250" data-aos-once="true">
                             <div class="flex items-center space-x-4"><div class="flex-shrink-0 h-12 w-12 flex items-center justify-center rounded-lg bg-amber-100"><i class="fas fa-plus text-xl text-amber-600"></i></div><div><h3 class="font-bold text-lg text-smaba-text group-hover:text-smaba-light-blue">Ajukan Tambah Item</h3><p class="text-sm text-gray-600 mt-1">Kirim permintaan penambahan alat/bahan baru.</p></div></div>
                        </a>
                    </div>
                    
                    @if(isset($activeLoans) && $activeLoans->isNotEmpty())
                        <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="300" data-aos-once="true">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-smaba-text mb-4">Item yang Sedang Anda Pinjam</h3>
                                <div class="space-y-4">
                                    @foreach($activeLoans as $loan)
                                        <div class="p-4 border rounded-lg bg-gray-50 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-sm hover:border-smaba-dark-blue/30">
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
                                        <div class="p-4 border rounded-lg bg-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
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
