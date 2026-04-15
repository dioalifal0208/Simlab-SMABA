<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-900 tracking-tight leading-tight">{{ __('common.nav.dashboard') }}</h2>
        
        {{-- Product Tour CSS & JS --}}
        <link rel="stylesheet" href="{{ asset('css/dashboard-tour.css') }}?v={{ time() }}">
        <script src="{{ asset('js/dashboard-tour.js') }}?v={{ time() }}" defer></script>
    </x-slot>

    <div class="py-8 sm:py-12 font-sans bg-slate-50 min-h-screen">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-8 bg-green-50 border-l-4 border-green-600 p-4 rounded-r-xl shadow-sm" role="alert">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                        <div>
                            <p class="font-bold text-slate-900">{{ __('common.messages.success') }}</p>
                            <p class="text-slate-600 text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- =================================== --}}
            {{-- ======= TAMPILAN UNTUK ADMIN ======= --}}
            {{-- =================================== --}}
            @if (auth()->user()->role === 'admin')
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    {{-- LEFT MAIN SECTION (65%) --}}
                    <div class="lg:col-span-8 space-y-8">
                        
                        {{-- Header --}}
                        <div data-aos="fade-up" data-aos-once="true">
                            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Dashboard Admin</h1>
                            <p class="mt-2 text-slate-500 text-base">Pantau seluruh aktivitas laboratorium Anda dalam satu dasbor pintar.</p>
                        </div>

                        {{-- STATS CARDS (2x2 Grid for Left Layout) --}}
                        <div id="tour-stats" class="grid grid-cols-1 sm:grid-cols-2 gap-5" data-aos="fade-up" data-aos-delay="50" data-aos-once="true">
                            <!-- Card 1 -->
                            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-5 transition-all hover:shadow-md hover:border-green-300 group">
                                <div class="w-14 h-14 bg-green-50 text-green-600 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-boxes-stacked text-2xl"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-slate-500 mb-1 tracking-wide uppercase">Total Inventaris</p>
                                    <p class="text-3xl font-extrabold text-slate-900 leading-none">{{ number_format($totalItemsCount ?? 0) }}</p>
                                </div>
                            </div>
                            <!-- Card 2 -->
                            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-5 transition-all hover:shadow-md hover:border-green-300 group">
                                <div class="w-14 h-14 bg-green-50 text-green-600 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-users text-2xl"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-slate-500 mb-1 tracking-wide uppercase">Total Pengguna</p>
                                    <p class="text-3xl font-extrabold text-slate-900 leading-none">{{ number_format($totalUsersCount ?? 0) }}</p>
                                </div>
                            </div>
                            <!-- Card 3 -->
                            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-5 transition-all hover:shadow-md hover:border-green-300 group">
                                <div class="w-14 h-14 bg-green-50 text-green-600 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-right-left text-2xl"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <p class="text-sm font-semibold text-slate-500 mb-1 tracking-wide uppercase">Transaksi Bulan Ini</p>
                                        <span class="text-xs font-bold text-green-600 bg-green-100 rounded-md px-2 py-0.5"><i class="fas fa-arrow-up mr-1 text-[10px]"></i>Aktif</span>
                                    </div>
                                    <p class="text-3xl font-extrabold text-slate-900 leading-none">{{ number_format($monthlyTransactionsCount ?? 0) }}</p>
                                </div>
                            </div>
                            <!-- Card 4 -->
                            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-5 transition-all hover:shadow-md hover:border-red-300 group">
                                <div class="w-14 h-14 bg-red-50 text-red-600 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-triangle-exclamation text-2xl"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-slate-500 mb-1 tracking-wide uppercase">Alat Rusak</p>
                                    <p class="text-3xl font-extrabold text-slate-900 leading-none">{{ number_format($brokenItemsCount ?? 0) }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- ACTIVITY FEED TIMELINE --}}
                        <div id="tour-activity" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="100" data-aos-once="true"
                             x-data="{ 
                                currentPage: 1, perPage: 6,
                                totalItems: {{ ($recentActivities ?? collect())->count() }},
                                get totalPages() { return Math.ceil(this.totalItems / this.perPage) },
                                get startIndex() { return (this.currentPage - 1) * this.perPage },
                                get endIndex() { return this.currentPage * this.perPage },
                                isVisible(index) { return index >= this.startIndex && index < this.endIndex }
                             }">
                            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                                <h2 class="text-lg font-bold text-slate-900">Aktivitas Terbaru</h2>
                                <span class="text-xs font-bold text-slate-500 bg-white px-3 py-1 rounded-full border border-slate-200 shadow-sm" x-show="totalPages > 1">
                                    Hal <span x-text="currentPage"></span> / <span x-text="totalPages"></span>
                                </span>
                            </div>
                            <div class="p-6">
                                @if(($recentActivities ?? collect())->count() > 0)
                                <div class="relative pl-4 border-l-2 border-slate-100 space-y-8">
                                    @foreach (($recentActivities ?? collect())->values() as $index => $activity)
                                    <div class="relative" style="display: {{ $index < 6 ? 'block' : 'none' }};" x-show="isVisible({{ $index }})" x-transition.opacity>
                                        {{-- Timeline dot --}}
                                        <div class="absolute -left-[25px] top-1 w-4 h-4 rounded-full bg-slate-200 border-4 border-white shadow-sm ring-1 ring-slate-100"></div>
                                        
                                        <div class="flex items-start gap-4 hover:bg-slate-50 p-3 -mt-3 -ml-2 rounded-xl transition-colors border border-transparent hover:border-slate-100">
                                            <div class="flex-shrink-0 mt-1">
                                                @if ($activity instanceof \App\Models\Loan)
                                                <div class="w-9 h-9 rounded-full bg-green-50 flex items-center justify-center text-green-600 border border-green-100"><i class="fas fa-handshake text-sm"></i></div>
                                                @elseif ($activity instanceof \App\Models\Booking)
                                                <div class="w-9 h-9 rounded-full bg-purple-50 flex items-center justify-center text-purple-600 border border-purple-100"><i class="fas fa-calendar-alt text-sm"></i></div>
                                                @elseif ($activity instanceof \App\Models\AuditLog)
                                                    @if ($activity->action === 'created')
                                                    <div class="w-9 h-9 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100"><i class="fas fa-plus text-sm"></i></div>
                                                    @elseif ($activity->action === 'updated')
                                                    <div class="w-9 h-9 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-600 border border-yellow-100"><i class="fas fa-edit text-sm"></i></div>
                                                    @elseif ($activity->action === 'deleted')
                                                    <div class="w-9 h-9 rounded-full bg-red-50 flex items-center justify-center text-red-600 border border-red-100"><i class="fas fa-trash text-sm"></i></div>
                                                    @else
                                                    <div class="w-9 h-9 rounded-full bg-slate-50 flex items-center justify-center text-slate-500 border border-slate-200"><i class="fas fa-info text-sm"></i></div>
                                                    @endif
                                                @else
                                                <div class="w-9 h-9 rounded-full bg-slate-50 flex items-center justify-center text-slate-500 border border-slate-200"><i class="fas fa-info text-sm"></i></div>
                                                @endif
                                            </div>
                                            <div class="flex-grow min-w-0">
                                                <p class="text-sm text-slate-900 font-medium leading-relaxed">
                                                    <span class="font-bold cursor-pointer hover:underline">{{ $activity->user->name ?? __('dashboard.activity.system') }}</span>
                                                    <span class="text-slate-600">
                                                        @if ($activity instanceof \App\Models\Loan)
                                                            {{ __('dashboard.activity.loan_created') }}
                                                        @elseif ($activity instanceof \App\Models\Booking)
                                                            {{ __('dashboard.activity.booking_created', ['purpose' => Str::limit($activity->tujuan_kegiatan, 40)]) }}
                                                        @elseif ($activity instanceof \App\Models\AuditLog)
                                                            {{ strtolower($activity->getActionLabel()) }} {{ $activity->getModelName() }}
                                                        @endif
                                                    </span>
                                                </p>
                                                <div class="flex items-center gap-3 mt-1.5 text-[11px] font-semibold text-slate-400">
                                                    <span class="flex items-center gap-1"><i class="far fa-clock"></i> {{ $activity->created_at->diffForHumans() }}</span>
                                                    <span>&bull;</span>
                                                    <span>{{ $activity->created_at->format('d M Y, H:i') }}</span>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0 pl-2">
                                                @if ($activity instanceof \App\Models\Loan)
                                                <a href="{{ route('loans.show', $activity->id) }}" class="text-xs font-bold text-green-600 bg-green-50 hover:bg-green-100 hover:text-green-700 px-3 py-1.5 rounded-lg transition-colors">Lihat</a>
                                                @elseif ($activity instanceof \App\Models\Booking)
                                                <a href="{{ route('bookings.show', $activity->id) }}" class="text-xs font-bold text-green-600 bg-green-50 hover:bg-green-100 hover:text-green-700 px-3 py-1.5 rounded-lg transition-colors">Lihat</a>
                                                @elseif ($activity instanceof \App\Models\AuditLog)
                                                <a href="{{ route('audit-logs.show', $activity->id) }}" class="text-xs font-bold text-green-600 bg-green-50 hover:bg-green-100 hover:text-green-700 px-3 py-1.5 rounded-lg transition-colors">Lihat</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="text-center py-12">
                                    <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-50 flex items-center justify-center mb-4 border border-slate-100 shadow-sm">
                                        <i class="fas fa-inbox text-2xl text-slate-300"></i>
                                    </div>
                                    <p class="text-slate-900 font-bold text-lg mb-1">{{ __('dashboard.activity.no_activity') }}</p>
                                    <p class="text-slate-500 text-sm">Belum ada aktivitas tercatat di sistem.</p>
                                </div>
                                @endif

                                @if(($recentActivities ?? collect())->count() > 6)
                                <div class="mt-8 flex justify-between items-center">
                                    <button @click="currentPage--" :disabled="currentPage === 1" class="text-xs font-bold text-slate-600 hover:text-slate-900 border border-slate-200 bg-white hover:bg-slate-50 rounded-lg disabled:opacity-30 transition px-4 py-2 shadow-sm"><i class="fas fa-chevron-left mr-2"></i> Prev</button>
                                    <button @click="currentPage++" :disabled="currentPage === totalPages" class="text-xs font-bold text-slate-600 hover:text-slate-900 border border-slate-200 bg-white hover:bg-slate-50 rounded-lg disabled:opacity-30 transition px-4 py-2 shadow-sm">Next <i class="fas fa-chevron-right ml-2"></i></button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT PANEL SECTION (35%) --}}
                    <div class="lg:col-span-4 space-y-6">
                        
                        {{-- QUICK ACTIONS --}}
                        <div id="tour-quick-actions" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5" data-aos="fade-up" data-aos-delay="150" data-aos-once="true">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 mb-4 border-b border-slate-100 pb-3 flex items-center gap-2">
                                <i class="fas fa-bolt text-yellow-500"></i> Tindakan Cepat
                            </h3>
                            <div class="flex flex-col gap-3">
                                <a href="{{ route('items.create') }}" class="flex items-center gap-3 px-4 py-3 bg-green-600 text-white text-sm font-semibold rounded-xl shadow-sm hover:bg-green-700 hover:shadow-md transition-all group">
                                    <div class="bg-white/20 p-1.5 rounded-lg group-hover:scale-110 transition-transform"><i class="fas fa-plus"></i></div> 
                                    Tambah Alat Baru
                                </a>
                                <a href="{{ route('loans.index', ['status' => 'pending']) }}" class="flex items-center gap-3 px-4 py-3 bg-white text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-50 border border-slate-200 shadow-sm transition-all group">
                                    <div class="bg-slate-100 text-slate-500 p-1.5 rounded-lg group-hover:text-green-600 transition-colors"><i class="fas fa-handshake"></i></div>
                                    Proses Peminjaman
                                </a>
                                <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-4 py-3 bg-white text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-50 border border-slate-200 shadow-sm transition-all group">
                                    <div class="bg-slate-100 text-slate-500 p-1.5 rounded-lg group-hover:text-green-600 transition-colors"><i class="fas fa-chart-pie"></i></div>
                                    Lihat Laporan
                                </a>
                            </div>
                        </div>

                        {{-- ALERTS (Low Stock / Overdue) --}}
                        @if((isset($lowStockItems) && $lowStockItems->isNotEmpty()) || ($overdueLoansCount ?? 0) > 0)
                        <div class="space-y-4" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                            @if(isset($lowStockItems) && $lowStockItems->isNotEmpty())
                            <div class="bg-white rounded-2xl p-4 border border-slate-200 border-l-4 border-l-yellow-400 shadow-sm flex items-start gap-3 relative overflow-hidden">
                                <div class="absolute -right-3 -top-3 opacity-10 text-yellow-500 text-5xl"><i class="fas fa-box-open"></i></div>
                                <div class="p-2 bg-yellow-50 text-yellow-600 rounded-lg shrink-0 relative z-10">
                                    <i class="fas fa-triangle-exclamation text-sm"></i>
                                </div>
                                <div class="relative z-10 w-full pr-2">
                                    <h3 class="font-bold text-slate-900 text-sm">{{ __('dashboard.cards.low_stock_title', ['count' => $lowStockItems->count()]) }}</h3>
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ __('dashboard.cards.low_stock_desc', ['count' => $lowStockItems->count()]) }}</p>
                                    <a href="{{ route('items.index') }}" class="text-xs font-bold text-yellow-700 hover:text-yellow-800 mt-2 inline-flex items-center border border-yellow-200 bg-yellow-50 px-2 py-1 rounded w-max">Tinjau <i class="fas fa-arrow-right ml-1 text-[10px]"></i></a>
                                </div>
                            </div>
                            @endif

                            @if(($overdueLoansCount ?? 0) > 0)
                            <div class="bg-white rounded-2xl p-4 border border-slate-200 border-l-4 border-l-red-500 shadow-sm flex items-start gap-3 relative overflow-hidden">
                                <div class="absolute -right-3 -top-3 opacity-10 text-red-500 text-5xl"><i class="fas fa-clock"></i></div>
                                <div class="p-2 bg-red-50 text-red-600 rounded-lg shrink-0 relative z-10">
                                    <i class="fas fa-bell text-sm"></i>
                                </div>
                                <div class="relative z-10 w-full pr-2">
                                    <h3 class="font-bold text-slate-900 text-sm">{{ __('dashboard.cards.overdue_loans_title') }}</h3>
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ __('dashboard.cards.overdue_loans_desc', ['count' => $overdueLoansCount]) }}</p>
                                    <a href="{{ route('loans.index', ['status' => 'Terlambat']) }}" class="text-xs font-bold text-red-700 hover:text-red-800 mt-2 inline-flex items-center border border-red-200 bg-red-50 px-2 py-1 rounded w-max">Aksi <i class="fas fa-arrow-right ml-1 text-[10px]"></i></a>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif

                        {{-- STATUS CARDS (Vertical list format) --}}
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="250" data-aos-once="true">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 px-5 pt-5 pb-3 border-b border-slate-100 flex items-center gap-2">
                                <i class="fas fa-server text-indigo-500"></i> Status Sistem
                            </h3>
                            <div class="flex flex-col divide-y divide-slate-100">
                                <a href="{{ route('loans.index', ['status' => 'pending']) }}" class="flex items-center justify-between p-4 hover:bg-slate-50 transition-colors group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-500 flex items-center justify-center shrink-0"><i class="fas fa-hourglass-half text-sm"></i></div>
                                        <span class="text-sm font-semibold text-slate-700">Peminjaman Tertunda</span>
                                    </div>
                                    <span class="text-sm font-extrabold text-slate-900 group-hover:text-yellow-600 transition">{{ $pendingLoansCount ?? 0 }}</span>
                                </a>
                                <a href="{{ route('bookings.index', ['status' => 'pending']) }}" class="flex items-center justify-between p-4 hover:bg-slate-50 transition-colors group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-500 flex items-center justify-center shrink-0"><i class="fas fa-calendar-alt text-sm"></i></div>
                                        <span class="text-sm font-semibold text-slate-700">Booking Tertunda</span>
                                    </div>
                                    <span class="text-sm font-extrabold text-slate-900 group-hover:text-purple-600 transition">{{ $pendingBookingsCount ?? 0 }}</span>
                                </a>
                                <a href="{{ route('damage-reports.index', ['status' => 'Dilaporkan']) }}" class="flex items-center justify-between p-4 hover:bg-slate-50 transition-colors group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center shrink-0"><i class="fas fa-triangle-exclamation text-sm"></i></div>
                                        <span class="text-sm font-semibold text-slate-700">Laporan Kerusakan</span>
                                    </div>
                                    <span class="text-sm font-extrabold text-slate-900 group-hover:text-red-600 transition">{{ $newDamageReportsCount ?? 0 }}</span>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            
            @else
                {{-- ======================================= --}}
                {{-- ======= TAMPILAN SISWA / GURU ======= --}}
                {{-- ======================================= --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    {{-- LEFT MAIN SECTION (65%) --}}
                    <div class="lg:col-span-8 space-y-8">
                        {{-- Header --}}
                        <div data-aos="fade-up" data-aos-once="true">
                            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Selamat Datang, {{ auth()->user()->name }} 👋</h1>
                            <p class="mt-2 text-slate-500 text-base">Pantau jadwal praktikum dan kelola peminjaman Anda di sini.</p>
                        </div>

                        {{-- Stats Row --}}
                        <div id="tour-stats" class="grid grid-cols-1 sm:grid-cols-3 gap-5" data-aos="fade-up" data-aos-delay="50" data-aos-once="true">
                            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4 transition-all hover:shadow-md group">
                                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-flask text-xl"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[11px] font-bold text-slate-500 tracking-wider uppercase truncate">Peminjaman Aktif</p>
                                    <p class="text-2xl font-black text-slate-900 leading-none mt-1">{{ ($activeLoans ?? collect())->count() }}</p>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4 transition-all hover:shadow-md group">
                                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-calendar-check text-xl"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[11px] font-bold text-slate-500 tracking-wider uppercase truncate">Booking Mendatang</p>
                                    <p class="text-2xl font-black text-slate-900 leading-none mt-1">{{ $nextBooking ? 1 : 0 }}</p>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4 transition-all hover:shadow-md group">
                                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-clock-rotate-left text-xl"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[11px] font-bold text-slate-500 tracking-wider uppercase truncate">Total Riwayat</p>
                                    <p class="text-2xl font-black text-slate-900 leading-none mt-1">{{ ($recentUserLoans ?? collect())->count() }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Timetable / History List --}}
                        <div id="tour-activity" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-[500px]" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 flex items-center gap-2">
                                    <i class="fas fa-clock-rotate-left text-green-600"></i> Riwayat Peminjaman Anda
                                </h2>
                            </div>
                            <div class="p-6 flex-grow overflow-y-auto custom-scrollbar">
                                <div class="space-y-4">
                                    @forelse ($recentUserLoans as $loan)
                                    <div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 hover:bg-slate-50 hover:border-slate-200 hover:shadow-sm transition-all group">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-full bg-slate-50 text-slate-600 flex items-center justify-center text-sm border border-slate-200 group-hover:bg-green-600 group-hover:text-white group-hover:border-green-600 transition-colors">
                                                <i class="fas fa-flask"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-900">Peminjaman Peralatan</p>
                                                <p class="text-xs font-semibold text-slate-500 mt-1"><i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($loan->tanggal_pinjam ?? $loan->created_at)->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end gap-2">
                                            @php $s = $loan->status; @endphp
                                            @if($s == 'pending')
                                            <span class="px-2.5 py-1 text-[10px] uppercase tracking-widest font-black text-yellow-700 bg-yellow-50 border border-yellow-200 rounded">Menunggu</span>
                                            @elseif($s == 'approved')
                                            <span class="px-2.5 py-1 text-[10px] uppercase tracking-widest font-black text-green-700 bg-green-50 border border-green-200 rounded">Disetujui</span>
                                            @elseif($s == 'rejected')
                                            <span class="px-2.5 py-1 text-[10px] uppercase tracking-widest font-black text-red-700 bg-red-50 border border-red-200 rounded">Ditolak</span>
                                            @elseif($s == 'completed')
                                            <span class="px-2.5 py-1 text-[10px] uppercase tracking-widest font-black text-slate-700 bg-slate-100 border border-slate-200 rounded">Selesai</span>
                                            @elseif($s == 'Terlambat')
                                            <span class="px-2.5 py-1 text-[10px] uppercase tracking-widest font-black text-red-700 bg-red-50 border border-red-300 rounded shadow-sm">Terlambat</span>
                                            @endif
                                            <a href="{{ route('loans.show', $loan->id) }}" class="text-[11px] font-bold text-green-600 hover:text-green-800 opacity-0 group-hover:opacity-100 transition-opacity">Detail <i class="fas fa-chevron-right text-[9px]"></i></a>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-center py-10 mt-10">
                                        <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-50 flex items-center justify-center mb-4 border border-slate-100"><i class="fas fa-folder-open text-2xl text-slate-300"></i></div>
                                        <p class="text-slate-900 font-bold text-lg mb-1">Belum ada riwayat</p>
                                        <p class="text-slate-500 text-sm">Anda belum melakukan peminjaman apapun.</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT PANEL SECTION (35%) --}}
                    <div class="lg:col-span-4 space-y-6">
                        
                        {{-- QUICK ACTIONS --}}
                        <div id="tour-quick-actions" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5" data-aos="fade-up" data-aos-delay="150" data-aos-once="true">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 mb-4 border-b border-slate-100 pb-3 flex items-center gap-2">
                                <i class="fas fa-paper-plane text-blue-500"></i> Aksi Cepat
                            </h3>
                            <div class="flex flex-col gap-3">
                                <a href="{{ route('loans.create') }}" class="flex items-center justify-between px-4 py-3.5 bg-green-600 text-white text-sm font-bold rounded-xl shadow-sm hover:bg-green-700 hover:-translate-y-0.5 hover:shadow-md transition-all group">
                                    <span class="flex items-center gap-3"><i class="fas fa-hand-holding-hand pl-1 opacity-80"></i> Ajukan Peminjaman</span>
                                    <i class="fas fa-arrow-right opacity-50 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
                                </a>
                                <a href="{{ route('bookings.index', ['create' => 'true']) }}" class="flex items-center justify-between px-4 py-3.5 bg-white text-slate-700 text-sm font-bold rounded-xl shadow-sm border border-slate-200 hover:bg-slate-50 hover:-translate-y-0.5 hover:border-slate-300 transition-all group">
                                    <span class="flex items-center gap-3"><i class="fas fa-calendar-plus text-slate-400 pl-1 group-hover:text-green-600 transition-colors"></i> Booking Ruangan</span>
                                    <i class="fas fa-arrow-right text-slate-300 group-hover:text-green-600 group-hover:translate-x-1 transition-all"></i>
                                </a>
                                <a href="{{ route('item-requests.create') }}" class="flex items-center justify-between px-4 py-3.5 bg-white text-slate-700 text-sm font-bold rounded-xl shadow-sm border border-slate-200 hover:bg-slate-50 hover:-translate-y-0.5 hover:border-slate-300 transition-all group">
                                    <span class="flex items-center gap-3"><i class="fas fa-comment-dots text-slate-400 pl-1 group-hover:text-green-600 transition-colors"></i> Request Item Baru</span>
                                    <i class="fas fa-arrow-right text-slate-300 group-hover:text-green-600 group-hover:translate-x-1 transition-all"></i>
                                </a>
                            </div>
                        </div>

                        {{-- NEXT SCHEDULE CARD --}}
                        <div class="bg-gradient-to-br from-green-600 to-emerald-700 rounded-2xl border border-green-700 shadow-md text-white overflow-hidden relative" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                            <div class="absolute right-0 top-0 opacity-10 transform scale-150 translate-x-4 -translate-y-4">
                                <i class="fas fa-calendar-day text-8xl"></i>
                            </div>
                            <div class="px-5 pt-5 pb-4 border-b border-green-500/30 relative z-10">
                                <h3 class="text-xs font-extrabold uppercase tracking-widest text-green-100">Jadwal Terdekat</h3>
                            </div>
                            <div class="p-6 relative z-10">
                                @if($nextBooking)
                                <div class="flex gap-4">
                                    <div class="bg-white/20 backdrop-blur-md rounded-xl p-3 flex flex-col items-center justify-center shrink-0 border border-white/20 min-w-[70px]">
                                        <span class="text-xs font-bold uppercase text-green-100">{{ $nextBooking->waktu_mulai->format('M') }}</span>
                                        <span class="text-3xl font-black leading-none mt-1 shadow-sm">{{ $nextBooking->waktu_mulai->format('d') }}</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-white leading-snug line-clamp-2">{{ $nextBooking->tujuan_kegiatan }}</p>
                                        <p class="text-sm text-green-100 mt-2 font-medium flex items-center gap-2"><i class="far fa-clock"></i> {{ $nextBooking->waktu_mulai->format('H:i') }} - {{ $nextBooking->waktu_selesai->format('H:i') }}</p>
                                    </div>
                                </div>
                                <div class="mt-5 text-right">
                                    <a href="{{ route('bookings.show', $nextBooking->id) }}" class="inline-block text-xs font-bold bg-white text-green-700 px-4 py-2 rounded-lg hover:bg-green-50 transition shadow-sm">Buka Detail</a>
                                </div>
                                @else
                                <div class="text-center py-4">
                                    <p class="text-green-50 font-medium text-sm">Tidak ada jadwal praktikum dalam waktu dekat.</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- ACTIVE LOANS SUMMARY --}}
                        @if(isset($activeLoans) && $activeLoans->isNotEmpty())
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="250" data-aos-once="true">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 px-5 pt-5 pb-3 border-b border-slate-100 flex items-center gap-2">
                                <i class="fas fa-box-open text-orange-500"></i> Tanggungan Alat
                            </h3>
                            <div class="p-5 space-y-4">
                                @foreach($activeLoans as $loan)
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 relative group">
                                    <span class="absolute -top-2.5 -right-2 bg-orange-100 text-orange-700 text-[9px] font-black tracking-widest uppercase px-2 py-1 rounded shadow-sm border border-orange-200 transform rotate-2 group-hover:rotate-0 transition">Dipinjam</span>
                                    <p class="font-bold text-slate-800 text-sm mb-2 leading-snug">{{ $loan->items->pluck('nama_alat')->join(', ') }}</p>
                                    <div class="flex items-center justify-between mt-3">
                                        <span class="text-[11px] font-semibold text-slate-500">Dikembalikan pada:</span>
                                        <span class="text-xs font-black text-slate-700">{{ $loan->tanggal_estimasi_kembali->format('d M Y') }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Pratinjau Dokumen --}}
    <div id="documentModal" class="hidden fixed inset-0 z-[100] bg-slate-900/60 backdrop-blur-sm items-center justify-center transition-all duration-300">
        <div class="bg-white w-11/12 max-w-5xl rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="documentModalContent">
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-white">
                <h3 id="modalTitle" class="text-lg font-bold text-slate-900">Pratinjau Dokumen</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors focus:outline-none"><i class="fas fa-xmark text-xl"></i></button>
            </div>
            <div class="p-6 bg-slate-50">
                <iframe id="documentFrame" class="w-full h-[75vh] border-0 rounded-xl shadow-inner bg-white" src=""></iframe>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; border: 1px solid #f8fafc; }
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
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('documentModal');
            const content = document.getElementById('documentModalContent');
            if (!modal) return;
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
