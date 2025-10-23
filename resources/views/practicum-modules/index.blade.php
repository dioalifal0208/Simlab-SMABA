<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                    {{ __('Modul Praktikum & SOP') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Daftar panduan praktikum dan standar operasional prosedur laboratorium.</p>
            </div>
            {{-- Tombol Tambah Modul Baru (Hanya Admin/Guru) --}}
            @can('manage-documents') {{-- Menggunakan gate yang sama dengan Pustaka Digital --}}
                <a href="{{ route('practicum-modules.create') }}" class="mt-3 sm:mt-0 px-5 py-2 bg-smaba-dark-blue text-white rounded-lg hover:bg-smaba-light-blue font-semibold text-sm shadow-md transition-colors duration-300 ease-in-out transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i> Buat Modul Baru
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">Sukses</p>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-once="true">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Modul</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembuat</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</th>
                                <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 divide-y divide-gray-200">
                            @forelse ($modules as $module)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm font-semibold text-gray-900">{{ $module->title }}</td>
                                    <td class="py-4 px-6 text-sm">{{ $module->user->name }}</td>
                                    <td class="py-4 px-6 text-sm">{{ $module->created_at->format('d M Y') }}</td>
                                    <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('practicum-modules.show', $module->id) }}" class="px-3 py-1 bg-smaba-dark-blue text-white rounded-md hover:bg-smaba-light-blue font-semibold text-xs shadow-sm transition-colors duration-300">
                                            Detail
                                        </a>

                                        @can('manage-documents') {{-- Asumsi gate yang sama --}}
                                            {{-- TOMBOL EDIT BARU --}}
                                            <a href="{{ route('practicum-modules.edit', $module->id) }}" class="p-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 shadow-sm transition-colors duration-300" title="Edit">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                            </a>
                                            
                                            {{-- Tombol Hapus (dari tutorial sebelumnya) --}}
                                            <form action="{{ route('practicum-modules.destroy', $module->id) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 bg-red-600 text-white rounded-md hover:bg-red-700 shadow-sm transition-colors duration-300" title="Hapus">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-500">
                                        <p class="font-semibold">Belum Ada Modul</p>
                                        <p class="text-sm mt-1">Silakan buat modul praktikum atau SOP baru.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-200">
                    {{ $modules->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>