<x-guest-layout>
    <div class="w-full max-w-md" data-aos="fade-left">

        {{-- Card Container --}}
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">

            {{-- Top Accent Bar --}}
            <div class="h-1.5 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500"></div>

            <div class="px-8 pt-8 pb-10">

                {{-- Header: Logo + Title --}}
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-green-50 border border-green-100 shadow-sm mb-5">
                        <a href="/">
                            <img src="{{ asset('images/logo-smaba.webp') }}" alt="Logo" class="w-10 h-10">
                        </a>
                    </div>
                    <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ __('auth.login.welcome_title') }}</h2>
                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">{{ __('auth.login.welcome_subtitle') }}</p>
                </div>

                {{-- Validation Error Alert --}}
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3" role="alert" data-aos="fade-in">
                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-red-500 flex-shrink-0 mt-0.5 border border-red-200 shadow-sm">
                            <i class="fas fa-exclamation-triangle text-sm"></i>
                        </div>
                        <div>
                            <p class="font-bold text-red-800 text-sm">{{ __('auth.login.error_title') }}</p>
                            <ul class="mt-1 space-y-0.5 text-xs text-red-600 list-inside list-disc">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                    @csrf

                    {{-- Email Field --}}
                    <div>
                        <label for="email" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">
                            <i class="fas fa-envelope text-slate-400 mr-1"></i> {{ __('auth.labels.email') }}
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-at text-slate-300 group-focus-within:text-green-500 transition-colors"></i>
                            </div>
                            <input id="email"
                                   type="email"
                                   name="email"
                                   :value="old('email')"
                                   required
                                   autofocus
                                   placeholder="nama@email.com"
                                   class="block w-full pl-11 pr-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium text-slate-800 placeholder-slate-400 shadow-sm hover:border-slate-300 hover:bg-white focus:bg-white focus:border-green-500 focus:ring-4 focus:ring-green-500/10 transition-all duration-200" />
                        </div>
                    </div>

                    {{-- Password Field --}}
                    <div>
                        <label for="password" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">
                            <i class="fas fa-lock text-slate-400 mr-1"></i> {{ __('auth.labels.password') }}
                        </label>
                        <div x-data="{ showPassword: false }" class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-key text-slate-300 group-focus-within:text-green-500 transition-colors"></i>
                            </div>
                            <input id="password"
                                   :type="showPassword ? 'text' : 'password'"
                                   name="password"
                                   required
                                   placeholder="••••••••"
                                   class="block w-full pl-11 pr-12 py-3.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium text-slate-800 placeholder-slate-400 shadow-sm hover:border-slate-300 hover:bg-white focus:bg-white focus:border-green-500 focus:ring-4 focus:ring-green-500/10 transition-all duration-200" />
                            <button type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-green-600 transition-colors"
                                    tabindex="-1">
                                <i class="fas text-sm" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Remember Me + Forgot Password --}}
                    <div class="flex items-center justify-between pt-1">
                        <label for="remember_me" class="flex items-center gap-2 cursor-pointer group">
                            <input id="remember_me"
                                   type="checkbox"
                                   name="remember"
                                   class="w-4 h-4 rounded-md border-slate-300 text-green-600 shadow-sm focus:ring-green-500/30 focus:ring-offset-0 transition-colors"
                                   {{ old('remember') ? 'checked' : '' }}>
                            <span class="text-sm text-slate-500 group-hover:text-slate-700 font-medium transition-colors select-none">{{ __('auth.labels.remember_me') }}</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-sm text-green-600 hover:text-green-700 font-semibold hover:underline underline-offset-2 transition-colors">
                                {{ __('auth.links.forgot_password') }}
                            </a>
                        @endif
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-2">
                        <button type="submit"
                                class="w-full relative flex justify-center items-center py-3.5 px-4 rounded-xl font-bold text-sm bg-green-600 text-white shadow-lg shadow-green-600/25 hover:bg-green-700 hover:shadow-green-700/30 hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-green-500/30 active:scale-[0.98] transition-all duration-200 group">
                            <i class="fas fa-right-to-bracket mr-2 text-white/80 group-hover:text-white transition-colors"></i>
                            {{ __('auth.buttons.login') }}
                        </button>
                    </div>
                </form>

                {{-- Footer Helper --}}
                <div class="mt-6 text-center">
                    <p class="text-xs text-slate-400">
                        <i class="fas fa-shield-halved mr-1 text-slate-300"></i>
                        Sesi Anda dilindungi dengan enkripsi SSL
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
