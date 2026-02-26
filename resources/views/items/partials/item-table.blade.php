<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-50">
            <tr>
                {{-- Checkbox "Pilih Semua" --}}
                <th class="py-4 px-4">
                    <input type="checkbox" @click="selectedItems = $event.target.checked ? Array.from(document.querySelectorAll('#item-list input[type=\'checkbox\']')).map(cb => cb.value) : []" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                </th>
                <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('items.table.name') }}</th>
                <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">{{ __('items.table.type') }}</th>
                <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('items.table.lab') }}</th>
                <th class="py-4 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('items.table.quantity') }}</th>
                <th class="py-4 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">{{ __('items.table.condition') }}</th>
                <th class="py-4 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('items.table.actions') }}</th>
            </tr>
        </thead>
        <tbody class="text-gray-700 divide-y divide-gray-200" id="item-list">
            @forelse ($items as $item)
                <tr class="hover:bg-gray-50" :class="{'bg-green-50': selectedItems.includes('{{ $item->id }}')}">
                    <td class="py-4 px-4">
                        <input type="checkbox" x-model="selectedItems" value="{{ $item->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 h-12 w-12">
                                @if($item->photo)
                                    <img
                                        class="h-12 w-12 rounded-md object-cover"
                                        src="{{ asset('storage/' . $item->photo) }}"
                                        alt="{{ $item->nama_alat }}"
                                    >
                                @else
                                    <div class="h-12 w-12 rounded-md bg-gray-100 flex items-center justify-center text-gray-400">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">{{ $item->nama_alat }}</div>
                                <div class="text-xs text-gray-500 md:hidden">{{ $item->tipe }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-sm hidden md:table-cell">{{ $item->tipe }}</td>
                    <td class="py-4 px-6 text-sm font-semibold text-gray-800">{{ $item->laboratorium }}</td>
                    <td class="py-4 px-6 text-sm text-center">
                        {{ $item->jumlah }} {{ $item->satuan }}
                        @if($item->stok_minimum && $item->jumlah < $item->stok_minimum)
                            <span class="block text-xs text-red-600 font-semibold">{{ __('items.status.low_stock') }}</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center hidden sm:table-cell">
                        @if($item->kondisi == 'baik')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ __('items.conditions.baik') }}</span>
                        @elseif($item->kondisi == 'kurang baik')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ __('items.conditions.kurang_baik') }}</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">{{ __('items.conditions.rusak') }}</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center">
                        <a href="{{ route('items.show', $item->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-semibold text-xs shadow-sm transition-colors duration-300">
                            {{ __('common.buttons.details') }}
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-12">
                        <div class="text-center">
                            <div class="w-20 h-20 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                <i class="fas fa-flask text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ __('items.empty.title') }}</h3>
                            <p class="text-sm text-gray-500 mb-4">{{ __('items.empty.description') }}</p>
                            @can('is-admin')
                            <a href="{{ route('items.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                                <i class="fas fa-plus"></i> {{ __('items.empty.action') }}
                            </a>
                            @else
                            <a href="{{ route('item-requests.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors shadow-sm">
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
{{-- Paginasi --}}
<div class="p-4 border-t border-gray-200">
    {{ $items->withQueryString()->links() }}
</div>

