<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight leading-tight">{{ __('common.nav.dashboard') }}</h2>
        
        {{-- Product Tour CSS & JS --}}
        <link rel="stylesheet" href="{{ asset('css/dashboard-tour.css') }}?v={{ time() }}">
        <script src="{{ asset('js/dashboard-tour.js') }}?v={{ time() }}" defer></script>
    </x-slot>

    <div class="py-16 sm:py-20 font-sans bg-white">
        <div class="max-w-7xl mx-auto px-6">

            @if (session('success'))
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-600 p-4 rounded-r-xl shadow-sm" role="alert">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-blue-600 text-lg"></i>
                        <div>
                            <p class="font-bold text-gray-900">{{ __('common.messages.success') }}</p>
                            <p class="text-gray-600 text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- =================================== --}}
            {{-- ======= TAMPILAN UNTUK ADMIN ======= --}}
            {{-- =================================== --}}
            @if (auth()->user()->role === 'admin')
                <div class="space-y-12">
                    
                    {{-- 1. SIMPLE HEADER --}}
                    <div data-aos="fade-up" data-aos-once="true">
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                            Dashboard
                        </h1>
                        <p class="mt-2 text-gray-600 text-base">
                            Pantau seluruh aktivitas laboratorium Anda dalam satu dasbor pintar.
                        </p>
                    </div>

                    {{-- 2. STATS CARDS (4 equal cards) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Card 1 -->
                        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm flex flex-col justify-center gap-4 transition-all hover:shadow-md" data-aos="fade-up" data-aos-delay="50" data-aos-once="true">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center shrink-0">
                                    <i class="fas fa-boxes-stacked text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Total Inventaris</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalItemsCount ?? 0) }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card 2 -->
                        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm flex flex-col justify-center gap-4 transition-all hover:shadow-md" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center shrink-0">
                                    <i class="fas fa-users text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Total Pengguna</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalUsersCount ?? 0) }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card 3 -->
                        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm flex flex-col justify-center gap-4 transition-all hover:shadow-md" data-aos="fade-up" data-aos-delay="150" data-aos-once="true">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center shrink-0">
                                    <i class="fas fa-right-left text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Transaksi Bulan Ini</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($monthlyTransactionsCount ?? 0) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4 -->
                        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm flex flex-col justify-center gap-4 transition-all hover:shadow-md" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center shrink-0">
                                    <i class="fas fa-triangle-exclamation text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Alat Rusak</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($brokenItemsCount ?? 0) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. QUICK ACTIONS --}}
                    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between" data-aos="fade-up" data-aos-once="true">
                        <h2 class="text-xl font-bold text-gray-900">Tindakan Cepat</h2>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('items.create') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-blue-700 transition">
                                <i class="fas fa-plus"></i> Tambah Alat
                            </a>
                            <a href="{{ route('loans.index', ['status' => 'pending']) }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 border border-gray-200 shadow-sm transition">
                                <i class="fas fa-handshake text-gray-400"></i> Proses Peminjaman
                            </a>
                            <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 border border-gray-200 shadow-sm transition">
                                <i class="fas fa-chart-pie text-gray-400"></i> Lihat Laporan
                            </a>
                        </div>
                    </div>

                    {{-- Alerts Section (Low Stock / Overdue) --}}
                    @if((isset($lowStockItems) && $lowStockItems->isNotEmpty()) || ($overdueLoansCount ?? 0) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" data-aos="fade-up" data-aos-once="true">
                        @if(isset($lowStockItems) && $lowStockItems->isNotEmpty())
                        <div class="bg-white rounded-xl p-5 border border-gray-200 border-l-4 border-l-yellow-400 shadow-sm hover:shadow-md transition-all">
                            <div class="flex items-start gap-4">
                                <div class="p-2.5 bg-yellow-50 rounded-lg shrink-0">
                                    <i class="fas fa-box-open text-xl text-yellow-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">{{ __('dashboard.cards.low_stock_title', ['count' => $lowStockItems->count()]) }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ __('dashboard.cards.low_stock_desc', ['count' => $lowStockItems->count()]) }}</p>
                                    <a href="{{ route('items.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 mt-2 inline-flex items-center gap-1">{{ __('common.buttons.view_details') }} <i class="fas fa-arrow-right text-[10px]"></i></a>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if(($overdueLoansCount ?? 0) > 0)
                        <div class="bg-white rounded-xl p-5 border border-gray-200 border-l-4 border-l-red-500 shadow-sm hover:shadow-md transition-all">
                            <div class="flex items-start gap-4">
                                <div class="p-2.5 bg-red-50 rounded-lg shrink-0">
                                    <i class="fas fa-clock text-xl text-red-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">{{ __('dashboard.cards.overdue_loans_title') }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ __('dashboard.cards.overdue_loans_desc', ['count' => $overdueLoansCount]) }}</p>
                                    <a href="{{ route('loans.index', ['status' => 'Terlambat']) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 mt-2 inline-flex items-center gap-1">{{ __('dashboard.cards.take_action') }} <i class="fas fa-arrow-right text-[10px]"></i></a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- 4. STATUS SUMMARY CARDS --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" data-aos="fade-up" data-aos-once="true">
                        {{-- Pending Loans --}}
                        <a href="{{ route('loans.index', ['status' => 'pending']) }}" class="bg-white rounded-xl p-5 border border-gray-200 border-l-4 border-l-yellow-400 shadow-sm hover:shadow-md transition-shadow group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Peminjaman Tertunda</p>
                                    <p class="text-gray-900 text-2xl font-bold group-hover:text-yellow-600 transition">{{ $pendingLoansCount ?? 0 }}</p>
                                </div>
                                <div class="w-10 h-10 flex items-center justify-center bg-gray-50 rounded-lg text-gray-400 group-hover:text-yellow-500 group-hover:bg-yellow-50 transition">
                                    <i class="fas fa-hourglass-half"></i>
                                </div>
                            </div>
                        </a>
                        
                        {{-- Pending Bookings --}}
                        <a href="{{ route('bookings.index', ['status' => 'pending']) }}" class="bg-white rounded-xl p-5 border border-gray-200 border-l-4 border-l-purple-500 shadow-sm hover:shadow-md transition-shadow group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Booking Tertunda</p>
                                    <p class="text-gray-900 text-2xl font-bold group-hover:text-purple-600 transition">{{ $pendingBookingsCount ?? 0 }}</p>
                                </div>
                                <div class="w-10 h-10 flex items-center justify-center bg-gray-50 rounded-lg text-gray-400 group-hover:text-purple-500 group-hover:bg-purple-50 transition">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                        </a>
                        
                        {{-- Damage Reports --}}
                        <a href="{{ route('damage-reports.index', ['status' => 'Dilaporkan']) }}" class="bg-white rounded-xl p-5 border border-gray-200 border-l-4 border-l-red-500 shadow-sm hover:shadow-md transition-shadow group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Laporan Kerusakan</p>
                                    <p class="text-gray-900 text-2xl font-bold group-hover:text-red-600 transition">{{ $newDamageReportsCount ?? 0 }}</p>
                                </div>
                                <div class="w-10 h-10 flex items-center justify-center bg-gray-50 rounded-lg text-gray-400 group-hover:text-red-500 group-hover:bg-red-50 transition">
                                    <i class="fas fa-triangle-exclamation"></i>
                                </div>
                            </div>
                        </a>
                        
                        {{-- This Week Schedule --}}
                        <a href="{{ route('calendar.index') }}" class="bg-white rounded-xl p-5 border border-gray-200 border-l-4 border-l-green-500 shadow-sm hover:shadow-md transition-shadow group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Jadwal Minggu Ini</p>
                                    <p class="text-gray-900 text-2xl font-bold group-hover:text-green-600 transition">{{ $upcomingBookingsCount ?? 0 }}</p>
                                </div>
                                <div class="w-10 h-10 flex items-center justify-center bg-gray-50 rounded-lg text-gray-400 group-hover:text-green-500 group-hover:bg-green-50 transition">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    {{-- 5. ACTIVITY FEED --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm" data-aos="fade-up" data-aos-once="true"
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
                            <h2 class="text-lg font-bold text-gray-900">Aktivitas Terbaru</h2>
                            <span class="text-xs font-medium text-gray-500 bg-gray-50 px-3 py-1 rounded-full border border-gray-200" x-show="totalPages > 1">
                                Halaman <span x-text="currentPage"></span> / <span x-text="totalPages"></span>
                            </span>
                        </div>
                        <div class="p-6">
                            @if(($recentActivities ?? collect())->count() > 0)
                            <div class="space-y-6 lg:space-y-4">
                                @foreach (($recentActivities ?? collect())->values() as $index => $activity)
                                <div class="flex items-start gap-4 p-4 lg:p-3 rounded-xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100"
                                     style="display: {{ $index < 5 ? 'flex' : 'none' }};"
                                     x-show="isVisible({{ $index }})" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 translate-x-4"
                                     x-transition:enter-end="opacity-100 translate-x-0">
                                     
                                     <div class="flex-shrink-0">
                                        @if ($activity instanceof \App\Models\Loan)
                                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100">
                                            <i class="fas fa-handshake"></i>
                                        </div>
                                        @elseif ($activity instanceof \App\Models\Booking)
                                        <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center text-purple-600 border border-purple-100">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        @elseif ($activity instanceof \App\Models\AuditLog)
                                            @if ($activity->action === 'created')
                                            <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600 border border-green-100">
                                                <i class="fas fa-plus"></i>
                                            </div>
                                            @elseif ($activity->action === 'updated')
                                            <div class="w-10 h-10 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-600 border border-yellow-100">
                                                <i class="fas fa-edit"></i>
                                            </div>
                                            @elseif ($activity->action === 'deleted')
                                            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600 border border-red-100">
                                                <i class="fas fa-trash"></i>
                                            </div>
                                            @elseif ($activity->action === 'login')
                                            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 border border-gray-200">
                                                <i class="fas fa-sign-in-alt"></i>
                                            </div>
                                            @else
                                            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 border border-gray-200">
                                                <i class="fas fa-clock-rotate-left"></i>
                                            </div>
                                            @endif
                                        @else
                                        <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 border border-gray-200">
                                            <i class="fas fa-info"></i>
                                        </div>
                                        @endif
                                     </div>
                                     
                                     <div class="flex-grow min-w-0 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                         <div>
                                            <p class="text-sm text-gray-900 font-medium">
                                                <span class="font-bold">{{ $activity->user->name ?? __('dashboard.activity.system') }}</span>
                                                <span class="text-gray-500 text-sm font-normal ml-1">
                                                    @if ($activity instanceof \App\Models\Loan)
                                                        {{ __('dashboard.activity.loan_created') }}
                                                    @elseif ($activity instanceof \App\Models\Booking)
                                                        {{ __('dashboard.activity.booking_created', ['purpose' => Str::limit($activity->tujuan_kegiatan, 40)]) }}
                                                    @elseif ($activity instanceof \App\Models\AuditLog)
                                                        {{ strtolower($activity->getActionLabel()) }} {{ $activity->getModelName() }}
                                                    @endif
                                                </span>
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1"><i class="far fa-clock mr-1"></i>{{ $activity->created_at->format('d M Y, H:i') }} ({{ $activity->created_at->diffForHumans() }})</p>
                                         </div>
                                         <div class="flex-shrink-0">
                                            @if ($activity instanceof \App\Models\Loan)
                                            <a href="{{ route('loans.show', $activity->id) }}" class="px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition shadow-sm inline-block">Detail</a>
                                            @elseif ($activity instanceof \App\Models\Booking)
                                            <a href="{{ route('bookings.show', $activity->id) }}" class="px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition shadow-sm inline-block">Detail</a>
                                            @elseif ($activity instanceof \App\Models\AuditLog)
                                            <a href="{{ route('audit-logs.show', $activity->id) }}" class="px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition shadow-sm inline-block">Detail</a>
                                            @endif
                                         </div>
                                     </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-10">
                                <div class="w-14 h-14 mx-auto rounded-full bg-gray-50 flex items-center justify-center mb-4 border border-gray-100">
                                    <i class="fas fa-inbox text-xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-900 font-bold mb-1">{{ __('dashboard.activity.no_activity') }}</p>
                                <p class="text-gray-500 text-sm">Belum ada aktivitas tercatat di sistem.</p>
                            </div>
                            @endif

                            {{-- Pagination Controls --}}
                            @if(($recentActivities ?? collect())->count() > 5)
                            <div class="mt-6 pt-4 border-t border-gray-100 flex justify-between items-center">
                                <button @click="currentPage--" :disabled="currentPage === 1" class="text-sm font-semibold text-gray-600 hover:text-gray-900 border border-gray-200 bg-white hover:bg-gray-50 rounded-lg disabled:opacity-50 transition px-4 py-2 shadow-sm"><i class="fas fa-chevron-left mr-2"></i> Sebelumnya</button>
                                <button @click="currentPage++" :disabled="currentPage === totalPages" class="text-sm font-semibold text-gray-600 hover:text-gray-900 border border-gray-200 bg-white hover:bg-gray-50 rounded-lg disabled:opacity-50 transition px-4 py-2 shadow-sm">Selanjutnya <i class="fas fa-chevron-right ml-2"></i></button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            
            @else
                {{-- ======================================= --}}
                {{-- ======= TAMPILAN SISWA / GURU ======= --}}
                {{-- ======================================= --}}
                <div class="space-y-12">
                    
                    {{-- Simple Header --}}
                    <div data-aos="fade-up" data-aos-once="true">
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                            Selamat Datang, {{ auth()->user()->name }} 👋
                        </h1>
                        <p class="mt-2 text-gray-600 text-base">
                            Siap untuk praktikum hari ini?
                        </p>
                    </div>

                    {{-- Quick Stats --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm flex items-center gap-4 transition-all hover:shadow-md" data-aos="fade-up" data-aos-delay="50" data-aos-once="true">
                            <div class="p-3 bg-blue-50 text-blue-600 rounded-lg shrink-0">
                                <i class="fas fa-flask text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Peminjaman Aktif</p>
                                <p class="text-2xl font-bold text-gray-900">{{ ($activeLoans ?? collect())->count() }}</p>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm flex items-center gap-4 transition-all hover:shadow-md" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                            <div class="p-3 bg-blue-50 text-blue-600 rounded-lg shrink-0">
                                <i class="fas fa-calendar-check text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Booking Mendatang</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $nextBooking ? 1 : 0 }}</p>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm flex items-center gap-4 transition-all hover:shadow-md" data-aos="fade-up" data-aos-delay="150" data-aos-once="true">
                            <div class="p-3 bg-blue-50 text-blue-600 rounded-lg shrink-0">
                                <i class="fas fa-clock-rotate-left text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Total Riwayat</p>
                                <p class="text-2xl font-bold text-gray-900">{{ ($recentUserLoans ?? collect())->count() }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between" data-aos="fade-up" data-aos-once="true">
                        <h2 class="text-xl font-bold text-gray-900">Tindakan Cepat</h2>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('loans.create') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-blue-700 transition">
                                <i class="fas fa-hand-holding-hand"></i> Ajukan Peminjaman
                            </a>
                            <a href="{{ route('bookings.index', ['create' => 'true']) }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 border border-gray-200 shadow-sm transition">
                                <i class="fas fa-calendar-plus text-gray-400"></i> Booking Lab
                            </a>
                            <a href="{{ route('item-requests.create') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 border border-gray-200 shadow-sm transition">
                                <i class="fas fa-comment-dots text-gray-400"></i> Request Item
                            </a>
                        </div>
                    </div>

                    {{-- Active Loans Card --}}
                    @if(isset($activeLoans) && $activeLoans->isNotEmpty())
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6" data-aos="fade-up" data-aos-once="true">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-gray-900 mb-4"><i class="fas fa-box-open mr-2 text-blue-500"></i>Item yang Sedang Dipinjam</h2>
                        <div class="space-y-4">
                            @foreach($activeLoans as $loan)
                            <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">Disetujui: {{ $loan->created_at->format('d M Y') }}</p>
                                    <div class="flex flex-wrap gap-2 mt-3">
                                        @foreach($loan->items as $item)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-white border border-gray-200 text-gray-700 rounded-lg px-3 py-1.5 shadow-sm">
                                            {{ $item->nama_alat }} <span class="text-gray-400 ml-1">({{ $item->pivot->jumlah }})</span>
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                                <span class="text-xs bg-white text-gray-700 px-3 py-1.5 rounded-lg font-bold border border-gray-200 shadow-sm whitespace-nowrap">
                                    <i class="far fa-calendar-check mr-1 text-blue-500"></i> Kembali: {{ $loan->tanggal_estimasi_kembali->format('d M Y') }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Two Column Layout: Schedule & History --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Upcoming Schedule --}}
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm flex flex-col h-full" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 border-t border-t-transparent rounded-t-xl">
                                <h2 class="text-sm font-bold uppercase tracking-wider text-gray-900"><i class="fas fa-calendar-day mr-2 text-blue-500"></i>Jadwal Mendatang</h2>
                            </div>
                            <div class="p-6 flex-grow flex flex-col justify-center">
                                @if($nextBooking)
                                <div class="flex items-start gap-5 p-5 rounded-xl bg-white border border-gray-200 shadow-sm">
                                    <div class="w-16 h-16 rounded-xl bg-blue-50 text-blue-600 flex flex-col items-center justify-center shrink-0 border border-blue-100">
                                        <span class="text-xs font-bold uppercase">{{ $nextBooking->waktu_mulai->format('M') }}</span>
                                        <span class="text-2xl font-extrabold">{{ $nextBooking->waktu_mulai->format('d') }}</span>
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <p class="font-bold text-gray-900 text-base truncate">{{ $nextBooking->tujuan_kegiatan }}</p>
                                        <p class="text-sm text-gray-500 mt-1"><i class="fas fa-clock text-gray-400 mr-1.5"></i>{{ $nextBooking->waktu_mulai->format('H:i') }} - {{ $nextBooking->waktu_selesai->format('H:i') }}</p>
                                        <a href="{{ route('bookings.show', $nextBooking->id) }}" class="inline-block mt-3 text-sm font-semibold text-blue-600 hover:text-blue-800 transition">Lihat Detail <i class="fas fa-arrow-right text-[10px] ml-1"></i></a>
                                    </div>
                                </div>
                                @else
                                <div class="text-center py-6">
                                    <div class="w-12 h-12 mx-auto rounded-full bg-gray-50 flex items-center justify-center mb-3 text-gray-400 border border-gray-100"><i class="fas fa-calendar-xmark text-xl"></i></div>
                                    <p class="text-sm text-gray-500 font-medium">Tidak ada jadwal mendatang</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Loan History --}}
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm flex flex-col h-full" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 border-t border-t-transparent rounded-t-xl">
                                <h2 class="text-sm font-bold uppercase tracking-wider text-gray-900"><i class="fas fa-clock-rotate-left mr-2 text-blue-500"></i>Riwayat Peminjaman</h2>
                            </div>
                            <div class="p-6 flex-grow">
                                <div class="space-y-3 max-h-72 overflow-y-auto pr-2 custom-scrollbar">
                                    @forelse ($recentUserLoans as $loan)
                                    <div class="flex items-center justify-between p-3.5 rounded-xl hover:bg-gray-50 transition border border-transparent hover:border-gray-200 group">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                                <i class="fas fa-flask"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">Peminjaman Alat</p>
                                                <p class="text-[11px] font-medium text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($loan->tanggal_pinjam ?? $loan->created_at)->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            @php $s = $loan->status; @endphp
                                            @if($s == 'pending')
                                            <span class="px-2.5 py-1 text-[10px] uppercase tracking-wider font-bold text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-md">Menunggu</span>
                                            @elseif($s == 'approved')
                                            <span class="px-2.5 py-1 text-[10px] uppercase tracking-wider font-bold text-blue-700 bg-blue-50 border border-blue-200 rounded-md">Disetujui</span>
                                            @elseif($s == 'rejected')
                                            <span class="px-2.5 py-1 text-[10px] uppercase tracking-wider font-bold text-red-700 bg-red-50 border border-red-200 rounded-md">Ditolak</span>
                                            @elseif($s == 'completed')
                                            <span class="px-2.5 py-1 text-[10px] uppercase tracking-wider font-bold text-gray-700 bg-gray-50 border border-gray-200 rounded-md">Selesai</span>
                                            @elseif($s == 'Terlambat')
                                            <span class="px-2.5 py-1 text-[10px] uppercase tracking-wider font-bold text-red-700 bg-red-50 border border-red-200 rounded-md">Terlambat</span>
                                            @endif
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-center py-6">
                                        <div class="w-12 h-12 mx-auto rounded-full bg-gray-50 flex items-center justify-center mb-3 text-gray-400 border border-gray-100"><i class="fas fa-folder-open text-xl"></i></div>
                                        <p class="text-sm text-gray-500 font-medium">Belum ada riwayat</p>
                                    </div>
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
    <div id="documentModal" class="hidden fixed inset-0 z-[100] bg-gray-900/60 backdrop-blur-sm items-center justify-center transition-all duration-300">
        <div class="bg-white w-11/12 max-w-5xl rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="documentModalContent">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-white">
                <h3 id="modalTitle" class="text-lg font-bold text-gray-900">Pratinjau Dokumen</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-900 hover:bg-gray-100 p-2 rounded-lg transition-colors focus:outline-none"><i class="fas fa-xmark text-xl"></i></button>
            </div>
            <div class="p-6 bg-gray-50">
                <iframe id="documentFrame" class="w-full h-[75vh] border-0 rounded-xl shadow-inner bg-white" src=""></iframe>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #94a3b8; }
    </style>
    <script>
        function openModal(url, title) {
            const modal = document.getElementById('documentModal');
            const content = document.getElementById('documentModalContent');
            if (!modal) return;
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('documentFrame').src = url;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Animation
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('documentModal');
            const content = document.getElementById('documentModalContent');
            if (!modal) return;
            
            // Animation
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.getElementById('documentFrame').src = '';
            }, 300);
        }
    </script>
</x-app-layout>
