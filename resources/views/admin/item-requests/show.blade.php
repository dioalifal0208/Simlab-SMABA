<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">Detail Permintaan Item</h2>
                <p class="text-sm text-gray-600 mt-1">Tinjau detail, lalu setujui untuk membuat item atau tolak dengan catatan.</p>
            </div>
            <a href="{{ route('admin.item-requests.index') }}" class="text-sm text-smaba-dark-blue hover:underline">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <p class="text-xs uppercase tracking-wide text-gray-500">Permintaan</p>
                            <h3 class="text-xl font-bold text-gray-900">{{ $itemRequest->nama_alat }}</h3>
                            <p class="text-sm text-gray-600">Diajukan oleh {{ $itemRequest->user->name ?? '-' }} ({{ $itemRequest->user->email ?? '-' }})</p>
                        </div>
                        <div class="space-y-2 text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $itemRequest->status === 'pending' ? 'bg-blue-100 text-blue-800' : ($itemRequest->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($itemRequest->status) }}
                            </span>
                            @if($itemRequest->urgensi === 'mendesak')
                                <div class="text-[11px] font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-3 py-1 rounded-full">Urgensi: Mendesak</div>
                            @endif
                        </div>
                    </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                            <div>
                                <p class="font-semibold text-gray-800">Jumlah & satuan</p>
                                <p>{{ $itemRequest->jumlah }} {{ $itemRequest->satuan }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Jenis/Tipe</p>
                                <p>{{ $itemRequest->tipe ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Laboratorium</p>
                                <p>{{ $itemRequest->laboratorium }}</p>
                            </div>
                        <div class="md:col-span-2">
                            <p class="font-semibold text-gray-800">Deskripsi</p>
                            <p class="text-gray-700">{{ $itemRequest->deskripsi ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="font-semibold text-gray-800">Alasan urgensi</p>
                            <p class="text-gray-700">{{ $itemRequest->alasan_urgent ?: '-' }}</p>
                        </div>
                        @if($itemRequest->processed_at)
                            <div>
                                <p class="font-semibold text-gray-800">Diproses oleh</p>
                                <p>{{ $itemRequest->processor->name ?? '-' }} pada {{ $itemRequest->processed_at->format('d M Y H:i') }}</p>
                            </div>
                        @endif
                        @if($itemRequest->admin_note)
                            <div class="md:col-span-2">
                                <p class="font-semibold text-gray-800">Catatan Admin</p>
                                <p class="text-gray-700">{{ $itemRequest->admin_note }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($itemRequest->status === 'pending')
                <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100">
                    <div class="p-6 space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900">Setujui & Buat Item</h3>
                        <form method="POST" action="{{ route('admin.item-requests.approve', $itemRequest) }}" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                                    <input type="number" name="jumlah" min="1" value="{{ old('jumlah', $itemRequest->jumlah) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                    <x-input-error :messages="$errors->get('jumlah')" class="mt-2" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Stok minimum (opsional)</label>
                                    <input type="number" name="stok_minimum" min="0" value="{{ old('stok_minimum') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                    <x-input-error :messages="$errors->get('stok_minimum')" class="mt-2" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Satuan</label>
                                    <input name="satuan" value="{{ old('satuan', $itemRequest->satuan) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                    <x-input-error :messages="$errors->get('satuan')" class="mt-2" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tipe (opsional)</label>
                                    <input name="tipe" value="{{ old('tipe', $itemRequest->tipe) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                    <x-input-error :messages="$errors->get('tipe')" class="mt-2" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Kondisi</label>
                                    <select name="kondisi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                        <option value="Baik">Baik</option>
                                        <option value="Kurang Baik">Kurang Baik</option>
                                        <option value="Rusak">Rusak</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('kondisi')" class="mt-2" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Lokasi penyimpanan</label>
                                    <input name="lokasi_penyimpanan" value="{{ old('lokasi_penyimpanan') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                    <x-input-error :messages="$errors->get('lokasi_penyimpanan')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Catatan admin (opsional)</label>
                                <textarea name="admin_note" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">{{ old('admin_note') }}</textarea>
                                <x-input-error :messages="$errors->get('admin_note')" class="mt-2" />
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
                                <button type="submit" class="inline-flex items-center justify-center px-5 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-md shadow hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                    Setujui & Buat Item
                                </button>
                                <button type="button" onclick="document.getElementById('reject-note').classList.toggle('hidden')" class="text-sm text-red-700 hover:underline">
                                    Tolak permintaan
                                </button>
                            </div>
                        </form>

                        <div id="reject-note" class="hidden bg-red-50 border border-red-200 rounded-lg p-4 space-y-3">
                            <form method="POST" action="{{ route('admin.item-requests.reject', $itemRequest) }}" class="space-y-3">
                                @csrf
                                <label class="block text-sm font-medium text-red-800">Alasan penolakan</label>
                                <textarea name="admin_note" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-600 focus:ring-red-600" placeholder="Tuliskan alasan penolakan" required></textarea>
                                <x-input-error :messages="$errors->get('admin_note')" class="mt-2" />
                                <div class="flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-md shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600">
                                        Tolak Permintaan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
