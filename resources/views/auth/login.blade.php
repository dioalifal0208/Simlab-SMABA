<x-guest-layout>
    {{-- Login Form Only --}}
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg" data-aos="fade-left">
        
        {{-- Header Kartu --}}
        <div class="text-center mb-8">

            
            <a href="/" class="lg:hidden mb-6 inline-block">
                <img src="{{ asset('images/logo-smaba.webp') }}" alt="Logo Smaba" class="w-16 h-16">
            </a>
            <h2 class="text-2xl font-bold text-gray-900">{{ __('auth.login.welcome_title') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('auth.login.welcome_subtitle') }}</p>
        </div>

        {{-- Menampilkan Pesan Error Validasi (Consolidated) --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-50 border-l-4 border-red-400 text-red-700 p-4 text-sm" role="alert">
                <p class="font-bold">{{ __('auth.login.error_title') }}:</p>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        {{-- Form Login --}}
        <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">{{ __('auth.labels.email') }}</label>
                <input id="email" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600" type="email" name="email" :value="old('email')" required autofocus />
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">{{ __('auth.labels.password') }}</label>
                <div x-data="{ showPassword: false }" class="relative">
                    <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                           class="block mt-1 w-full pr-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600" />
                    <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">
                        <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember"
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                           {{ old('remember') ? 'checked' : '' }}
                    >
                    <span class="ml-2 text-sm text-gray-600">{{ __('auth.labels.remember_me') }}</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-indigo-500 hover:text-indigo-600 hover:underline">{{ __('auth.links.forgot_password') }}</a>
                @endif
            </div>
            <div class="pt-2">
                <button type="submit" class="w-full justify-center py-3 px-4 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-colors duration-300">
                    {{ __('auth.buttons.login') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
