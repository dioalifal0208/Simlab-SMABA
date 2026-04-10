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

        // Color coding by Lab mapped to Standard UI
        $labColor = match($booking->laboratorium) {
            'Biologi' => 'emerald',
            'Fisika' => 'teal',
            'Bahasa' => 'amber',
            default => 'green' // Default inc. Komputer
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
        
        {{-- HEADER --}}
        <div class="bg-white pt-6 pb-2 border-b border-slate-100 mb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <a href="{{ route('bookings.index') }}" class="text-[11px] font-extrabold text-slate-400 hover:text-green-600 transition-colors uppercase tracking-widest flex items-center gap-1.5 mb-2 group">
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
                        <a href="{{ route('bookings.surat', $booking->id) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-xl font-bold text-xs text-slate-700 hover:bg-slate-50 hover:text-green-600 transition-all shadow-sm">
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
                <div class="mb-8 bg-white border-l-4 border-l-emerald-500 p-4 rounded-xl shadow-sm flex items-start gap-4" data-aos="fade-in">
                    <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0 mt-0.5"><i class="fas fa-check"></i></div>
                    <div>
                        <h4 class="font-bold text-slate-800">{{ __('common.messages.success') }}</h4>
                        <p class="text-sm text-slate-600 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-8 bg-white border-l-4 border-l-red-500 p-4 rounded-xl shadow-sm flex items-start gap-4" data-aos="fade-in">
                    <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-600 flex-shrink-0 mt-0.5"><i class="fas fa-exclamation-triangle"></i></div>
                    <div>
                        <h4 class="font-bold text-slate-800">{{ __('common.messages.error_title') }}</h4>
                        <ul class="mt-1 space-y-1 text-sm text-slate-600 list-inside list-disc">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- MAIN SAAS LAYOUT: 2 COLUMN (65:35) --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pb-10">
                
                {{-- LEFT COLUMN: PRIMARY CONTENT (65%) --}}
                <div class="lg:col-span-8 space-y-8">
                    
                    {{-- 1. Booking Information Card --}}
                    <section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" data-aos="fade-up" data-aos-once="true">
                        <div class="p-6 md:p-8">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <i class="fas fa-info-circle text-green-500 text-sm"></i> Informasi Kegiatan
                            </h3>
                            
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-6 mb-6">
                                <div>
                                    <h4 class="text-2xl font-black text-slate-800 tracking-tight">{{ $booking->tujuan_kegiatan }}</h4>
                                    <p class="text-sm text-slate-500 font-medium mt-2 flex items-center gap-2"><i class="fas fa-user-circle text-slate-400"></i> Diajukan oleh: <span class="font-bold text-slate-800">{{ $booking->user->name }}</span></p>
                                </div>
                                <div class="bg-slate-50 border border-slate-200 rounded-xl px-5 py-3 text-center min-w-[140px] shadow-sm shrink-0">
                                    <div class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Laboratorium</div>
                                    <div class="text-base font-black text-slate-700 mt-1 flex items-center justify-center gap-1.5">
                                        <i class="fas fa-flask text-green-600"></i> {{ $booking->laboratorium }}
                                    </div>
                                </div>
                            </div>

                            @if($booking->mata_pelajaran)
                            <div class="mb-2">
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">{{ __('bookings.details.subject') }}</div>
                                <div class="text-sm font-bold text-slate-800 inline-block bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm">{{ $booking->mata_pelajaran }}</div>
                            </div>
                            @endif
                        </div>
                    </section>

                    {{-- 2. Schedule & Time Block Card --}}
                    <section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                        <div class="p-6 md:p-8">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <i class="fas fa-calendar-alt text-green-500 text-sm"></i> Detail Jadwal & Waktu
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Tanggal</div>
                                    <div class="text-sm font-black text-slate-800">{{ $startTime->translatedFormat('d F Y') }}</div>
                                    <div class="text-xs font-bold text-slate-400 mt-0.5">{{ $startTime->translatedFormat('l') }}</div>
                                </div>
                                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Waktu Pelaksanaan</div>
                                    <div class="text-[17px] font-black text-slate-800 flex items-center gap-2 tracking-tighter">
                                        {{ $startTime->format('H:i') }} <span class="text-slate-300 font-medium text-sm">to</span> {{ $endTime->format('H:i') }}
                                    </div>
                                </div>
                                <div class="bg-green-50 p-4 rounded-xl border border-green-100 shadow-sm">
                                    <div class="text-[10px] font-bold text-green-600 uppercase tracking-widest mb-1.5">Total Durasi</div>
                                    <div class="text-sm font-black text-green-700">{{ $durationText }}</div>
                                </div>
                            </div>

                            {{-- CSS Grid Horizontal Time Visualizer --}}
                            <div class="mb-2">
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Timeline Penggunaan Ruangan (07:00 - 16:00)</div>
                                <div class="relative w-full h-14 bg-slate-50 rounded-xl overflow-hidden shadow-inner border border-slate-200 group">
                                    {{-- The specific booking block --}}
                                    <div class="absolute top-0 bottom-0 bg-green-500 transition-all duration-1000 ease-out flex items-center justify-center hover:bg-green-600 hover:shadow-lg z-10"
                                         style="left: {{ $leftPercent }}%; width: {{ $widthPercent }}%; border-radius: 6px;"
                                         title="Durasi: {{ $durationText }}">
                                        @if($widthPercent > 12)
                                            <span class="text-white text-[10px] font-black px-2 truncate relative z-10 drop-shadow-md">{{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }}</span>
                                        @endif
                                    </div>

                                    {{-- Hour Ticks Track --}}
                                    <div class="absolute inset-0 flex justify-between px-2 pt-9 z-0 pointer-events-none opacity-40">
                                        @for($h = 7; $h <= 16; $h++)
                                            <div class="flex flex-col items-center">
                                                <div class="h-2 w-[1.5px] bg-slate-300"></div>
                                                <span class="text-[8px] font-bold text-slate-400 mt-0.5">{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}</span>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Conflict Detection Alert (Edge State) --}}
                        @if($conflict)
                        <div class="p-6 md:p-8 bg-red-50 border-t border-red-100">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-red-600 flex-shrink-0 shadow-sm border border-red-200 mt-0.5">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="w-full">
                                    <h4 class="font-bold text-red-900 flex items-center gap-2 text-lg">
                                        Peringatan: Jadwal Bentrok!
                                    </h4>
                                    <p class="text-sm text-red-700 mt-1 leading-relaxed font-medium">
                                        Terdapat jadwal / booking lain yang <strong>telah disetujui</strong> pada waktu yang bersinggungan di Lab {{ $booking->laboratorium }}.
                                    </p>
                                    
                                    <div class="mt-4 bg-white border border-red-200 rounded-xl p-4 shadow-sm flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                                        <div>
                                            <div class="font-extrabold text-slate-800">{{ $conflict->tujuan_kegiatan }}</div>
                                            <div class="text-xs font-bold text-slate-500 mt-1"><i class="fas fa-clock mr-1 text-slate-400"></i> {{ \Carbon\Carbon::parse($conflict->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($conflict->waktu_selesai)->format('H:i') }}</div>
                                        </div>
                                        <a href="{{ route('bookings.show', $conflict->id) }}" target="_blank" class="px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-lg font-bold text-xs transition-colors border border-slate-200 shadow-sm whitespace-nowrap text-center">Lihat Konflik <i class="fas fa-external-link-alt ml-1 text-slate-400"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                    </section>
                </div>

                {{-- RIGHT COLUMN: SECONDARY PANEL (35% -> col-span-4) --}}
                <div class="lg:col-span-4">
                    <div class="sticky top-8 space-y-6">
                        
                        {{-- A. HEAVILY DOMINANT STATUS CARD --}}
                        @php
                            $statusMeta = match($booking->status) {
                                'pending'   => ['bg' => 'bg-amber-50', 'border' => 'border-amber-200', 'text' => 'text-amber-800', 'iconText' => 'text-amber-600', 'icon' => 'fa-clock', 'desc' => 'Menunggu persetujuan Admin', 'ring' => 'ring-amber-500 shadow-amber-500/20'],
                                'approved'  => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-800', 'iconText' => 'text-emerald-600', 'icon' => 'fa-check-circle', 'desc' => 'Jadwal disetujui. Siap digunakan.', 'ring' => 'ring-emerald-500 shadow-emerald-500/20'],
                                'rejected'  => ['bg' => 'bg-red-50', 'border' => 'border-red-200', 'text' => 'text-red-800', 'iconText' => 'text-red-600', 'icon' => 'fa-times-circle', 'desc' => 'Pengajuan jadwal ditolak.', 'ring' => 'ring-red-500 shadow-red-500/20'],
                                'completed' => ['bg' => 'bg-slate-50', 'border' => 'border-slate-200', 'text' => 'text-slate-800', 'iconText' => 'text-slate-600', 'icon' => 'fa-clipboard-check', 'desc' => 'Kegiatan telah direkap selesai.', 'ring' => 'ring-slate-500 shadow-slate-500/20'],
                            };
                        @endphp
                        
                        <div class="rounded-2xl shadow-sm border {{ $statusMeta['bg'] }} {{ $statusMeta['border'] }} p-6 relative overflow-hidden group" data-aos="fade-left" data-aos-once="true">
                            <h3 class="text-[10px] font-black {{ $statusMeta['iconText'] }} opacity-70 uppercase tracking-widest mb-4">Status Pengajuan</h3>
                            
                            <div class="flex items-center gap-4 relative z-10">
                                <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center {{ $statusMeta['iconText'] }} text-2xl border {{ $statusMeta['border'] }} shadow-sm">
                                    <i class="fas {{ $statusMeta['icon'] }}"></i>
                                </div>
                                <div>
                                    <div class="font-black text-xl {{ $statusMeta['text'] }} capitalize tracking-tight">{{ $booking->status }}</div>
                                    <div class="text-xs font-bold {{ $statusMeta['iconText'] }} opacity-80 leading-tight mt-0.5">{{ $statusMeta['desc'] }}</div>
                                </div>
                            </div>

                            @if($booking->admin_notes)
                            <div class="mt-5 pt-5 border-t {{ $statusMeta['border'] }}">
                                <div class="text-[10px] font-black {{ $statusMeta['iconText'] }} opacity-70 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                                    <i class="fas fa-comment-dots text-sm"></i> Catatan Admin
                                </div>
                                <div class="text-sm font-bold bg-white/60 p-3 rounded-xl border {{ $statusMeta['border'] }} {{ $statusMeta['text'] }}">
                                    {{ $booking->admin_notes }}
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- B. HIGHLY VISUAL VERTICAL TIMELINE --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6" data-aos="fade-left" data-aos-delay="100" data-aos-once="true">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Timeline Proses</h3>
                            
                            <div class="relative pl-3 space-y-8">
                                {{-- Background Track --}}
                                <div class="absolute left-[17px] top-4 bottom-4 w-0.5 bg-slate-100 rounded-full"></div>

                                {{-- Node 1: Diajukan --}}
                                <div class="relative flex items-start gap-4">
                                    <div class="w-2.5 h-2.5 rounded-full {{ in_array($booking->status, ['pending', 'approved', 'completed', 'rejected']) ? 'bg-green-500 ring-4 ring-green-100 shadow-sm shadow-green-500/40' : 'bg-slate-200' }} mt-1 z-10 shadow-sm relative transition-all"></div>
                                    <div>
                                        <div class="text-sm font-black text-slate-800">Booking Diajukan</div>
                                        <div class="text-[11px] font-bold text-slate-400 mt-0.5">{{ $booking->created_at->format('d M Y, H:i') }}</div>
                                    </div>
                                </div>

                                {{-- Node 2: Persetujuan --}}
                                @php
                                    $step2Active = in_array($booking->status, ['approved', 'completed']);
                                    $step2Rejected = $booking->status === 'rejected';
                                @endphp
                                <div class="relative flex items-start gap-4 group">
                                    @if($step2Active || $step2Rejected)
                                        <div class="absolute -left-[5px] -top-8 bottom-4 w-0.5 {{ $step2Rejected ? 'bg-red-200' : 'bg-green-500' }}"></div>
                                    @endif
                                    
                                    <div class="w-2.5 h-2.5 rounded-full mt-1 z-10 shadow-sm relative transition-all {{ $step2Rejected ? 'bg-red-500 ring-4 ring-red-100' : ($step2Active ? 'bg-green-500 ring-4 ring-green-100 shadow-sm shadow-green-500/40' : 'bg-white border-2 border-slate-300 ring-4 ring-slate-50') }}">
                                        @if($booking->status === 'pending')
                                            {{-- Pulser for pending phase --}}
                                            <div class="absolute -inset-1 rounded-full border-2 border-amber-400 animate-pulse hidden group-hover:block"></div>
                                        @endif
                                    </div>
                                    
                                    <div>
                                        @if($step2Rejected)
                                            <div class="text-sm font-black text-red-600">Booking Ditolak</div>
                                            <div class="text-[11px] font-bold text-red-400 mt-0.5">{{ $booking->updated_at->format('d M Y, H:i') }}</div>
                                        @elseif($step2Active)
                                            <div class="text-sm font-black text-green-600">Jadwal Disetujui</div>
                                            <div class="text-[11px] font-bold text-slate-400 mt-0.5">Siap digunakan</div>
                                        @else
                                            <div class="text-sm font-black text-slate-400">Persetujuan Admin</div>
                                            <div class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md inline-flex items-center mt-1.5 border border-amber-200">
                                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5 animate-pulse"></span> Pending
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Node 3: Selesai --}}
                                @if(!$step2Rejected)
                                    @php
                                        $step3Active = $booking->status === 'completed';
                                    @endphp
                                    <div class="relative flex items-start gap-4 group">
                                        @if($step3Active)
                                            <div class="absolute -left-[5px] -top-8 bottom-4 w-0.5 bg-green-500"></div>
                                        @endif
                                        
                                        <div class="w-2.5 h-2.5 rounded-full mt-1 z-10 shadow-sm relative transition-all {{ $step3Active ? 'bg-slate-700 ring-4 ring-slate-100 shadow-sm' : 'bg-white border-2 border-slate-300 ring-4 ring-slate-50' }}">
                                            @if($booking->status === 'approved')
                                                <div class="absolute -inset-1 rounded-full border-2 border-blue-400 animate-pulse hidden group-hover:block"></div>
                                            @endif
                                        </div>
                                        
                                        <div>
                                            @if($step3Active)
                                                <div class="text-sm font-black text-slate-800">Selesai Digunakan</div>
                                                <div class="text-[11px] font-bold text-slate-400 mt-0.5">{{ $booking->waktu_pengembalian ? \Carbon\Carbon::parse($booking->waktu_pengembalian)->format('d M Y, H:i') : $booking->updated_at->format('d M Y, H:i') }}</div>
                                            @else
                                                <div class="text-sm font-black text-slate-400">Penyelesaian</div>
                                                <div class="text-[10px] font-bold text-slate-400 mt-1">Belum ditandai selesai</div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- C. ACTION PANEL HIERARCHY (Hanya untuk Admin) --}}
                        @can('is-admin')
                            @if(in_array($booking->status, ['pending', 'approved']))
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Aksi Operator</h3>
                                
                                <div class="space-y-3">
                                    @if($booking->status == 'pending')
                                        {{-- Primary Action: Massive Green Button --}}
                                        <button @click="showModal = true; modalType = 'approved'; actionTitle = 'Setujui Jadwal'; actionColor = 'emerald'; actionIcon = 'fa-check'; btnColor = 'bg-green-600 hover:bg-green-700 text-white shadow-md shadow-green-600/20'" 
                                                class="w-full relative flex justify-center items-center py-3.5 px-4 rounded-xl font-bold text-sm bg-green-600 hover:bg-green-700 shadow-md shadow-green-600/20 transition-all hover:-translate-y-0.5 text-white active:scale-95">
                                            <i class="fas fa-check mr-2"></i> Approve Jadwal
                                        </button>
                                        
                                        {{-- Secondary Action: Muted Ghost Button --}}
                                        <button @click="showModal = true; modalType = 'rejected'; actionTitle = 'Tolak Jadwal'; actionColor = 'red'; actionIcon = 'fa-times'; btnColor = 'bg-white border text-white hover:bg-red-600 bg-red-500 shadow-sm'" 
                                                class="w-full relative flex justify-center items-center py-3 px-4 rounded-xl font-bold text-sm bg-transparent border-2 border-slate-200 text-slate-500 hover:text-red-600 hover:border-red-200 hover:bg-red-50 shadow-none transition-all group active:scale-95">
                                            <i class="fas fa-times mr-2 text-slate-400 group-hover:text-red-500"></i> Reject Jadwal
                                        </button>
                                    @elseif($booking->status == 'approved')
                                        <button @click="showModal = true; modalType = 'completed'; actionTitle = 'Tandai Selesai'; actionColor = 'emerald'; actionIcon = 'fa-flag-checkered'; btnColor = 'bg-slate-800 hover:bg-slate-900 text-white shadow-sm'" 
                                                class="w-full relative flex justify-center items-center py-3.5 px-4 rounded-xl font-bold text-sm bg-slate-800 hover:bg-slate-900 shadow-md shadow-slate-800/20 transition-all hover:-translate-y-0.5 text-white active:scale-95">
                                            <i class="fas fa-clipboard-check mr-2"></i> Mark as Completed
                                        </button>
                                    @endif

                                    <div class="border-t border-slate-100 pt-4 mt-5 text-center">
                                        <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data jadwal ini sepenuhnya secara permanen?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-red-500 transition-colors">
                                                <i class="fas fa-trash-alt mr-1"></i> Hapus Permanen File
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endcan

                        {{-- Metadata Footprint --}}
                        <div class="text-center pt-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ID Log: <span class="text-slate-500">SYS-BK-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span></p>
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
                                    <p class="text-sm text-slate-600 leading-relaxed font-medium">Apakah Anda yakin ingin <strong class="text-green-600">menyetujui</strong> jadwal ini? Jadwal ini akan terlihat publik oleh seluruh instansi sekolah.</p>
                                    @if($conflict)
                                        <div class="mt-4 bg-red-50 border border-red-200 p-3 rounded-xl flex items-start gap-2 shadow-sm">
                                            <i class="fas fa-exclamation-triangle text-red-500 mt-0.5"></i>
                                            <p class="text-[11px] font-bold text-red-700 leading-tight">Peringatan: Terdapat potensi jadwal bentrok jika disetujui, lihat panel peringatan di background.</p>
                                        </div>
                                    @endif
                                </div>
                            </template>
                            
                            <template x-if="modalType === 'completed'">
                                <p class="text-sm text-slate-600 leading-relaxed font-medium">Tandai kegiatan ini telah selesai dilaksanakan pada laboratorium terkait?</p>
                            </template>
                            
                            {{-- Field Spesifik untuk Penolakan --}}
                            <template x-if="modalType === 'rejected'">
                                <div>
                                    <p class="text-sm text-slate-600 leading-relaxed font-medium mb-4">Pengajuan yang ditolak tidak dapat diubah statusnya lagi. Harap masukkan alasan agar pengaju dapat mengetahui alasannya.</p>
                                    <label for="admin_notes" class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 flex gap-1">
                                        Alasan Penolakan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="admin_notes" id="admin_notes" rows="3" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-red-500 focus:ring-red-500/20 text-sm p-4 bg-slate-50 hover:bg-white focus:bg-white transition-colors resize-none placeholder-slate-400 font-medium" placeholder="Cth: Jadwal bentrok dengan ujian..."></textarea>
                                </div>
                            </template>
                        </div>
                        
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-slate-50 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-100 transition-colors text-sm shadow-sm rounded-lg">Batal</button>
                            <button type="submit" :class="btnColor" class="px-6 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <i class="fas fa-paper-plane mr-1 text-xs opacity-70 mt-0.5"></i> Konfirmasi Tindakan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>
