@php
    $statusColors = [
        'pending' => ['bg' => 'bg-amber-100/80', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'icon' => 'fa-clock'],
        'approved' => ['bg' => 'bg-emerald-100/80', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'icon' => 'fa-check-circle'],
        'rejected' => ['bg' => 'bg-red-100/80', 'text' => 'text-red-700', 'border' => 'border-red-200', 'icon' => 'fa-times-circle'],
        'completed' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'border' => 'border-slate-300', 'icon' => 'fa-box-check'],
    ];

    $cfg = $statusColors[$booking->status] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'border' => 'border-slate-200', 'icon' => 'fa-info-circle'];
@endphp

<a href="{{ route('bookings.show', $booking->id) }}" class="block p-5 sm:p-6 bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-green-300 transition-all duration-300 hover:-translate-y-1 outline-none group relative overflow-hidden">
    
    <div class="flex flex-col md:flex-row gap-6 md:items-center justify-between">
        
        {{-- PANEL 1: DATE & TIME (KIRI) --}}
        <div class="flex flex-col md:w-1/4 xl:w-1/5 shrink-0 border-b md:border-b-0 md:border-r border-slate-100 pb-4 md:pb-0">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5"><i class="fas fa-calendar-day mr-1"></i> {{ $booking->waktu_mulai->translatedFormat('d M Y') }}</div>
            <div class="flex items-center gap-2">
                <span class="text-2xl font-black text-slate-800 tracking-tighter">{{ $booking->waktu_mulai->format('H:i') }}</span>
                <span class="text-slate-300 font-medium text-sm">to</span>
                <span class="text-lg font-bold text-slate-500">{{ $booking->waktu_selesai->format('H:i') }}</span>
            </div>
        </div>

        {{-- PANEL 2: DETAIL METADATA (TENGAH) --}}
        <div class="flex-grow flex flex-col justify-center min-w-0 pr-0 md:pr-4">
            <h4 class="text-lg font-black text-slate-900 group-hover:text-green-700 transition-colors truncate mb-2.5">{{ $booking->tujuan_kegiatan }}</h4>
            
            <div class="flex flex-wrap items-center gap-3">
                <span class="inline-flex items-center justify-center px-2.5 py-1.5 text-[11px] font-bold uppercase tracking-wider bg-slate-50 text-slate-600 border border-slate-200 rounded-md shadow-sm whitespace-nowrap">
                    <i class="fas fa-flask mr-1.5 text-slate-400"></i> {{ $booking->laboratorium }}
                </span>
                
                <span class="inline-flex items-center justify-center px-2.5 py-1.5 text-[11px] font-bold uppercase tracking-wider bg-slate-50 text-slate-600 border border-slate-200 rounded-md shadow-sm whitespace-nowrap">
                    <i class="fas fa-users mr-1.5 text-slate-400"></i> {{ $booking->jumlah_peserta ?? '-' }} Orang
                </span>

                @if (auth()->user()->role == 'admin')
                <div class="flex items-center gap-2 ml-aut sm:ml-2 mt-2 sm:mt-0 opacity-80">
                    <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500 shrink-0 border border-slate-300">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="text-[11px] font-extrabold uppercase tracking-wide text-slate-500 truncate max-w-[120px]">{{ $booking->user->name }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- PANEL 3: STATUS & ACTION (KANAN) --}}
        <div class="flex flex-row md:flex-col items-center md:items-end justify-between md:justify-center gap-4 shrink-0 md:w-[150px] pt-4 md:pt-0 border-t md:border-t-0 border-slate-100">
            
            {{-- Badge Status --}}
            <span class="inline-flex items-center px-3.5 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $cfg['bg'] }} {{ $cfg['text'] }} {{ $cfg['border'] }} border shadow-sm">
                <i class="fas {{ $cfg['icon'] }} mr-1.5 text-[11px]"></i> 
                {{ match($booking->status) {
                    'pending' => 'Pending',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    'completed' => 'Selesai',
                    default => ucfirst($booking->status)
                } }}
            </span>

            {{-- Quick action jika role admin & pending --}}
            <div class="flex items-center gap-2" onclick="event.stopPropagation();">
                @if(auth()->user()->role == 'admin' && $booking->status == 'pending')
                    <form action="{{ route('bookings.update', $booking->id) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white border border-emerald-200 transition-all flex items-center justify-center shadow-sm" title="Setujui">
                            <i class="fas fa-check text-sm"></i>
                        </button>
                    </form>
                    <form action="{{ route('bookings.update', $booking->id) }}" method="POST" class="inline">
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
