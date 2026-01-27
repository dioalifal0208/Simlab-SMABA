<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                    Edit Pengguna: {{ $user->name }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Perbarui informasi dan peran pengguna.</p>
            </div>
            <a href="{{ route('users.index') }}" class="mt-3 sm:mt-0 text-sm font-semibold text-smaba-light-blue hover:text-smaba-dark-blue transition-colors">
                &larr; Kembali ke Daftar Pengguna
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-once="true">
                <div class="p-6 md:p-8 text-gray-900">

                    {{-- Pesan Error Validasi --}}
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-400 text-red-700 p-4 text-sm rounded-lg" role="alert">
                            <p class="font-bold">Oops! Ada yang salah:</p>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-6">
                            {{-- Nama Pengguna --}}
                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" required>
                            </div>

                            {{-- Alamat Email --}}
                            <div>
                                <label for="email" class="block font-medium text-sm text-gray-700">Alamat Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" required>
                            </div>

                            {{-- Peran (Role) --}}
                            <div>
                                <label for="role" class="block font-medium text-sm text-gray-700">Peran (Role)</label>
                                <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" required>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="guru" {{ old('role', $user->role) == 'guru' ? 'selected' : '' }}>Guru</option>
                                </select>
                            </div>

                            {{-- Laboratorium (untuk Guru) --}}
                            <div>
                                <label for="laboratorium" class="block font-medium text-sm text-gray-700">Laboratorium (khusus Guru)</label>
                                <select name="laboratorium" id="laboratorium" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                    <option value="">-- Pilih Lab --</option>
                                    <option value="Biologi" @selected(old('laboratorium', $user->laboratorium) === 'Biologi')>Biologi</option>
                                    <option value="Fisika" @selected(old('laboratorium', $user->laboratorium) === 'Fisika')>Fisika</option>
                                    <option value="Bahasa" @selected(old('laboratorium', $user->laboratorium) === 'Bahasa')>Bahasa</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Setelan ini membatasi tampilan inventaris & peminjaman guru ke lab terkait.</p>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 font-semibold text-sm transition-colors">Batal</a>
                            <button type="submit" class="px-6 py-2 bg-smaba-dark-blue text-white rounded-md hover:bg-smaba-light-blue font-semibold text-sm shadow-md transition-colors">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
