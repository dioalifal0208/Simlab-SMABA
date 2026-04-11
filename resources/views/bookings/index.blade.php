<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    @if (auth()->user()->role == 'admin')
                        {{ __('bookings.title_admin') }}
                    @else
                        {{ __('bookings.title_user') }}
                    @endif
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    @if (auth()->user()->role == 'admin')
                        {{ __('bookings.subtitle_admin') }}
                    @else
                        {{ __('bookings.subtitle_user') }}
                    @endif
                </p>
            </div>
            
            <a id="tour-btn-create" href="{{ route('bookings.create') }}" class="mt-4 sm:mt-0 px-5 py-2.5 bg-green-600 text-white rounded-xl shadow-[0_4px_14px_0_rgb(34,197,94,0.39)] hover:shadow-[0_6px_20px_rgba(34,197,94,0.23)] hover:-translate-y-0.5 transition-all font-bold text-sm flex items-center gap-2">
                <i class="fas fa-plus"></i> {{ __('bookings.actions.create_new') }}
            </a>
        </div>

        {{-- Product Tour CSS & JS --}}
        <link rel="stylesheet" href="{{ asset('css/dashboard-tour.css') }}?v={{ time() }}">
        <script src="{{ asset('js/bookings-tour.js') }}?v={{ time() }}" defer></script>
    </x-slot>

    @php
        $viewMode = request('view', 'list');
        $startOfWeek = now()->startOfWeek(\Carbon\Carbon::MONDAY);
        $endOfWeek = now()->endOfWeek(\Carbon\Carbon::SUNDAY);
        
        // Custom query for calendar view to ignore pagination and get current week
        if ($viewMode === 'calendar') {
            $calendarQuery = \App\Models\Booking::with('user');
            if (Auth::user()->role !== 'admin') {
                $calendarQuery->where('user_id', Auth::id());
            }
            if (request('laboratorium')) {
                $calendarQuery->where('laboratorium', request('laboratorium'));
            }
            if (request('status')) {
                $calendarQuery->where('status', request('status'));
            }
            // Include items that overlap with this week
            $weeklyBookings = $calendarQuery->where(function ($q) use ($startOfWeek, $endOfWeek) {
                $q->whereBetween('waktu_mulai', [$startOfWeek, $endOfWeek])
                  ->orWhereBetween('waktu_selesai', [$startOfWeek, $endOfWeek]);
            })->orderBy('waktu_mulai')->get();
            
            // Group by day of week (1=Mon, 7=Sun)
            $bookingsByDay = [];
            for($i = 1; $i <= 7; $i++) {
                $bookingsByDay[$i] = collect();
            }
            
            foreach ($weeklyBookings as $b) {
                $dayId = \Carbon\Carbon::parse($b->waktu_mulai)->format('N'); // 1 (Mon) - 7 (Sun)
                if (isset($bookingsByDay[$dayId])) {
                    $bookingsByDay[$dayId]->push($b);
                }
            }
        }
    @endphp

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-white border border-slate-100 border-l-4 border-l-emerald-500 p-4 rounded-xl shadow-sm" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-emerald-500 mr-3 text-lg"></i>
                        <p class="font-bold text-emerald-800">{{ __('common.messages.success') }}: <span class="font-normal text-emerald-700">{{ session('success') }}</span></p>
                    </div>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 bg-white border border-slate-100 border-l-4 border-l-red-500 p-4 rounded-xl shadow-sm" role="alert">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3 text-lg mt-0.5"></i>
                        <div>
                            <p class="font-bold text-red-800">{{ __('common.messages.error_title') }}:</p>
                            <ul class="mt-1 list-disc list-inside text-red-700 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- FILTER & VIEW CONTROLS --}}
            <div id="tour-filter" class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white p-4 rounded-2xl shadow-sm border border-slate-100" data-aos="fade-up" data-aos-once="true">
                
                {{-- View Toggles --}}
                <div class="flex bg-slate-100 p-1 rounded-xl w-full lg:w-auto overflow-x-auto hide-scrollbar">
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}" class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-6 py-2 rounded-lg text-sm font-bold transition-all {{ $viewMode === 'list' ? 'bg-white text-green-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                        <i class="fas fa-list-ul"></i> List
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'calendar']) }}" class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-6 py-2 rounded-lg text-sm font-bold transition-all {{ $viewMode === 'calendar' ? 'bg-white text-green-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                        <i class="fas fa-calendar-alt"></i> Kalender <span class="hidden sm:inline">(Minggu Ini)</span>
                    </a>
                </div>

                {{-- Filter Forms --}}
                <form action="{{ route('bookings.index') }}" method="GET" id="filter-form" class="w-full lg:w-auto flex flex-col sm:flex-row items-center gap-3">
                    <input type="hidden" name="view" value="{{ $viewMode }}">
                    
                    {{-- Status Select --}}
                    <div class="relative w-full sm:w-auto bg-slate-50 rounded-xl border border-slate-200">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-info-circle text-slate-400"></i>
                        </div>
                        <select name="status" id="status" onchange="this.form.submit()" class="pl-10 pr-8 py-2.5 w-full sm:w-40 bg-transparent border-none focus:ring-0 text-sm font-semibold text-slate-700 cursor-pointer appearance-none">
                            <option value="">Semua Status</option>
                            <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                            <option value="approved" @selected(request('status') === 'approved')>Disetujui</option>
                            <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
                            <option value="completed" @selected(request('status') === 'completed')>Selesai</option>
                        </select>
                    </div>

                    {{-- Lab Select --}}
                    <div class="relative w-full sm:w-auto bg-slate-50 rounded-xl border border-slate-200">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-flask text-slate-400"></i>
                        </div>
                        <select name="laboratorium" id="laboratorium" onchange="this.form.submit()" class="pl-10 pr-8 py-2.5 w-full sm:w-48 bg-transparent border-none focus:ring-0 text-sm font-semibold text-slate-700 cursor-pointer appearance-none">
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

            @if ($viewMode === 'list')
                {{-- LIST VIEW MODE --}}
                
                @php
                    $todayBookings = collect();
                    $upcomingBookings = collect();
                    $pastBookings = collect();
                    
                    foreach ($bookings as $b) {
                        $start = \Carbon\Carbon::parse($b->waktu_mulai)->startOfDay();
                        $now = now()->startOfDay();
                        
                        if ($start->equalTo($now)) {
                            $todayBookings->push($b);
                        } elseif ($start->greaterThan($now)) {
                            $upcomingBookings->push($b);
                        } else {
                            $pastBookings->push($b);
                        }
                    }
                @endphp

                <div id="tour-booking-list" class="space-y-12 block">
                    
                    {{-- Hari Ini --}}
                    @if($todayBookings->isNotEmpty())
                    <div data-aos="fade-up" data-aos-delay="100">
                        <h3 class="text-sm font-extrabold tracking-widest text-slate-400 uppercase mb-4 flex items-center gap-2">
                            <i class="fas fa-calendar-day text-slate-400"></i> Hari Ini
                        </h3>
                        <div class="space-y-5">
                            @foreach($todayBookings as $booking)
                                @include('bookings.partials.booking-card', ['booking' => $booking])
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Akan Datang --}}
                    @if($upcomingBookings->isNotEmpty())
                    <div data-aos="fade-up" data-aos-delay="150">
                        <h3 class="text-sm font-extrabold tracking-widest text-slate-400 uppercase mb-4 flex items-center gap-2">
                            <i class="fas fa-calendar-plus text-slate-400"></i> Akan Datang
                        </h3>
                        <div class="space-y-5">
                            @foreach($upcomingBookings as $booking)
                                @include('bookings.partials.booking-card', ['booking' => $booking])
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Riwayat / Masa Lalu --}}
                    @if($pastBookings->isNotEmpty() || ($todayBookings->isEmpty() && $upcomingBookings->isEmpty()))
                    <div data-aos="fade-up" data-aos-delay="200">
                        <h3 class="text-sm font-extrabold tracking-widest text-slate-400 uppercase mb-4 flex items-center gap-2">
                            <i class="fas fa-history text-slate-400"></i> Riwayat & Selesai
                        </h3>
                        @if($pastBookings->isEmpty())
                            <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-slate-200">
                                <div class="w-20 h-20 mx-auto rounded-full bg-slate-50 flex items-center justify-center mb-4 text-slate-300">
                                    <i class="fas fa-folder-open text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-700 mb-1">Belum Ada Riwayat Jadwal</h3>
                                <p class="text-sm text-slate-500 mb-5">Filter yang Anda pilih kosong atau Anda belum memiliki jadwal.</p>
                                <a href="{{ route('bookings.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white rounded-xl shadow-[0_4px_14px_0_rgb(34,197,94,0.39)] hover:shadow-[0_6px_20px_rgba(34,197,94,0.23)] hover:-translate-y-0.5 transition-all text-sm font-bold">
                                    <i class="fas fa-plus"></i> Buat Jadwal Baru
                                </a>
                            </div>
                        @else
                            <div class="space-y-5">
                                @foreach($pastBookings as $booking)
                                    @include('bookings.partials.booking-card', ['booking' => $booking])
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @endif

                    {{-- Pagination Controls (Hanya untuk mode list) --}}
                    @if ($bookings->hasPages())
                    <div class="mt-8">
                        {{ $bookings->withQueryString()->links() }}
                    </div>
                    @endif
                </div>

            @else
                {{-- CALENDAR VIEW MODE (Minggu Ini) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" data-aos="fade-up">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-lg">
                                <i class="fas fa-calendar-week"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800">Jadwal Minggu Ini</h3>
                                <p class="text-sm text-slate-500">{{ $startOfWeek->translatedFormat('d F Y') }} - {{ $endOfWeek->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <div class="min-w-[800px] grid grid-cols-7 divide-x divide-slate-100 border-b border-slate-100 bg-slate-50/30 text-center text-xs font-bold text-slate-500 uppercase tracking-widest">
                            @php
                                $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                            @endphp
                            @foreach($days as $index => $day)
                                @php 
                                    $currentDate = $startOfWeek->copy()->addDays($index);
                                    $isToday = $currentDate->isToday();
                                @endphp
                                <div class="py-4 {{ $isToday ? 'bg-slate-100/80 text-slate-700' : '' }}">
                                    <div class="{{ $isToday ? 'font-extrabold' : '' }}">{{ $day }}</div>
                                    <div class="mt-1 {{ $isToday ? 'text-slate-600' : 'text-slate-400' }}">{{ $currentDate->format('d/m') }}</div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="min-w-[800px] grid grid-cols-7 divide-x divide-slate-100 bg-white min-h-[400px]">
                            @for ($i = 1; $i <= 7; $i++)
                                <div class="p-2 space-y-2 relative group {{ $startOfWeek->copy()->addDays($i-1)->isToday() ? 'bg-slate-50/50' : '' }}">
                                    @forelse($bookingsByDay[$i] as $b)
                                        @php
                                            // Warna berdasarkan status (monochrome)
                                            $colorClass = 'bg-white text-slate-700 border-slate-200 border-l-slate-400';
                                            
                                            if ($b->status == 'pending') $colorClass = 'bg-white text-slate-700 border-slate-200 border-l-amber-400';
                                            elseif ($b->status == 'approved') $colorClass = 'bg-white text-slate-700 border-slate-200 border-l-emerald-400';
                                            elseif ($b->status == 'rejected') $colorClass = 'bg-slate-50 text-slate-500 border-slate-200 border-l-red-500 opacity-60 line-through';
                                            elseif ($b->status == 'completed') $colorClass = 'bg-white text-slate-700 border-slate-200 border-l-slate-300';

                                        @endphp
                                        <a href="{{ route('bookings.show', $b->id) }}" class="block p-2.5 rounded-r-lg border border-l-4 shadow-sm hover:shadow-md transition-all cursor-pointer {{ $colorClass }} relative z-10 hover:z-20 hover:-translate-y-0.5">
                                            <div class="text-[10px] font-extrabold opacity-80 mb-0.5 flex items-center justify-between">
                                                <span>{{ \Carbon\Carbon::parse($b->waktu_mulai)->format('H:i') }}</span>
                                                @if($b->status == 'pending')<i class="fas fa-clock text-amber-500" title="Pending"></i>
                                                @elseif($b->status == 'approved')<i class="fas fa-check-circle text-emerald-500" title="Disetujui"></i>
                                                @endif
                                            </div>
                                            <div class="text-xs font-bold leading-tight truncate" title="{{ $b->tujuan_kegiatan }}">{{ $b->tujuan_kegiatan }}</div>
                                            <div class="text-[10px] mt-1 opacity-70 truncate flex items-center gap-1">
                                                <i class="fas fa-user"></i> {{ explode(' ', $b->user->name)[0] }}
                                            </div>
                                        </a>
                                    @empty
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span class="text-xs font-bold text-slate-300">Kosong</span>
                                        </div>
                                    @endforelse
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
