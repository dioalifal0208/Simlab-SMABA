<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">Laporan & Analitik</h2>
                <p class="text-sm font-medium text-slate-500 mt-1">Pantau tren penggunaan laboratorium dan aktivitas inventaris secara real-time.</p>
            </div>
            
            {{-- GLOBAL FILTER / EXPORT BAR --}}
            <div class="flex flex-wrap items-center gap-3">
                <form action="{{ route('reports.export-loans') }}" method="POST" class="flex items-center gap-2 bg-white border border-slate-200 p-1 rounded-xl shadow-sm hover:shadow transition-shadow">
                    @csrf
                    <select name="month" class="text-xs border-transparent rounded-lg focus:ring-indigo-500 focus:border-indigo-500 font-bold text-slate-600 bg-slate-50 py-1.5 px-3">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $i, 1)->translatedFormat('F') }}</option>
                        @endfor
                    </select>
                    <select name="year" class="text-xs border-transparent rounded-lg focus:ring-indigo-500 focus:border-indigo-500 font-bold text-slate-600 bg-slate-50 py-1.5 pl-3 pr-8">
                        @for ($i = date('Y') - 1; $i <= date('Y') + 1; $i++)
                            <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="p-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition-colors flex items-center justify-center w-8 h-8 focus:ring-2 focus:ring-offset-1 focus:ring-emerald-500" title="Ekspor Peminjaman Alat">
                        <i class="fas fa-file-excel"></i>
                    </button>
                </form>

                <form action="{{ route('reports.export') }}" method="POST" class="flex items-center gap-2 bg-white border border-slate-200 p-1 rounded-xl shadow-sm hover:shadow transition-shadow">
                    @csrf
                    <select name="month" class="text-xs border-transparent rounded-lg focus:ring-indigo-500 focus:border-indigo-500 font-bold text-slate-600 bg-slate-50 py-1.5 px-3">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $i, 1)->translatedFormat('F') }}</option>
                        @endfor
                    </select>
                    <select name="year" class="text-xs border-transparent rounded-lg focus:ring-indigo-500 focus:border-indigo-500 font-bold text-slate-600 bg-slate-50 py-1.5 pl-3 pr-8">
                        @for ($i = date('Y') - 1; $i <= date('Y') + 1; $i++)
                            <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="p-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors flex items-center justify-center w-8 h-8 focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500" title="Ekspor Booking Jadwal">
                        <i class="fas fa-file-pdf"></i>
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- 1. SUMMARY CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Card 1: Total Peminjaman --}}
                <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 p-6 flex flex-col justify-between group hover:-translate-y-1 transition-all duration-300" data-aos="fade-up" data-aos-delay="0">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Total Peminjaman</p>
                            <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($currentBorrowings) }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-lg border border-indigo-100 group-hover:scale-110 transition-transform">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        @if($borrowingTrend >= 0)
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md"><i class="fas fa-arrow-trend-up"></i> +{{ $borrowingTrend }}%</span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-md"><i class="fas fa-arrow-trend-down"></i> {{ $borrowingTrend }}%</span>
                        @endif
                        <span class="text-xs font-medium text-slate-400">vs bulan lalu</span>
                    </div>
                </div>

                {{-- Card 2: Total Booking --}}
                <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 p-6 flex flex-col justify-between group hover:-translate-y-1 transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Total Jadwal Lab</p>
                            <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($currentBookings) }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-lg border border-emerald-100 group-hover:scale-110 transition-transform">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        @if($bookingTrend >= 0)
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md"><i class="fas fa-arrow-trend-up"></i> +{{ $bookingTrend }}%</span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-md"><i class="fas fa-arrow-trend-down"></i> {{ $bookingTrend }}%</span>
                        @endif
                        <span class="text-xs font-medium text-slate-400">vs bulan lalu</span>
                    </div>
                </div>

                {{-- Card 3: Lab Terpopuler --}}
                <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 p-6 flex flex-col justify-between group hover:-translate-y-1 transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Lab Terpopuler</p>
                            <h3 class="text-xl font-extrabold text-slate-800 leading-tight">{{ $mostUsedLab }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-lg border border-amber-100 group-hover:scale-110 transition-transform">
                            <i class="fas fa-fire"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        <span class="inline-flex items-center text-xs font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md">{{ number_format($mostUsedLabCount) }} booking</span>
                        <span class="text-xs font-medium text-slate-400">bulan ini</span>
                    </div>
                </div>

                {{-- Card 4: Alat Paling Banyak Dipinjam --}}
                <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 p-6 flex flex-col justify-between group hover:-translate-y-1 transition-all duration-300" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Alat Terfavorit</p>
                            <h3 class="text-lg font-extrabold text-slate-800 leading-tight line-clamp-2 truncate" title="{{ $mostBorrowedItem }}">{{ Str::limit($mostBorrowedItem, 20) }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center text-lg border border-rose-100 group-hover:scale-110 transition-transform flex-shrink-0">
                            <i class="fas fa-heart"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        <span class="inline-flex items-center text-xs font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md">{{ number_format($mostBorrowedItemCount) }} unit</span>
                        <span class="text-xs font-medium text-slate-400">dipinjam bulan ini</span>
                    </div>
                </div>
            </div>

            {{-- 2. CHART SECTION --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Bar Chart: Tren Peminjaman (Left, spanning 2 cols) --}}
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6" data-aos="fade-in">
                    <div class="flex justify-between items-end mb-6">
                        <div>
                            <h3 class="font-extrabold text-lg text-slate-800">Tren Peminjaman Bulanan</h3>
                            <p class="text-xs font-medium text-slate-400 mt-1">Distribusi permintaan alat menurut bulan pada tahun {{ date('Y') }}.</p>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100"><i class="fas fa-chart-bar"></i></div>
                    </div>
                    <div class="relative h-72 w-full">
                        <canvas id="loanBarChart"></canvas>
                    </div>
                </div>

                {{-- Donut Chart: Booking by Lab (Right, 1 col) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col" data-aos="fade-in" data-aos-delay="100">
                    <div class="flex justify-between items-end mb-6">
                        <div>
                            <h3 class="font-extrabold text-lg text-slate-800">Distribusi Lab</h3>
                            <p class="text-xs font-medium text-slate-400 mt-1">Mayoritas penggunaan lab bulan ini.</p>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100"><i class="fas fa-chart-pie"></i></div>
                    </div>
                    <div class="relative flex-grow flex items-center justify-center w-full min-h-[250px]">
                        @if(count($labDistributionLabels) > 0)
                            <canvas id="labDonutChart" class="mx-auto max-h-56"></canvas>
                        @else
                            <div class="text-center">
                                <i class="fas fa-chart-pie text-slate-200 text-4xl mb-3 block"></i>
                                <span class="text-sm font-bold text-slate-400">Tidak ada data distribusi.</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Line Chart: Tren Jadwal (Spanning full width or 2 cols depending on preference -- let's make it full width next row) --}}
                <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-slate-100 p-6" data-aos="fade-in">
                    <div class="flex justify-between items-end mb-6">
                        <div>
                            <h3 class="font-extrabold text-lg text-slate-800">Tren Penggunaan Jadwal Lab</h3>
                            <p class="text-xs font-medium text-slate-400 mt-1">Frekuensi booking yang disetujui selama tahun berjalan.</p>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100"><i class="fas fa-chart-line"></i></div>
                    </div>
                    <div class="relative h-64 w-full">
                        <canvas id="bookingLineChart"></canvas>
                    </div>
                </div>

            </div>

            {{-- 3. TABLE DETAIL (Riwayat Peminjaman Terbaru) --}}
            <div class="bg-white overflow-hidden shadow-sm border border-slate-100 sm:rounded-2xl" data-aos="fade-up">
                <div class="p-6 border-b border-slate-50">
                    <h3 class="font-extrabold text-lg text-slate-800 flex items-center gap-2">
                        <i class="fas fa-history text-indigo-500"></i> Riwayat Transaksi Peminjaman
                    </h3>
                </div>
                
                <div class="overflow-x-auto">
                    @if($recentTransactions->count() > 0)
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-left">ID Peminjaman</th>
                                <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-left">Peminjam</th>
                                <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-left">Tanggal Pinjam</th>
                                <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-left">Status</th>
                                <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($recentTransactions as $loan)
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-6 py-4 text-sm font-bold text-slate-700">#{{ str_pad($loan->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-xs mr-3">
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
                                        @if($loan->status == 'pending')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold bg-amber-50 text-amber-600 tracking-wide border border-amber-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> PENDING
                                            </span>
                                        @elseif($loan->status == 'approved')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-600 tracking-wide border border-emerald-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> APPROVED
                                            </span>
                                        @elseif($loan->status == 'returned')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold bg-slate-100 text-slate-600 tracking-wide border border-slate-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> RETURNED
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold bg-red-50 text-red-600 tracking-wide border border-red-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> REJECTED
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('loans.show', $loan->id) }}" class="inline-flex items-center justify-center p-2 text-slate-400 hover:text-indigo-600 hover:bg-slate-100 rounded-lg transition-colors" title="Lihat Detail">
                                            <i class="fas fa-chevron-right text-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                        {{-- Empty State --}}
                        <div class="text-center py-16 px-4">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                <i class="fas fa-folder-open text-2xl text-slate-300"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-700">Belum Ada Transaksi</h3>
                            <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Riwayat peminjaman akan muncul di sini secara otomatis setelah diajukan oleh pengguna.</p>
                        </div>
                    @endif
                </div>

                {{-- Pagination Pagination --}}
                @if($recentTransactions->hasPages())
                <div class="p-6 border-t border-slate-50 bg-slate-50/50">
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
                            backgroundColor: '#6366f1', // indigo-500
                            hoverBackgroundColor: '#4f46e5', // indigo-600
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
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: '#f1f5f9', drawBorder: false }, // slate-100
                                border: { display: false }
                            },
                            x: {
                                grid: { display: false, drawBorder: false },
                                border: { display: false }
                            }
                        }
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
                            borderColor: '#10b981', // emerald-500
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 3,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#10b981',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4 // curvy lines
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: tooltipConfig
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: '#f1f5f9', drawBorder: false },
                                border: { display: false }
                            },
                            x: {
                                grid: { display: false, drawBorder: false },
                                border: { display: false }
                            }
                        }
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
                                '#6366f1', // indigo
                                '#10b981', // emerald
                                '#f59e0b', // amber
                                '#ef4444', // red
                                '#3b82f6'  // blue
                            ],
                            borderWidth: 0,
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
                                    boxWidth: 12,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    padding: 20,
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
