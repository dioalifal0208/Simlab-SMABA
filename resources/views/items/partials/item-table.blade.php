<div class="overflow-x-auto min-h-[400px]">
    <table class="min-w-full bg-white text-left relative">
        <thead class="bg-slate-50 sticky top-0 z-10 border-b border-slate-200">
            <tr>
                <th class="py-5 px-5 w-10">
                    <input type="checkbox" @click="selectedItems = $event.target.checked ? Array.from(document.querySelectorAll('#item-list input[type=\'checkbox\']')).map(cb => cb.value) : []" class="rounded border-slate-300 text-green-600 shadow-sm focus:ring-green-500 cursor-pointer">
                </th>
                <th class="py-5 px-6 text-xs font-bold text-slate-500 uppercase tracking-widest">{{ __('items.table.name') }}</th>
                <th class="py-5 px-6 text-xs font-bold text-slate-500 uppercase tracking-widest hidden md:table-cell">{{ __('items.table.type') }}</th>
                <th class="py-5 px-6 text-xs font-bold text-slate-500 uppercase tracking-widest">{{ __('items.table.lab') }}</th>
                <th class="py-5 px-6 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">{{ __('items.table.quantity') }}</th>
                <th class="py-5 px-6 text-xs font-bold text-slate-500 uppercase tracking-widest text-center hidden sm:table-cell">{{ __('items.table.condition') }}</th>
                <th class="py-5 px-6 text-xs font-bold text-slate-500 uppercase tracking-widest text-center w-24">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100" id="item-list">
            @forelse ($items as $item)
                {{-- Zebra Striping + Hover Highlight + Alpine Clickable Row --}}
                <tr class="group even:bg-slate-50/30 hover:bg-green-50/50 cursor-pointer transition-colors duration-200" 
                    :class="{'bg-green-100/50 hover:bg-green-100/50': selectedItems.includes('{{ $item->id }}')}"
                    @click="if($event.target.tagName !== 'INPUT' && $event.target.tagName !== 'A' && !$event.target.closest('a') && !$event.target.closest('button')) window.location.href='{{ route('items.show', $item->id) }}'">
                    
                    <td class="py-6 px-5">
                        <input type="checkbox" x-model="selectedItems" value="{{ $item->id }}" class="rounded border-slate-300 text-green-600 shadow-sm focus:ring-green-500 cursor-pointer transition-transform hover:scale-110" @click.stop>
                    </td>
                    
                    <td class="py-6 px-6">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 h-[60px] w-[60px] rounded-xl border border-slate-200 shadow-sm overflow-hidden bg-white">
                                @if($item->photo)
                                    <img class="h-full w-full object-cover transform group-hover:scale-110 transition-transform duration-500" src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->nama_alat }}">
                                @else
                                    <div class="h-full w-full bg-slate-50 flex items-center justify-center text-slate-400">
                                        <i class="fas fa-box-open text-xl group-hover:text-green-500 transition-colors"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="text-[15px] font-extrabold text-slate-900 truncate group-hover:text-green-700 transition-colors">{{ $item->nama_alat }}</div>
                                <div class="text-[11px] font-bold tracking-widest text-slate-400 mt-1 md:hidden uppercase">{{ $item->tipe }}</div>
                            </div>
                        </div>
                    </td>
                    
                    <td class="py-6 px-6 hidden md:table-cell">
                        <span class="inline-flex items-center px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-slate-600 bg-white border border-slate-200 rounded-lg shadow-sm">
                            {{ $item->tipe }}
                        </span>
                    </td>
                    
                    <td class="py-6 px-6">
                        <div class="flex items-center gap-2.5">
                            <i class="fas fa-flask text-slate-300"></i>
                            <span class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">{{ $item->laboratorium }}</span>
                        </div>
                    </td>
                    
                    <td class="py-6 px-6 text-center">
                        <div class="inline-flex flex-col items-center justify-center">
                            <span class="text-xl font-black text-slate-900">{{ $item->jumlah }}<span class="text-xs font-bold text-slate-400 ml-1.5 uppercase">{{ $item->satuan }}</span></span>
                            @if($item->stok_minimum && $item->jumlah < $item->stok_minimum)
                                <span class="bg-red-50 text-red-600 text-[9px] font-black px-2 py-0.5 rounded border border-red-200 mt-1.5 uppercase tracking-widest flex items-center gap-1 shadow-sm"><i class="fas fa-arrow-down max-w-2"></i> Low Minimum</span>
                            @endif
                        </div>
                    </td>
                    
                    <td class="py-6 px-6 text-center hidden sm:table-cell">
                        @if($item->kondisi == 'baik')
                            <span class="px-3.5 py-1.5 inline-flex text-[11px] leading-tight font-black uppercase tracking-wider rounded-lg bg-green-50 text-green-700 border border-green-200 shadow-sm">
                                <i class="fas fa-check-circle mr-1.5 object-center mt-[1px]"></i> {{ __('items.conditions.baik') }}
                            </span>
                        @elseif($item->kondisi == 'kurang baik')
                            <span class="px-3.5 py-1.5 inline-flex text-[11px] leading-tight font-black uppercase tracking-wider rounded-lg bg-amber-50 text-amber-700 border border-amber-200 shadow-sm">
                                <i class="fas fa-exclamation-circle mr-1.5 object-center mt-[1px]"></i> {{ __('items.conditions.kurang_baik') }}
                            </span>
                        @else
                            <span class="px-3.5 py-1.5 inline-flex text-[11px] leading-tight font-black uppercase tracking-wider rounded-lg bg-red-50 text-red-700 border border-red-200 shadow-sm">
                                <i class="fas fa-times-circle mr-1.5 object-center mt-[1px]"></i> {{ __('items.conditions.rusak') }}
                            </span>
                        @endif
                    </td>
                    
                    <td class="py-6 px-6 text-center" x-data="{ openOptions: false }">
                        {{-- SaaS Dropdown Menu Action --}}
                        <div class="relative flex justify-center items-center" @click.stop>
                            <button @click="openOptions = !openOptions" class="text-slate-400 hover:text-green-600 focus:outline-none p-2 rounded-lg hover:bg-green-50 transition-colors border border-transparent hover:border-green-200">
                                <i class="fas fa-ellipsis-vertical px-2"></i>
                            </button>
                            <div x-show="openOptions" @click.outside="openOptions = false" style="display: none;" class="absolute right-0 top-10 mt-1 w-44 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 z-50 transform origin-top-right transition-all">
                                <a href="{{ route('items.show', $item->id) }}" class="flex items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-green-600 w-full text-left font-bold transition-colors">
                                    <i class="fas fa-eye w-6 text-center text-slate-400 mr-1"></i> Detail
                                </a>
                                @can('is-admin')
                                <a href="{{ route('items.edit', $item->id) }}" class="flex items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600 w-full text-left font-bold transition-colors">
                                    <i class="fas fa-edit w-6 text-center text-slate-400 mr-1"></i> Edit Data
                                </a>
                                <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="w-full" onsubmit="return confirm('Yakin ingin menghapus item ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-red-50 hover:text-red-600 w-full text-left font-bold transition-colors">
                                        <i class="fas fa-trash-alt w-6 text-center text-slate-400 mr-1"></i> Hapus
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-20">
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
<div class="p-5 border-t border-slate-100 bg-white rounded-b-2xl">
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
    display: none;
}
nav[role="navigation"] p.text-sm.text-gray-700 {
    font-size: 13px !important;
    color: #64748b !important;
    font-weight: 500;
}
nav[role="navigation"] p.text-sm.text-gray-700 span.font-medium {
    font-weight: 700;
    color: #1e293b;
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
    border-color: #f1f5f9 !important;
    transition: all 0.2s;
}

/* Active Page */
nav[role="navigation"] span[aria-current="page"] > span {
    background-color: #16a34a !important; 
    color: white !important;
    border-color: #16a34a !important;
    box-shadow: 0 4px 6px -1px rgba(22, 163, 74, 0.2), 0 2px 4px -1px rgba(22, 163, 74, 0.1);
    z-index: 10;
}

/* Inactive/Hover Links */
nav[role="navigation"] span.relative.z-0.inline-flex > a:hover {
    background-color: #f8fafc !important;
    color: #1e293b !important;
}
</style>
@endif
