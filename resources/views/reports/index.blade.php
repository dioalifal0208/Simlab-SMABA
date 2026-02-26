<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                {{ __('Laporan & Analitik') }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">Visualisasi data untuk wawasan operasional laboratorium.</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Export Card: Peminjaman Alat (sesuai dengan grafik di halaman ini) --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl p-6 md:p-8" data-aos="fade-up" data-aos-once="true">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Download Laporan Peminjaman Alat</h3>
                        <p class="text-sm text-gray-500 mt-1">Rekapitulasi data peminjaman alat & bahan (sesuai grafik di atas).</p>
                    </div>
                    <form action="{{ route('reports.export-loans') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                        @csrf
                        <select name="month" class="rounded-lg border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 shadow-sm text-sm">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                                </option>
                            @endforeach
                        </select>
                        <select name="year" class="rounded-lg border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 shadow-sm text-sm">
                            @foreach(range(date('Y')-1, date('Y')+1) as $y)
                                <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <i class="fas fa-file-excel mr-2"></i> Download Alat
                        </button>
                    </form>
                </div>
            </div>

            {{-- Export Card: Booking Ruang Lab --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl p-6 md:p-8" data-aos="fade-up" data-aos-once="true">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Download Laporan Booking Ruang Lab</h3>
                        <p class="text-sm text-gray-500 mt-1">Rekapitulasi data peminjaman ruang laboratorium.</p>
                    </div>
                    <form action="{{ route('reports.export') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                        @csrf
                        <select name="month" class="rounded-lg border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 shadow-sm text-sm">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                                </option>
                            @endforeach
                        </select>
                        <select name="year" class="rounded-lg border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 shadow-sm text-sm">
                            @foreach(range(date('Y')-1, date('Y')+1) as $y)
                                <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <i class="fas fa-file-excel mr-2"></i> Download Lab
                        </button>
                    </form>
                </div>
            </div>

            {{-- Grafik Tren Peminjaman --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-once="true">
                <div class="p-6 md:p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Tren Peminjaman Bulanan (Tahun {{ date('Y') }})</h3>
                    <div class="h-80">
                        <canvas id="monthlyLoansChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Grafik Item Terpopuler --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                <div class="p-6 md:p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Top 10 Item Paling Sering Dipinjam</h3>
                    <div class="h-96">
                        <canvas id="topItemsChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Script untuk Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // === Grafik 1: Tren Peminjaman Bulanan (Line Chart) ===
            const monthlyCtx = document.getElementById('monthlyLoansChart').getContext('2d');
            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: @json($monthlyLabels),
                    datasets: [{
                        label: 'Jumlah Peminjaman',
                        data: @json($monthlyData),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1 // Hanya tampilkan angka bulat di sumbu Y
                            }
                        }
                    }
                }
            });

            // === Grafik 2: Item Terpopuler (Bar Chart) ===
            const topItemsCtx = document.getElementById('topItemsChart').getContext('2d');
            new Chart(topItemsCtx, {
                type: 'bar',
                data: {
                    labels: @json($topItemsLabels),
                    datasets: [{
                        label: 'Total Dipinjam',
                        data: @json($topItemsData),
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y', // Membuat grafik menjadi horizontal (lebih mudah dibaca)
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
