<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('common.nav.dashboard') }}</h2>
        
        {{-- Product Tour CSS & JS --}}
        <link rel="stylesheet" href="{{ asset('css/dashboard-tour.css') }}?v={{ time() }}">
        <script src="{{ asset('js/dashboard-tour.js') }}?v={{ time() }}" defer></script>
    </x-slot>

    <div class="py-6 sm:py-8 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 text-lg mr-3"></i>
                        <div>
                            <p class="font-bold text-green-800">{{ __('common.messages.success') }}</p>
                            <p class="text-green-700 text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- =================================== --}}
            {{-- ======= TAMPILAN UNTUK ADMIN ======= --}}
            {{-- =================================== --}}
            @if (auth()->user()->role === 'admin')
                <div class="space-y-8">
                    
                    {{-- 1. HERO / WELCOME SECTION & OVERLAPPING STATS --}}
                    <div>
                        {{-- Hero Background Container --}}
                        <div class="relative overflow-hidden bg-gradient-to-r from-emerald-600 to-green-500 rounded-3xl shadow-lg border border-green-400">
                            {{-- Decorative Background Patterns --}}
                            <div class="absolute inset-0 opacity-20">
                                <svg class="absolute right-0 bottom-0 text-white w-96 h-96 transform translate-x-1/3 translate-y-1/3" fill="currentColor" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="50"></circle>
                                </svg>
                                <svg class="absolute top-0 right-1/4 text-white w-32 h-32 transform -translate-y-1/2 opacity-50" fill="currentColor" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="50"></circle>
                                </svg>
                                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.7) 1px, transparent 0); background-size: 32px 32px; opacity: 0.3;"></div>
                            </div>
                            
                            <div class="relative px-8 py-10 pb-20 md:px-12 md:py-14 md:pb-24 z-10 flex flex-col md:flex-row justify-between items-start md:items-center">
                                <div data-aos="fade-up" data-aos-once="true">
                                    <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">
                                        Selamat datang, {{ Auth::user()->name }}! 👋
                                    </h1>
                                    <p class="mt-2 text-green-50 text-base md:text-lg max-w-xl font-medium">
                                        Pantau seluruh aktivitas laboratorium Anda dalam satu dasbor pintar.
                                    </p>
                                </div>
                                <div class="mt-6 md:mt-0 opacity-80" data-aos="fade-left" data-aos-once="true" data-aos-delay="200">
                                    <img src="{{ asset('images/logo-smaba.webp') }}" alt="Logo" class="h-20 w-20 object-contain drop-shadow-md brightness-0 invert">
                                </div>
                            </div>
                        </div>
                        
                        {{-- 2. STATISTIC CARDS (Overlapping) --}}
                        <div class="relative px-4 sm:px-6 lg:px-8 -mt-12 sm:-mt-16 z-20">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <div class="bg-white/90 backdrop-blur-md rounded-2xl p-6 border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.1)] transition-all duration-300 group" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                                    <div class="flex items-center gap-5">
                                        <div class="p-3.5 bg-green-50 rounded-xl group-hover:bg-green-500 group-hover:text-white transition-colors duration-300 shadow-sm border border-green-100 flex-shrink-0">
                                            <i class="fas fa-boxes-stacked text-2xl text-green-600 group-hover:text-white transition-colors"></i>
                                        </div>
                                        <div>
                                            <p class="text-slate-500 text-[11px] font-bold uppercase tracking-wider mb-1">Total Inventaris</p>
                                            <p class="text-slate-900 text-3xl font-extrabold tracking-tight">{{ number_format($totalItemsCount ?? 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white/90 backdrop-blur-md rounded-2xl p-6 border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.1)] transition-all duration-300 group" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                                    <div class="flex items-center gap-5">
                                        <div class="p-3.5 bg-blue-50 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300 shadow-sm border border-blue-100 flex-shrink-0">
                                            <i class="fas fa-users text-2xl text-blue-600 group-hover:text-white transition-colors"></i>
                                        </div>
                                        <div>
                                            <p class="text-slate-500 text-[11px] font-bold uppercase tracking-wider mb-1">Total Pengguna</p>
                                            <p class="text-slate-900 text-3xl font-extrabold tracking-tight">{{ number_format($totalUsersCount ?? 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white/90 backdrop-blur-md rounded-2xl p-6 border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.1)] transition-all duration-300 group" data-aos="fade-up" data-aos-delay="300" data-aos-once="true">
                                    <div class="flex items-center gap-5">
                                        <div class="p-3.5 bg-indigo-50 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300 shadow-sm border border-indigo-100 flex-shrink-0">
                                            <i class="fas fa-right-left text-2xl text-indigo-600 group-hover:text-white transition-colors"></i>
                                        </div>
                                        <div>
                                            <p class="text-slate-500 text-[11px] font-bold uppercase tracking-wider mb-1">Transaksi Bulan Ini</p>
                                            <p class="text-slate-900 text-3xl font-extrabold tracking-tight">{{ number_format($monthlyTransactionsCount ?? 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- 3. QUICK ACTIONS --}}
                    <div class="mt-4 flex flex-wrap gap-4 items-center justify-between" data-aos="fade-up" data-aos-delay="400" data-aos-once="true">
                        <h2 class="text-xl font-bold text-slate-800">Tindakan Cepat</h2>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('items.create') }}" class="group relative inline-flex items-center gap-2 px-6 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl overflow-hidden shadow-[0_4px_14px_0_rgb(34,197,94,0.39)] hover:shadow-[0_6px_20px_rgba(34,197,94,0.23)] hover:bg-green-700 hover:-translate-y-0.5 transition-all duration-200">
                                <i class="fas fa-plus relative z-10 transition-transform group-hover:rotate-90"></i>
                                <span class="relative z-10">Tambah Alat</span>
                            </a>
                            <a href="{{ route('loans.index', ['status' => 'pending']) }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-white text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-all duration-200 border border-slate-200 shadow-sm hover:shadow hover:-translate-y-0.5">
                                <i class="fas fa-handshake text-slate-400"></i> Proses Peminjaman
                            </a>
                            <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-white text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-all duration-200 border border-slate-200 shadow-sm hover:shadow hover:-translate-y-0.5">
                                <i class="fas fa-chart-pie text-slate-400"></i> Lihat Laporan
                            </a>
                        </div>
                    </div>
                    
                    {{-- Alerts Section (Low Stock / Overdue) --}}
                    @if((isset($lowStockItems) && $lowStockItems->isNotEmpty()) || ($overdueLoansCount ?? 0) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                        @if(isset($lowStockItems) && $lowStockItems->isNotEmpty())
                        <div class="bg-amber-50 rounded-2xl p-5 border border-amber-200/60 shadow-sm relative overflow-hidden group">
                            <div class="absolute right-0 top-0 -mt-4 -mr-4 w-24 h-24 bg-amber-200/30 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
                            <div class="flex items-start gap-4 relative z-10">
                                <div class="p-2.5 bg-amber-100 rounded-lg shrink-0 border border-amber-200">
                                    <i class="fas fa-triangle-exclamation text-xl text-amber-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-amber-800 tracking-tight">{{ __('dashboard.cards.low_stock_title', ['count' => $lowStockItems->count()]) }}</h3>
                                    <p class="text-sm text-amber-700 mt-1.5">{{ __('dashboard.cards.low_stock_desc', ['count' => $lowStockItems->count()]) }}</p>
                                    <a href="{{ route('items.index') }}" class="text-sm font-bold text-amber-700 hover:text-amber-900 mt-3 flex items-center gap-1 group/link">{{ __('common.buttons.view_details') }} <i class="fas fa-arrow-right text-[10px] group-hover/link:translate-x-1 transition-transform"></i></a>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if(($overdueLoansCount ?? 0) > 0)
                        <div class="bg-red-50 rounded-2xl p-5 border border-red-200/60 shadow-sm relative overflow-hidden group">
                            <div class="absolute right-0 top-0 -mt-4 -mr-4 w-24 h-24 bg-red-200/30 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
                            <div class="flex items-start gap-4 relative z-10">
                                <div class="p-2.5 bg-red-100 rounded-lg shrink-0 border border-red-200">
                                    <i class="fas fa-clock text-xl text-red-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-red-800 tracking-tight">{{ __('dashboard.cards.overdue_loans_title') }}</h3>
                                    <p class="text-sm text-red-700 mt-1.5">{{ __('dashboard.cards.overdue_loans_desc', ['count' => $overdueLoansCount]) }}</p>
                                    <a href="{{ route('loans.index', ['status' => 'Terlambat']) }}" class="text-sm font-bold text-red-700 hover:text-red-900 mt-3 flex items-center gap-1 group/link">{{ __('dashboard.cards.take_action') }} <i class="fas fa-arrow-right text-[10px] group-hover/link:translate-x-1 transition-transform"></i></a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                    
                    {{-- 4. STATUS CARDS - Color Coded --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5" data-aos="fade-up" data-aos-delay="150" data-aos-once="true">
                        {{-- Pending Loans --}}
                        <a href="{{ route('loans.index', ['status' => 'pending']) }}" class="group bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:border-yellow-200 transition-all duration-300 relative overflow-hidden">
                            <div class="absolute right-0 top-0 w-2 h-full bg-yellow-400 group-hover:w-full group-hover:opacity-5 transition-all duration-500"></div>
                            <div class="flex items-center justify-between mb-4 relative z-10">
                                <div class="w-12 h-12 flex items-center justify-center bg-yellow-50 rounded-xl text-yellow-500 border border-yellow-100 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-hourglass-half text-xl"></i>
                                </div>
                                <span class="text-3xl font-extrabold text-slate-800 group-hover:text-yellow-600 transition-colors">{{ $pendingLoansCount ?? 0 }}</span>
                            </div>
                            <div class="relative z-10">
                                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Peminjaman Tertunda</h3>
                                <p class="text-xs text-slate-500 mt-1 font-medium">Perlu Persetujuan</p>
                            </div>
                        </a>
                        
                        {{-- Pending Bookings --}}
                        <a href="{{ route('bookings.index', ['status' => 'pending']) }}" class="group bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:border-purple-200 transition-all duration-300 relative overflow-hidden">
                            <div class="absolute right-0 top-0 w-2 h-full bg-purple-400 group-hover:w-full group-hover:opacity-5 transition-all duration-500"></div>
                            <div class="flex items-center justify-between mb-4 relative z-10">
                                <div class="w-12 h-12 flex items-center justify-center bg-purple-50 rounded-xl text-purple-600 border border-purple-100 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-calendar-alt text-xl"></i>
                                </div>
                                <span class="text-3xl font-extrabold text-slate-800 group-hover:text-purple-600 transition-colors">{{ $pendingBookingsCount ?? 0 }}</span>
                            </div>
                            <div class="relative z-10">
                                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Booking Tertunda</h3>
                                <p class="text-xs text-slate-500 mt-1 font-medium">Menunggu Jadwal</p>
                            </div>
                        </a>
                        
                        {{-- Damage Reports --}}
                        <a href="{{ route('damage-reports.index', ['status' => 'Dilaporkan']) }}" class="group bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:border-orange-200 transition-all duration-300 relative overflow-hidden">
                            <div class="absolute right-0 top-0 w-2 h-full bg-orange-400 group-hover:w-full group-hover:opacity-5 transition-all duration-500"></div>
                            <div class="flex items-center justify-between mb-4 relative z-10">
                                <div class="w-12 h-12 flex items-center justify-center bg-orange-50 rounded-xl text-orange-500 border border-orange-100 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-triangle-exclamation text-xl"></i>
                                </div>
                                <span class="text-3xl font-extrabold text-slate-800 group-hover:text-orange-600 transition-colors">{{ $newDamageReportsCount ?? 0 }}</span>
                            </div>
                            <div class="relative z-10">
                                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Laporan Kerusakan</h3>
                                <p class="text-xs text-slate-500 mt-1 font-medium">Butuh Verifikasi</p>
                            </div>
                        </a>
                        
                        {{-- This Week Schedule --}}
                        <a href="{{ route('calendar.index') }}" class="group bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:border-emerald-200 transition-all duration-300 relative overflow-hidden">
                            <div class="absolute right-0 top-0 w-2 h-full bg-emerald-400 group-hover:w-full group-hover:opacity-5 transition-all duration-500"></div>
                            <div class="flex items-center justify-between mb-4 relative z-10">
                                <div class="w-12 h-12 flex items-center justify-center bg-emerald-50 rounded-xl text-emerald-600 border border-emerald-100 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-calendar-check text-xl"></i>
                                </div>
                                <span class="text-3xl font-extrabold text-slate-800 group-hover:text-emerald-600 transition-colors">{{ $upcomingBookingsCount ?? 0 }}</span>
                            </div>
                            <div class="relative z-10">
                                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Jadwal Minggu Ini</h3>
                                <p class="text-xs text-slate-500 mt-1 font-medium">Praktikum Dijadwalkan</p>
                            </div>
                        </a>
                    </div>
                    
                    {{-- 5. ACTIVITY FEED - VERTICAL TIMELINE --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)]" data-aos="fade-up" data-aos-delay="200" data-aos-once="true"
                         x-data="{ 
                            currentPage: 1, 
                            perPage: 5,
                            totalItems: {{ ($recentActivities ?? collect())->count() }},
                            get totalPages() { return Math.ceil(this.totalItems / this.perPage) },
                            get startIndex() { return (this.currentPage - 1) * this.perPage },
                            get endIndex() { return this.currentPage * this.perPage },
                            isVisible(index) { return index >= this.startIndex && index < this.endIndex }
                         }">
                        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                            <h2 class="text-lg font-bold text-slate-800">Aktivitas Terbaru</h2>
                            <span class="text-xs font-bold text-slate-400 bg-slate-50 px-3 py-1 rounded-full border border-slate-100" x-show="totalPages > 1">
                                Halaman <span x-text="currentPage"></span> / <span x-text="totalPages"></span>
                            </span>
                        </div>
                        <div class="p-6">
                            
                            @if(($recentActivities ?? collect())->count() > 0)
                            <div class="relative pl-4 space-y-6">
                                {{-- Timeline vertical line --}}
                                <div class="absolute top-4 bottom-4 left-[27px] w-0.5 bg-slate-100"></div>
                                
                                @foreach (($recentActivities ?? collect())->values() as $index => $activity)
                                <div class="relative flex items-start gap-4 group"
                                     style="display: {{ $index < 5 ? 'flex' : 'none' }};"
                                     x-show="isVisible({{ $index }})" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 translate-x-4"
                                     x-transition:enter-end="opacity-100 translate-x-0">
                                     
                                    {{-- Icon Node --}}
                                    <div class="relative z-10 flex-shrink-0 bg-white ring-8 ring-white rounded-full mt-1.5 transition-transform group-hover:scale-110">
                                        @if ($activity instanceof \App\Models\Loan)
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center border border-blue-200 shadow-sm">
                                            <i class="fas fa-handshake text-blue-600 text-[10px]"></i>
                                        </div>
                                        @elseif ($activity instanceof \App\Models\Booking)
                                        <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center border border-purple-200 shadow-sm">
                                            <i class="fas fa-calendar-alt text-purple-600 text-[10px]"></i>
                                        </div>
                                        @elseif ($activity instanceof \App\Models\AuditLog)
                                            @if ($activity->action === 'created')
                                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center border border-emerald-200 shadow-sm">
                                                <i class="fas fa-plus text-emerald-600 text-[10px]"></i>
                                            </div>
                                            @elseif ($activity->action === 'updated')
                                            <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center border border-amber-200 shadow-sm">
                                                <i class="fas fa-edit text-amber-600 text-[10px]"></i>
                                            </div>
                                            @elseif ($activity->action === 'deleted')
                                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center border border-red-200 shadow-sm">
                                                <i class="fas fa-trash text-red-600 text-[10px]"></i>
                                            </div>
                                            @elseif ($activity->action === 'login')
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center border border-indigo-200 shadow-sm">
                                                <i class="fas fa-sign-in-alt text-indigo-600 text-[10px]"></i>
                                            </div>
                                            @else
                                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center border border-slate-200 shadow-sm">
                                                <i class="fas fa-clock-rotate-left text-slate-500 text-[10px]"></i>
                                            </div>
                                            @endif
                                        @else
                                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center border border-slate-200 shadow-sm">
                                            <i class="fas fa-info text-slate-500 text-[10px]"></i>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    {{-- Content Block --}}
                                    <div class="flex-grow min-w-0 bg-slate-50/50 rounded-xl p-4 border border-slate-100 group-hover:bg-slate-50 group-hover:border-slate-200 group-hover:shadow-sm transition-all duration-200">
                                        <div class="flex justify-between items-start flex-wrap gap-2">
                                            <div>
                                                <p class="text-sm font-medium text-slate-800">
                                                    <span class="font-bold text-slate-900">{{ $activity->user->name ?? __('dashboard.activity.system') }}</span>
                                                    @if ($activity instanceof \App\Models\Loan)
                                                    {{ __('dashboard.activity.loan_created') }}
                                                    @elseif ($activity instanceof \App\Models\Booking)
                                                    {{ __('dashboard.activity.booking_created', ['purpose' => Str::limit($activity->tujuan_kegiatan, 40)]) }}
                                                    @elseif ($activity instanceof \App\Models\AuditLog)
                                                    <span class="text-slate-600">{{ strtolower($activity->getActionLabel()) }}</span> {{ $activity->getModelName() }}
                                                    @endif
                                                </p>
                                                <p class="text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider">{{ $activity->created_at->format('d M Y, H:i') }} ({{ $activity->created_at->diffForHumans() }})</p>
                                            </div>
                                            
                                            <div class="flex-shrink-0">
                                                @if ($activity instanceof \App\Models\Loan)
                                                <a href="{{ route('loans.show', $activity->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold bg-white text-green-600 border border-green-200 rounded-lg hover:bg-green-50 transition-colors shadow-sm">Lihat <i class="fas fa-arrow-right ml-1"></i></a>
                                                @elseif ($activity instanceof \App\Models\Booking)
                                                <a href="{{ route('bookings.show', $activity->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold bg-white text-purple-600 border border-purple-200 rounded-lg hover:bg-purple-50 transition-colors shadow-sm">Lihat <i class="fas fa-arrow-right ml-1"></i></a>
                                                @elseif ($activity instanceof \App\Models\AuditLog)
                                                <a href="{{ route('audit-logs.show', $activity->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold bg-white text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors shadow-sm">Detail <i class="fas fa-arrow-right ml-1"></i></a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                                <div class="text-center py-12 bg-slate-50 border border-dashed border-slate-200 rounded-2xl">
                                    <div class="w-16 h-16 mx-auto rounded-full bg-white flex items-center justify-center mb-4 shadow-sm border border-slate-100">
                                        <i class="fas fa-inbox text-2xl text-slate-300"></i>
                                    </div>
                                    <p class="text-slate-500 font-medium tracking-tight">{{ __('dashboard.activity.no_activity') }}</p>
                                    <p class="text-xs text-slate-400 mt-1">Belum ada aktivitas yang tercatat dalam sistem.</p>
                                </div>
                            @endif
                            
                            {{-- Pagination Controls --}}
                            @if(($recentActivities ?? collect())->count() > 5)
                            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                                <button @click="currentPage = Math.max(1, currentPage - 1)" 
                                        :disabled="currentPage === 1"
                                        :class="currentPage === 1 ? 'text-slate-300 cursor-not-allowed' : 'text-slate-700 hover:text-slate-900 bg-slate-50 hover:bg-slate-100'"
                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-bold transition-all border border-transparent">
                                    <i class="fas fa-chevron-left text-[10px]"></i> Sebelumnya
                                </button>
                                
                                <div class="flex items-center gap-2">
                                    <template x-for="page in totalPages" :key="page">
                                        <button @click="currentPage = page"
                                                :class="currentPage === page ? 'bg-green-600 text-white shadow-md shadow-green-600/20' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200'"
                                                class="w-8 h-8 rounded-lg text-xs font-bold transition-all"
                                                x-text="page">
                                        </button>
                                    </template>
                                </div>
                                
                                <button @click="currentPage = Math.min(totalPages, currentPage + 1)" 
                                        :disabled="currentPage === totalPages"
                                        :class="currentPage === totalPages ? 'text-slate-300 cursor-not-allowed' : 'text-slate-700 hover:text-slate-900 bg-slate-50 hover:bg-slate-100'"
                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-bold transition-all border border-transparent">
                                    Selanjutnya <i class="fas fa-chevron-right text-[10px]"></i>
                                </button>
                            </div>
                            @endif
                            
                            @if(($recentActivities ?? collect())->count() > 0)
                            <div class="mt-6 text-center">
                                <a href="{{ route('audit-logs.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-green-600 hover:text-green-700 hover:underline underline-offset-4 decoration-2">
                                    Lihat Semua Log Aktivitas <i class="fas fa-arrow-right text-[10px]"></i>
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
                    
                    {{-- Hero Section - Matching Admin Style --}}
                    <div>
                        <div class="relative overflow-hidden bg-gradient-to-r from-teal-600 to-emerald-500 rounded-3xl shadow-lg border border-teal-400">
                            {{-- Pattern --}}
                            <div class="absolute inset-0 opacity-20">
                                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.7) 1px, transparent 0); background-size: 32px 32px; opacity: 0.3;"></div>
                            </div>
                            
                            <div class="relative px-8 py-10 pb-20 md:px-12 md:py-14 md:pb-24 z-10">
                                <div class="flex items-center gap-5" data-aos="fade-up" data-aos-once="true">
                                    <div class="flex-shrink-0">
                                        <div class="h-16 w-16 md:h-20 md:w-20 rounded-2xl bg-white/20 backdrop-blur-md border border-white/40 shadow-xl flex items-center justify-center transform -rotate-6">
                                            <span class="text-2xl md:text-3xl font-extrabold text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-teal-50 text-[11px] md:text-xs font-bold uppercase tracking-widest mb-1.5 drop-shadow-sm">Selamat Datang</p>
                                        <h1 class="text-2xl md:text-4xl font-extrabold text-white leading-tight tracking-tight drop-shadow-sm">
                                            {{ auth()->user()->name }} 👋
                                        </h1>
                                        <p class="mt-1 text-teal-50 font-medium">Siap untuk praktikum hari ini?</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Quick Stats Overlapping --}}
                        <div class="relative px-4 sm:px-6 lg:px-8 -mt-12 sm:-mt-16 z-20">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <div class="bg-white/90 backdrop-blur-md rounded-2xl p-5 border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:-translate-y-1 hover:shadow-lg transition-transform" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                                    <div class="flex items-center gap-4">
                                        <div class="p-3 bg-green-50 rounded-xl border border-green-100 flex-shrink-0">
                                            <i class="fas fa-flask text-xl text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-slate-500 text-[11px] font-bold uppercase tracking-wider mb-1">Peminjaman Aktif</p>
                                            <p class="text-slate-900 text-2xl font-extrabold">{{ ($activeLoans ?? collect())->count() }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white/90 backdrop-blur-md rounded-2xl p-5 border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:-translate-y-1 hover:shadow-lg transition-transform" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                                    <div class="flex items-center gap-4">
                                        <div class="p-3 bg-blue-50 rounded-xl border border-blue-100 flex-shrink-0">
                                            <i class="fas fa-calendar-check text-xl text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-slate-500 text-[11px] font-bold uppercase tracking-wider mb-1">Booking Mendatang</p>
                                            <p class="text-slate-900 text-2xl font-extrabold">{{ $nextBooking ? 1 : 0 }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white/90 backdrop-blur-md rounded-2xl p-5 border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:-translate-y-1 hover:shadow-lg transition-transform" data-aos="fade-up" data-aos-delay="300" data-aos-once="true">
                                    <div class="flex items-center gap-4">
                                        <div class="p-3 bg-indigo-50 rounded-xl border border-indigo-100 flex-shrink-0">
                                            <i class="fas fa-clock-rotate-left text-xl text-indigo-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-slate-500 text-[11px] font-bold uppercase tracking-wider mb-1">Total Riwayat</p>
                                            <p class="text-slate-900 text-2xl font-extrabold">{{ ($recentUserLoans ?? collect())->count() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Quick Actions --}}
                    <div class="flex flex-wrap gap-4 items-center justify-between mt-4" data-aos="fade-up" data-aos-delay="400" data-aos-once="true">
                        <h2 class="text-xl font-bold text-slate-800">Tindakan Cepat</h2>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('loans.create') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl shadow-[0_4px_14px_0_rgb(16,185,129,0.39)] hover:shadow-[0_6px_20px_rgba(16,185,129,0.23)] hover:bg-emerald-700 hover:-translate-y-0.5 transition-all">
                                <i class="fas fa-hand-holding-hand"></i> Ajukan Peminjaman
                            </a>
                            <a href="{{ route('bookings.index', ['create' => 'true']) }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-white text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 border border-slate-200 shadow-sm hover:shadow hover:-translate-y-0.5 transition-all">
                                <i class="fas fa-calendar-plus text-slate-400"></i> Booking Lab
                            </a>
                            <a href="{{ route('item-requests.create') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-white text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 border border-slate-200 shadow-sm hover:shadow hover:-translate-y-0.5 transition-all">
                                <i class="fas fa-comment-dots text-slate-400"></i> Request Item
                            </a>
                        </div>
                    </div>
                    
                    {{-- Active Loans Card --}}
                    @if(isset($activeLoans) && $activeLoans->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)]" data-aos="fade-up" data-aos-once="true">
                        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-800"><i class="fas fa-box-open mr-2 text-indigo-500"></i>Item yang Sedang Dipinjam</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            @foreach($activeLoans as $loan)
                            <div class="p-5 rounded-xl bg-white border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-center mb-4 pb-3 border-b border-slate-100">
                                    <span class="font-bold text-slate-800 text-sm">Disetujui: {{ $loan->created_at->format('d M Y') }}</span>
                                    <span class="text-xs tracking-wider uppercase bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full font-bold border border-emerald-200 shadow-sm">
                                        <i class="far fa-calendar-check mr-1"></i> Kembali: {{ $loan->tanggal_estimasi_kembali->format('d M Y') }}
                                    </span>
                                </div>
                                <div class="flex flex-wrap gap-2.5">
                                    @foreach($loan->items as $item)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-slate-50 border border-slate-200 text-slate-700 rounded-lg px-3 py-1.5 shadow-sm">
                                        <i class="fas fa-flask text-indigo-400"></i>
                                        {{ $item->nama_alat }} <span class="text-slate-400 ml-1">({{ $item->pivot->jumlah }})</span>
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    {{-- Two Column Layout: Schedule & History --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Upcoming Schedule --}}
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] flex flex-col h-full" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-800"><i class="fas fa-calendar-day mr-2 text-blue-500"></i>Jadwal Mendatang</h2>
                            </div>
                            <div class="p-6 flex-grow flex flex-col justify-center">
                                @if($nextBooking)
                                <div class="flex items-start gap-5 p-5 rounded-xl bg-blue-50/50 border border-blue-100">
                                    <div class="flex-shrink-0 flex flex-col items-center justify-center w-16 h-16 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-md">
                                        <span class="text-xs font-bold uppercase">{{ $nextBooking->waktu_mulai->format('M') }}</span>
                                        <span class="text-2xl font-extrabold">{{ $nextBooking->waktu_mulai->format('d') }}</span>
                                    </div>
                                    <div class="flex-grow">
                                        <p class="font-bold text-slate-800 text-lg leading-tight">{{ $nextBooking->tujuan_kegiatan }}</p>
                                        <div class="flex items-center gap-3 mt-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                            <span class="bg-white px-2 py-1 rounded border border-slate-200"><i class="fas fa-clock text-blue-400 mr-1.5"></i>{{ $nextBooking->waktu_mulai->format('H:i') }} - {{ $nextBooking->waktu_selesai->format('H:i') }}</span>
                                        </div>
                                        <a href="{{ route('bookings.show', $nextBooking->id) }}" class="inline-block mt-4 text-sm font-bold text-blue-600 hover:text-blue-800 hover:underline transition-all">Lihat Detail <i class="fas fa-arrow-right text-[10px] ml-1"></i></a>
                                    </div>
                                </div>
                                @else
                                <div class="text-center py-10">
                                    <div class="w-16 h-16 mx-auto rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mb-4">
                                        <i class="fas fa-calendar-xmark text-2xl text-slate-300"></i>
                                    </div>
                                    <p class="text-slate-500 font-medium">Tidak ada jadwal booking mendatang.</p>
                                    <a href="{{ route('bookings.index', ['create' => 'true']) }}" class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-blue-600 bg-blue-50 px-4 py-2 rounded-lg hover:bg-blue-100 transition-colors">
                                        Buat Booking Baru <i class="fas fa-arrow-right text-[10px] ml-1"></i>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Loan History --}}
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] flex flex-col h-full" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-800"><i class="fas fa-clock-rotate-left mr-2 text-green-500"></i>Riwayat Peminjaman</h2>
                            </div>
                            <div class="p-6 flex-grow">
                                <div class="space-y-3 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
                                    @forelse ($recentUserLoans as $loan)
                                    <div class="flex items-center gap-4 p-3.5 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100 group">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center group-hover:bg-green-50 group-hover:text-green-600 transition-colors">
                                                <i class="fas fa-flask text-slate-400 group-hover:text-green-500 transition-colors"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow min-w-0">
                                            <p class="text-sm font-bold text-slate-800">Peminjaman Alat</p>
                                            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($loan->tanggal_pinjam ?? $loan->created_at)->format('d M Y') }}</p>
                                        </div>
                                        <div class="flex-shrink-0 text-right">
                                            @php $s = $loan->status; @endphp
                                            @if($s == 'pending')
                                            <span class="inline-flex items-center px-2.5 py-1 text-[10px] uppercase tracking-wider font-bold text-yellow-700 bg-yellow-100 border border-yellow-200 rounded-md">Menunggu</span>
                                            @elseif($s == 'approved')
                                            <span class="inline-flex items-center px-2.5 py-1 text-[10px] uppercase tracking-wider font-bold text-green-700 bg-green-100 border border-green-200 rounded-md">Disetujui</span>
                                            @elseif($s == 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-1 text-[10px] uppercase tracking-wider font-bold text-red-700 bg-red-100 border border-red-200 rounded-md">Ditolak</span>
                                            @elseif($s == 'completed')
                                            <span class="inline-flex items-center px-2.5 py-1 text-[10px] uppercase tracking-wider font-bold text-slate-700 bg-slate-100 border border-slate-200 rounded-md">Selesai</span>
                                            @elseif($s == 'Terlambat')
                                            <span class="inline-flex items-center px-2.5 py-1 text-[10px] uppercase tracking-wider font-bold text-red-700 bg-red-100 border border-red-200 rounded-md animate-pulse">Terlambat</span>
                                            @endif
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-center py-10">
                                        <div class="w-16 h-16 mx-auto rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mb-4">
                                            <i class="fas fa-folder-open text-2xl text-slate-300"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium text-sm text-center">Belum ada riwayat peminjaman.</p>
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
    <div id="documentModal" class="hidden fixed inset-0 z-[100] bg-slate-900/60 backdrop-blur-sm items-center justify-center transition-all duration-300">
        <div class="bg-white w-11/12 max-w-5xl rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="documentModalContent">
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 id="modalTitle" class="text-lg font-bold text-slate-800">Pratinjau Dokumen</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-700 hover:bg-slate-200 p-2 rounded-lg transition-colors focus:outline-none"><i class="fas fa-xmark text-xl"></i></button>
            </div>
            <div class="p-6 bg-slate-50">
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
