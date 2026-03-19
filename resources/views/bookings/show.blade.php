<x-app-layout>
    @php
        $startTime = \Carbon\Carbon::parse($booking->waktu_mulai);
        $endTime = \Carbon\Carbon::parse($booking->waktu_selesai);
        $durationHours = $startTime->diffInHours($endTime);
        $durationMinutes = $startTime->diffInMinutes($endTime) % 60;
        
        $durationText = '';
        if ($durationHours > 0) $durationText .= $durationHours . ' Jam ';
        if ($durationMinutes > 0) $durationText .= $durationMinutes . ' Menit';
        $durationText = trim($durationText) ?: '0 Menit';

        // Time block visualization logic (07:00 to 16:00)
        $vizStartHour = 7;
        $vizEndHour = 16;
        $totalVizMins = ($vizEndHour - $vizStartHour) * 60; // 540 mins
        
        $bookingStartMins = ($startTime->format('G') * 60) + $startTime->format('i');
        $baseStartMins = $vizStartHour * 60;
        
        $leftMins = $bookingStartMins - $baseStartMins;
        $leftPercent = max(0, min(100, ($leftMins / $totalVizMins) * 100));
        
        $bookingDurationMins = ($durationHours * 60) + $durationMinutes;
        $widthPercent = ($bookingDurationMins / $totalVizMins) * 100;
        
        if ($leftPercent + $widthPercent > 100) {
            $widthPercent = 100 - $leftPercent;
        }

        // Color coding by Lab
        $labColor = match($booking->laboratorium) {
            'Biologi' => 'emerald',
            'Fisika' => 'blue',
            'Bahasa' => 'amber',
            default => 'indigo' // Default inc. Komputer
        };

        // Conflict Detection
        $conflict = null;
        if (in_array($booking->status, ['pending', 'approved'])) {
            $conflict = \App\Models\Booking::where('laboratorium', $booking->laboratorium)
                ->where('status', 'approved')
                ->where('id', '!=', $booking->id)
                ->whereDate('waktu_mulai', $startTime->format('Y-m-d'))
                ->where(function($q) use ($startTime, $endTime) {
                    $q->where('waktu_mulai', '<', $endTime)
                      ->where('waktu_selesai', '>', $startTime);
                })->first();
        }
    @endphp

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
                        <a href="{{ route('bookings.index') }}" class="text-[11px] font-extrabold text-slate-400 hover:text-indigo-600 transition-colors uppercase tracking-widest flex items-center gap-1.5 mb-2 group">
                            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> Dashboard / Jadwal Lab
                        </a>
                        <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-3">
                            {{ __('bookings.details.title') }} 
                            <span class="text-slate-300 font-light text-xl">#{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </h2>
                    </div>

                    {{-- Header Badges --}}
                    @if(($booking->status == 'approved' || $booking->status == 'completed') && (auth()->user()->role === 'admin' || auth()->id() === $booking->user_id))
                    <div class="flex gap-3">
                        <a href="{{ route('bookings.surat', $booking->id) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-xl font-bold text-xs text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-sm">
                            <i class="fas fa-print mr-2 text-slate-400"></i> Cetak Surat
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ALERTS --}}
            @if (session('success'))
                <div class="mb-8 bg-emerald-50 border border-emerald-100 p-4 rounded-xl shadow-sm flex items-start gap-4" data-aos="fade-in">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0 mt-0.5"><i class="fas fa-check"></i></div>
                    <div>
                        <h4 class="font-bold text-emerald-800">{{ __('common.messages.success') }}</h4>
                        <p class="text-sm text-emerald-700 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-8 bg-red-50 border border-red-100 p-4 rounded-xl shadow-sm flex items-start gap-4" data-aos="fade-in">
                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 flex-shrink-0 mt-0.5"><i class="fas fa-exclamation-triangle"></i></div>
                    <div>
                        <h4 class="font-bold text-red-800">{{ __('common.messages.error_title') }}</h4>
                        <ul class="mt-1 space-y-1 text-sm text-red-700 list-inside list-disc">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- MAIN SAAS LAYOUT: 2 COLUMN --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-10">
                
                {{-- LEFT COLUMN: PRIMARY CONTENT --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- 1. Booking Information Card --}}
                    <section class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" data-aos="fade-up" data-aos-once="true">
                        <div class="p-6 border-b border-slate-50/50">
                            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <i class="fas fa-info-circle text-indigo-400"></i> Informasi Kegiatan
                            </h3>
                            
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 mb-6">
                                <div>
                                    <h4 class="text-xl font-bold text-slate-800 tracking-tight">{{ $booking->tujuan_kegiatan }}</h4>
                                    <p class="text-sm text-slate-500 font-medium mt-1"><i class="fas fa-user-circle text-slate-400 mr-1.5"></i> Diajukan oleh: <span class="font-bold text-slate-700">{{ $booking->user->name }}</span></p>
                                </div>
                                <div class="bg-{{$labColor}}-50 border border-{{$labColor}}-100 rounded-xl px-4 py-2 text-center min-w-[120px] shadow-sm">
                                    <div class="text-[10px] font-bold text-{{$labColor}}-400 uppercase tracking-widest">Laboratorium</div>
                                    <div class="text-sm font-extrabold text-{{$labColor}}-700 mt-0.5 flex items-center justify-center gap-1.5">
                                        <i class="fas fa-flask"></i> {{ $booking->laboratorium }}
                                    </div>
                                </div>
                            </div>

                            @if($booking->mata_pelajaran)
                            <div class="mb-2">
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">{{ __('bookings.details.subject') }}</div>
                                <div class="text-sm font-bold text-slate-800 inline-block bg-slate-50 px-3 py-1 rounded-lg border border-slate-100">{{ $booking->mata_pelajaran }}</div>
                            </div>
                            @endif
                        </div>
                    </section>

                    {{-- 2. Schedule & Time Block Card --}}
                    <section class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                        <div class="p-6">
                            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <i class="fas fa-calendar-alt text-indigo-400"></i> Detail Jadwal & Waktu
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tanggal</div>
                                    <div class="text-sm font-extrabold text-slate-800">{{ $startTime->translatedFormat('l, d F Y') }}</div>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Jam Pelaksanaan</div>
                                    <div class="text-sm font-extrabold text-slate-800 flex items-center gap-2">
                                        {{ $startTime->format('H:i') }} <span class="text-slate-300 font-normal">to</span> {{ $endTime->format('H:i') }}
                                    </div>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Durasi</div>
                                    <div class="text-sm font-extrabold text-indigo-600">{{ $durationText }}</div>
                                </div>
                            </div>

                            {{-- CSS Grid Horizontal Time Visualizer --}}
                            <div class="mb-4">
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Visualisasi Blok Waktu (07:00 - 16:00)</div>
                                <div class="relative w-full h-12 bg-slate-100 rounded-xl overflow-hidden shadow-inner border border-slate-200 group">
                                    {{-- The specific booking block --}}
                                    <div class="absolute top-0 bottom-0 bg-gradient-to-r from-{{$labColor}}-400 to-{{$labColor}}-500 transition-all duration-1000 ease-out flex items-center justify-center opacity-90 group-hover:opacity-100 hover:shadow-lg cursor-crosshair z-10"
                                         style="left: {{ $leftPercent }}%; width: {{ $widthPercent }}%;"
                                         title="Durasi: {{ $durationText }}">
                                        {{-- Optional Pattern Overlay --}}
                                        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPjxyZWN0IHdpZHRoPSI0IiBoZWlnaHQ9IjQiIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iLjA1Ii8+PC9zdmc+')] mix-blend-overlay"></div>
                                        @if($widthPercent > 15)
                                            <span class="text-white text-[10px] font-bold px-2 truncate relative z-10 drop-shadow-md">{{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }}</span>
                                        @endif
                                    </div>

                                    {{-- Hour Ticks Track --}}
                                    <div class="absolute inset-0 flex justify-between px-2 pt-8 z-0 pointer-events-none opacity-40">
                                        @for($h = 7; $h <= 16; $h++)
                                            <div class="flex flex-col items-center">
                                                <div class="h-1.5 w-px bg-slate-400"></div>
                                                <span class="text-[8px] font-bold text-slate-500 mt-0.5">{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}</span>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Conflict Detection Alert (Edge State) --}}
                        @if($conflict)
                        <div class="p-6 bg-red-50 border-t border-red-100">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 flex-shrink-0 animate-pulse">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="w-full">
                                    <h4 class="font-bold text-red-800 flex items-center gap-2">
                                        Peringatan: Jadwal Bentrok!
                                    </h4>
                                    <p class="text-sm text-red-700 mt-1 leading-relaxed">
                                        Terdapat jadwal / booking lain yang <strong>telah disetujui</strong> pada waktu yang bersinggungan di Lab {{ $booking->laboratorium }}.
                                    </p>
                                    
                                    <div class="mt-3 bg-white border border-red-200 rounded-lg p-3 shadow-sm flex justify-between items-center text-sm">
                                        <div>
                                            <div class="font-bold text-slate-800">{{ $conflict->tujuan_kegiatan }}</div>
                                            <div class="text-xs text-slate-500"><i class="fas fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($conflict->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($conflict->waktu_selesai)->format('H:i') }}</div>
                                        </div>
                                        <a href="{{ route('bookings.show', $conflict->id) }}" target="_blank" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 rounded-md font-bold text-xs transition-colors border border-red-200">Lihat Detail <i class="fas fa-external-link-alt ml-1"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                    </section>
                </div>

                {{-- RIGHT COLUMN: SECONDARY PANEL (STICKY) --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-8 space-y-8">
                        
                        {{-- A. Status Summary Card --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative overflow-hidden group" data-aos="fade-left" data-aos-once="true">
                            @php
                                $statusMeta = match($booking->status) {
                                    'pending'   => ['color' => 'amber',   'icon' => 'fa-clock',        'desc' => 'Menunggu persetujuan dari Admin Lab.'],
                                    'approved'  => ['color' => 'emerald', 'icon' => 'fa-check-circle', 'desc' => 'Jadwal disetujui. Silakan gunakan lab.'],
                                    'rejected'  => ['color' => 'red',     'icon' => 'fa-times-circle', 'desc' => 'Pengajuan jadwal ditolak oleh admin.'],
                                    'completed' => ['color' => 'slate',   'icon' => 'fa-clipboard-check', 'desc' => 'Kegiatan telah selesai dilaksanakan.'],
                                    default     => ['color' => 'indigo',  'icon' => 'fa-info-circle',  'desc' => 'Status tidak diketahui.']
                                };
                                $metaColor = $statusMeta['color'];
                            @endphp
                            
                            {{-- Decorative gradient blob --}}
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-{{$metaColor}}-50 rounded-full blur-2xl opacity-60 group-hover:opacity-100 transition-opacity"></div>
                            
                            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-4">Status Pengajuan</h3>
                            
                            <div class="flex items-center gap-4 mb-3 relative z-10">
                                <div class="w-12 h-12 rounded-xl bg-{{$metaColor}}-50 flex items-center justify-center text-{{$metaColor}}-500 text-xl border border-{{$metaColor}}-100 shadow-inner">
                                    <i class="fas {{ $statusMeta['icon'] }}"></i>
                                </div>
                                <div>
                                    <div class="font-extrabold text-lg text-slate-800 capitalize">{{ $booking->status }}</div>
                                    <div class="text-[11px] font-medium text-slate-500 leading-tight mt-0.5">{{ $statusMeta['desc'] }}</div>
                                </div>
                            </div>

                            @if($booking->admin_notes)
                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-1">
                                    <i class="fas fa-comment-dots text-indigo-400"></i> Catatan Admin
                                </div>
                                <div class="text-sm font-medium {{ $booking->status == 'rejected' ? 'text-red-600 bg-red-50' : 'text-slate-600 bg-slate-50' }} p-3 rounded-lg border border-slate-100">
                                    {{ $booking->admin_notes }}
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- B. Vertical Process Timeline --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6" data-aos="fade-left" data-aos-delay="100" data-aos-once="true">
                            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-6">Timeline Proses</h3>
                            
                            <div class="relative pl-3 space-y-8">
                                {{-- Background Track --}}
                                <div class="absolute left-[17px] top-4 bottom-4 w-0.5 bg-slate-100 rounded-full"></div>

                                {{-- Node 1: Diajukan --}}
                                <div class="relative flex items-start gap-4" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                                    <div class="w-2.5 h-2.5 rounded-full {{ in_array($booking->status, ['pending', 'approved', 'completed', 'rejected']) ? 'bg-indigo-500 ring-4 ring-indigo-50' : 'bg-slate-200' }} mt-1.5 z-10 shadow-sm relative"></div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-800">Booking Diajukan</div>
                                        <div class="text-[11px] font-medium text-slate-400 mt-0.5">{{ $booking->created_at->format('d M Y, H:i') }}</div>
                                    </div>
                                </div>

                                {{-- Node 2: Persetujuan --}}
                                @php
                                    $step2Active = in_array($booking->status, ['approved', 'completed']);
                                    $step2Rejected = $booking->status === 'rejected';
                                @endphp
                                <div class="relative flex items-start gap-4 group" data-aos="fade-up" data-aos-delay="300" data-aos-once="true">
                                    @if($step2Active || $step2Rejected)
                                        <div class="absolute -left-[5px] -top-8 bottom-4 w-0.5 {{ $step2Rejected ? 'bg-red-200' : 'bg-indigo-500' }}"></div>
                                    @endif
                                    
                                    <div class="w-2.5 h-2.5 rounded-full mt-1.5 z-10 shadow-sm relative transition-all {{ $step2Rejected ? 'bg-red-500 ring-4 ring-red-50' : ($step2Active ? 'bg-indigo-500 ring-4 ring-indigo-50' : 'bg-white border-2 border-slate-300 ring-4 ring-white') }}">
                                        {{-- Tooltip hover on node --}}
                                        <div class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 bg-slate-800 text-white text-[10px] font-bold py-1 px-2 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all whitespace-nowrap z-20">Tahap Persetujuan</div>
                                    </div>
                                    
                                    <div>
                                        @if($step2Rejected)
                                            <div class="text-sm font-bold text-red-600 drop-shadow-sm">Booking Ditolak</div>
                                            <div class="text-[11px] font-medium text-slate-400 mt-0.5">{{ $booking->updated_at->format('d M Y, H:i') }}</div>
                                        @elseif($step2Active)
                                            <div class="text-sm font-bold text-indigo-600">Jadwal Disetujui</div>
                                            <div class="text-[11px] font-medium text-slate-400 mt-0.5">Siap digunakan</div>
                                        @else
                                            <div class="text-sm font-bold text-slate-400">Persetujuan Admin</div>
                                            <div class="text-[10px] font-bold text-amber-500 bg-amber-50 px-2 py-0.5 rounded-md inline-block mt-1">Pending</div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Node 3: Selesai --}}
                                @if(!$step2Rejected)
                                    @php
                                        $step3Active = $booking->status === 'completed';
                                    @endphp
                                    <div class="relative flex items-start gap-4 group" data-aos="fade-up" data-aos-delay="400" data-aos-once="true">
                                        @if($step3Active)
                                            <div class="absolute -left-[5px] -top-8 bottom-4 w-0.5 bg-emerald-500"></div>
                                        @endif
                                        
                                        <div class="w-2.5 h-2.5 rounded-full mt-1.5 z-10 shadow-sm relative transition-all {{ $step3Active ? 'bg-emerald-500 ring-4 ring-emerald-50' : 'bg-white border-2 border-slate-300 ring-4 ring-white' }}"></div>
                                        
                                        <div>
                                            @if($step3Active)
                                                <div class="text-sm font-bold text-emerald-600">Selesai Digunakan</div>
                                                <div class="text-[11px] font-medium text-slate-400 mt-0.5">{{ $booking->waktu_pengembalian ? \Carbon\Carbon::parse($booking->waktu_pengembalian)->format('d M Y, H:i') : $booking->updated_at->format('d M Y, H:i') }}</div>
                                            @else
                                                <div class="text-sm font-bold text-slate-400">Penyelesaian</div>
                                                <div class="text-[10px] font-medium text-slate-400 mt-0.5">Menunggu kegiatan selesai</div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- C. Quick Actions Panel (Hanya untuk Admin) --}}
                        @can('is-admin')
                            @if(in_array($booking->status, ['pending', 'approved']))
                            <div class="bg-slate-800 text-white rounded-2xl shadow-lg border border-slate-700 p-6 relative overflow-hidden" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                                <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
                                
                                <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-4">Tindakan Cepat</h3>
                                
                                <div class="space-y-3">
                                    @if($booking->status == 'pending')
                                        <button @click="showModal = true; modalType = 'approved'; actionTitle = 'Setujui Jadwal'; actionColor = 'emerald'; actionIcon = 'fa-check'; btnColor = 'bg-emerald-500 hover:bg-emerald-600 text-white shadow-[0_4px_14px_0_rgba(16,185,129,0.39)]'" 
                                                class="w-full relative flex justify-center items-center py-3 px-4 rounded-xl font-bold text-sm bg-emerald-500 hover:bg-emerald-400 shadow-[0_4px_14px_0_rgba(16,185,129,0.39)] hover:shadow-[0_6px_20px_rgba(16,185,129,0.23)] transition-all hover:-translate-y-0.5 text-white">
                                            <i class="fas fa-check mr-2"></i> Approve Jadwal
                                        </button>
                                        
                                        <button @click="showModal = true; modalType = 'rejected'; actionTitle = 'Tolak Jadwal'; actionColor = 'red'; actionIcon = 'fa-times'; btnColor = 'bg-red-500 hover:bg-red-600 text-white shadow-sm'" 
                                                class="w-full relative flex justify-center items-center py-3 px-4 rounded-xl font-bold text-sm bg-slate-700 hover:bg-slate-600 text-slate-300 hover:text-white transition-all">
                                            <i class="fas fa-times mr-2"></i> Reject Jadwal
                                        </button>
                                    @elseif($booking->status == 'approved')
                                        <button @click="showModal = true; modalType = 'completed'; actionTitle = 'Tandai Selesai'; actionColor = 'indigo'; actionIcon = 'fa-flag-checkered'; btnColor = 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-[0_4px_14px_0_rgba(79,70,229,0.39)]'" 
                                                class="w-full relative flex justify-center items-center py-3 px-4 rounded-xl font-bold text-sm bg-indigo-600 hover:bg-indigo-500 shadow-[0_4px_14px_0_rgba(79,70,229,0.39)] hover:shadow-[0_6px_20px_rgba(79,70,229,0.23)] transition-all hover:-translate-y-0.5 text-white">
                                            <i class="fas fa-clipboard-check mr-2"></i> Mark as Completed
                                        </button>
                                    @endif

                                    <div class="border-t border-slate-700 pt-3 mt-4 text-center">
                                        <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data jadwal ini sepenuhnya secara permanen?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest hover:text-red-400 transition-colors">
                                                <i class="fas fa-trash-alt mr-1"></i> Hapus Permanen
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endcan

                        {{-- Metadata Footprint --}}
                        <div class="text-center">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ID Booking: <span class="text-slate-500">{{ $booking->id }}</span></p>
                            <p class="text-[10px] font-medium text-slate-400 mt-1">Terakhir update: {{ $booking->updated_at->diffForHumans() }}</p>
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
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                     class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModal = false"></div>
                
                {{-- Modal Panel --}}
                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-lg w-full relative z-10 overflow-hidden">
                    
                    {{-- Header Modal Dinamis --}}
                    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center" :class="'bg-' + actionColor + '-50/50'">
                        <h3 class="font-extrabold text-lg text-slate-800 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm" :class="'bg-' + actionColor + '-100 text-' + actionColor + '-600'"><i class="fas" :class="actionIcon"></i></div>
                            <span x-text="actionTitle"></span>
                        </h3>
                        <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex justify-center items-center rounded-lg hover:bg-slate-100"><i class="fas fa-times"></i></button>
                    </div>
                    
                    {{-- Body Modal (Form) --}}
                    <form action="{{ route('bookings.update', $booking->id) }}" method="POST" class="p-6">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" x-model="modalType">
                        
                        <div class="mb-6">
                            <template x-if="modalType === 'approved'">
                                <div>
                                    <p class="text-sm text-slate-600 leading-relaxed font-medium">Apakah Anda yakin ingin <strong class="text-emerald-600">menyetujui</strong> jadwal ini? Jadwal ini akan terlihat publik oleh seluruh instansi sekolah.</p>
                                    @if($conflict)
                                        <div class="mt-4 bg-red-50 border border-red-200 p-3 rounded-lg flex items-start gap-2">
                                            <i class="fas fa-exclamation-triangle text-red-500 mt-0.5"></i>
                                            <p class="text-xs text-red-700 font-bold">Peringatan: Terdapat potensi jadwal bentrok jika disetujui, lihat panel peringatan di background.</p>
                                        </div>
                                    @endif
                                </div>
                            </template>
                            
                            <template x-if="modalType === 'completed'">
                                <p class="text-sm text-slate-600 leading-relaxed font-medium">Tandai kegiatan ini telah selesai dilaksanakan pada laboratorium terkait?</p>
                            </template>
                            
                            {{-- Field Spesifik untuk Penolakan (WAJIB) --}}
                            <template x-if="modalType === 'rejected'">
                                <div>
                                    <p class="text-sm text-slate-600 leading-relaxed font-medium mb-4">Pengajuan yang ditolak tidak dapat diubah statusnya lagi. Harap masukkan alasan.</p>
                                    <label for="admin_notes" class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-widest mb-2 flex flex-col gap-1">
                                        Alasan Penolakan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="admin_notes" id="admin_notes" rows="3" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-red-500 focus:ring-red-500/20 text-sm p-4 bg-slate-50 hover:bg-white focus:bg-white transition-colors resize-none placeholder-slate-400" placeholder="Jadwal bentrok..."></textarea>
                                </div>
                            </template>
                        </div>
                        
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-colors text-sm">Batal</button>
                            <button type="submit" :class="btnColor" class="px-6 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <span x-text="actionTitle"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>
