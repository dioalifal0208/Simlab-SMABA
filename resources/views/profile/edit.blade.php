<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                    {{ __('Pengaturan Profil') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola informasi dasar, keamanan, dan preferensi akun Anda.</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center gap-2 text-sm text-gray-600">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-50 text-green-700 font-semibold">
                    <span class="w-2 h-2 mr-2 rounded-full bg-green-500"></span>Akun aktif
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-gradient-to-r from-smaba-dark-blue to-smaba-light-blue text-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up" data-aos-once="true">
                <div class="p-6 md:p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="h-14 w-14 rounded-full bg-white/20 flex items-center justify-center text-2xl font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-white/80">Profil akun</p>
                            <h3 class="text-2xl font-semibold">{{ auth()->user()->name }}</h3>
                            <p class="text-white/80 text-sm">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3 text-sm">
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 font-semibold">
                            Role: {{ ucfirst(auth()->user()->role ?? 'Pengguna') }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 font-semibold">
                            Diperbarui: {{ optional(auth()->user()->updated_at)->format('d M Y') ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    @include('profile.partials.update-profile-information-form')
                    @include('profile.partials.update-password-form')
                    @include('profile.partials.two-factor')
                </div>

                <div class="space-y-6">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
