<x-guest-layout>
    @php
        // Logika untuk menentukan tab mana yang aktif saat halaman dimuat.
        $activeTab = ($errors->has('name') || $errors->has('password_confirmation')) ? 'signup' : 'login';
    @endphp

    {{-- Kartu Login/Register dengan Animasi --}}
    <div x-data="{ activeTab: '{{ $activeTab }}' }" class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg" data-aos="fade-left">
        
        {{-- Header Kartu --}}
        <div class="text-center mb-8">
            <a href="/" class="lg:hidden mb-6 inline-block">
                <img src="{{ asset('images/logo-smaba.webp') }}" alt="Logo Smaba" class="w-16 h-16">
            </a>
            <h2 class="text-2xl font-bold text-smaba-text">Selamat Datang!</h2>
            <p class="text-sm text-gray-500 mt-1">Silakan masuk atau buat akun baru.</p>
        </div>

        {{-- Tab Switcher (DENGAN PERBAIKAN) --}}
        <div class="mb-6 flex rounded-lg bg-gray-100 p-1">
            {{-- PERBAIKAN DI SINI: Kelas CSS di :class diubah agar lebih kontras --}}
            <button @click="activeTab = 'login'" 
                    class="w-1/2 py-2 px-4 rounded-md text-sm font-semibold text-center transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-smaba-light-blue"
                    :class="{
                        'bg-white shadow-md text-smaba-dark-blue': activeTab === 'login',
                        'text-gray-500 hover:bg-gray-200': activeTab !== 'login'
                    }">
                Login
            </button>
            <button @click="activeTab = 'signup'" 
                    class="w-1/2 py-2 px-4 rounded-md text-sm font-semibold text-center transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-smaba-light-blue"
                    :class="{
                        'bg-white shadow-md text-smaba-dark-blue': activeTab === 'signup',
                        'text-gray-500 hover:bg-gray-200': activeTab !== 'signup'
                    }">
                Register
            </button>
        </div>

        {{-- Menampilkan Pesan Error Validasi --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-50 border-l-4 border-red-400 text-red-700 p-4 text-sm" role="alert">
                <p class="font-bold">Oops! Ada yang salah:</p>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        {{-- Form Login --}}
        <div x-show="activeTab === 'login'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                    <input id="email" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" type="email" name="email" :value="old('email')" required autofocus />
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div x-data="{ showPassword: false }" class="relative">
                        <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                               class="block mt-1 w-full pr-10 rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" />
                        <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">
                            <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                               class="rounded border-gray-300 text-smaba-dark-blue shadow-sm focus:ring-smaba-light-blue"
                               {{ old('remember') ? 'checked' : '' }}
                        >
                        <span class="ml-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-smaba-light-blue hover:text-smaba-dark-blue hover:underline">Lupa password?</a>
                    @endif
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full justify-center py-3 px-4 bg-smaba-dark-blue text-white font-semibold rounded-lg shadow-md hover:bg-smaba-light-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-smaba-dark-blue transition-colors duration-300">
                        {{ __('Log In') }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Form Register --}}
        <div x-show="activeTab === 'signup'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" style="display: none;">
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input id="name" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" type="text" name="name" :value="old('name')" required autofocus />
                </div>
                <div class="mt-4">
                    <label for="register_email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                    <input id="register_email" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" type="email" name="email" :value="old('email')" required />
                </div>
                <div class="mt-4">
                    <label for="register_password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div x-data="{ showPassword: false }" class="relative">
                        <input id="register_password" :type="showPassword ? 'text' : 'password'" name="password" required
                               class="block mt-1 w-full pr-10 rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" />
                        <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">
                            <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>
                <div class="mt-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                    <div x-data="{ showPassword: false }" class="relative">
                        <input id="password_confirmation" :type="showPassword ? 'text' : 'password'" name="password_confirmation" required
                               class="block mt-1 w-full pr-10 rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" />
                        <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">
                            <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full justify-center py-3 px-4 bg-smaba-dark-blue text-white font-semibold rounded-lg shadow-md hover:bg-smaba-light-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-smaba-dark-blue transition-colors duration-300">
                        {{ __('Register') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>