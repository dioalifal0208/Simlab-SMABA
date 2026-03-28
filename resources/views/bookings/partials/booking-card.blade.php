@php
    $statusColors = [
        'pending' => 'bg-white text-slate-800 border-slate-200 border-l-amber-500',
        'approved' => 'bg-white text-slate-800 border-slate-200 border-l-emerald-500',
        'rejected' => 'bg-slate-50 text-slate-500 border-slate-200 border-l-red-500 opacity-80',
        'completed' => 'bg-white text-slate-800 border-slate-200 border-l-slate-400',
    ];
    $statusColor = $statusColors[$booking->status] ?? 'bg-white text-slate-800 border-slate-200 border-l-slate-500';

    $statusIcons = [
        'pending' => 'fa-clock',
        'approved' => 'fa-check-circle',
        'rejected' => 'fa-times-circle',
        'completed' => 'fa-box-check',
    ];
    $statusIcon = $statusIcons[$booking->status] ?? 'fa-info-circle';
    
    $labColor = 'text-slate-500 bg-slate-50 border border-slate-200';
@endphp

<div class="bg-white rounded-2xl border {{ $statusColor }} border-l-4 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 overflow-hidden group">
    <div class="p-5 sm:p-6 flex flex-col md:flex-row gap-4 md:items-center justify-between">
        
        {{-- Kiri: Waktu & Status --}}
        <div class="flex flex-col md:w-1/4">
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-widest border border-white/50 bg-white/60">
                    <i class="fas {{ $statusIcon }} mr-1"></i> {{ match($booking->status) {
                        'pending' => __('common.status.pending'),
                        'approved' => __('common.status.approved'),
                        'rejected' => __('common.status.rejected'),
                        'completed' => __('common.status.completed'),
                        default => ucfirst($booking->status)
                    } }}
                </span>
            </div>
            
            <div class="text-sm font-extrabold text-slate-800">
                <i class="fas fa-calendar-alt text-slate-400 mb-1"></i> {{ $booking->waktu_mulai->translatedFormat('d M Y') }}
            </div>
            <div class="text-lg font-bold text-slate-800 tracking-tight flex items-center gap-2">
                {{ $booking->waktu_mulai->format('H:i') }} <span class="text-slate-300 text-sm font-light">to</span> {{ $booking->waktu_selesai->format('H:i') }}
            </div>
        </div>

        {{-- Tengah: Informasi Lab & User --}}
        <div class="flex-grow flex flex-col justify-center border-t md:border-t-0 md:border-l border-slate-100 pt-4 md:pt-0 md:pl-6">
            <div class="flex items-center gap-2 mb-1.5 opacity-80">
                <span class="text-xs font-bold px-2 py-0.5 rounded-md {{ $labColor }}">
                    <i class="fas fa-flask mr-1"></i> {{ $booking->laboratorium }}
                </span>
                <span class="text-xs text-slate-500 font-medium whitespace-nowrap"><i class="fas fa-users mr-1"></i> {{ $booking->jumlah_peserta ?? 'N/A' }} Peserta</span>
            </div>
            
            <h4 class="text-base font-bold text-slate-800 mb-2 truncate max-w-sm">{{ $booking->tujuan_kegiatan }}</h4>

            @if (auth()->user()->role == 'admin')
            <div class="flex items-center gap-2 mt-auto">
                <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500">
                    {{ substr($booking->user->name, 0, 1) }}
                </div>
                <div class="text-xs font-semibold text-slate-600">{{ $booking->user->name }}</div>
            </div>
            @endif
        </div>

        {{-- Kanan: Aksi --}}
        <div class="flex flex-row md:flex-col lg:flex-row items-center justify-end gap-2 pt-4 border-t md:border-t-0 border-slate-100 mt-4 md:mt-0 md:w-1/4">
            
            @if(auth()->user()->role == 'admin' && $booking->status == 'pending')
                {{-- Quick Actions Cepat --}}
                <div class="flex items-center gap-2 mr-auto md:mr-0 lg:mr-auto transition-opacity opacity-0 group-hover:opacity-100 max-md:opacity-100">
                    <form action="{{ route('bookings.update', $booking->id) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 hover:bg-emerald-500 hover:text-white border border-emerald-200 transition-all flex items-center justify-center shadow-sm" title="Setujui Cepat">
                            <i class="fas fa-check text-sm"></i>
                        </button>
                    </form>
                    <form action="{{ route('bookings.update', $booking->id) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-500 hover:text-white border border-red-200 transition-all flex items-center justify-center shadow-sm" title="Tolak Cepat">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </form>
                </div>
            @endif

            <a href="{{ route('bookings.show', $booking->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-white text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-green-600 hover:border-green-300 font-bold text-xs shadow-sm transition-all whitespace-nowrap w-full md:w-auto ml-auto">
                {{ __('common.buttons.details') }} <i class="fas fa-arrow-right ml-2 text-[10px]"></i>
            </a>
        </div>
    </div>
</div>
