<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">Permintaan Item dari Guru</h2>
                <p class="text-sm text-gray-600 mt-1">Tinjau permintaan penambahan item, setujui untuk membuat item, atau tolak dengan catatan.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Permintaan</h3>
                        <p class="text-xs text-gray-500 mt-1">Permintaan terbaru ditampilkan terlebih dahulu.</p>
                    </div>
                    <span class="text-xs px-3 py-1 rounded-full bg-gray-100 text-gray-700">{{ $requests->total() }} data</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Urgensi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lab</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100 text-sm">
                            @forelse($requests as $req)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-gray-900">{{ $req->nama_alat }}</p>
                                        <p class="text-xs text-gray-500">{{ $req->created_at->format('d M Y H:i') }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-gray-800">{{ $req->user->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">{{ $req->user->email ?? '' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($req->urgensi === 'mendesak')
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">Mendesak</span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">Normal</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $req->laboratorium }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if($req->status === 'pending')
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Menunggu</span>
                                        @elseif($req->status === 'approved')
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">Disetujui</span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.item-requests.show', $req) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-white bg-smaba-dark-blue rounded-md shadow hover:bg-smaba-light-blue transition">
                                            Lihat
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada permintaan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($requests->hasPages())
                    <div class="px-5 py-4 border-t border-gray-100">
                        {{ $requests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
