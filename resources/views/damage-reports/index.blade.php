<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                    {{ __('Kelola Laporan Kerusakan') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Lihat dan kelola semua laporan kerusakan alat & bahan.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div data-aos="fade-up" data-aos-duration="500" data-aos-once="true">
                {{-- Form Filter Status Otomatis --}}
                <div class="mb-6 bg-white overflow-hidden shadow-lg sm:rounded-xl">
                    <form action="{{ route('damage-reports.index') }}" method="GET" class="p-4 sm:p-6" id="filter-form">
                        <div class="flex items-center space-x-4">
                            <label for="status" class="text-sm font-medium text-gray-700">Filter Status:</label>
                            <select name="status" id="status" class="w-full sm:w-auto rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue text-sm">
                                <option value="">Semua Status</option>
                                <option value="Dilaporkan" @selected(request('status') == 'Dilaporkan')>Dilaporkan</option>
                                <option value="Diverifikasi" @selected(request('status') == 'Diverifikasi')>Diverifikasi</option>
                                <option value="Diperbaiki" @selected(request('status') == 'Diperbaiki')>Selesai Diperbaiki</option>
                            </select>
                        </div>
                    </form>
                </div>

                {{-- Tabel Daftar Laporan --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Rusak</th>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Lapor</th>
                                    <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 divide-y divide-gray-200">
                                @forelse ($reports as $report)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-4 px-6 text-sm font-medium text-gray-500">#{{ $report->id }}</td>
                                        <td class="py-4 px-6 text-sm font-semibold text-gray-900">{{ $report->item->nama_alat }}</td>
                                        <td class="py-4 px-6 text-sm">{{ $report->user->name }}</td>
                                        <td class="py-4 px-6 text-sm">{{ $report->created_at->format('d M Y') }}</td>
                                        <td class="py-4 px-6 text-center">
                                            @if($report->status == 'Dilaporkan')
                                                <span class="px-3 py-1 text-xs font-bold leading-none text-yellow-800 bg-yellow-100 rounded-full">Dilaporkan</span>
                                            @elseif($report->status == 'Diverifikasi')
                                                <span class="px-3 py-1 text-xs font-bold leading-none text-green-800 bg-green-100 rounded-full">Diverifikasi</span>
                                            @else
                                                <span class="px-3 py-1 text-xs font-bold leading-none text-green-800 bg-green-100 rounded-full">Diperbaiki</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <a href="{{ route('damage-reports.show', $report->id) }}" class="px-4 py-2 bg-smaba-dark-blue text-white rounded-md hover:bg-smaba-light-blue font-semibold text-xs shadow-sm transition-colors duration-300">
                                                Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12">
                                            <div class="text-center">
                                                <div class="w-20 h-20 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                                    <i class="fas fa-tools text-3xl text-gray-400"></i>
                                                </div>
                                                <h3 class="text-lg font-semibold text-gray-900 mb-1">Tidak Ada Laporan Kerusakan</h3>
                                                <p class="text-sm text-gray-500">Belum ada laporan kerusakan yang tercatat.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-200">
                        {{ $reports->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const filterForm = document.getElementById('filter-form');
                const statusSelect = document.getElementById('status');
                statusSelect.addEventListener('change', () => {
                    filterForm.submit();
                });
            });
        </script>
    @endpush
</x-app-layout>