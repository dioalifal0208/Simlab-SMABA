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
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-amber-100 flex items-center justify-between group relative overflow-hidden">
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-amber-400"></div>
                    <div>
                        <p class="text-[11px] font-extrabold text-amber-600 uppercase tracking-widest mb-1">Pending</p>
                        <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalPending }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center group-hover:bg-amber-100 transition-colors">
                        <i class="fas fa-clock text-amber-500 text-xl"></i>
                    </div>
                </div>
                {{-- Card: Approved --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-emerald-100 flex items-center justify-between group relative overflow-hidden">
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-emerald-500"></div>
                    <div>
                        <p class="text-[11px] font-extrabold text-emerald-600 uppercase tracking-widest mb-1">Disetujui</p>
                        <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalApproved }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center group-hover:bg-emerald-100 transition-colors">
                        <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
                    </div>
                </div>
                {{-- Card: Rejected --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-red-100 flex items-center justify-between group relative overflow-hidden">
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-red-500"></div>
                    <div>
                        <p class="text-[11px] font-extrabold text-red-600 uppercase tracking-widest mb-1">Ditolak</p>
                        <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalRejected }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center group-hover:bg-red-100 transition-colors">
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
                       @if($currentStatus == 'pending') bg-white text-amber-600 shadow-sm @else text-slate-500 hover:text-slate-700 hover:bg-slate-100 @endif">
                        Pending
                    </a>
                    <a href="{{ route('loans.index', array_merge(request()->query(), ['status' => 'approved'])) }}" 
                       class="px-5 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-all duration-200 
                       @if($currentStatus == 'approved') bg-white text-emerald-600 shadow-sm @else text-slate-500 hover:text-slate-700 hover:bg-slate-100 @endif">
                        Disetujui
                    </a>
                    <a href="{{ route('loans.index', array_merge(request()->query(), ['status' => 'rejected'])) }}" 
                       class="px-5 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-all duration-200 
                       @if($currentStatus == 'rejected') bg-white text-red-600 shadow-sm @else text-slate-500 hover:text-slate-700 hover:bg-slate-100 @endif">
                        Ditolak
                    </a>
                    <a href="{{ route('loans.index', array_merge(request()->query(), ['status' => 'completed'])) }}" 
                       class="px-5 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-all duration-200 
                       @if($currentStatus == 'completed') bg-white text-slate-800 shadow-sm @else text-slate-500 hover:text-slate-700 hover:bg-slate-100 @endif">
                        Selesai
                    </a>
                </div>

                {{-- Lab Filter Form --}}
                <form action="{{ route('loans.index') }}" method="GET" id="filter-form" class="w-full md:w-auto flex items-center gap-2">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <div class="relative bg-white rounded-xl shadow-sm border border-slate-200 flex items-center">
                        <div class="pl-3 py-2 pointer-events-none">
                            <i class="fas fa-flask text-slate-400"></i>
                        </div>
                        <select name="laboratorium" id="laboratorium" onchange="this.form.submit()" class="pl-2 pr-10 py-2.5 w-full md:w-48 bg-transparent border-none focus:ring-0 text-sm font-semibold text-slate-700 cursor-pointer appearance-none">
                            <option value="">Semua Laboratorium</option>
                            <option value="Biologi" @selected(request('laboratorium') === 'Biologi')>Biologi</option>
                            <option value="Fisika" @selected(request('laboratorium') === 'Fisika')>Fisika</option>
                            <option value="Bahasa" @selected(request('laboratorium') === 'Bahasa')>Bahasa</option>
                            <option value="Komputer 1" @selected(request('laboratorium') === 'Komputer 1')>Komputer 1</option>
                            <option value="Komputer 2" @selected(request('laboratorium') === 'Komputer 2')>Komputer 2</option>
                            <option value="Komputer 3" @selected(request('laboratorium') === 'Komputer 3')>Komputer 3</option>
                            <option value="Komputer 4" @selected(request('laboratorium') === 'Komputer 4')>Komputer 4</option>
                        </select>
                    </div>
                </form>
            </div>

            {{-- 3. TABEL DAFTAR PEMINJAMAN --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-100" data-aos="fade-up" data-aos-delay="200">
                <div class="overflow-x-auto min-h-[300px]">
                    <table class="min-w-full text-left relative">
                        <thead class="bg-slate-50/90 backdrop-blur-md sticky top-0 z-10 shadow-sm border-b border-slate-200">
                            <tr>
                                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-widest w-16">ID</th>
                                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-widest">{{ __('loans.table.borrower') }} & Waktu</th>
                                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-widest hidden sm:table-cell text-center">Detail Item</th>
                                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">{{ __('loans.table.status') }}</th>
                                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-widest text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($loans as $loan)
                                <tr class="group hover:bg-slate-50 cursor-pointer transition-colors duration-200 even:bg-slate-50/30" 
                                    @click="if(!$event.target.closest('form') && !$event.target.closest('a') && !$event.target.closest('button')) window.location.href='{{ route('loans.show', $loan->id) }}'">
                                    
                                    <td class="py-4 px-6">
                                        <span class="text-xs font-bold text-slate-400">#{{ $loan->id }}</span>
                                    </td>
                                    
                                    <td class="py-4 px-6">
                                        <div class="flex items-start gap-4">
                                            @if (auth()->user()->role == 'admin')
                                                <div class="w-10 h-10 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-700 font-bold shadow-sm flex-shrink-0">
                                                    {{ substr($loan->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                @if (auth()->user()->role == 'admin')
                                                    <div class="text-sm font-bold text-slate-800 group-hover:text-green-700 transition-colors">{{ $loan->user->name }}</div>
                                                @endif
                                                <div class="{{ (auth()->user()->role == 'admin') ? 'text-xs text-slate-500 mt-0.5' : 'text-sm font-bold text-slate-800' }}">
                                                    <i class="fas fa-calendar-alt text-slate-400 mr-1"></i> 
                                                    {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y') }}
                                                </div>
                                                <div class="text-[11px] font-semibold text-slate-400 mt-1 uppercase tracking-wider">
                                                    <i class="fas fa-flask mr-1"></i> {{ $loan->laboratorium }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="py-4 px-6 hidden sm:table-cell text-center">
                                        <div class="inline-flex items-center px-3 py-1.5 bg-slate-100 border border-slate-200 rounded-lg text-xs font-bold text-slate-600 shadow-sm">
                                            <span>{{ $loan->items->count() }} Item</span>
                                        </div>
                                    </td>
                                    
                                    <td class="py-4 px-6 text-center">
                                        @if($loan->status == 'pending')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-lg bg-amber-50 text-amber-700 border border-amber-200 shadow-sm whitespace-nowrap">
                                                <i class="fas fa-clock mr-1.5 object-center mt-[2px]"></i> {{ __('common.status.pending') }}
                                            </span>
                                        @elseif($loan->status == 'approved')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-sm whitespace-nowrap">
                                                <i class="fas fa-check-circle mr-1.5 object-center mt-[2px]"></i> {{ __('common.status.approved') }}
                                            </span>
                                        @elseif($loan->status == 'rejected')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-lg bg-red-50 text-red-700 border border-red-200 shadow-sm whitespace-nowrap">
                                                <i class="fas fa-times-circle mr-1.5 object-center mt-[2px]"></i> {{ __('common.status.rejected') }}
                                            </span>
                                        @elseif($loan->status == 'completed')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-lg bg-slate-100 text-slate-700 border border-slate-300 shadow-sm whitespace-nowrap">
                                                <i class="fas fa-box-check mr-1.5 object-center mt-[2px]"></i> {{ __('common.status.completed') }}
                                            </span>
                                        @elseif($loan->status == 'Terlambat')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-lg bg-rose-50 text-rose-700 border border-rose-200 shadow-sm whitespace-nowrap animate-pulse">
                                                <i class="fas fa-exclamation-triangle mr-1.5 object-center mt-[2px]"></i> {{ __('loans.status.overdue') }}
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            @if (auth()->user()->role == 'admin' && $loan->status == 'pending')
                                                {{-- Quick Actions untuk Admin --}}
                                                <form action="{{ route('loans.update', $loan->id) }}" method="POST" class="inline" @click.stop>
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-emerald-200 text-emerald-600 hover:bg-emerald-50 hover:border-emerald-300 transition-all shadow-sm group/btn" title="Setujui Cepat">
                                                        <i class="fas fa-check text-xs group-hover/btn:scale-110 transition-transform"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('loans.update', $loan->id) }}" method="POST" class="inline" @click.stop>
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-red-200 text-red-600 hover:bg-red-50 hover:border-red-300 transition-all shadow-sm group/btn" title="Tolak Cepat">
                                                        <i class="fas fa-times text-xs group-hover/btn:scale-110 transition-transform"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <a href="{{ route('loans.show', $loan->id) }}" class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-300 hover:bg-indigo-50 transition-all shadow-sm group-hover:shadow" title="{{ __('common.buttons.details') }}">
                                                <i class="fas fa-chevron-right text-xs"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-16">
                                        <div class="text-center px-4">
                                            <div class="w-24 h-24 mx-auto rounded-full bg-slate-50 border border-dashed border-slate-300 flex items-center justify-center mb-5 relative group">
                                                <div class="absolute inset-0 bg-green-100 rounded-full scale-0 group-hover:scale-100 transition-transform duration-500 opacity-50"></div>
                                                <i class="fas fa-clipboard-list text-4xl text-slate-300 relative z-10 group-hover:text-green-500 transition-colors"></i>
                                            </div>
                                            <h3 class="text-lg font-bold text-slate-800 mb-1 tracking-tight">{{ __('loans.empty.title') }}</h3>
                                            <p class="text-sm text-slate-500 mb-6 max-w-sm mx-auto">{{ __('loans.empty.description') }}</p>
                                            @unless (auth()->user()->role == 'admin')
                                                <a href="{{ route('loans.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl shadow-[0_4px_14px_0_rgb(34,197,94,0.39)] hover:shadow-[0_6px_20px_rgba(34,197,94,0.23)] hover:bg-green-700 hover:-translate-y-0.5 transition-all">
                                                    <i class="fas fa-plus"></i> {{ __('loans.empty.action') }}
                                                </a>
                                            @endunless
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if ($loans->hasPages())
                <div class="p-5 border-t border-slate-100 bg-slate-50/30">
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
