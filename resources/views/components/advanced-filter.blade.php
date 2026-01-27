{{-- Advanced Search & Filter Component --}}
{{-- Usage: @include('components.advanced-filter', ['filters' => [...], 'searchRoute' => route('items.index')]) --}}

@php
    $filters = $filters ?? [];
    $searchRoute = $searchRoute ?? '#';
    $searchPlaceholder = $searchPlaceholder ?? 'Cari data...';
@endphp

<div x-data="{
    showFilters: false,
    searchQuery: '{{ request('search') ?? '' }}',
    activeFilters: {},
    searchTimeout: null,
    
    init() {
        // Load active filters from URL
        @foreach($filters as $filter)
            @if(request($filter['name']))
                this.activeFilters['{{ $filter['name'] }}'] = '{{ request($filter['name']) }}';
            @endif
        @endforeach
    },
    
    // Live search with debounce
    liveSearch() {
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => {
            this.applyFilters();
        }, 500); // 500ms debounce
    },
    
    // Apply all filters
    applyFilters() {
        const params = new URLSearchParams();
        
        if (this.searchQuery) {
            params.append('search', this.searchQuery);
        }
        
        Object.keys(this.activeFilters).forEach(key => {
            if (this.activeFilters[key]) {
                params.append(key, this.activeFilters[key]);
            }
        });
        
        window.location.href = '{{ $searchRoute }}?' + params.toString();
    },
    
    // Remove specific filter
    removeFilter(filterName) {
        delete this.activeFilters[filterName];
        this.applyFilters();
    },
    
    // Clear all filters
    clearAll() {
        this.searchQuery = '';
        this.activeFilters = {};
        window.location.href = '{{ $searchRoute }}';
    }
}" class="space-y-4">

    {{-- Search Bar with Live Search --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1 relative">
            <input 
                type="text" 
                x-model="searchQuery"
                @input="liveSearch()"
                placeholder="{{ $searchPlaceholder }}"
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-smaba-dark-blue focus:border-smaba-dark-blue"
            >
            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                <i class="fas fa-search"></i>
            </div>
            <div x-show="searchQuery" class="absolute right-3 top-1/2 -translate-y-1/2">
                <button @click="searchQuery = ''; liveSearch()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        {{-- Advanced Filters Toggle --}}
        @if(count($filters) > 0)
            <button 
                @click="showFilters = !showFilters"
                class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2"
            >
                <i class="fas fa-sliders-h"></i>
                <span>Filter Lanjutan</span>
                <i class="fas fa-chevron-down transition-transform" :class="{ 'rotate-180': showFilters }"></i>
            </button>
        @endif
    </div>

    {{-- Advanced Filters Panel --}}
    @if(count($filters) > 0)
        <div 
            x-show="showFilters" 
            x-collapse
            class="bg-gray-50 rounded-lg p-4 border border-gray-200"
        >
            <div class="grid grid-cols-1 md:grid-cols-{{ min(count($filters), 4) }} gap-4">
                @foreach($filters as $filter)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ $filter['label'] }}
                        </label>
                        
                        @if($filter['type'] === 'select')
                            <select 
                                x-model="activeFilters['{{ $filter['name'] }}']"
                                class="w-full rounded-lg border-gray-300 focus:border-smaba-dark-blue focus:ring-smaba-dark-blue text-sm"
                            >
                                <option value="">Semua</option>
                                @foreach($filter['options'] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        @elseif($filter['type'] === 'date')
                            <input 
                                type="date"
                                x-model="activeFilters['{{ $filter['name'] }}']"
                                class="w-full rounded-lg border-gray-300 focus:border-smaba-dark-blue focus:ring-smaba-dark-blue text-sm"
                            >
                        @else
                            <input 
                                type="text"
                                x-model="activeFilters['{{ $filter['name'] }}']"
                                placeholder="{{ $filter['placeholder'] ?? '' }}"
                                class="w-full rounded-lg border-gray-300 focus:border-smaba-dark-blue focus:ring-smaba-dark-blue text-sm"
                            >
                        @endif
                    </div>
                @endforeach
            </div>
            
            <div class="flex gap-2 mt-4">
                <button 
                    @click="applyFilters()"
                    class="px-4 py-2 bg-smaba-dark-blue text-white rounded-lg hover:bg-smaba-light-blue font-semibold text-sm transition-colors"
                >
                    <i class="fas fa-filter mr-2"></i> Terapkan Filter
                </button>
                <button 
                    @click="clearAll()"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold text-sm transition-colors"
                >
                    <i class="fas fa-sync-alt mr-2"></i> Reset Semua
                </button>
            </div>
        </div>
    @endif

    {{-- Active Filter Chips --}}
    <div class="flex flex-wrap gap-2" x-show="Object.keys(activeFilters).length > 0 || searchQuery">
        <template x-if="searchQuery">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                <i class="fas fa-search text-xs"></i>
                <span>Pencarian: <strong x-text="searchQuery"></strong></span>
                <button @click="searchQuery = ''; liveSearch()" class="hover:text-blue-900">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        </template>
        
        <template x-for="(value, key) in activeFilters" :key="key">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                <i class="fas fa-filter text-xs"></i>
                <span x-text="key + ': ' + value"></span>
                <button @click="removeFilter(key)" class="hover:text-green-900">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        </template>
    </div>
</div>
