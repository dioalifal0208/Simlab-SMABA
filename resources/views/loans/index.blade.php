<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight leading-tight">
                    @if (auth()->user()->role == 'admin')
                        {{ __('loans.title_admin') }}
                    @else
                        {{ __('loans.title_user') }}
                    @endif
                </h2>
                <p class="text-sm font-medium text-slate-500 mt-1">
                    @if (auth()->user()->role == 'admin')
                        {{ __('loans.subtitle_admin') }}
                    @else
                        {{ __('loans.subtitle_user') }}
                    @endif
                </p>
            </div>
            
            @unless (auth()->user()->role == 'admin')
                <a href="{{ route('loans.create') }}" class="mt-4 sm:mt-0 px-5 py-2.5 bg-green-600 text-white rounded-xl shadow-[0_4px_14px_0_rgb(34,197,94,0.39)] hover:shadow-[0_6px_20px_rgba(34,197,94,0.23)] hover:-translate-y-0.5 transition-all font-bold text-sm flex items-center gap-2">
                    <i class="fas fa-plus"></i> {{ __('loans.actions.create_new') }}
                </a>
            @endunless
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-100 p-4 rounded-xl shadow-sm flex items-center gap-3" role="alert">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0"><i class="fas fa-check-circle"></i></div>
                    <p class="font-bold text-emerald-800 text-sm">{{ __('common.messages.success') }}: <span class="font-normal text-emerald-700">{{ session('success') }}</span></p>
                </div>
            @endif

            {{-- 1. SUMMARY CARDS (Dynamic Count) --}}
            @php
                $baseQuery = \App\Models\Loan::query();
                if (auth()->user()->role !== 'admin') {
                    $baseQuery->where('user_id', auth()->id());
                }
                if (auth()->user()->role === 'guru' && auth()->user()->laboratorium) {
                    $baseQuery->where('laboratorium', auth()->user()->laboratorium);
                }
                if (request('laboratorium')) {
                    $baseQuery->where('laboratorium', request('laboratorium'));
                }
                
                $totalPending = (clone $baseQuery)->where('status', 'pending')->count();
                $totalApproved = (clone $baseQuery)->where('status', 'approved')->count();
                $totalRejected = (clone $baseQuery)->where('status', 'rejected')->count();
                $totalAll = (clone $baseQuery)->count();
                $currentStatus = request('status', 'all');
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8" data-aos="fade-up">
                {{-- Card: Total All --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between group">
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Total</p>
                        <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalAll }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-slate-100 transition-colors">
                        <i class="fas fa-layer-group text-slate-400 text-xl"></i>
                    </div>
                </div>
                {{-- Card: Pending --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between group">
                    <div>
                        <p class="text-[11px] font-extrabold text-amber-600 uppercase tracking-widest mb-1">Pending</p>
                        <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalPending }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-slate-100 transition-colors">
                        <i class="fas fa-clock text-amber-500 text-xl"></i>
                    </div>
                </div>
                {{-- Card: Approved --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between group">
                    <div>
                        <p class="text-[11px] font-extrabold text-emerald-600 uppercase tracking-widest mb-1">Disetujui</p>
                        <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalApproved }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-slate-100 transition-colors">
                        <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
                    </div>
                </div>
                {{-- Card: Rejected --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between group">
                    <div>
                        <p class="text-[11px] font-extrabold text-red-600 uppercase tracking-widest mb-1">Ditolak</p>
                        <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalRejected }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-slate-100 transition-colors">
                        <i class="fas fa-times-circle text-red-500 text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- 2. TABS & FILTERS --}}
            <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4" data-aos="fade-up" data-aos-delay="100">
                {{-- Tabs Filter --}}
                <div class="flex overflow-x-auto hide-scrollbar space-x-2 bg-slate-100/50 p-1.5 rounded-xl border border-slate-200">
                    <a href="{{ route('loans.index', array_merge(request()->query(), ['status' => null])) }}" 
                       class="px-5 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-all duration-200 
                       @if(!$currentStatus || $currentStatus == 'all') bg-white text-slate-800 shadow-sm @else text-slate-500 hover:text-slate-700 hover:bg-slate-100 @endif">
                        Semua Status
                    </a>
                    <a href="{{ route('loans.index', array_merge(request()->query(), ['status' => 'pending'])) }}" 
                       class="px-5 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-all duration-200 
                       @if($currentStatus == 'pending') bg-white text-slate-800 shadow-sm @else text-slate-500 hover:text-slate-700 hover:bg-slate-100 @endif">
                        Pending
                    </a>
                    <a href="{{ route('loans.index', array_merge(request()->query(), ['status' => 'approved'])) }}" 
                       class="px-5 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-all duration-200 
                       @if($currentStatus == 'approved') bg-white text-slate-800 shadow-sm @else text-slate-500 hover:text-slate-700 hover:bg-slate-100 @endif">
                        Disetujui
                    </a>
                    <a href="{{ route('loans.index', array_merge(request()->query(), ['status' => 'rejected'])) }}" 
                       class="px-5 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-all duration-200 
                       @if($currentStatus == 'rejected') bg-white text-slate-800 shadow-sm @else text-slate-500 hover:text-slate-700 hover:bg-slate-100 @endif">
                        Ditolak
                    </a>
                    <a href="{{ route('loans.index', array_merge(request()->query(), ['status' => 'completed'])) }}" 
                       class="px-5 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-all duration-200 
                       @if($currentStatus == 'completed') bg-white text-slate-800 shadow-sm @else text-slate-500 hover:text-slate-700 hover:bg-slate-100 @endif">
                        Selesai
                    </a>
                </div>

                {{-- Lab Filter Form --}}
                <form action="{{ route('loans.index') }}" method="GET" id="filter-form" class="w-full md:w-auto flex flex-col items-end gap-1">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <div class="relative bg-white rounded-xl shadow-sm border border-slate-200 flex items-center {{ auth()->user()->role === 'guru' ? 'bg-slate-50' : '' }}">
                        <div class="pl-3 py-2 pointer-events-none">
                            <i class="fas fa-flask text-slate-400"></i>
                        </div>
                        <select name="laboratorium" id="laboratorium" onchange="this.form.submit()" class="pl-2 pr-10 py-2.5 w-full md:w-48 bg-transparent border-none focus:ring-0 text-sm font-semibold text-slate-700 cursor-pointer appearance-none" {{ auth()->user()->role === 'guru' ? 'disabled' : '' }}>
                            @if(auth()->user()->role === 'admin')
                                <option value="">Semua Laboratorium</option>
                            @endif
                            <option value="Biologi" @selected(request('laboratorium', auth()->user()->laboratorium) === 'Biologi')>Biologi</option>
                            <option value="Fisika" @selected(request('laboratorium', auth()->user()->laboratorium) === 'Fisika')>Fisika</option>
                            <option value="Bahasa" @selected(request('laboratorium', auth()->user()->laboratorium) === 'Bahasa')>Bahasa</option>
                            <option value="Komputer 1" @selected(request('laboratorium', auth()->user()->laboratorium) === 'Komputer 1')>Komputer 1</option>
                            <option value="Komputer 2" @selected(request('laboratorium', auth()->user()->laboratorium) === 'Komputer 2')>Komputer 2</option>
                            <option value="Komputer 3" @selected(request('laboratorium', auth()->user()->laboratorium) === 'Komputer 3')>Komputer 3</option>
                            <option value="Komputer 4" @selected(request('laboratorium', auth()->user()->laboratorium) === 'Komputer 4')>Komputer 4</option>
                        </select>
                        <div class="absolute right-3 pointer-events-none">
                            <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                        </div>
                    </div>
                    @if(auth()->user()->role === 'guru')
                        <p class="text-[10px] font-bold text-slate-400 flex items-center gap-1 mt-1 mr-1">
                            <i class="fas fa-lock text-[9px]"></i> {{ __('items.filters.locked_lab') }}
                        </p>
                    @endif
                </form>
            </div>

            {{-- 3. DAFTAR PEMINJAMAN (CARD-BASED SAAS LIST) --}}
            <div class="space-y-4" data-aos="fade-up" data-aos-delay="200">
                @forelse ($loans as $loan)
                    @php
                        $statusColors = [
                            'pending' => ['bg' => 'bg-amber-100/80', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'icon' => 'fa-clock'],
                            'approved' => ['bg' => 'bg-emerald-100/80', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'icon' => 'fa-check-circle'],
                            'rejected' => ['bg' => 'bg-red-100/80', 'text' => 'text-red-700', 'border' => 'border-red-200', 'icon' => 'fa-times-circle'],
                            'completed' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'border' => 'border-slate-300', 'icon' => 'fa-box-check'],
                            'Terlambat' => ['bg' => 'bg-rose-100/80', 'text' => 'text-rose-700', 'border' => 'border-rose-200', 'icon' => 'fa-exclamation-triangle'],
                        ];

                        $cfg = $statusColors[$loan->status] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'border' => 'border-slate-200', 'icon' => 'fa-info-circle'];
                        
                        $isOverdue = $loan->status === 'Terlambat';
                    @endphp
                    
                    <a href="{{ route('loans.show', $loan->id) }}" class="block w-full bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-green-300 transition-all duration-300 hover:-translate-y-1 outline-none group overflow-hidden relative">
                        <div class="p-5 sm:p-6 flex flex-col md:flex-row gap-6 md:items-center justify-between">
                            
                            {{-- PANEL KIRI: PEMINJAM & TANGGAL --}}
                            <div class="flex items-center gap-4 md:w-1/3 xl:w-2/5 shrink-0 border-b md:border-b-0 md:border-r border-slate-100 pb-4 md:pb-0">
                                @if (auth()->user()->role == 'admin')
                                    <div class="w-12 h-12 rounded-full {{ $isOverdue ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-slate-50 text-slate-500 border-slate-100' }} border flex items-center justify-center font-black shadow-sm flex-shrink-0 text-lg group-hover:scale-105 transition-transform">
                                        {{ substr($loan->user->name, 0, 1) }}
                                    </div>
                                @else
                                     <div class="w-12 h-12 rounded-full bg-green-50 text-green-600 border border-green-100 flex items-center justify-center font-black shadow-sm flex-shrink-0 text-lg group-hover:scale-105 transition-transform">
                                        <i class="fas fa-box-open"></i>
                                    </div>
                                @endif
                                <div>
                                    <h4 class="text-base font-black text-slate-800 tracking-tight group-hover:text-green-700 transition-colors {{ auth()->user()->role !== 'admin' ? 'hidden' : '' }}">
                                        {{ $loan->user->name ?? 'User' }}
                                    </h4>
                                    <div class="text-xs font-bold text-slate-500 flex items-center gap-1.5 mt-0.5">
                                        <i class="fas fa-calendar-alt text-slate-400"></i> {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y') }}
                                    </div>
                                    <div class="text-[10px] font-black text-slate-400 mt-1.5 uppercase tracking-widest inline-flex items-center bg-slate-50 px-2.5 py-1 rounded-md border border-slate-200 tracking-wider">
                                        <i class="fas fa-flask mr-1.5 text-slate-400"></i> {{ $loan->laboratorium }}
                                    </div>
                                </div>
                            </div>

                            {{-- PANEL TENGAH: RINGKASAN ITEM --}}
                            <div class="flex-grow flex flex-col justify-center min-w-0 pr-0 md:pr-4">
                                <div class="inline-flex items-center gap-3">
                                    <div class="px-3 md:px-4 py-2 border border-slate-200 rounded-xl text-xs font-black shadow-sm flex items-center gap-2 whitespace-nowrap {{ $loan->status === 'approved' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-slate-50 text-slate-600' }}">
                                        <i class="fas fa-boxes opacity-50"></i> 
                                        <span>{{ $loan->items->count() }} Item Diajukan</span>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        Sys-ID: #{{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                                @if($loan->keterangan)
                                <p class="text-xs text-slate-500 font-medium mt-3 truncate max-w-[280px]">
                                    <span class="font-bold">Ket:</span> {{ $loan->keterangan }}
                                </p>
                                @endif
                            </div>

                            {{-- PANEL KANAN: STATUS & AKSI --}}
                            <div class="flex flex-row md:flex-col items-center md:items-end justify-between md:justify-center gap-4 shrink-0 md:w-[180px] pt-4 md:pt-0 border-t md:border-t-0 border-slate-100">
                                
                                {{-- Status Badge --}}
                                <span class="inline-flex items-center px-3.5 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $cfg['bg'] }} {{ $cfg['text'] }} {{ $cfg['border'] }} border shadow-sm {{ $isOverdue ? 'animate-pulse' : '' }}">
                                    <i class="fas {{ $cfg['icon'] }} mr-1.5 text-[11px]"></i> 
                                    {{ match($loan->status) {
                                        'pending' => 'Pending',
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                        'completed' => 'Selesai',
                                        'Terlambat' => 'Terlambat',
                                        default => ucfirst($loan->status)
                                    } }}
                                </span>

                                {{-- Admin Actions & Secondary Drilldown --}}
                                <div class="flex items-center gap-2" onclick="event.stopPropagation();">
                                    @if(auth()->user()->role == 'admin' && $loan->status == 'pending')
                                        <form action="{{ route('loans.update', $loan->id) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white border border-emerald-200 transition-all flex items-center justify-center shadow-sm" title="Setujui">
                                                <i class="fas fa-check text-sm"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('loans.update', $loan->id) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white border border-red-200 transition-all flex items-center justify-center shadow-sm" title="Tolak">
                                                <i class="fas fa-times text-sm"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-slate-400 group-hover:bg-green-100 group-hover:text-green-600 border border-transparent group-hover:border-green-200 transition-all shadow-sm">
                                        <i class="fas fa-chevron-right text-xs"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </a>
                @empty
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-16 text-center">
                        <div class="w-20 h-20 mx-auto rounded-full bg-slate-50 border border-dashed border-slate-300 flex items-center justify-center mb-5 relative group">
                            <i class="fas fa-clipboard-list text-3xl text-slate-300 group-hover:text-slate-400 transition-colors"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-1 tracking-tight">{{ __('loans.empty.title') }}</h3>
                        <p class="text-sm text-slate-500 mb-6 max-w-sm mx-auto">{{ __('loans.empty.description') }}</p>
                        @unless (auth()->user()->role == 'admin')
                            <a href="{{ route('loans.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white text-sm font-bold rounded-xl shadow-[0_4px_14px_0_rgb(34,197,94,0.39)] hover:shadow-[0_6px_20px_rgba(34,197,94,0.23)] hover:bg-green-700 hover:-translate-y-0.5 transition-all">
                                <i class="fas fa-plus"></i> {{ __('loans.empty.action') }}
                            </a>
                        @endunless
                    </div>
                @endforelse
                
                @if ($loans->hasPages())
                <div class="pt-6">
                    {{ $loans->withQueryString()->links() }}
                </div>
                
                {{-- Pagination Override Styles --}}
                <style>
                    nav[role="navigation"] p.text-sm.text-gray-700 { font-size: 13px !important; color: #64748b !important; font-weight: 500; }
                    nav[role="navigation"] p.text-sm.text-gray-700 span.font-medium { font-weight: 700; color: #1e293b; }
                    nav[role="navigation"] span.relative.z-0.inline-flex { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border-radius: 0.5rem; }
                    nav[role="navigation"] span.relative.z-0.inline-flex > span > span, nav[role="navigation"] span.relative.z-0.inline-flex > a { padding: 0.5rem 0.75rem !important; font-size: 0.875rem !important; font-weight: 600 !important; border-color: #f1f5f9 !important; transition: all 0.2s; }
                    nav[role="navigation"] span[aria-current="page"] > span { background-color: #16a34a !important; color: white !important; border-color: #16a34a !important; box-shadow: 0 4px 6px -1px rgba(22, 163, 74, 0.2), 0 2px 4px -1px rgba(22, 163, 74, 0.1); z-index: 10; }
                    nav[role="navigation"] span.relative.z-0.inline-flex > a:hover { background-color: #f8fafc !important; color: #1e293b !important; }
                    nav[role="navigation"] > div.flex.justify-between.flex-1 { display: none; }
                </style>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
