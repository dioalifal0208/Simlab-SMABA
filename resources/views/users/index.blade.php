<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                    {{ __('Manajemen Pengguna') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola akun pengguna dan peran (role) mereka.</p>
            </div>
            {{-- Tambahkan tombol "Tambah Pengguna Baru" jika Anda punya fiturnya --}}
            {{-- <a href="{{ route('users.create') }}" class="mt-3 sm:mt-0 ... ">+ Tambah Pengguna</a> --}}
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
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Peran (Role)</th>
                                <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 divide-y divide-gray-200">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm font-semibold text-gray-900">{{ $user->name }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $user->email }}</td>
                                    <td class="py-4 px-6 text-center">
                                        {{-- Badge Berwarna untuk Role --}}
                                        @if($user->role == 'admin')
                                            <span class="px-3 py-1 text-xs font-bold leading-none text-red-800 bg-red-100 rounded-full">Admin</span>
                                        @elseif($user->role == 'guru')
                                            <span class="px-3 py-1 text-xs font-bold leading-none text-blue-800 bg-blue-100 rounded-full">Guru</span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-bold leading-none text-green-800 bg-green-100 rounded-full">Siswa</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        {{-- Tombol Edit Didesain Ulang --}}
                                        <a href="{{ route('users.edit', $user->id) }}" class="inline-flex items-center justify-center bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded-lg shadow-sm text-xs transition-colors duration-300" title="Edit Peran">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-500">
                                        <p class="font-semibold">Tidak Ada Pengguna</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Paginasi (jika Anda menggunakannya di Controller) --}}
                @if ($users->hasPages())
                    <div class="p-4 border-t border-gray-200">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>