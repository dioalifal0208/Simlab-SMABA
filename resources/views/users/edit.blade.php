<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('users.index') }}" class="text-slate-400 hover:text-emerald-600 transition-colors w-10 h-10 flex items-center justify-center bg-white rounded-full shadow-sm border border-slate-200">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">Edit Pengguna</h2>
                <p class="text-sm font-medium text-slate-500 mt-1">Perbarui informasi dan peran <strong>{{ $user->name }}</strong>.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 bg-white border-l-4 border-l-red-500 p-4 rounded-xl shadow-sm flex items-start gap-4" data-aos="fade-in">
                    <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-500 flex-shrink-0 mt-0.5"><i class="fas fa-exclamation-triangle"></i></div>
                    <div>
                        <h4 class="font-bold text-red-700">Oops! Ada yang salah:</h4>
                        <ul class="mt-1 text-sm text-red-700 list-inside list-disc">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <x-form.section title="Informasi Akun" description="Perbarui nama lengkap dan alamat email pengguna." icon="fa-user-pen">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.input name="name" label="Nama Lengkap" :value="old('name', $user->name)" icon="fa-user" required />
                        <x-form.input name="email" type="email" label="Alamat Email" :value="old('email', $user->email)" icon="fa-envelope" required />
                    </div>
                </x-form.section>

                <x-form.section title="Peran & Laboratorium" description="Tentukan peran dan, jika diperlukan, laboratorium pengguna." icon="fa-shield">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ role: '{{ old('role', $user->role) }}' }">
                        <x-form.select name="role" label="Peran (Role)" icon="fa-shield" x-model="role" required>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="guru" {{ old('role', $user->role) == 'guru' ? 'selected' : '' }}>Guru</option>
                        </x-form.select>

                        <div x-show="role === 'guru'" x-transition>
                            <x-form.select name="laboratorium" label="Laboratorium" icon="fa-flask" helper="Setelan ini membatasi tampilan inventaris & peminjaman guru ke lab terkait.">
                                <option value="">-- Pilih Lab --</option>
                                <option value="Biologi" @selected(old('laboratorium', $user->laboratorium) === 'Biologi')>Biologi</option>
                                <option value="Fisika" @selected(old('laboratorium', $user->laboratorium) === 'Fisika')>Fisika</option>
                                <option value="Bahasa" @selected(old('laboratorium', $user->laboratorium) === 'Bahasa')>Bahasa</option>
                                <option value="Komputer 1" @selected(old('laboratorium', $user->laboratorium) === 'Komputer 1')>Komputer 1</option>
                                <option value="Komputer 2" @selected(old('laboratorium', $user->laboratorium) === 'Komputer 2')>Komputer 2</option>
                                <option value="Komputer 3" @selected(old('laboratorium', $user->laboratorium) === 'Komputer 3')>Komputer 3</option>
                                <option value="Komputer 4" @selected(old('laboratorium', $user->laboratorium) === 'Komputer 4')>Komputer 4</option>
                            </x-form.select>
                        </div>
                    </div>
                </x-form.section>

                <x-form.sticky-actions cancelUrl="{{ route('users.index') }}" submitText="Simpan Perubahan" submitIcon="fa-save" />
            </form>
        </div>
    </div>
</x-app-layout>
