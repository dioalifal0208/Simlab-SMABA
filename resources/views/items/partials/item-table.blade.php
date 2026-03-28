<div class="overflow-x-auto min-h-[400px]">
    <table class="min-w-full bg-white text-left relative">
        <thead class="bg-slate-50/90 backdrop-blur-md sticky top-0 z-10 shadow-sm border-b border-slate-200">
            <tr>
                {{-- Checkbox "Pilih Semua" --}}
                <th class="py-4 px-5 w-10">
                    <input type="checkbox" @click="selectedItems = $event.target.checked ? Array.from(document.querySelectorAll('#item-list input[type=\'checkbox\']')).map(cb => cb.value) : []" class="rounded border-slate-300 text-green-600 shadow-sm focus:ring-green-500 cursor-pointer">
                </th>
                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-widest">{{ __('items.table.name') }}</th>
                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-widest hidden md:table-cell">{{ __('items.table.type') }}</th>
                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-widest">{{ __('items.table.lab') }}</th>
                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">{{ __('items.table.quantity') }}</th>
                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-widest text-center hidden sm:table-cell">{{ __('items.table.condition') }}</th>
                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-widest text-center w-24">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100" id="item-list">
            @forelse ($items as $item)
                {{-- Zebra Striping + Hover Highlight + Alpine Clickable Row --}}
                <tr class="group hover:bg-slate-50 cursor-pointer transition-colors duration-200" 
                    :class="{'bg-green-50/50 hover:bg-green-50': selectedItems.includes('{{ $item->id }}')}"
                    @click="if($event.target.tagName !== 'INPUT' && $event.target.tagName !== 'A' && !$event.target.closest('a') && !$event.target.closest('button')) window.location.href='{{ route('items.show', $item->id) }}'">
                    
                    <td class="py-4 px-5">
                        <input type="checkbox" x-model="selectedItems" value="{{ $item->id }}" class="rounded border-slate-300 text-green-600 shadow-sm focus:ring-green-500 cursor-pointer transition-transform hover:scale-110">
                    </td>
                    
                    <td class="py-4 px-6">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 h-12 w-12 rounded-xl border border-slate-100 shadow-sm overflow-hidden bg-white">
                                @if($item->photo)
                                    <img class="h-full w-full object-cover transform group-hover:scale-110 transition-transform duration-500" src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->nama_alat }}">
                                @else
                                    <div class="h-full w-full bg-slate-50 flex items-center justify-center text-slate-300">
                                        <i class="fas fa-image text-lg"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-bold text-slate-800 truncate group-hover:text-green-700 transition-colors">{{ $item->nama_alat }}</div>
                                <div class="text-[11px] font-semibold tracking-wider text-slate-400 mt-0.5 md:hidden uppercase">{{ $item->tipe }}</div>
                            </div>
                        </div>
                    </td>
                    
                    <td class="py-4 px-6 hidden md:table-cell">
                        <span class="inline-flex items-center px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider text-slate-600 bg-slate-100 border border-slate-200 rounded-lg">
                            {{ $item->tipe }}
                        </span>
                    </td>
                    
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-flask text-slate-300"></i>
                            <span class="text-sm font-bold text-slate-700">{{ $item->laboratorium }}</span>
                        </div>
                    </td>
                    
                    <td class="py-4 px-6 text-center">
                        <div class="inline-flex flex-col items-center justify-center">
                            <span class="text-lg font-extrabold text-slate-800">{{ $item->jumlah }}<span class="text-xs font-semibold text-slate-500 ml-1">{{ $item->satuan }}</span></span>
                            @if($item->stok_minimum && $item->jumlah < $item->stok_minimum)
                                <span class="bg-red-50 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded border border-red-100 mt-1 uppercase tracking-wider flex items-center gap-1 shadow-sm"><i class="fas fa-arrow-down max-w-2"></i> Low</span>
                            @endif
                        </div>
                    </td>
                    
                    <td class="py-4 px-6 text-center hidden sm:table-cell">
                        @if($item->kondisi == 'baik')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-sm">
                                <i class="fas fa-check-circle mr-1.5 object-center mt-[2px]"></i> {{ __('items.conditions.baik') }}
                            </span>
                        @elseif($item->kondisi == 'kurang baik')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-lg bg-amber-50 text-amber-700 border border-amber-200 shadow-sm">
                                <i class="fas fa-exclamation-circle mr-1.5 object-center mt-[2px]"></i> {{ __('items.conditions.kurang_baik') }}
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-lg bg-red-50 text-red-700 border border-red-200 shadow-sm">
                                <i class="fas fa-times-circle mr-1.5 object-center mt-[2px]"></i> {{ __('items.conditions.rusak') }}
                            </span>
                        @endif
                    </td>
                    
                    <td class="py-4 px-6 text-center">
                        {{-- Minimalist Action Button --}}
                        <div class="flex justify-center items-center">
                            <a href="{{ route('items.show', $item->id) }}" class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-green-600 hover:border-green-300 hover:bg-green-50 transition-all shadow-sm group-hover:shadow" title="{{ __('common.buttons.details') }}">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-16">
                        <div class="text-center px-4">
                            <div class="w-24 h-24 mx-auto rounded-full bg-slate-50 border border-dashed border-slate-300 flex items-center justify-center mb-5 relative group">
                                <div class="absolute inset-0 bg-slate-100 rounded-full scale-0 group-hover:scale-100 transition-transform duration-500 opacity-50"></div>
                                <i class="fas fa-box-open text-4xl text-slate-300 relative z-10 group-hover:text-slate-500 transition-colors"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-1 tracking-tight">{{ __('items.empty.title') }}</h3>
                            <p class="text-sm text-slate-500 mb-6 max-w-sm mx-auto">{{ __('items.empty.description') }}</p>
                            @can('is-admin')
                            <a href="{{ route('items.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl shadow-[0_4px_14px_0_rgb(34,197,94,0.39)] hover:shadow-[0_6px_20px_rgba(34,197,94,0.23)] hover:bg-green-700 hover:-translate-y-0.5 transition-all">
                                <i class="fas fa-plus"></i> {{ __('items.empty.action') }}
                            </a>
                            @else
                            <a href="{{ route('item-requests.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl shadow-[0_4px_14px_0_rgb(34,197,94,0.39)] hover:shadow-[0_6px_20px_rgba(34,197,94,0.23)] hover:bg-green-700 hover:-translate-y-0.5 transition-all">
                                <i class="fas fa-plus"></i> {{ __('items.messages.request_new_item') }}
                            </a>
                            @endcan
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Paginasi SaaS Styling Overrides --}}
@if ($items->hasPages())
<div class="p-5 border-t border-slate-100 bg-slate-50/30 rounded-b-2xl">
    <div class="flex items-center justify-between">
        {{ $items->withQueryString()->links() }}
    </div>
