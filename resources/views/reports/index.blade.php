<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">Laporan & Analitik</h2>
                <p class="text-sm font-medium text-slate-500 mt-1">Pantau tren penggunaan laboratorium dan aktivitas inventaris secara real-time.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- 1. FILTER & EXPORT BAR --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4" data-aos="fade-down" data-aos-once="true">
                <div class="flex items-center gap-2 text-sm font-bold text-slate-500">
                    <i class="fas fa-filter text-slate-400"></i>
                    <span>Filter & Ekspor Data</span>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    {{-- Export Peminjaman --}}
                    <form action="{{ route('reports.export-loans') }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <select name="month" class="text-xs border border-slate-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 font-bold text-slate-600 bg-white py-2 px-3 shadow-sm">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $i, 1)->translatedFormat('F') }}</option>
                            @endfor
                        </select>
                        <select name="year" class="text-xs border border-slate-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 font-bold text-slate-600 bg-white py-2 pl-3 pr-8 shadow-sm">
                            @for ($i = date('Y') - 1; $i <= date('Y') + 1; $i++)
                                <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl transition-colors text-xs font-bold shadow-sm" title="Ekspor Peminjaman Alat">
                            <i class="fas fa-file-excel"></i> Ekspor Peminjaman
                        </button>
                    </form>

                    {{-- Export Booking --}}
                    <form action="{{ route('reports.export') }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-xl transition-colors text-xs font-bold shadow-sm" title="Ekspor Booking Jadwal">
                            <i class="fas fa-file-pdf text-slate-400"></i> Ekspor Booking
                        </button>
                    </form>
                </div>
            </div>

            {{-- 2. SUMMARY CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Card 1: Total Peminjaman --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 group hover:-translate-y-0.5 transition-all duration-300" data-aos="fade-up" data-aos-delay="0" data-aos-once="true">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Total Peminjaman</p>
                            <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($currentBorrowings) }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center text-lg border border-slate-100 group-hover:scale-110 transition-transform">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        @if($borrowingTrend >= 0)
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600"><i class="fas fa-arrow-trend-up"></i> +{{ $borrowingTrend }}%</span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-red-500"><i class="fas fa-arrow-trend-down"></i> {{ $borrowingTrend }}%</span>
                        @endif
                        <span class="text-xs font-medium text-slate-400">vs bulan lalu</span>
                    </div>
                </div>

                {{-- Card 2: Total Booking --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 group hover:-translate-y-0.5 transition-all duration-300" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Total Jadwal Lab</p>
                            <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($currentBookings) }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center text-lg border border-slate-100 group-hover:scale-110 transition-transform">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        @if($bookingTrend >= 0)
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600"><i class="fas fa-arrow-trend-up"></i> +{{ $bookingTrend }}%</span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-red-500"><i class="fas fa-arrow-trend-down"></i> {{ $bookingTrend }}%</span>
                        @endif
                        <span class="text-xs font-medium text-slate-400">vs bulan lalu</span>
                    </div>
                </div>

                {{-- Card 3: Lab Terpopuler --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 group hover:-translate-y-0.5 transition-all duration-300" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Lab Terpopuler</p>
                            <h3 class="text-xl font-extrabold text-slate-800 leading-tight">{{ $mostUsedLab }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center text-lg border border-slate-100 group-hover:scale-110 transition-transform">
                            <i class="fas fa-flask"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-600">{{ number_format($mostUsedLabCount) }} booking</span>
                        <span class="text-xs font-medium text-slate-400">bulan ini</span>
                    </div>
                </div>

                {{-- Card 4: Alat Paling Banyak Dipinjam --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 group hover:-translate-y-0.5 transition-all duration-300" data-aos="fade-up" data-aos-delay="300" data-aos-once="true">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Alat Terfavorit</p>
                            <h3 class="text-lg font-extrabold text-slate-800 leading-tight truncate" title="{{ $mostBorrowedItem }}">{{ Str::limit($mostBorrowedItem, 20) }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center text-lg border border-slate-100 group-hover:scale-110 transition-transform flex-shrink-0">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-600">{{ number_format($mostBorrowedItemCount) }} unit</span>
                        <span class="text-xs font-medium text-slate-400">dipinjam bulan ini</span>
                    </div>
                </div>
            </div>

            {{-- 3. KEY INSIGHTS --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-start gap-4" data-aos="fade-up" data-aos-once="true">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-lg border border-emerald-100 flex-shrink-0">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div>
                    <h4 class="text-sm font-extrabold text-slate-800">Insight Bulan Ini</h4>
                    <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                        Lab terpopuler bulan ini adalah <strong class="text-slate-700">{{ $mostUsedLab }}</strong> dengan <strong class="text-emerald-600">{{ number_format($mostUsedLabCount) }} booking</strong>.
                        Alat paling banyak dipinjam adalah <strong class="text-slate-700">{{ $mostBorrowedItem }}</strong> ({{ number_format($mostBorrowedItemCount) }} unit).
                        @if($borrowingTrend >= 0)
                            Tren peminjaman naik <strong class="text-emerald-600">+{{ $borrowingTrend }}%</strong> dibandingkan bulan lalu.
                        @else
                            Tren peminjaman turun <strong class="text-red-500">{{ $borrowingTrend }}%</strong> dibandingkan bulan lalu.
                        @endif
                    </p>
                </div>
            </div>

            {{-- 4. CHART SECTION --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Bar Chart: Tren Peminjaman (2 cols) --}}
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-6" data-aos="fade-in" data-aos-once="true"
                     x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 600)">
                    <div class="flex justify-between items-end mb-6">
                        <div>
                            <h3 class="font-extrabold text-lg text-slate-800">Tren Peminjaman Bulanan</h3>
                            <p class="text-xs font-medium text-slate-400 mt-1">Distribusi permintaan alat menurut bulan pada tahun {{ date('Y') }}.</p>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100"><i class="fas fa-chart-bar"></i></div>
                    </div>
                    {{-- Skeleton --}}
                    <div x-show="!loaded" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="h-72 w-full animate-pulse">
                        <div class="flex items-end justify-between gap-2 h-full px-4 pb-4">
                            <div class="w-full h-1/3 bg-slate-100 rounded-md"></div>
                            <div class="w-full h-2/3 bg-slate-100 rounded-md"></div>
                            <div class="w-full h-1/2 bg-slate-100 rounded-md"></div>
                            <div class="w-full h-3/4 bg-slate-100 rounded-md"></div>
                            <div class="w-full h-1/4 bg-slate-100 rounded-md"></div>
                            <div class="w-full h-2/5 bg-slate-100 rounded-md"></div>
                            <div class="w-full h-3/5 bg-slate-100 rounded-md"></div>
                            <div class="w-full h-1/2 bg-slate-100 rounded-md"></div>
                            <div class="w-full h-full bg-slate-100 rounded-md"></div>
                            <div class="w-full h-2/3 bg-slate-100 rounded-md"></div>
                            <div class="w-full h-1/3 bg-slate-100 rounded-md"></div>
                            <div class="w-full h-1/4 bg-slate-100 rounded-md"></div>
                        </div>
                    </div>
                    <div x-show="loaded" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="relative h-72 w-full">
                        <canvas id="loanBarChart"></canvas>
                    </div>
                </div>

                {{-- Donut Chart: Booking by Lab (1 col) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col" data-aos="fade-in" data-aos-delay="100" data-aos-once="true"
                     x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 800)">
                    <div class="flex justify-between items-end mb-6">
                        <div>
                            <h3 class="font-extrabold text-lg text-slate-800">Distribusi Lab</h3>
                            <p class="text-xs font-medium text-slate-400 mt-1">Mayoritas penggunaan lab bulan ini.</p>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100"><i class="fas fa-chart-pie"></i></div>
                    </div>
                    {{-- Skeleton --}}
                    <div x-show="!loaded" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="flex-grow flex items-center justify-center min-h-[250px]">
                        <div class="w-44 h-44 rounded-full border-[16px] border-slate-100 animate-pulse"></div>
                    </div>
                    <div x-show="loaded" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="relative flex-grow flex items-center justify-center w-full min-h-[250px]">
                        @if(count($labDistributionLabels) > 0)
                            <canvas id="labDonutChart" class="mx-auto max-h-56"></canvas>
                        @else
                            <div class="text-center">
                                <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-slate-100">
                                    <i class="fas fa-chart-pie text-slate-300 text-xl"></i>
                                </div>
                                <span class="text-sm font-bold text-slate-400">Tidak ada data distribusi.</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Line Chart: Tren Jadwal (Full width) --}}
                <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-slate-200 p-6" data-aos="fade-in" data-aos-once="true"
                     x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 1000)">
                    <div class="flex justify-between items-end mb-6">
                        <div>
                            <h3 class="font-extrabold text-lg text-slate-800">Tren Penggunaan Jadwal Lab</h3>
                            <p class="text-xs font-medium text-slate-400 mt-1">Frekuensi booking yang disetujui selama tahun berjalan.</p>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100"><i class="fas fa-chart-line"></i></div>
                    </div>
                    {{-- Skeleton --}}
                    <div x-show="!loaded" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="h-64 w-full animate-pulse">
                        <div class="h-full w-full bg-slate-50 rounded-xl flex items-end px-4 pb-4 gap-2">
                            <div class="w-full h-1/3 bg-slate-100 rounded-sm"></div>
                            <div class="w-full h-1/2 bg-slate-100 rounded-sm"></div>
                            <div class="w-full h-2/3 bg-slate-100 rounded-sm"></div>
                            <div class="w-full h-1/3 bg-slate-100 rounded-sm"></div>
                            <div class="w-full h-3/4 bg-slate-100 rounded-sm"></div>
                            <div class="w-full h-1/2 bg-slate-100 rounded-sm"></div>
                        </div>
                    </div>
                    <div x-show="loaded" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="relative h-64 w-full">
                        <canvas id="bookingLineChart"></canvas>
                    </div>
                </div>

            </div>

            {{-- 5. DATA TABLE (Riwayat Peminjaman Terbaru) --}}
            <div class="bg-white overflow-hidden shadow-sm border border-slate-200 sm:rounded-2xl" data-aos="fade-up" data-aos-once="true">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-extrabold text-lg text-slate-800 flex items-center gap-2">
                        <i class="fas fa-history text-slate-400"></i> Riwayat Transaksi Peminjaman
                    </h3>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Terbaru</span>
                </div>

                <div class="overflow-x-auto">
                    @if($recentTransactions->count() > 0)
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100">
                                <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-left">ID</th>
                                <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-left">Peminjam</th>
                                <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-left">Tanggal Pinjam</th>
                                <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-left">Status</th>
                                <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($recentTransactions as $loan)
                                <tr class="hover:bg-slate-50/60 transition-colors group">
                                    <td class="px-6 py-4 text-sm font-bold text-slate-700">#{{ str_pad($loan->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs mr-3 border border-slate-200">
                                                {{ strtoupper(substr($loan->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-slate-800">{{ $loan->user->name }}</div>
                                                <div class="text-[11px] font-medium text-slate-400 capitalize">{{ $loan->user->role }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 font-medium whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($loan->waktu_peminjaman)->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusConfig = match($loan->status) {
                                                'pending'  => ['color' => 'amber', 'label' => 'PENDING'],
                                                'approved' => ['color' => 'emerald', 'label' => 'APPROVED'],
                                                'returned' => ['color' => 'slate', 'label' => 'RETURNED'],
                                                default    => ['color' => 'red', 'label' => 'REJECTED'],
                                            };
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-{{ $statusConfig['color'] }}-600 tracking-wide">
                                            <span class="w-1.5 h-1.5 rounded-full bg-{{ $statusConfig['color'] }}-500"></span>
                                            {{ $statusConfig['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('loans.show', $loan->id) }}" class="inline-flex items-center justify-center p-2 text-slate-400 hover:text-emerald-600 hover:bg-slate-50 rounded-lg transition-colors" title="Lihat Detail">
                                            <i class="fas fa-chevron-right text-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                        {{-- Empty State --}}
                        <div class="text-center py-20 px-4">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                <i class="fas fa-folder-open text-2xl text-slate-300"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-700">Belum Ada Transaksi</h3>
                            <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Riwayat peminjaman akan muncul di sini secara otomatis setelah diajukan oleh pengguna.</p>
                        </div>
                    @endif
                </div>

                {{-- Pagination --}}
                @if($recentTransactions->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $recentTransactions->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>

    @push('scripts')
    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart Defaults (SaaS look)
            Chart.defaults.font.family = "'Inter', 'Nunito', sans-serif";
            Chart.defaults.color = '#94a3b8'; // text-slate-400

            const tooltipConfig = {
                backgroundColor: '#1e293b', // slate-800
                titleFont: { size: 13, weight: 'bold' },
                bodyFont: { size: 12 },
                padding: 12,
                cornerRadius: 8,
                displayColors: true,
                boxPadding: 4
            };

            const gridConfig = {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9', drawBorder: false },
                    border: { display: false },
                    ticks: { font: { size: 11, weight: '600' } }
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    border: { display: false },
                    ticks: { font: { size: 11, weight: '600' } }
                }
            };

            // 1. LOAN BAR CHART
            const barCtx = document.getElementById('loanBarChart');
            if (barCtx) {
                new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($monthlyLabels) !!},
                        datasets: [{
                            label: 'Jumlah Peminjaman',
                            data: {!! json_encode($monthlyData) !!},
                            backgroundColor: '#10b981', // emerald-500
                            hoverBackgroundColor: '#059669', // emerald-600
                            borderRadius: 6,
                            barPercentage: 0.6,
                            categoryPercentage: 0.8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: tooltipConfig
                        },
                        scales: gridConfig
                    }
                });
            }

            // 2. BOOKING LINE CHART
            const lineCtx = document.getElementById('bookingLineChart');
            if (lineCtx) {
                new Chart(lineCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($monthlyLabels) !!},
                        datasets: [{
                            label: 'Jadwal Tersetujui',
                            data: {!! json_encode($monthlyBookingData) !!},
                            borderColor: '#334155', // slate-700
                            backgroundColor: 'rgba(51, 65, 85, 0.05)',
                            borderWidth: 2.5,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#334155',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: tooltipConfig
                        },
                        scales: gridConfig
                    }
                });
            }

            // 3. LAB DONUT CHART
            const donutCtx = document.getElementById('labDonutChart');
            if (donutCtx) {
                new Chart(donutCtx, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($labDistributionLabels) !!},
                        datasets: [{
                            data: {!! json_encode($labDistributionData) !!},
                            backgroundColor: [
                                '#10b981', // emerald-500
                                '#334155', // slate-700
                                '#94a3b8', // slate-400
                                '#cbd5e1', // slate-300
                                '#e2e8f0', // slate-200
                                '#d1d5db', // gray-300
                                '#f1f5f9'  // slate-100
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 10,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    padding: 16,
                                    font: { size: 11, weight: 'bold' }
                                }
                            },
                            tooltip: tooltipConfig
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
