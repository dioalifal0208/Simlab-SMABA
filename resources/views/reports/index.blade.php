<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                {{ __('Laporan & Analitik') }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">Visualisasi data untuk wawasan operasional laboratorium.</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Grafik Tren Peminjaman --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-once="true">
                <div class="p-6 md:p-8">
                    <h3 class="text-xl font-bold text-smaba-text mb-4">Tren Peminjaman Bulanan (Tahun {{ date('Y') }})</h3>
                    <div class="h-80">
                        <canvas id="monthlyLoansChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Grafik Item Terpopuler --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                <div class="p-6 md:p-8">
                    <h3 class="text-xl font-bold text-smaba-text mb-4">Top 10 Item Paling Sering Dipinjam</h3>
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