</div>

<style>
/* Custom Pagination Adjustments for SaaS Look */
nav[role="navigation"] {
    width: 100%;
}
nav[role="navigation"] > div.flex.justify-between.flex-1 {
    display: none; /* Hide simple mobile nav if desktop full nav is present */
}
nav[role="navigation"] p.text-sm.text-gray-700 {
    font-size: 13px !important;
    color: #64748b !important; /* slate-500 */
    font-weight: 500;
}
nav[role="navigation"] p.text-sm.text-gray-700 span.font-medium {
    font-weight: 700;
    color: #1e293b; /* slate-800 */
}

/* Pagination Links Styling */
nav[role="navigation"] span.relative.z-0.inline-flex {
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    border-radius: 0.5rem;
}
nav[role="navigation"] span.relative.z-0.inline-flex > span > span,
nav[role="navigation"] span.relative.z-0.inline-flex > a {
    padding: 0.5rem 0.75rem !important;
    font-size: 0.875rem !important;
    font-weight: 600 !important;
    border-color: #f1f5f9 !important; /* slate-100 */
    transition: all 0.2s;
}

/* Active Page */
nav[role="navigation"] span[aria-current="page"] > span {
    background-color: #16a34a !important; /* green-600 */
    color: white !important;
    border-color: #16a34a !important;
    box-shadow: 0 4px 6px -1px rgba(22, 163, 74, 0.2), 0 2px 4px -1px rgba(22, 163, 74, 0.1);
    z-index: 10;
}

/* Inactive/Hover Links */
nav[role="navigation"] span.relative.z-0.inline-flex > a:hover {
    background-color: #f8fafc !important; /* slate-50 */
    color: #1e293b !important; /* slate-800 */
}
</style>
@endif
