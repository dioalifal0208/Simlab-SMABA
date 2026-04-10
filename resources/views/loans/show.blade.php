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
        
        {{-- HEADER BERGRADIENT HALUS (DIHAPUS) --}}
        <div class="bg-white pt-6 pb-2 border-b border-slate-100 mb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <a href="{{ route('loans.index') }}" class="text-[11px] font-extrabold text-slate-400 hover:text-green-600 transition-colors uppercase tracking-widest flex items-center gap-1.5 mb-2 group">
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
                <div class="mb-8 bg-white border-l-4 border-l-emerald-500 p-4 rounded-xl shadow-sm flex items-start gap-4" data-aos="fade-in">
                    <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0 mt-0.5">
                        <i class="fas fa-check"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800">{{ __('common.messages.success') }}</h4>
                        <p class="text-sm text-slate-600 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-8 bg-white border-l-4 border-l-red-500 p-4 rounded-xl shadow-sm flex items-start gap-4" data-aos="fade-in">
                    <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-600 flex-shrink-0 mt-0.5">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800">{{ __('common.messages.error_title') }}</h4>
                        <ul class="mt-1 space-y-1 text-sm text-slate-600 list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- MAIN SAAS LAYOUT: 2 COLUMN (65:35 Mapping via 12-cols) --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pb-10">
                
                {{-- LEFT COLUMN: PRIMARY CONTENT (65%) --}}
                <div class="lg:col-span-8 space-y-8">
                    
                    {{-- 1. Borrower Information Card --}}
                    <section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" data-aos="fade-up" data-aos-once="true">
                        <div class="p-6 md:p-8 border-b border-slate-50/50">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <i class="fas fa-id-card text-green-500 text-sm"></i> Informasi Peminjam
                            </h3>
                            
                            <div class="flex flex-col sm:flex-row gap-6 items-start">
                                <div class="w-16 h-16 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-500 font-extrabold shadow-sm flex-shrink-0">
                                    <span class="text-2xl">{{ substr($loan->user->name, 0, 1) }}</span>
                                </div>
                                <div class="flex-grow w-full">
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                                        <div>
                                            <h4 class="text-2xl font-black text-slate-800 tracking-tight">{{ $loan->user->name }}</h4>
                                            <p class="text-sm text-slate-500 font-medium mt-1"><i class="fas fa-envelope text-slate-400 mr-1.5"></i>{{ $loan->user->email }}</p>
                                        </div>
                                        <div class="bg-white border border-slate-200 rounded-xl px-5 py-3 text-center min-w-[140px] shadow-sm shrink-0">
                                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('bookings.form.lab') }}</div>
                                            <div class="text-base font-black text-slate-700 mt-1 flex items-center justify-center gap-1.5"><i class="fas fa-flask text-green-600"></i> {{ $loan->laboratorium }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Borrowing Details Grid --}}
                        <div class="p-6 md:p-8 border-t border-slate-100 bg-slate-50/30">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-8 gap-x-8">
                                <div>
                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">{{ __('loans.details.submission_date') }}</div>
                                    <div class="text-sm font-bold text-slate-800 flex items-center gap-2 bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                                        <i class="fas fa-calendar-plus text-slate-400 opacity-50"></i> {{ $loan->created_at->format('d F Y, H:i') }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">{{ __('loans.details.plan_borrow_date') }}</div>
                                    <div class="text-[15px] font-black text-slate-800 flex items-center gap-2 bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                                        <i class="fas fa-calendar-check text-green-500"></i> {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d F Y') }}
                                    </div>
                                </div>
                                @if($loan->tanggal_kembali)
                                <div>
                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">{{ __('loans.details.return_date') }}</div>
                                    <div class="text-sm font-bold text-slate-800 flex items-center gap-2 bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                                        <i class="fas fa-box-check text-slate-400"></i> {{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d F Y, H:i') }}
                                    </div>
                                </div>
                                @endif
                                
                                <div class="col-span-1 sm:col-span-2 mt-2">
                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-1.5"><i class="fas fa-align-left text-slate-400"></i> {{ __('loans.details.borrower_notes') }}</div>
                                    <div class="bg-white border text-sm {{ $loan->catatan ? 'border-slate-200 text-slate-700 shadow-sm font-medium' : 'border-dashed border-slate-200 text-slate-400 italic' }} p-4 rounded-xl leading-relaxed">
                                        {{ $loan->catatan ?: "Tidak ada catatan tambahan dari peminjam." }}
                                    </div>
                                </div>

                                <!-- Edge State: Alasan Penolakan -->
                                @if($loan->status === 'rejected' && $loan->admin_notes)
                                <div class="col-span-1 sm:col-span-2 mt-2">
                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                                        <i class="fas fa-exclamation-circle text-red-500 text-sm"></i> Alasan Penolakan (Admin)
                                    </div>
                                    <div class="bg-red-50 border border-red-200 text-red-800 text-sm p-4 rounded-xl leading-relaxed font-bold shadow-sm">
                                        {{ $loan->admin_notes }}
                                    </div>
                                </div>
                                @elseif($loan->admin_notes)
                                <div class="col-span-1 sm:col-span-2 mt-2">
                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                                        <i class="fas fa-comment-dots text-slate-400 text-sm"></i> Catatan Tindak Lanjut Admin
                                    </div>
                                    <div class="bg-slate-50 border border-slate-200 text-slate-700 text-sm p-4 rounded-xl leading-relaxed font-medium shadow-sm">
                                        {{ $loan->admin_notes }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </section>
                    
                    {{-- 2. Item List Component --}}
                    <section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                        <div class="p-6 md:p-8">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                    <i class="fas fa-boxes text-green-500 text-sm"></i> Daftar Item Dipinjam
                                </h3>
                                <div class="bg-slate-50 border border-slate-200 text-slate-600 text-[11px] font-black px-4 py-1.5 rounded-xl shadow-sm">
                                    Total: {{ $loan->items->count() }} Item
                                </div>
                            </div>
                            
                            @if($loan->items->isEmpty())
                                {{-- Empty State Fallback --}}
                                <div class="text-center py-12 px-4 border border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                                    <div class="w-16 h-16 mx-auto rounded-full bg-white shadow-sm border border-slate-100 flex items-center justify-center text-slate-300 mb-4">
                                        <i class="fas fa-box-open text-2xl"></i>
                                    </div>
                                    <h4 class="text-base font-black text-slate-700 mb-1">Tidak ada item terlampir</h4>
                                    <p class="text-xs font-medium text-slate-500">Peminjaman ini tidak mencantumkan detail inventaris yang jelas.</p>
                                </div>
                            @else
                                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm">
                                    <table class="min-w-full text-left">
                                        <thead class="bg-slate-50/80 border-b border-slate-200">
                                            <tr>
                                                <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('loans.details.item_name') }}</th>
                                                <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">{{ __('loans.details.quantity') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 bg-white">
                                            @foreach($loan->items as $item)
                                            <tr class="hover:bg-green-50/30 transition-colors group">
                                                <td class="py-5 px-6">
                                                    <div class="flex items-center gap-4">
                                                        <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 group-hover:text-green-600 group-hover:border-green-200 transition-colors shadow-sm">
                                                            <i class="fas fa-microscope text-lg"></i>
                                                        </div>
                                                        <div>
                                                            <div class="font-black text-base text-slate-800 tracking-tight">{{ $item->nama_alat }}</div>
                                                            <div class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-widest opacity-80"><i class="fas fa-map-marker-alt mr-1"></i>{{ $item->laboratorium }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-5 px-6 text-right w-32 align-middle">
                                                    <div class="inline-flex flex-col items-end gap-1">
                                                        <span class="bg-slate-50 border border-slate-200 text-slate-800 font-black px-4 py-1.5 rounded-lg text-lg shadow-sm">
                                                            {{ $item->pivot->jumlah }}
                                                        </span>
                                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $item->satuan ?: 'Unit' }}</span>
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

                {{-- RIGHT COLUMN: SECONDARY PANEL (STICKY, 35%) --}}
                <div class="lg:col-span-4">
                    <div class="sticky top-8 space-y-6">
                        
                        {{-- A. HEAVILY DOMINANT STATUS CARD --}}
                        @php
                            $statusMeta = match($loan->status) {
                                'pending'   => ['bg' => 'bg-amber-50', 'border' => 'border-amber-200', 'text' => 'text-amber-800', 'iconText' => 'text-amber-600', 'icon' => 'fa-clock', 'desc' => 'Menunggu persetujuan Admin', 'ring' => 'ring-amber-500 shadow-amber-500/20'],
                                'approved'  => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-800', 'iconText' => 'text-emerald-600', 'icon' => 'fa-check-circle', 'desc' => 'Jadwal disetujui. Siap dipinjam.', 'ring' => 'ring-emerald-500 shadow-emerald-500/20'],
                                'rejected'  => ['bg' => 'bg-red-50', 'border' => 'border-red-200', 'text' => 'text-red-800', 'iconText' => 'text-red-600', 'icon' => 'fa-times-circle', 'desc' => 'Pengajuan ditolak secara sistem.', 'ring' => 'ring-red-500 shadow-red-500/20'],
                                'completed' => ['bg' => 'bg-slate-50', 'border' => 'border-slate-200', 'text' => 'text-slate-800', 'iconText' => 'text-slate-600', 'icon' => 'fa-box-check', 'desc' => 'Barang telah dikembalikan sepenuhnya.', 'ring' => 'ring-slate-500 shadow-slate-500/20'],
                                'Terlambat' => ['bg' => 'bg-rose-50', 'border' => 'border-rose-200', 'text' => 'text-rose-800', 'iconText' => 'text-rose-600', 'icon' => 'fa-exclamation-triangle', 'desc' => 'Melewati batas pengembalian.', 'ring' => 'ring-rose-500 shadow-rose-500/20'],
                                default     => ['bg' => 'bg-slate-50', 'border' => 'border-slate-200', 'text' => 'text-slate-800', 'iconText' => 'text-slate-600', 'icon' => 'fa-info-circle', 'desc' => 'Status tidak diketahui.', 'ring' => 'ring-slate-500']
                            };
                        @endphp
                        
                        <div class="rounded-2xl shadow-sm border {{ $statusMeta['bg'] }} {{ $statusMeta['border'] }} p-6 relative overflow-hidden group" data-aos="fade-left" data-aos-once="true">
                            <h3 class="text-[10px] font-black {{ $statusMeta['iconText'] }} opacity-70 uppercase tracking-widest mb-4">Status Peminjaman</h3>
                            
                            <div class="flex items-center gap-4 relative z-10">
                                <div class="w-14 h-14 rounded-xl bg-white flex items-center justify-center {{ $statusMeta['iconText'] }} text-3xl border {{ $statusMeta['border'] }} shadow-sm">
                                    <i class="fas {{ $statusMeta['icon'] }}"></i>
                                </div>
                                <div>
                                    <div class="font-black text-2xl {{ $statusMeta['text'] }} capitalize tracking-tight">{{ $loan->status }}</div>
                                    <div class="text-xs font-bold {{ $statusMeta['iconText'] }} opacity-80 leading-tight mt-0.5">{{ $statusMeta['desc'] }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- B. HIGHLY VISUAL VERTICAL TIMELINE --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6" data-aos="fade-left" data-aos-delay="100" data-aos-once="true">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Timeline Alur</h3>
                            
                            <div class="relative pl-3 space-y-8">
                                {{-- Background Track --}}
                                <div class="absolute left-[17px] top-6 bottom-4 w-0.5 bg-slate-100 rounded-full"></div>

                                {{-- Node 1: Diajukan --}}
                                <div class="relative flex items-start gap-4">
                                    <div class="w-2.5 h-2.5 rounded-full {{ in_array($loan->status, ['pending', 'approved', 'completed', 'Terlambat', 'rejected']) ? 'bg-green-500 ring-4 ring-green-100 shadow-sm shadow-green-500/40' : 'bg-slate-200' }} mt-1 z-10 shadow-sm relative transition-all" data-aos="zoom-in" data-aos-delay="200" data-aos-once="true"></div>
                                    <div>
                                        <div class="text-sm font-black text-slate-800">Pengajuan Dibuat</div>
                                        <div class="text-[11px] font-bold text-slate-400 mt-0.5">{{ $loan->created_at->format('d M Y, H:i') }}</div>
                                    </div>
                                </div>

                                {{-- Node 2: Persetujuan --}}
                                @php
                                    $step2Active = in_array($loan->status, ['approved', 'completed', 'Terlambat']);
                                    $step2Rejected = $loan->status === 'rejected';
                                    $step2Pending = $loan->status === 'pending';
                                @endphp
                                <div class="relative flex items-start gap-4 group">
                                    @if($step2Active || $step2Rejected)
                                        <div class="absolute -left-[5px] -top-8 bottom-4 w-0.5 {{ $step2Rejected ? 'bg-red-200' : 'bg-green-500' }}" data-aos="fade-down" data-aos-delay="300" data-aos-once="true" data-aos-duration="500"></div>
                                    @endif
                                    
                                    <div class="w-2.5 h-2.5 rounded-full mt-1 z-10 shadow-sm relative transition-all {{ $step2Rejected ? 'bg-red-500 ring-4 ring-red-100' : ($step2Active ? 'bg-green-500 ring-4 ring-green-100 shadow-sm shadow-green-500/40' : 'bg-white border-2 border-slate-300 ring-4 ring-slate-50') }}" data-aos="zoom-in" data-aos-delay="400" data-aos-once="true">
                                        @if($step2Pending)
                                            <div class="absolute -inset-1 rounded-full border-2 border-amber-400 animate-pulse hidden group-hover:block"></div>
                                        @endif
                                    </div>
                                    
                                    <div>
                                        @if($step2Rejected)
                                            <div class="text-sm font-black text-red-600">Pengajuan Ditolak</div>
                                            <div class="text-[11px] font-bold text-red-400 mt-0.5">{{ $loan->updated_at->format('d M Y, H:i') }}</div>
                                        @elseif($step2Active)
                                            <div class="text-sm font-black text-green-600">Disetujui Admin</div>
                                            <div class="text-[11px] font-bold text-slate-400 mt-0.5">Disetujui untuk peminjaman</div>
                                        @else
                                            <div class="text-sm font-black text-slate-400">Persetujuan Admin</div>
                                            <div class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md inline-flex items-center mt-1.5 border border-amber-200">
                                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5 animate-pulse"></span> Pending
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Node 3: Selesai / Terlambat --}}
                                @if(!$step2Rejected)
                                    @php
                                        $step3Active = $loan->status === 'completed';
                                        $step3Late   = $loan->status === 'Terlambat';
                                        $step3Wait   = $loan->status === 'approved';
                                    @endphp
                                    <div class="relative flex items-start gap-4 group">
                                        @if($step3Active || $step3Late)
                                            <div class="absolute -left-[5px] -top-8 bottom-4 w-0.5 bg-green-500" data-aos="fade-down" data-aos-delay="500" data-aos-once="true" data-aos-duration="500"></div>
                                        @endif
                                        
                                        <div class="w-2.5 h-2.5 rounded-full mt-1 z-10 shadow-sm relative transition-all {{ $step3Late ? 'bg-rose-500 ring-4 ring-rose-100 shadow-sm shadow-rose-500/40' : ($step3Active ? 'bg-slate-700 ring-4 ring-slate-100 shadow-sm' : 'bg-white border-2 border-slate-300 ring-4 ring-slate-50') }}" data-aos="zoom-in" data-aos-delay="600" data-aos-once="true">
                                            @if($step3Wait)
                                                <div class="absolute -inset-1 rounded-full border-2 border-blue-400 animate-pulse hidden group-hover:block"></div>
                                            @endif
                                        </div>
                                        
                                        <div>
                                            @if($step3Late)
                                                <div class="text-sm font-black text-rose-600">Terlambat Pengembalian</div>
                                                <div class="text-[11px] font-bold text-rose-400 mt-0.5">Melewati batas waktu {{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M') }}!</div>
                                            @elseif($step3Active)
                                                <div class="text-sm font-black text-slate-800">Telah Dikembalikan</div>
                                                <div class="text-[11px] font-bold text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M Y, H:i') }}</div>
                                            @else
                                                <div class="text-sm font-black text-slate-400">Pengembalian</div>
                                                <div class="text-[10px] font-bold text-slate-400 mt-1">Belum ditandai dikembalikan</div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- C. ACTION PANEL HIERARCHY (Hanya untuk Admin) --}}
                        @can('is-admin')
                            @if(in_array($loan->status, ['pending', 'approved', 'Terlambat']))
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 relative overflow-hidden" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Aksi Operator</h3>
                                
                                <div class="space-y-3">
                                    @if($loan->status == 'pending')
                                        {{-- Primary Action: Massive Green Button --}}
                                        <button @click="showModal = true; modalType = 'approved'; actionTitle = 'Setujui Peminjaman'; actionColor = 'emerald'; actionIcon = 'fa-check'; btnColor = 'bg-green-600 hover:bg-green-700 text-white shadow-md shadow-green-600/20'" 
                                                class="w-full relative flex justify-center items-center py-3.5 px-4 rounded-xl font-bold text-sm bg-green-600 hover:bg-green-700 transition-all hover:-translate-y-0.5 text-white shadow-md shadow-green-600/20 active:scale-95">
                                            <i class="fas fa-check mr-2"></i> Approve Request
                                        </button>
                                        
                                        {{-- Secondary Action: Muted Ghost Button --}}
                                        <button @click="showModal = true; modalType = 'rejected'; actionTitle = 'Tolak Peminjaman'; actionColor = 'red'; actionIcon = 'fa-times'; btnColor = 'bg-white border text-white hover:bg-red-600 bg-red-500 shadow-sm'" 
                                                class="w-full relative flex justify-center items-center py-3 px-4 rounded-xl font-bold text-sm bg-transparent border-2 border-slate-200 text-slate-500 hover:text-red-600 hover:border-red-200 hover:bg-red-50 shadow-none transition-all group active:scale-95">
                                            <i class="fas fa-times mr-2 text-slate-400 group-hover:text-red-500"></i> Reject Request
                                        </button>
                                    @elseif(in_array($loan->status, ['approved', 'Terlambat']))
                                        {{-- Primary Completion Action --}}
                                        <button @click="showModal = true; modalType = 'completed'; actionTitle = 'Tandai Dikembalikan'; actionColor = 'slate'; actionIcon = 'fa-box-check'; btnColor = 'bg-slate-800 hover:bg-slate-900 text-white shadow-sm shadow-slate-800/20'" 
                                                class="w-full relative flex justify-center items-center py-3.5 px-4 rounded-xl font-bold text-sm bg-slate-800 hover:bg-slate-900 transition-all hover:-translate-y-0.5 text-white shadow-md shadow-slate-800/20 active:scale-95">
                                            <i class="fas fa-box-check mr-2"></i> Mark as Returned
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @endif
                        @endcan

                        {{-- Metadata Footprint --}}
                        <div class="text-center pt-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ID Log: <span class="text-slate-500">SYS-LN-{{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}</span></p>
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
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm shadow-sm" :class="'bg-' + actionColor + '-100 text-' + actionColor + '-600'">
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
                                <p class="text-sm text-slate-600 leading-relaxed font-medium">Apakah Anda yakin ingin <strong class="text-emerald-600">menyetujui</strong> pengajuan peminjaman ini? Stok inventaris akan dikurangi otomatis dari sistem.</p>
                            </template>
                            
                            <template x-if="modalType === 'completed'">
                                <p class="text-sm text-slate-600 leading-relaxed font-medium">Apakah barang peminjaman sudah dikembalikan dengan kondisi lengkap? Item akan <strong class="text-slate-800">dikembalikan ke stok inventaris</strong>.</p>
                            </template>
                            
                            {{-- Field Spesifik untuk Penolakan (WAJIB) --}}
                            <template x-if="modalType === 'rejected'">
                                <div>
                                    <p class="text-sm text-slate-600 leading-relaxed font-medium mb-4">Pengajuan ini tidak dapat dibatalkan jika sudah ditolak. Harap masukkan alasan spesifik penolakan kepada peminjam.</p>
                                    <label for="admin_notes" class="block text-[11px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-1 mb-2">
                                        Alasan Penolakan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="admin_notes" id="admin_notes" rows="4" required
                                        class="w-full rounded-xl border-slate-200 shadow-sm focus:border-red-500 focus:ring-red-500/20 text-sm p-4 bg-slate-50 hover:bg-white focus:bg-white transition-colors resize-none placeholder-slate-400 font-medium" 
                                        placeholder="Cth: Stok alat saat ini sedang tidak tersedia / perbaikan..."></textarea>
                                </div>
                            </template>
                            
                            {{-- Field Opsional untuk Persetujuan --}}
                            <template x-if="modalType === 'approved'">
                                <div class="mt-5">
                                    <label for="admin_notes_optional" class="block text-[11px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-1 mb-2">
                                        Catatan Pengambilan <span class="text-slate-400 font-medium normal-case">(Opsional)</span>
                                    </label>
                                    <input type="text" name="admin_notes" id="admin_notes_optional"
                                        class="w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500/20 text-sm p-3 bg-slate-50 hover:bg-white focus:bg-white transition-colors placeholder-slate-400 font-medium" 
                                        placeholder="Cth: Silakan ambil di loker ruang Biologi B...">
                                </div>
                            </template>
                        </div>
                        
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-slate-50 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-100 transition-colors text-sm shadow-sm">
                                Batal
                            </button>
                            <button type="submit" :class="btnColor" class="px-6 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <i class="fas fa-paper-plane mr-1 text-xs opacity-70 mt-0.5 max-sm:hidden"></i>
                                <span x-text="actionTitle"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

    </div>
</x-app-layout>
