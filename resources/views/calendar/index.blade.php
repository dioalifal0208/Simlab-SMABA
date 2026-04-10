<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight tracking-tight">
                    {{ __('calendar.title') }}
                </h2>
                <p class="text-sm font-medium text-slate-500 mt-1">{{ __('calendar.subtitle') }}</p>
            </div>
            <div class="flex items-center gap-3">
                 <a href="{{ route('bookings.create') }}" class="inline-flex items-center px-5 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl hover:bg-green-700 shadow-sm transition-all hover:-translate-y-0.5">
                      <i class="fas fa-plus mr-2 text-green-200"></i> Tambah Booking
                 </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            {{-- FILTER & LEGEND CARD --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5" data-aos="fade-up" data-aos-once="true">
                <div class="flex flex-col lg:flex-row gap-6 justify-between lg:items-center">
                    
                    {{-- Toggle Filters --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <div class="flex items-center text-sm font-bold text-slate-800 border-r border-slate-100 pr-4 shrink-0 uppercase tracking-wide">
                            <i class="fas fa-filter text-green-600 mr-2 border border-green-100 bg-green-50 p-1.5 rounded-lg shadow-sm"></i> Tampilan
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="filter-lab" class="flex items-center cursor-pointer group hover:bg-slate-50 px-2 py-1 rounded-lg transition-colors">
                                <div class="relative flex items-center">
                                    <input type="checkbox" id="filter-lab" class="sr-only peer" checked>
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500 shadow-inner"></div>
                                </div>
                                <span class="ml-2.5 text-sm font-bold text-slate-600 group-hover:text-slate-900 transition-colors">{{ __('calendar.filters.lab_schedule') }}</span>
                            </label>
                            
                            <label for="filter-holidays" class="flex items-center cursor-pointer group hover:bg-slate-50 px-2 py-1 rounded-lg transition-colors ml-2">
                                <div class="relative flex items-center">
                                    <input type="checkbox" id="filter-holidays" class="sr-only peer" checked>
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-red-500 shadow-inner"></div>
                                </div>
                                <span class="ml-2.5 text-sm font-bold text-slate-600 group-hover:text-slate-900 transition-colors">{{ __('calendar.filters.national_holidays') }}</span>
                            </label>
                        </div>
                    </div>

                    {{-- Lab Selector --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        <label for="lab-selector" class="text-xs font-bold text-slate-500 uppercase tracking-widest"><i class="fas fa-flask mr-1"></i> Ruangan:</label>
                        <select id="lab-selector" class="rounded-lg border-slate-200 shadow-sm focus:border-green-500 focus:ring-4 focus:ring-green-500/20 text-sm font-bold text-slate-700 bg-slate-50 hover:bg-white cursor-pointer transition-all py-2 min-w-[200px]">
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
                
                {{-- Compact Legend --}}
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">
                        <span class="flex items-center gap-1.5 px-2 py-1 bg-blue-50/50 rounded-md border border-blue-100"><span class="h-2.5 w-2.5 rounded-full bg-[#2563eb] border border-blue-600/50 shadow-sm shadow-blue-500/20"></span>Biologi</span>
                        <span class="flex items-center gap-1.5 px-2 py-1 bg-green-50/50 rounded-md border border-green-100"><span class="h-2.5 w-2.5 rounded-full bg-[#16a34a] border border-green-700/50 shadow-sm shadow-green-500/20"></span>Fisika</span>
                        <span class="flex items-center gap-1.5 px-2 py-1 bg-amber-50/50 rounded-md border border-amber-100"><span class="h-2.5 w-2.5 rounded-full bg-[#f59e0b] border border-amber-600/50 shadow-sm shadow-amber-500/20"></span>Bahasa</span>
                        <span class="flex items-center gap-1.5 px-2 py-1 bg-violet-50/50 rounded-md border border-violet-100"><span class="h-2.5 w-2.5 rounded-full bg-[#7c3aed] border border-violet-700/50 shadow-sm shadow-violet-500/20"></span>Komputer 1</span>
                        <span class="flex items-center gap-1.5 px-2 py-1 bg-pink-50/50 rounded-md border border-pink-100"><span class="h-2.5 w-2.5 rounded-full bg-[#db2777] border border-pink-700/50 shadow-sm shadow-pink-500/20"></span>Komputer 2</span>
                        <span class="flex items-center gap-1.5 px-2 py-1 bg-teal-50/50 rounded-md border border-teal-100"><span class="h-2.5 w-2.5 rounded-full bg-[#0d9488] border border-teal-700/50 shadow-sm shadow-teal-500/20"></span>Komputer 3</span>
                        <span class="flex items-center gap-1.5 px-2 py-1 bg-orange-50/50 rounded-md border border-orange-100"><span class="h-2.5 w-2.5 rounded-full bg-[#ea580c] border border-orange-700/50 shadow-sm shadow-orange-500/20"></span>Komputer 4</span>
                        <span class="flex items-center gap-1.5 px-2 py-1 bg-red-50/50 rounded-md border border-red-100"><span class="h-2.5 w-2.5 rounded-full bg-red-500 border border-red-700/50 shadow-sm shadow-red-500/20"></span>{{ __('calendar.legend.national_holiday') }}</span>
                    </div>
                </div>
            </div>

            {{-- CALENDAR WRAPPER --}}
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-4 sm:p-6" data-aos="fade-up" data-aos-once="true" data-aos-delay="100">
                <div id="calendar" class="saas-calendar"></div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    window.googleCalendarApiKey = "{{ config('services.google.calendar_api_key') }}";
</script>

<style>
/* SAAS CALENDAR OVERRIDES */

/* Base Typography & Variables */
.saas-calendar {
    --fc-page-bg-color: #ffffff;
    --fc-neutral-bg-color: #f8fafc;
    --fc-neutral-text-color: #64748b;
    --fc-border-color: #f1f5f9; /* Slate 100 instead of default dark grey */
    --fc-today-bg-color: #f0fdf4; /* Green 50 */
    --fc-event-bg-color: #16a34a;
    --fc-event-border-color: transparent;
    
    font-family: 'Inter', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
}

/* Hardcode Borders to Light Slate */
.saas-calendar .fc-theme-standard td, 
.saas-calendar .fc-theme-standard th, 
.saas-calendar .fc-theme-standard .fc-scrollgrid {
    border-color: var(--fc-border-color) !important;
}

/* Header Toolbar Buttons */
.saas-calendar .fc-toolbar-chunk .fc-button {
    background-color: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    color: #475569 !important;
    font-weight: 700 !important;
    font-size: 0.8rem !important;
    padding: 0.5rem 1rem !important;
    text-transform: capitalize !important;
    transition: all 0.2s ease !important;
    border-radius: 0.5rem !important;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
}

.saas-calendar .fc-toolbar-chunk .fc-button-group > .fc-button {
    border-radius: 0 !important;
}
.saas-calendar .fc-toolbar-chunk .fc-button-group > .fc-button:first-child {
    border-top-left-radius: 0.5rem !important;
    border-bottom-left-radius: 0.5rem !important;
}
.saas-calendar .fc-toolbar-chunk .fc-button-group > .fc-button:last-child {
    border-top-right-radius: 0.5rem !important;
    border-bottom-right-radius: 0.5rem !important;
}

.saas-calendar .fc-toolbar-chunk .fc-button:hover {
    background-color: #f8fafc !important;
    color: #16a34a !important; /* Green hover */
    border-color: #cbd5e1 !important;
}

.saas-calendar .fc-toolbar-chunk .fc-button .fc-icon {
    font-size: 1rem;
}

/* Active Button State */
.saas-calendar .fc-toolbar-chunk .fc-button-active, 
.saas-calendar .fc-toolbar-chunk .fc-button:active {
    background-color: #16a34a !important;
    border-color: #16a34a !important;
    color: #ffffff !important;
    box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.1) !important;
}

/* Title Styling */
.saas-calendar .fc-toolbar-title {
    font-size: 1.25rem !important;
    font-weight: 900 !important;
    color: #0f172a !important;
    text-transform: uppercase !important;
    letter-spacing: 0.05em !important;
}

/* Day Headers (Mon, Tue, Wed) */
.saas-calendar .fc-col-header-cell-cushion {
    padding: 1rem 0 !important;
    font-size: 0.75rem !important;
    font-weight: 800 !important;
    color: #64748b !important;
    text-transform: uppercase !important;
    letter-spacing: 0.1em !important;
}

/* Grid Cells (Hover Effects) */
.saas-calendar .fc-daygrid-day {
    transition: background-color 0.2s ease !important;
    cursor: pointer !important;
}

.saas-calendar .fc-daygrid-day:hover {
    background-color: #f8fafc !important; /* slate-50 */
}

/* Today Cell Highlight */
.saas-calendar .fc-day-today {
    background-color: var(--fc-today-bg-color) !important;
    position: relative;
}

.saas-calendar .fc-day-today::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background-color: #16a34a;
    z-index: 5;
}

.saas-calendar .fc-day-today .fc-daygrid-day-number {
    font-weight: 900 !important;
    color: #16a34a !important;
    background: #dcfce7 !important; /* green-100 */
    margin-right: 0.25rem;
    margin-top: 0.25rem;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9999px;
}

/* Event Labels */
.saas-calendar .fc-event {
    border-radius: 6px !important;
    padding: 3px 6px !important;
    font-size: 0.7rem !important;
    font-weight: 700 !important;
    line-height: 1.2 !important;
    border: none !important;
    margin-bottom: 4px !important;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
    transition: transform 0.2s, box-shadow 0.2s !important;
}

.saas-calendar .fc-event:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
    z-index: 10 !important;
}

.saas-calendar .fc-daygrid-event-dot {
    border-width: 4px !important; 
    margin-right: 4px !important;
}

/* General Layout Fixes */
.saas-calendar .fc-view-harness {
    min-height: 600px !important;
}

.saas-calendar .fc-daygrid-day-frame {
    padding: 4px !important;
}

.saas-calendar .fc-daygrid-day-number {
    font-weight: 700 !important;
    color: #475569 !important;
    padding: 0.5rem !important;
    text-decoration: none !important;
}

/* Remove underline on event links */
.saas-calendar a.fc-event {
    text-decoration: none !important;
}

</style>
