<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                    {{ __('Kalender Jadwal Laboratorium') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Lihat jadwal penggunaan lab dalam tampilan kalender.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden border border-gray-100 shadow-sm sm:rounded-xl" data-aos="fade-up" data-aos-once="true">
                {{-- Kontrol Filter --}}
                <div class="p-4 border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <label for="filter-lab" class="flex items-center cursor-pointer">
                            <input type="checkbox" id="filter-lab" class="rounded border-gray-300 text-smaba-dark-blue shadow-sm focus:ring-smaba-light-blue" checked>
                            <span class="ml-2 text-sm text-gray-600">Jadwal Lab</span>
                        </label>
                        <label for="filter-holidays" class="flex items-center cursor-pointer">
                            <input type="checkbox" id="filter-holidays" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500" checked>
                            <span class="ml-2 text-sm text-gray-600">Hari Libur Nasional</span>
                        </label>
                    </div>
                    <div class="flex items-center gap-3">
                        <label for="lab-selector" class="text-sm font-semibold text-gray-700">Pilih Lab:</label>
                        <select id="lab-selector" class="rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue text-sm">
                            <option value="">Semua Lab</option>
                            <option value="Biologi">Biologi</option>
                            <option value="Fisika">Fisika</option>
                            <option value="Bahasa">Bahasa</option>
                        </select>
                    </div>
                </div>

                <div class="p-6 text-gray-900 space-y-4">
                    {{-- Kalender akan ditampilkan di dalam div ini --}}
                    <div id="calendar"></div>
                    <div class="flex flex-wrap items-center gap-3 text-xs text-gray-600">
                        <span class="font-semibold">Legend:</span>
                        <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-[#2563eb] border border-green-500"></span>Biologi</span>
                        <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-[#16a34a] border border-green-600"></span>Fisika</span>
                        <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-[#f59e0b] border border-amber-600"></span>Bahasa</span>
                        <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-red-500"></span>Libur Nasional</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    // Mengirim API Key dari backend Laravel ke JavaScript di frontend
    window.googleCalendarApiKey = "{{ config('services.google.calendar_api_key') }}";
</script>
