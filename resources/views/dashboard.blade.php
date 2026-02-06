<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        
        {{-- Product Tour CSS & JS --}}
        <link rel="stylesheet" href="{{ asset('css/dashboard-tour.css') }}">
        <script src="{{ asset('js/dashboard-tour.js') }}" defer></script>
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
                <div class="space-y-8">
                    
                    {{-- ============================================== --}}
                    {{-- FOCUS DASHBOARD: Hero Section with Key Metrics --}}
                    {{-- ============================================== --}}
                    <div class="relative overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 rounded-2xl border border-gray-200 shadow-sm">
                        {{-- Background Pattern --}}
                        <div class="absolute inset-0 opacity-30">
                            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, #cbd5e1 1px, transparent 0); background-size: 40px 40px;"></div>
                        </div>
                        
                        <div class="relative px-8 py-10 md:px-12 md:py-14">
                            {{-- Greeting --}}
                            <div class="mb-8" data-aos="fade-up" data-aos-once="true">
                                <p class="text-blue-600 text-sm font-medium uppercase tracking-wider mb-2">Dashboard Admin</p>
                                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight">
                                    Selamat datang, {{ Auth::user()->name }}! 👋
                                </h1>
                                <p class="mt-2 text-gray-600 text-lg">Ringkasan aktivitas laboratorium hari ini.</p>
                            </div>
                            
                            {{-- Key Metrics Grid --}}
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                                    <div class="flex items-center gap-4">
                                        <div class="p-3 bg-blue-100 rounded-lg">
                                            <i class="fas fa-flask text-2xl text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Total Item</p>
                                            <p class="text-gray-900 text-3xl font-bold">{{ number_format($totalItemsCount ?? 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                                    <div class="flex items-center gap-4">
                                        <div class="p-3 bg-purple-100 rounded-lg">
                                            <i class="fas fa-users text-2xl text-purple-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Total Pengguna</p>
                                            <p class="text-gray-900 text-3xl font-bold">{{ number_format($totalUsersCount ?? 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm" data-aos="fade-up" data-aos-delay="300" data-aos-once="true">
                                    <div class="flex items-center gap-4">
                                        <div class="p-3 bg-emerald-100 rounded-lg">
                                            <i class="fas fa-exchange-alt text-2xl text-emerald-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Transaksi Bulan Ini</p>
                                            <p class="text-gray-900 text-3xl font-bold">{{ number_format($monthlyTransactionsCount ?? 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Quick Actions --}}
                            <div class="mt-8 flex flex-wrap gap-3" data-aos="fade-up" data-aos-delay="400" data-aos-once="true">
                                <a href="{{ route('items.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                    <i class="fas fa-plus"></i> Tambah Item
                                </a>
                                <a href="{{ route('loans.index', ['status' => 'pending']) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors border border-gray-300">
                                    <i class="fas fa-clock"></i> Proses Peminjaman
                                </a>
                                <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors border border-gray-300">
                                    <i class="fas fa-chart-bar"></i> Lihat Laporan
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    {{-- ============================================== --}}
                    {{-- Alerts Section (Low Stock / Overdue) --}}
                    {{-- ============================================== --}}
                    @if((isset($lowStockItems) && $lowStockItems->isNotEmpty()) || ($overdueLoansCount ?? 0) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                        @if(isset($lowStockItems) && $lowStockItems->isNotEmpty())
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
                            <div class="flex items-start gap-4">
                                <div class="p-2 bg-amber-100 rounded-lg">
                                    <i class="fas fa-exclamation-triangle text-amber-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-amber-800">Stok Menipis</h3>
                                    <p class="text-sm text-amber-700 mt-1">{{ $lowStockItems->count() }} item memerlukan restok segera.</p>
                                    <a href="{{ route('items.index') }}" class="text-sm font-medium text-amber-700 hover:text-amber-900 mt-2 inline-block">Lihat Detail →</a>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if(($overdueLoansCount ?? 0) > 0)
                        <div class="bg-red-50 border border-red-200 rounded-xl p-5">
                            <div class="flex items-start gap-4">
                                <div class="p-2 bg-red-100 rounded-lg">
                                    <i class="fas fa-clock text-red-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-red-800">Peminjaman Terlambat</h3>
                                    <p class="text-sm text-red-700 mt-1">{{ $overdueLoansCount }} peminjaman melewati batas waktu.</p>
                                    <a href="{{ route('loans.index', ['status' => 'Terlambat']) }}" class="text-sm font-medium text-red-700 hover:text-red-900 mt-2 inline-block">Tindak Lanjuti →</a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                    
                    {{-- ============================================== --}}
                    {{-- Action Cards - What Needs Attention --}}
                    {{-- ============================================== --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" data-aos="fade-up" data-aos-delay="150" data-aos-once="true">
                        {{-- Pending Loans --}}
                        <a href="{{ route('loans.index', ['status' => 'pending']) }}" class="group bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-lg hover:border-blue-200 transition-all duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-blue-50 rounded-xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                    <i class="fas fa-hourglass-half text-xl"></i>
                                </div>
                                <span class="text-3xl font-bold text-gray-900">{{ $pendingLoansCount ?? 0 }}</span>
                            </div>
                            <h3 class="font-semibold text-gray-700">Peminjaman Pending</h3>
                            <p class="text-sm text-gray-500 mt-1">Perlu persetujuan</p>
                        </a>
                        
                        {{-- Pending Bookings --}}
                        <a href="{{ route('bookings.index', ['status' => 'pending']) }}" class="group bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-lg hover:border-purple-200 transition-all duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-purple-50 rounded-xl text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                                    <i class="fas fa-calendar-alt text-xl"></i>
                                </div>
                                <span class="text-3xl font-bold text-gray-900">{{ $pendingBookingsCount ?? 0 }}</span>
                            </div>
                            <h3 class="font-semibold text-gray-700">Booking Pending</h3>
                            <p class="text-sm text-gray-500 mt-1">Jadwal menunggu</p>
                        </a>
                        
                        {{-- Damage Reports --}}
                        <a href="{{ route('damage-reports.index', ['status' => 'Dilaporkan']) }}" class="group bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-lg hover:border-orange-200 transition-all duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-orange-50 rounded-xl text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                                    <i class="fas fa-exclamation-circle text-xl"></i>
                                </div>
                                <span class="text-3xl font-bold text-gray-900">{{ $newDamageReportsCount ?? 0 }}</span>
                            </div>
                            <h3 class="font-semibold text-gray-700">Laporan Kerusakan</h3>
                            <p class="text-sm text-gray-500 mt-1">Perlu verifikasi</p>
                        </a>
                        
                        {{-- This Week Schedule --}}
                        <a href="{{ route('calendar.index') }}" class="group bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-lg hover:border-emerald-200 transition-all duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                    <i class="fas fa-calendar-check text-xl"></i>
                                </div>
                                <span class="text-3xl font-bold text-gray-900">{{ $upcomingBookingsCount ?? 0 }}</span>
                            </div>
                            <h3 class="font-semibold text-gray-700">Jadwal Minggu Ini</h3>
                            <p class="text-sm text-gray-500 mt-1">Praktikum terjadwal</p>
                        </a>
                    </div>
                    
                    {{-- ============================================== --}}
                    {{-- Recent Activity Timeline with Pagination --}}
                    {{-- ============================================== --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm" data-aos="fade-up" data-aos-delay="200" data-aos-once="true"
                         x-data="{ 
                            currentPage: 1, 
                            perPage: 5,
                            totalItems: {{ ($recentActivities ?? collect())->count() }},
                            get totalPages() { return Math.ceil(this.totalItems / this.perPage) },
                            get startIndex() { return (this.currentPage - 1) * this.perPage },
                            get endIndex() { return this.currentPage * this.perPage },
                            isVisible(index) { return index >= this.startIndex && index < this.endIndex }
                         }">
                        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h2>
                            <span class="text-sm text-gray-500" x-show="totalPages > 1">
                                Halaman <span x-text="currentPage"></span> dari <span x-text="totalPages"></span>
                            </span>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @forelse (($recentActivities ?? collect())->values() as $index => $activity)
                                <div class="flex items-start gap-4 p-4 rounded-lg hover:bg-gray-50 transition-colors"
                                     x-show="isVisible({{ $index }})" x-transition>
                                    <div class="flex-shrink-0">
                                        @if ($activity instanceof \App\Models\Loan)
                                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <i class="fas fa-flask text-green-600"></i>
                                        </div>
                                        @elseif ($activity instanceof \App\Models\Booking)
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-calendar text-blue-600"></i>
                                        </div>
                                        @elseif ($activity instanceof \App\Models\AuditLog)
                                            @if ($activity->action === 'created')
                                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                                <i class="fas fa-plus text-emerald-600"></i>
                                            </div>
                                            @elseif ($activity->action === 'updated')
                                            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                                                <i class="fas fa-edit text-amber-600"></i>
                                            </div>
                                            @elseif ($activity->action === 'deleted')
                                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                                <i class="fas fa-trash text-red-600"></i>
                                            </div>
                                            @elseif ($activity->action === 'login')
                                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <i class="fas fa-sign-in-alt text-indigo-600"></i>
                                            </div>
                                            @else
                                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                                <i class="fas fa-history text-gray-600"></i>
                                            </div>
                                            @endif
                                        @else
                                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                            <i class="fas fa-info text-gray-600"></i>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <p class="text-sm text-gray-900">
                                            <span class="font-semibold">{{ $activity->user->name ?? 'System' }}</span>
                                            @if ($activity instanceof \App\Models\Loan)
                                            mengajukan peminjaman alat
                                            @elseif ($activity instanceof \App\Models\Booking)
                                            mengajukan booking lab untuk "{{ Str::limit($activity->tujuan_kegiatan, 40) }}"
                                            @elseif ($activity instanceof \App\Models\AuditLog)
                                            {{ $activity->getActionLabel() }} {{ $activity->getModelName() }}
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @if ($activity instanceof \App\Models\Loan)
                                        <a href="{{ route('loans.show', $activity->id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">Lihat</a>
                                        @elseif ($activity instanceof \App\Models\Booking)
                                        <a href="{{ route('bookings.show', $activity->id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">Lihat</a>
                                        @elseif ($activity instanceof \App\Models\AuditLog)
                                        <a href="{{ route('audit-logs.show', $activity->id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">Detail</a>
                                        @endif
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                        <i class="fas fa-inbox text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-gray-500">Belum ada aktivitas terbaru.</p>
                                </div>
                                @endforelse
                            </div>
                            
                            {{-- Pagination Controls --}}
                            @if(($recentActivities ?? collect())->count() > 5)
                            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                                <button @click="currentPage = Math.max(1, currentPage - 1)" 
                                        :disabled="currentPage === 1"
                                        :class="currentPage === 1 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:text-gray-900'"
                                        class="flex items-center gap-1 text-sm font-medium transition-colors">
                                    <i class="fas fa-chevron-left text-xs"></i> Sebelumnya
                                </button>
                                
                                <div class="flex items-center gap-2">
                                    <template x-for="page in totalPages" :key="page">
                                        <button @click="currentPage = page"
                                                :class="currentPage === page ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                                class="w-8 h-8 rounded-lg text-sm font-medium transition-colors"
                                                x-text="page">
                                        </button>
                                    </template>
                                </div>
                                
                                <button @click="currentPage = Math.min(totalPages, currentPage + 1)" 
                                        :disabled="currentPage === totalPages"
                                        :class="currentPage === totalPages ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:text-gray-900'"
                                        class="flex items-center gap-1 text-sm font-medium transition-colors">
                                    Selanjutnya <i class="fas fa-chevron-right text-xs"></i>
                                </button>
                            </div>
                            @endif
                            
                            @if(($recentActivities ?? collect())->count() > 0)
                            <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                                <a href="{{ route('audit-logs.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                    Lihat Semua Aktivitas →
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                </div>
            
            @else
                {{-- ======================================= --}}
                {{-- ======= TAMPILAN SISWA / GURU (REDESIGN) ======= --}}
                {{-- ======================================= --}}
                <div class="space-y-8">
                    
                    {{-- ============================================== --}}
                    {{-- Hero Section - Matching Admin Style --}}
                    {{-- ============================================== --}}
                    <div class="relative overflow-hidden bg-gradient-to-br from-slate-50 via-emerald-50 to-teal-100 rounded-2xl border border-gray-200 shadow-sm">
                        {{-- Background Pattern --}}
                        <div class="absolute inset-0 opacity-30">
                            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, #cbd5e1 1px, transparent 0); background-size: 40px 40px;"></div>
                        </div>
                        
                        <div class="relative px-8 py-10 md:px-12 md:py-14">
                            {{-- Greeting --}}
                            <div class="flex items-center gap-4 mb-6" data-aos="fade-up" data-aos-once="true">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg">
                                        <span class="text-2xl font-bold text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                    </span>
                                </div>
                                <div>
                                    <p class="text-emerald-600 text-sm font-medium uppercase tracking-wider mb-1">Selamat Datang</p>
                                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight">
                                        {{ auth()->user()->name }}! 👋
                                    </h1>
                                    <p class="mt-1 text-gray-600">Siap untuk praktikum hari ini?</p>
                                </div>
                            </div>
                            
                            {{-- Quick Stats --}}
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                                <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2.5 bg-green-100 rounded-lg">
                                            <i class="fas fa-flask text-lg text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 text-xs font-medium">Peminjaman Aktif</p>
                                            <p class="text-gray-900 text-xl font-bold">{{ ($activeLoans ?? collect())->count() }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2.5 bg-blue-100 rounded-lg">
                                            <i class="fas fa-calendar-check text-lg text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 text-xs font-medium">Booking Mendatang</p>
                                            <p class="text-gray-900 text-xl font-bold">{{ $nextBooking ? 1 : 0 }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm" data-aos="fade-up" data-aos-delay="300" data-aos-once="true">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2.5 bg-amber-100 rounded-lg">
                                            <i class="fas fa-history text-lg text-amber-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 text-xs font-medium">Total Riwayat</p>
                                            <p class="text-gray-900 text-xl font-bold">{{ ($recentUserLoans ?? collect())->count() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Quick Actions --}}
                            <div class="flex flex-wrap gap-3" data-aos="fade-up" data-aos-delay="400" data-aos-once="true">
                                <a href="{{ route('loans.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">
                                    <i class="fas fa-flask"></i> Ajukan Peminjaman
                                </a>
                                <a href="{{ route('bookings.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors border border-gray-300">
                                    <i class="fas fa-calendar-alt"></i> Booking Lab
                                </a>
                                <a href="{{ route('item-requests.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors border border-gray-300">
                                    <i class="fas fa-plus"></i> Ajukan Item Baru
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    {{-- ============================================== --}}
                    {{-- Alert: Overdue Loans --}}
                    {{-- ============================================== --}}
                    @if(isset($overdueLoans) && $overdueLoans->isNotEmpty())
                    <div class="bg-red-50 border border-red-200 rounded-xl p-5" data-aos="fade-up" data-aos-once="true">
                        <div class="flex items-start gap-4">
                            <div class="p-2 bg-red-100 rounded-lg">
                                <i class="fas fa-exclamation-triangle text-red-600"></i>
                            </div>
                            <div class="flex-grow">
                                <h3 class="font-semibold text-red-800">⚠️ Peminjaman Terlambat!</h3>
                                <p class="text-sm text-red-700 mt-1">Anda memiliki {{ $overdueLoans->count() }} peminjaman yang sudah melewati batas waktu pengembalian.</p>
                                <div class="mt-3 space-y-2">
                                    @foreach($overdueLoans as $loan)
                                    <div class="bg-white rounded-lg p-3 border border-red-100">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-gray-800">Pengajuan: {{ $loan->created_at->format('d M Y') }}</span>
                                            <span class="text-xs text-red-600 font-semibold">Seharusnya: {{ $loan->tanggal_estimasi_kembali->format('d M Y') }}</span>
                                        </div>
                                        <ul class="mt-2 text-xs text-gray-600 list-disc list-inside">
                                            @foreach($loan->items->take(3) as $item)
                                            <li>{{ $item->nama_alat }} ({{ $item->pivot->jumlah }} {{ $item->satuan }})</li>
                                            @endforeach
                                            @if($loan->items->count() > 3)
                                            <li class="text-gray-500">+{{ $loan->items->count() - 3 }} item lainnya</li>
                                            @endif
                                        </ul>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    {{-- ============================================== --}}
                    {{-- Active Loans Card --}}
                    {{-- ============================================== --}}
                    @if(isset($activeLoans) && $activeLoans->isNotEmpty())
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm" data-aos="fade-up" data-aos-once="true">
                        <div class="px-6 py-5 border-b border-gray-100">
                            <h2 class="text-lg font-semibold text-gray-900">📦 Item yang Sedang Dipinjam</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            @foreach($activeLoans as $loan)
                            <div class="p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors border border-gray-100">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="font-semibold text-gray-800">Pengajuan: {{ $loan->created_at->format('d M Y') }}</span>
                                    <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded-full font-medium">
                                        Kembali: {{ $loan->tanggal_estimasi_kembali->format('d M Y') }}
                                    </span>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($loan->items as $item)
                                    <span class="inline-flex items-center gap-1 text-xs bg-white border border-gray-200 rounded-full px-3 py-1">
                                        <i class="fas fa-flask text-gray-400"></i>
                                        {{ $item->nama_alat }} <span class="text-gray-400">({{ $item->pivot->jumlah }})</span>
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    {{-- ============================================== --}}
                    {{-- Two Column Layout: Schedule & History --}}
                    {{-- ============================================== --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Upcoming Schedule --}}
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                            <div class="px-6 py-5 border-b border-gray-100">
                                <h2 class="text-lg font-semibold text-gray-900">📅 Jadwal Mendatang</h2>
                            </div>
                            <div class="p-6">
                                @if($nextBooking)
                                <div class="flex items-start gap-4 p-4 rounded-lg bg-blue-50 border border-blue-100">
                                    <div class="flex-shrink-0 flex flex-col items-center justify-center w-14 h-14 rounded-lg bg-blue-600 text-white">
                                        <span class="text-xs font-medium uppercase">{{ $nextBooking->waktu_mulai->format('M') }}</span>
                                        <span class="text-xl font-bold">{{ $nextBooking->waktu_mulai->format('d') }}</span>
                                    </div>
                                    <div class="flex-grow">
                                        <p class="font-semibold text-gray-900">{{ $nextBooking->tujuan_kegiatan }}</p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-clock text-gray-400 mr-1"></i>
                                            {{ $nextBooking->waktu_mulai->format('H:i') }} - {{ $nextBooking->waktu_selesai->format('H:i') }}
                                        </p>
                                        <a href="{{ route('bookings.show', $nextBooking->id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                            Lihat Detail →
                                        </a>
                                    </div>
                                </div>
                                @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                        <i class="fas fa-calendar-times text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-gray-500 text-sm">Tidak ada jadwal booking mendatang.</p>
                                    <a href="{{ route('bookings.create') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                        Buat Booking Baru →
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Loan History --}}
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                            <div class="px-6 py-5 border-b border-gray-100">
                                <h2 class="text-lg font-semibold text-gray-900">📋 Riwayat Peminjaman</h2>
                            </div>
                            <div class="p-6">
                                <div class="space-y-3 max-h-80 overflow-y-auto">
                                    @forelse ($recentUserLoans as $loan)
                                    <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                                <i class="fas fa-flask text-green-600"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow min-w-0">
                                            <p class="text-sm font-medium text-gray-900">Peminjaman Alat</p>
                                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($loan->tanggal_pinjam ?? $loan->created_at)->format('d M Y') }}</p>
                                        </div>
                                        <div class="flex-shrink-0 text-right">
                                            @php $s = $loan->status; @endphp
                                            @if($s == 'pending')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full">Menunggu</span>
                                            @elseif($s == 'approved')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">Disetujui</span>
                                            @elseif($s == 'rejected')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">Ditolak</span>
                                            @elseif($s == 'completed')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full">Selesai</span>
                                            @elseif($s == 'Terlambat')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">Terlambat</span>
                                            @endif
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-center py-8">
                                        <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                            <i class="fas fa-inbox text-2xl text-gray-400"></i>
                                        </div>
                                        <p class="text-gray-500 text-sm">Belum ada riwayat peminjaman.</p>
                                        <a href="{{ route('loans.create') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                            Ajukan Peminjaman →
                                        </a>
                                    </div>
                                    @endforelse
                                </div>
                                
                                @if(($recentUserLoans ?? collect())->count() > 0)
                                <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                                    <a href="{{ route('loans.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                        Lihat Semua Riwayat →
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    {{-- ============================================== --}}
                    {{-- Status Summary Section --}}
                    {{-- ============================================== --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm" data-aos="fade-up" data-aos-delay="300" data-aos-once="true">
                        <div class="px-6 py-5 border-b border-gray-100">
                            <h2 class="text-lg font-semibold text-gray-900">📊 Ringkasan Status</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Loan Stats --}}
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                                        <i class="fas fa-flask text-green-600"></i> Status Peminjaman
                                    </h3>
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm text-gray-600">Total Peminjaman</span>
                                            <span class="text-lg font-bold text-gray-900">{{ $userLoanStats['total'] ?? 0 }}</span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                                                    <span class="text-xs text-gray-600">Menunggu</span>
                                                </div>
                                                <span class="text-sm font-bold text-yellow-700">{{ $userLoanStats['pending'] ?? 0 }}</span>
                                            </div>
                                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                                    <span class="text-xs text-gray-600">Disetujui</span>
                                                </div>
                                                <span class="text-sm font-bold text-green-700">{{ $userLoanStats['approved'] ?? 0 }}</span>
                                            </div>
                                            <div class="flex items-center justify-between p-3 bg-gray-100 rounded-lg border border-gray-200">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-2 h-2 rounded-full bg-gray-500"></div>
                                                    <span class="text-xs text-gray-600">Selesai</span>
                                                </div>
                                                <span class="text-sm font-bold text-gray-700">{{ $userLoanStats['completed'] ?? 0 }}</span>
                                            </div>
                                            @if(($userLoanStats['overdue'] ?? 0) > 0)
                                            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-100">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                                    <span class="text-xs text-gray-600">Terlambat</span>
                                                </div>
                                                <span class="text-sm font-bold text-red-700">{{ $userLoanStats['overdue'] ?? 0 }}</span>
                                            </div>
                                            @else
                                            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-100">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-2 h-2 rounded-full bg-red-400"></div>
                                                    <span class="text-xs text-gray-600">Ditolak</span>
                                                </div>
                                                <span class="text-sm font-bold text-red-600">{{ $userLoanStats['rejected'] ?? 0 }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Booking Stats --}}
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                                        <i class="fas fa-calendar-alt text-blue-600"></i> Status Booking Lab
                                    </h3>
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm text-gray-600">Total Booking</span>
                                            <span class="text-lg font-bold text-gray-900">{{ $userBookingStats['total'] ?? 0 }}</span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                                                    <span class="text-xs text-gray-600">Menunggu</span>
                                                </div>
                                                <span class="text-sm font-bold text-yellow-700">{{ $userBookingStats['pending'] ?? 0 }}</span>
                                            </div>
                                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-100">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                                    <span class="text-xs text-gray-600">Disetujui</span>
                                                </div>
                                                <span class="text-sm font-bold text-blue-700">{{ $userBookingStats['approved'] ?? 0 }}</span>
                                            </div>
                                            <div class="flex items-center justify-between p-3 bg-gray-100 rounded-lg border border-gray-200">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-2 h-2 rounded-full bg-gray-500"></div>
                                                    <span class="text-xs text-gray-600">Selesai</span>
                                                </div>
                                                <span class="text-sm font-bold text-gray-700">{{ $userBookingStats['completed'] ?? 0 }}</span>
                                            </div>
                                            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-100">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-2 h-2 rounded-full bg-red-400"></div>
                                                    <span class="text-xs text-gray-600">Ditolak</span>
                                                </div>
                                                <span class="text-sm font-bold text-red-600">{{ $userBookingStats['rejected'] ?? 0 }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Upcoming Week Indicator --}}
                            @if(($upcomingUserBookingsCount ?? 0) > 0)
                            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-blue-100 rounded-lg">
                                        <i class="fas fa-calendar-week text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-blue-800">
                                            {{ $upcomingUserBookingsCount }} booking dalam 7 hari ke depan
                                        </p>
                                        <a href="{{ route('calendar.index') }}" class="text-xs text-blue-600 hover:text-blue-800">Lihat Kalender →</a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    {{-- ============================================== --}}
                    {{-- Recent Documents Section --}}
                    {{-- ============================================== --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm" data-aos="fade-up" data-aos-delay="400" data-aos-once="true">
                        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">📄 Dokumen Terbaru</h2>
                            <a href="{{ route('documents.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                Lihat Semua →
                            </a>
                        </div>
                        <div class="p-6">
                            @if(isset($recentDocuments) && $recentDocuments->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($recentDocuments as $doc)
                                <div class="group p-4 rounded-lg border border-gray-100 hover:border-blue-200 hover:bg-blue-50/50 transition-colors cursor-pointer"
                                     onclick="openModal('{{ Storage::url($doc->file_path) }}', '{{ $doc->title }}')">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 p-2 rounded-lg 
                                            @if(Str::endsWith($doc->file_path, '.pdf')) bg-red-100
                                            @elseif(Str::endsWith($doc->file_path, ['.doc', '.docx'])) bg-blue-100
                                            @elseif(Str::endsWith($doc->file_path, ['.xls', '.xlsx'])) bg-green-100
                                            @elseif(Str::endsWith($doc->file_path, ['.ppt', '.pptx'])) bg-orange-100
                                            @else bg-gray-100 @endif">
                                            @if(Str::endsWith($doc->file_path, '.pdf'))
                                            <i class="fas fa-file-pdf text-red-600"></i>
                                            @elseif(Str::endsWith($doc->file_path, ['.doc', '.docx']))
                                            <i class="fas fa-file-word text-blue-600"></i>
                                            @elseif(Str::endsWith($doc->file_path, ['.xls', '.xlsx']))
                                            <i class="fas fa-file-excel text-green-600"></i>
                                            @elseif(Str::endsWith($doc->file_path, ['.ppt', '.pptx']))
                                            <i class="fas fa-file-powerpoint text-orange-600"></i>
                                            @else
                                            <i class="fas fa-file text-gray-600"></i>
                                            @endif
                                        </div>
                                        <div class="flex-grow min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate group-hover:text-blue-700">
                                                {{ $doc->title }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $doc->created_at->diffForHumans() }}
                                            </p>
                                            @if($doc->description)
                                            <p class="text-xs text-gray-400 mt-1 truncate">
                                                {{ Str::limit($doc->description, 50) }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                    <i class="fas fa-file-alt text-2xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-500 text-sm">Belum ada dokumen yang tersedia.</p>
                                <a href="{{ route('documents.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                    Jelajahi Dokumen →
                                </a>
                            </div>
                            @endif
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
