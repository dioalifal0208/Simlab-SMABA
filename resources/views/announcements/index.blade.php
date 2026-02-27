<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('Manajemen Pengumuman Global') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Buat pengumuman baru untuk ditampilkan di banner atas semua halaman.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">Sukses</p>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border-l-4 border-red-400 text-red-700 p-4 text-sm rounded-lg" role="alert">
                    <p class="font-bold">Oops! Ada yang salah:</p>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form Buat Pengumuman Baru --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-once="true">
                <div class="p-6 md:p-8 text-gray-900">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Buat Pengumuman Baru</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Menyimpan pengumuman baru akan secara otomatis menggantikan dan menonaktifkan pengumuman lama yang sedang aktif.
                    </p>
                    <form action="{{ route('announcements.store') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="message" class="block font-medium text-sm text-gray-700">Isi Pengumuman</label>
                                <textarea name="message" id="message" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600" required>{{ old('message') }}</textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold text-sm shadow-sm transition-colors">
                                    Simpan & Publikasikan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Daftar Riwayat Pengumuman --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Riwayat Pengumuman</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Isi Pesan</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Oleh</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 divide-y divide-gray-200">
                            @forelse ($announcements as $announcement)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-4 px-6 text-center">
                                        @if($announcement->status == 'active')
                                            <span class="px-3 py-1 text-xs font-bold leading-none text-green-800 bg-green-100 rounded-full">Aktif</span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-bold leading-none text-gray-800 bg-gray-100 rounded-full">Diarsipkan</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-sm">{{ Str::limit($announcement->message, 80) }}</td>
                                    <td class="py-4 px-6 text-sm">{{ $announcement->user->name }}</td>
                                    <td class="py-4 px-6 text-sm whitespace-nowrap">{{ $announcement->created_at->format('d M Y, H:i') }}</td>
                                    <td class="py-4 px-6 text-center">
                                        @if($announcement->status == 'active')
                                            <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1.5 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 font-semibold text-xs shadow-sm transition-colors duration-300">
                                                    Arsipkan
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-500">
                                        <p class="font-semibold">Belum ada pengumuman.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                 @if ($announcements->hasPages())
                    <div class="p-4 border-t border-gray-200">
                        {{ $announcements->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
