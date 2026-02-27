<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('calendar.title') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('calendar.subtitle') }}</p>
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
                            <input type="checkbox" id="filter-lab" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" checked>
                            <span class="ml-2 text-sm text-gray-600">{{ __('calendar.filters.lab_schedule') }}</span>
                        </label>
                        <label for="filter-holidays" class="flex items-center cursor-pointer">
                            <input type="checkbox" id="filter-holidays" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500" checked>
                            <span class="ml-2 text-sm text-gray-600">{{ __('calendar.filters.national_holidays') }}</span>
                        </label>
                    </div>
                    <div class="flex items-center gap-3">
                        <label for="lab-selector" class="text-sm font-semibold text-gray-700">{{ __('calendar.filters.select_lab') }}:</label>
                        <select id="lab-selector" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 text-sm">
                            <option value="">{{ __('calendar.filters.all_labs') }}</option>
                            <option value="Biologi">Biologi</option>
                            <option value="Fisika">Fisika</option>
                            <option value="Bahasa">Bahasa</option>
                            <option value="Komputer 1">Komputer 1</option>
                            <option value="Komputer 2">Komputer 2</option>
                            <option value="Komputer 3">Komputer 3</option>
                            <option value="Komputer 4">Komputer 4</option>
                        </select>
                    </div>
                </div>

                <div class="p-6 text-gray-900 space-y-4">
                    {{-- Kalender akan ditampilkan di dalam div ini --}}
                    <div id="calendar"></div>
                    <div class="flex flex-wrap items-center gap-3 text-xs text-gray-600">
                        <span class="font-semibold">{{ __('calendar.legend.title') }}</span>
                        <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-[#2563eb] border border-blue-600 flex-shrink-0"></span>Biologi</span>
                        <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-[#16a34a] border border-green-700 flex-shrink-0"></span>Fisika</span>
                        <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-[#f59e0b] border border-amber-600 flex-shrink-0"></span>Bahasa</span>
                        <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-[#7c3aed] border border-violet-700 flex-shrink-0"></span>Komputer 1</span>
                        <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-[#db2777] border border-pink-700 flex-shrink-0"></span>Komputer 2</span>
                        <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-[#0d9488] border border-teal-700 flex-shrink-0"></span>Komputer 3</span>
                        <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-[#ea580c] border border-orange-700 flex-shrink-0"></span>Komputer 4</span>
                        <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-red-500 border border-red-700 flex-shrink-0"></span>{{ __('calendar.legend.national_holiday') }}</span>
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

