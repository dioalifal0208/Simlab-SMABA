<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-50">
            <tr>
                {{-- Checkbox "Pilih Semua" --}}
                <th class="py-4 px-4">
                    <input type="checkbox" @click="selectedItems = $event.target.checked ? Array.from(document.querySelectorAll('#item-list input[type=\'checkbox\']')).map(cb => cb.value) : []" class="rounded border-gray-300 text-smaba-dark-blue shadow-sm focus:ring-smaba-light-blue">
                </th>
                <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Alat/Bahan</th>
                <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Tipe</th>
                <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lab</th>
                <th class="py-4 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                <th class="py-4 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Kondisi</th>
                <th class="py-4 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-gray-700 divide-y divide-gray-200" id="item-list">
            @forelse ($items as $item)
                <tr class="hover:bg-gray-50" :class="{'bg-blue-50': selectedItems.includes('{{ $item->id }}')}">
                    <td class="py-4 px-4">
                        <input type="checkbox" x-model="selectedItems" value="{{ $item->id }}" class="rounded border-gray-300 text-smaba-dark-blue shadow-sm focus:ring-smaba-light-blue">
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
                            <span class="block text-xs text-red-600 font-semibold">Stok Rendah</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center hidden sm:table-cell">
                        @if($item->kondisi == 'Baik')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Baik</span>
                        @elseif($item->kondisi == 'Kurang Baik')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Kurang Baik</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rusak</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center">
                        <a href="{{ route('items.show', $item->id) }}" class="px-4 py-2 bg-smaba-dark-blue text-white rounded-md hover:bg-smaba-light-blue font-semibold text-xs shadow-sm transition-colors duration-300">
                            Detail
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
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Tidak Ada Item Ditemukan</h3>
                            <p class="text-sm text-gray-500 mb-4">Coba ubah filter pencarian Anda atau tambahkan item baru.</p>
                            @can('is-admin')
                            <a href="{{ route('items.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                <i class="fas fa-plus"></i> Tambah Item Baru
                            </a>
                            @else
                            <a href="{{ route('item-requests.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                <i class="fas fa-plus"></i> Ajukan Item Baru
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
