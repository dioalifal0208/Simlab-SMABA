<?php

$file = 'resources/views/items/index.blade.php';
$content = file_get_contents($file);

// Replace default emerald with green
$content = str_replace('emerald', 'green', $content);
$content = str_replace('bg-emerald', 'bg-green', $content);
$content = str_replace('text-emerald', 'text-green', $content);

// Remove the old action buttons from the Header x-slot
$content = preg_replace('/<div class="mt-3 sm:mt-0 flex items-center space-x-3">.*?<\/div>\s*<\/div>/s', "</div>\n    ", $content);

// Replace the filter panel logic completely
$newFilterPanel = <<<HTML
            {{-- ACTION BAR (Top) --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6" data-aos="fade-up">
                <div class="w-full md:w-96 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400"></i>
                    </div>
                    <input type="text" name="search" id="search" placeholder="{{ __('items.filters.search') }}" value="{{ request('search') }}" form="filter-form" class="w-full rounded-lg border-slate-200 shadow-sm focus:border-green-500 focus:ring-4 focus:ring-green-500/20 pl-10 py-2.5 text-sm transition-all bg-white text-slate-800 placeholder-slate-400 font-medium h-[42px]">
                </div>
                
                <div class="flex items-center gap-3 w-full md:w-auto shrink-0">
                    @can('is-admin')
                        <button @click="showImportModal = true" class="flex-1 md:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50 font-bold text-sm shadow-sm transition-all hover:shadow hover:border-slate-300 h-[42px]">
                            <i class="fas fa-cloud-arrow-up mr-2.5 text-slate-400"></i> {{ __('items.actions.import') }}
                        </button>
                        <a href="{{ route('items.create') }}" class="flex-1 md:flex-none inline-flex items-center justify-center px-5 py-2.5 bg-green-600 text-white text-sm font-bold rounded-lg shadow-sm hover:bg-green-700 hover:shadow-md transition-all h-[42px] hover:-translate-y-0.5">
                            <i class="fas fa-plus mr-2.5 text-green-200"></i> {{ __('items.actions.add') }}
                        </a>
                    @else
                        <a href="{{ route('item-requests.create') }}" class="flex-1 md:flex-none inline-flex items-center justify-center px-5 py-2.5 bg-green-600 text-white text-sm font-bold rounded-lg shadow-sm hover:bg-green-700 hover:shadow-md transition-all h-[42px] hover:-translate-y-0.5">
                            <i class="fas fa-plus mr-2.5 text-green-200"></i> {{ __('items.actions.request_add') }}
                        </a>
                    @endcan
                </div>
            </div>

            {{-- FILTER PANEL CARD --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-8" data-aos="fade-up" data-aos-delay="50">
                <form action="{{ route('items.index') }}" method="GET" id="filter-form" class="flex flex-col md:flex-row items-center gap-5">
                    <div class="flex items-center text-sm font-bold text-slate-800 md:border-r border-slate-100 md:pr-5 shrink-0 self-start md:self-auto uppercase tracking-wide">
                        <i class="fas fa-filter text-green-600 mr-2 border border-green-100 bg-green-50 p-1.5 rounded-lg shadow-sm"></i> Filter
                    </div>

                    <div class="flex flex-col sm:flex-row flex-grow w-full gap-3">
                        <select name="tipe" id="tipe" class="flex-1 rounded-lg border-slate-200 shadow-sm focus:border-green-500 focus:ring-4 focus:ring-green-500/20 py-2.5 text-sm transition-all text-slate-700 bg-slate-50 hover:bg-white font-medium h-[42px]">
                            <option value="">{{ __('items.filters.type') }}</option>
                            <option value="Alat" @selected(request('tipe') == 'Alat')>{{ __('items.categories.alat') }}</option>
                            <option value="Bahan Habis Pakai" @selected(request('tipe') == 'Bahan Habis Pakai')>{{ __('items.categories.bahan') }}</option>
                        </select>
                        <select name="kondisi" id="kondisi" class="flex-1 rounded-lg border-slate-200 shadow-sm focus:border-green-500 focus:ring-4 focus:ring-green-500/20 py-2.5 text-sm transition-all text-slate-700 bg-slate-50 hover:bg-white font-medium h-[42px]">
                            <option value="">{{ __('items.filters.condition') }}</option>
                            <option value="baik" @selected(request('kondisi') == 'baik')>{{ __('items.conditions.baik') }}</option>
                            <option value="kurang baik" @selected(request('kondisi') == 'kurang baik')>{{ __('items.conditions.kurang_baik') }}</option>
                            <option value="Rusak" @selected(request('kondisi') == 'Rusak')>{{ __('items.conditions.rusak') }}</option>
                        </select>
                        @php
                            \$isAdmin = auth()->user()?->role === 'admin';
                            \$lockedLab = auth()->user()?->laboratorium;
                        @endphp
                        <div class="flex-1 relative">
                            <select name="laboratorium" id="laboratorium" class="w-full rounded-lg border-slate-200 shadow-sm focus:border-green-500 focus:ring-4 focus:ring-green-500/20 py-2.5 text-sm transition-all text-slate-700 bg-slate-50 hover:bg-white font-medium h-[42px]" {{ \$isAdmin ? '' : 'disabled' }}>
                                <option value="">{{ __('items.filters.all_labs') }}</option>
                                <option value="Biologi" @selected(request('laboratorium', \$lockedLab) == 'Biologi')>{{ __('common.labs.biologi') }}</option>
                                <option value="Fisika" @selected(request('laboratorium', \$lockedLab) == 'Fisika')>{{ __('common.labs.fisika') }}</option>
                                <option value="Bahasa" @selected(request('laboratorium', \$lockedLab) == 'Bahasa')>{{ __('common.labs.bahasa') }}</option>
                                <option value="Komputer" @selected(request('laboratorium', \$lockedLab) == 'Komputer')>{{ __('common.labs.komputer') }}</option>
                            </select>
                            @unless(\$isAdmin)
                                <input type="hidden" name="laboratorium" value="{{ request('laboratorium', \$lockedLab) }}">
                                <span class="absolute right-8 top-3 text-[10px] uppercase font-bold text-slate-400 bg-slate-100 px-1.5 rounded">{{ __('items.filters.locked_lab') }}</span>
                            @endunless
                        </div>
                    </div>

                    <a href="{{ route('items.index') }}" class="shrink-0 text-sm font-bold text-slate-500 hover:text-slate-900 px-4 py-2.5 rounded-lg border border-transparent hover:bg-slate-100 hover:border-slate-200 transition-all flex items-center h-[42px]" title="{{ __('items.actions.reset_filters') }}">
                        Reset
                    </a>
                </form>
            </div>
HTML;

$content = preg_replace('/<div class="mb-8" data-aos="fade-up">.*?<\/form>\s*<\/div>\s*<\/div>/s', $newFilterPanel, $content);

// Remove the inline styles that were breaking some things or might clash
file_put_contents($file, $content);
echo "Indexed update done";

