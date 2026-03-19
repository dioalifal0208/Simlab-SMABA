<x-app-layout>
    <div x-data="{ 
            showModal: false, 
            modalType: '', 
            actionTitle: '', 
            actionColor: '', 
            actionIcon: '',
            btnColor: ''
        }" 
        @keydown.escape.window="showModal = false"
        class="relative min-h-screen pb-12">
        
        {{-- HEADER BERGRADIENT HALUS --}}
        <div class="bg-gradient-to-b from-slate-50 to-white/0 pt-6 pb-2 border-b border-slate-100/50 mb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <a href="{{ route('loans.index') }}" class="text-[11px] font-extrabold text-slate-400 hover:text-indigo-600 transition-colors uppercase tracking-widest flex items-center gap-1.5 mb-2 group">
                            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> Dashboard / Peminjaman
                        </a>
                        <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-3">
                            {{ __('loans.details.title') }} 
                            <span class="text-slate-300 font-light text-xl">#{{ str_pad($loan->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ALERTS --}}
            @if (session('success'))
                <div class="mb-8 bg-emerald-50 border border-emerald-100 p-4 rounded-xl shadow-sm flex items-start gap-4" data-aos="fade-in">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0 mt-0.5">
                        <i class="fas fa-check"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-emerald-800">{{ __('common.messages.success') }}</h4>
                        <p class="text-sm text-emerald-700 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-8 bg-red-50 border border-red-100 p-4 rounded-xl shadow-sm flex items-start gap-4" data-aos="fade-in">
                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 flex-shrink-0 mt-0.5">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-red-800">{{ __('common.messages.error_title') }}</h4>
                        <ul class="mt-1 space-y-1 text-sm text-red-700 list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- MAIN SAAS LAYOUT: 2 COLUMN --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-10">
                
                {{-- LEFT COLUMN: PRIMARY CONTENT --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- 1. Borrower Information Card --}}
                    <section class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" data-aos="fade-up" data-aos-once="true">
                        <div class="p-6 border-b border-slate-50/50">
                            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <i class="fas fa-id-card text-indigo-400"></i> Informasi Peminjam
                            </h3>
                            
                            <div class="flex flex-col sm:flex-row gap-6 items-start">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-50 to-blue-50 border border-indigo-100/50 flex items-center justify-center text-indigo-600 font-bold shadow-sm shadow-indigo-100/20 flex-shrink-0">
                                    <span class="text-2xl">{{ substr($loan->user->name, 0, 1) }}</span>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                                        <div>
                                            <h4 class="text-xl font-bold text-slate-800 tracking-tight">{{ $loan->user->name }}</h4>
                                            <p class="text-sm text-slate-500 font-medium mt-0.5"><i class="fas fa-envelope text-slate-400 mr-1.5"></i>{{ $loan->user->email }}</p>
                                        </div>
                                        <div class="bg-slate-50 border border-slate-100 rounded-lg px-4 py-2 text-center">
                                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ __('bookings.form.lab') }}</div>
                                            <div class="text-sm font-bold text-indigo-700 mt-0.5">{{ $loan->laboratorium }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Borrowing Details Grid --}}
                        <div class="p-6 bg-slate-50/30">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8">
                                <div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">{{ __('loans.details.submission_date') }}</div>
                                    <div class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                        <i class="fas fa-calendar-plus text-slate-400"></i> {{ $loan->created_at->format('d F Y, H:i') }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">{{ __('loans.details.plan_borrow_date') }}</div>
                                    <div class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                        <i class="fas fa-calendar-check text-indigo-500"></i> {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d F Y') }}
                                    </div>
                                </div>
                                @if($loan->tanggal_kembali)
                                <div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">{{ __('loans.details.return_date') }}</div>
                                    <div class="text-sm font-bold text-emerald-600 flex items-center gap-2">
                                        <i class="fas fa-box-check text-emerald-500"></i> {{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d F Y, H:i') }}
                                    </div>
                                </div>
                                @endif
                                
                                <div class="col-span-1 sm:col-span-2 mt-2">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">{{ __('loans.details.borrower_notes') }}</div>
                                    <div class="bg-white border text-sm {{ $loan->catatan ? 'border-slate-200 text-slate-700' : 'border-dashed border-slate-200 text-slate-400 italic' }} p-4 rounded-xl leading-relaxed">
                                        {{ $loan->catatan ?: "Tidak ada catatan tambahan dari peminjam." }}
                                    </div>
                                </div>

                                {{-- Edge State: Alasan Penolakan (Prominent Rejection Note) --}}
                                @if($loan->status === 'rejected' && $loan->admin_notes)
                                <div class="col-span-1 sm:col-span-2 mt-2">
                                    <div class="text-[10px] font-bold text-red-500 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                                        <i class="fas fa-exclamation-circle"></i> Alasan Penolakan (Admin)
                                    </div>
                                    <div class="bg-red-50 border border-red-100 text-red-800 text-sm p-4 rounded-xl leading-relaxed font-medium">
                                        {{ $loan->admin_notes }}
                                    </div>
                                </div>
                                @elseif($loan->admin_notes)
                                <div class="col-span-1 sm:col-span-2 mt-2">
                                    <div class="text-[10px] font-bold text-amber-500 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                                        <i class="fas fa-comment-dots"></i> Catatan Admin
                                    </div>
                                    <div class="bg-amber-50 border border-amber-100 text-amber-900 text-sm p-4 rounded-xl leading-relaxed">
                                        {{ $loan->admin_notes }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </section>
                    
                    {{-- 2. Item List Component --}}
                    <section class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                    <i class="fas fa-boxes text-indigo-400"></i> Daftar Item Dipinjam
                                </h3>
                                <div class="bg-slate-100 text-slate-600 text-[11px] font-bold px-3 py-1 rounded-lg">
                                    {{ $loan->items->count() }} Item
                                </div>
                            </div>
                            
                            @if($loan->items->isEmpty())
                                {{-- Empty State Fallback --}}
                                <div class="text-center py-12 px-4 border border-dashed border-slate-200 rounded-xl bg-slate-50/50">
                                    <div class="w-16 h-16 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-300 mb-4">
                                        <i class="fas fa-box-open text-2xl"></i>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-700 mb-1">Tidak ada item</h4>
                                    <p class="text-xs text-slate-500">Peminjaman ini tidak memiliki detail item spesifik.</p>
                                </div>
                            @else
                                <div class="border border-slate-100 rounded-xl overflow-hidden">
                                    <table class="min-w-full text-left">
                                        <thead class="bg-slate-50/80 border-b border-slate-100">
                                            <tr>
                                                <th class="py-3.5 px-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">{{ __('loans.details.item_name') }}</th>
                                                <th class="py-3.5 px-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-right">{{ __('loans.details.quantity') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 bg-white">
                                            @foreach($loan->items as $item)
                                            <tr class="hover:bg-slate-50 transition-colors group">
                                                <td class="py-4 px-5">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 group-hover:text-indigo-500 group-hover:bg-indigo-50 transition-colors">
                                                            <i class="fas fa-flask"></i>
                                                        </div>
                                                        <div>
                                                            <div class="font-bold text-sm text-slate-800">{{ $item->nama_alat }}</div>
                                                            <div class="text-[11px] font-medium text-slate-400 mt-0.5"><i class="fas fa-map-marker-alt mr-1"></i>{{ $item->laboratorium }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-5 text-right align-middle">
                                                    <div class="inline-flex items-center justify-center bg-white border border-slate-200 text-slate-700 font-bold px-3 py-1.5 rounded-lg text-sm shadow-sm group-hover:border-indigo-200 group-hover:text-indigo-700 transition-colors">
                                                        {{ $item->pivot->jumlah }} <span class="ml-1 text-[10px] font-semibold opacity-60 uppercase">{{ $item->satuan ?: 'Unit' }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </section>
                </div>

                {{-- RIGHT COLUMN: SECONDARY PANEL (STICKY) --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-8 space-y-8">
                        
                        {{-- A. Status Summary Card --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative overflow-hidden group" data-aos="fade-left" data-aos-once="true">
                            @php
                                $statusMeta = match($loan->status) {
                                    'pending'   => ['color' => 'amber',   'icon' => 'fa-clock',        'desc' => 'Menunggu tindak lanjut dari Admin Lab.'],
                                    'approved'  => ['color' => 'emerald', 'icon' => 'fa-check-circle', 'desc' => 'Pengajuan disetujui. Siap dipinjam.'],
                                    'rejected'  => ['color' => 'red',     'icon' => 'fa-times-circle', 'desc' => 'Pengajuan ditolak. Lihat catatan admin.'],
                                    'completed' => ['color' => 'slate',   'icon' => 'fa-box-check',    'desc' => 'Item telah dikembalikan sepenuhnya.'],
                                    'Terlambat' => ['color' => 'rose',    'icon' => 'fa-exclamation-triangle', 'desc' => 'Melewati batas waktu peminjaman.'],
                                    default     => ['color' => 'indigo',  'icon' => 'fa-info-circle',  'desc' => 'Status tidak diketahui.']
                                };
                                $metaColor = $statusMeta['color'];
                            @endphp
                            
                            {{-- Decorative gradient blob --}}
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-{{$metaColor}}-50 rounded-full blur-2xl opacity-60 group-hover:opacity-100 transition-opacity"></div>
                            
                            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-4">Status Peminjaman</h3>
                            
                            <div class="flex items-center gap-4 mb-3 relative z-10">
                                <div class="w-12 h-12 rounded-xl bg-{{$metaColor}}-50 flex items-center justify-center text-{{$metaColor}}-500 text-xl border border-{{$metaColor}}-100 shadow-inner">
                                    <i class="fas {{ $statusMeta['icon'] }}"></i>
                                </div>
                                <div>
                                    <div class="font-extrabold text-lg text-slate-800 capitalize">{{ $loan->status }}</div>
                                    <div class="text-[11px] font-medium text-slate-500 leading-tight mt-0.5">{{ $statusMeta['desc'] }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- B. Vertical Timeline --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6" data-aos="fade-left" data-aos-delay="100" data-aos-once="true">
                            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-6">Timeline Alur</h3>
                            
                            <div class="relative pl-3 space-y-8">
                                {{-- Background Track --}}
                                <div class="absolute left-[17px] top-6 bottom-4 w-0.5 bg-slate-100 rounded-full"></div>

                                {{-- Node 1: Dibuat --}}
                                <div class="relative flex items-start gap-4">
                                    <div class="w-3 h-3 rounded-full {{ in_array($loan->status, ['pending', 'approved', 'completed', 'Terlambat', 'rejected']) ? 'bg-emerald-500 ring-4 ring-emerald-50' : 'bg-slate-200' }} mt-1 z-10 shadow-sm relative transition-all" data-aos="zoom-in" data-aos-delay="200" data-aos-once="true"></div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-800">Pengajuan Dibuat</div>
                                        <div class="text-xs font-medium text-slate-400 mt-0.5">{{ $loan->created_at->format('d M Y, H:i') }}</div>
                                    </div>
                                </div>

                                {{-- Node 2: Persetujuan --}}
                                @php
                                    $step2Active = in_array($loan->status, ['approved', 'completed', 'Terlambat']);
                                    $step2Rejected = $loan->status === 'rejected';
                                @endphp
                                <div class="relative flex items-start gap-4 group">
                                    @if($step2Active || $step2Rejected)
                                        <div class="absolute -left-[5px] -top-8 bottom-4 w-0.5 bg-emerald-500/50" data-aos="fade-down" data-aos-delay="300" data-aos-once="true" data-aos-duration="500"></div>
                                    @endif
                                    
                                    <div class="w-3 h-3 rounded-full mt-1 z-10 shadow-sm relative transition-all {{ $step2Rejected ? 'bg-red-500 ring-4 ring-red-50' : ($step2Active ? 'bg-emerald-500 ring-4 ring-emerald-50' : 'bg-white border-2 border-slate-300 ring-4 ring-white') }}" data-aos="zoom-in" data-aos-delay="400" data-aos-once="true">
                                        {{-- Tooltip hover on node --}}
                                        <div class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 bg-slate-800 text-white text-[10px] font-bold py-1 px-2 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all whitespace-nowrap z-20">
                                            Tahap Persetujuan Admin
                                        </div>
                                    </div>
                                    
                                    <div>
                                        @if($step2Rejected)
                                            <div class="text-sm font-bold text-red-600 drop-shadow-sm">Pengajuan Ditolak</div>
                                            <div class="text-xs font-medium text-slate-400 mt-0.5">{{ $loan->updated_at->format('d M Y, H:i') }}</div>
                                        @elseif($step2Active)
                                            <div class="text-sm font-bold text-emerald-600">Disetujui Admin</div>
                                            <div class="text-xs font-medium text-slate-400 mt-0.5">Disetujui untuk peminjaman</div>
                                        @else
                                            <div class="text-sm font-bold text-slate-400">Persetujuan Admin</div>
                                            <div class="text-[10px] font-bold text-amber-500 bg-amber-50 px-2 py-0.5 rounded inline-block mt-1">Pending</div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Node 3: Selesai --}}
                                @if(!$step2Rejected)
                                    @php
                                        $step3Active = $loan->status === 'completed';
                                        $step3Late = $loan->status === 'Terlambat';
                                    @endphp
                                    <div class="relative flex items-start gap-4 group">
                                        @if($step3Active || $step3Late)
                                            <div class="absolute -left-[5px] -top-8 bottom-4 w-0.5 bg-emerald-500/50" data-aos="fade-down" data-aos-delay="500" data-aos-once="true" data-aos-duration="500"></div>
                                        @endif
                                        
                                        <div class="w-3 h-3 rounded-full mt-1 z-10 shadow-sm relative transition-all {{ $step3Late ? 'bg-rose-500 ring-4 ring-rose-50' : ($step3Active ? 'bg-emerald-500 ring-4 ring-emerald-50' : 'bg-white border-2 border-slate-300 ring-4 ring-white') }}" data-aos="zoom-in" data-aos-delay="600" data-aos-once="true">
                                             <div class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 bg-slate-800 text-white text-[10px] font-bold py-1 px-2 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all whitespace-nowrap z-20">
                                                Tahap Pengembalian Item
                                            </div>
                                        </div>
                                        
                                        <div>
                                            @if($step3Late)
                                                <div class="text-sm font-bold text-rose-600">Terlambat Dikembalikan</div>
                                                <div class="text-xs font-medium text-rose-400/80 mt-0.5">Melewati batas waktu</div>
                                            @elseif($step3Active)
                                                <div class="text-sm font-bold text-slate-800">Telah Dikembalikan</div>
                                                <div class="text-xs font-medium text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M Y, H:i') }}</div>
                                            @else
                                                <div class="text-sm font-bold text-slate-400">Pengembalian</div>
                                                <div class="text-xs font-medium text-slate-400 mt-0.5">Menunggu barang dikembalikan</div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- C. Quick Actions Panel (Hanya untuk Admin) --}}
                        @can('is-admin')
                            @if(in_array($loan->status, ['pending', 'approved']))
                            <div class="bg-slate-800 text-white rounded-2xl shadow-lg border border-slate-700 p-6 relative overflow-hidden" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                                <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
                                
                                <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-4">Tindakan Cepat</h3>
                                
                                <div class="space-y-3">
                                    @if($loan->status == 'pending')
                                        <button @click="showModal = true; modalType = 'approved'; actionTitle = 'Setujui Peminjaman'; actionColor = 'emerald'; actionIcon = 'fa-check'; btnColor = 'bg-emerald-500 hover:bg-emerald-600 text-white shadow-emerald-500/30'" 
                                                class="w-full relative flex justify-center items-center py-3 px-4 rounded-xl font-bold text-sm shadow-[0_4px_14px_0_rgba(16,185,129,0.39)] hover:shadow-[0_6px_20px_rgba(16,185,129,0.23)] bg-emerald-500 hover:bg-emerald-400 transition-all hover:-translate-y-0.5 text-white">
                                            <i class="fas fa-check mr-2"></i> Approve Request
                                        </button>
                                        
                                        <button @click="showModal = true; modalType = 'rejected'; actionTitle = 'Tolak Peminjaman'; actionColor = 'red'; actionIcon = 'fa-times'; btnColor = 'bg-red-500 hover:bg-red-600 text-white shadow-red-500/30'" 
                                                class="w-full relative flex justify-center items-center py-3 px-4 rounded-xl font-bold text-sm bg-slate-700 hover:bg-slate-600 text-slate-300 hover:text-white transition-all">
                                            <i class="fas fa-times mr-2"></i> Reject Request
                                        </button>
                                    @elseif($loan->status == 'approved')
                                        <button @click="showModal = true; modalType = 'completed'; actionTitle = 'Tandai Selesai'; actionColor = 'indigo'; actionIcon = 'fa-box-check'; btnColor = 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-indigo-500/30'" 
                                                class="w-full relative flex justify-center items-center py-3 px-4 rounded-xl font-bold text-sm shadow-[0_4px_14px_0_rgba(79,70,229,0.39)] hover:shadow-[0_6px_20px_rgba(79,70,229,0.23)] bg-indigo-600 hover:bg-indigo-500 transition-all hover:-translate-y-0.5 text-white">
                                            <i class="fas fa-box-check mr-2"></i> Mark as Returned
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @endif
                        @endcan

                        {{-- Metadata Footprint --}}
                        <div class="text-center">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ID Peminjaman: <span class="text-slate-500">{{ $loan->id }}</span></p>
                            <p class="text-[10px] font-medium text-slate-400 mt-1">Terakhir update: {{ $loan->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- MODAL KONFIRMASI (ALPINE.JS) --}}
        <template x-teleport="body">
            <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0" x-cloak>
                {{-- Backdrop --}}
                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0" 
                     x-transition:enter-end="opacity-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100" 
                     x-transition:leave-end="opacity-0" 
                     class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" 
                     @click="showModal = false"></div>
                
                {{-- Modal Panel --}}
                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300 delay-100" 
                     x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-lg w-full relative z-10 overflow-hidden">
                    
                    {{-- Header Modal Dinamis --}}
                    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center" :class="'bg-' + actionColor + '-50/50'">
                        <h3 class="font-extrabold text-lg text-slate-800 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm" :class="'bg-' + actionColor + '-100 text-' + actionColor + '-600'">
                                <i class="fas" :class="actionIcon"></i>
                            </div>
                            <span x-text="actionTitle"></span>
                        </h3>
                        <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex justify-center items-center rounded-lg hover:bg-slate-100">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    {{-- Body Modal (Form) --}}
                    <form action="{{ route('loans.update', $loan->id) }}" method="POST" class="p-6">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" x-model="modalType">
                        
                        <div class="mb-6">
                            <template x-if="modalType === 'approved'">
                                <p class="text-sm text-slate-600 leading-relaxed font-medium">Apakah Anda yakin ingin <strong class="text-emerald-600">menyetujui</strong> pengajuan peminjaman ini? Stok inventaris akan dikurangi sementara.</p>
                            </template>
                            
                            <template x-if="modalType === 'completed'">
                                <p class="text-sm text-slate-600 leading-relaxed font-medium">Apakah barang peminjaman sudah dikembalikan dengan kondisi lengkap? Item akan dikembalikan ke stok inventaris.</p>
                            </template>
                            
                            {{-- Field Spesifik untuk Penolakan (WAJIB) --}}
                            <template x-if="modalType === 'rejected'">
                                <div>
                                    <p class="text-sm text-slate-600 leading-relaxed font-medium mb-4">Pengajuan ini tidak dapat dibatalkan jika sudah ditolak. Harap masukkan alasan spesifik penolakan.</p>
                                    <label for="admin_notes" class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-widest flex items-center gap-1 mb-2">
                                        Alasan Penolakan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="admin_notes" id="admin_notes" rows="4" required
                                        class="w-full rounded-xl border-slate-200 shadow-sm focus:border-red-500 focus:ring-red-500/20 text-sm p-4 bg-slate-50 hover:bg-white focus:bg-white transition-colors resize-none placeholder-slate-400" 
                                        placeholder="Contoh: Stok alat saat ini sedang perbaikan..."></textarea>
                                </div>
                            </template>
                            
                            {{-- Field Opsional untuk Persetujuan --}}
                            <template x-if="modalType === 'approved'">
                                <div class="mt-5">
                                    <label for="admin_notes_optional" class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-widest flex items-center gap-1 mb-2">
                                        Catatan Tambahan <span class="text-slate-400 font-medium normal-case">(Opsional)</span>
                                    </label>
                                    <input type="text" name="admin_notes" id="admin_notes_optional"
                                        class="w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500/20 text-sm p-3 bg-slate-50 hover:bg-white focus:bg-white transition-colors placeholder-slate-400" 
                                        placeholder="Catatan untuk peminjam saat mengambil barang...">
                                </div>
                            </template>
                        </div>
                        
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-colors text-sm">
                                Batal
                            </button>
                            <button type="submit" :class="btnColor" class="px-6 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <span x-text="actionTitle"></span> <i class="fas max-sm:hidden" :class="(modalType === 'approved' || modalType === 'completed') ? 'fa-arrow-right' : 'fa-times'"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

    </div>
</x-app-layout>
