<x-guest-layout>
    <div class="w-full max-w-md" data-aos="fade-up">

        {{-- Card Container --}}
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">

            {{-- Top Accent Bar --}}
            <div class="h-1.5 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500"></div>

            <div class="px-8 pt-8 pb-10">

                {{-- Header --}}
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-amber-50 border border-amber-100 shadow-sm mb-4">
                        <i class="fas fa-shield-halved text-2xl text-amber-600"></i>
                    </div>
                    <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Verifikasi 2FA</h2>
                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">Masukkan kode 6 digit dari aplikasi authenticator atau recovery code.</p>
                </div>

                {{-- Status Message --}}
                @if (session('status'))
                    <div class="mb-5 bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3">
                        <div class="w-7 h-7 rounded-full bg-white flex items-center justify-center text-green-600 flex-shrink-0 border border-green-200 shadow-sm">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <p class="text-sm text-green-700 font-medium">{{ session('status') }}</p>
                    </div>
                @endif

                {{-- Error Alert --}}
                @if ($errors->any())
                    <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
                        <div class="w-7 h-7 rounded-full bg-white flex items-center justify-center text-red-500 flex-shrink-0 border border-red-200 shadow-sm">
                            <i class="fas fa-exclamation-triangle text-xs"></i>
                        </div>
                        <div>
                            <ul class="space-y-0.5 text-sm text-red-600 list-inside list-disc">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('two-factor.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="otp" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">
                            <i class="fas fa-key text-slate-400 mr-1"></i> Kode Verifikasi
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-hashtag text-slate-300 group-focus-within:text-green-500 transition-colors"></i>
                            </div>
                            <input id="otp"
                                   name="code"
                                   inputmode="numeric"
                                   maxlength="16"
                                   required
                                   autofocus
                                   placeholder="000000"
                                   class="block w-full pl-11 pr-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50 text-lg font-bold text-slate-800 tracking-[0.3em] text-center placeholder-slate-300 shadow-sm hover:border-slate-300 hover:bg-white focus:bg-white focus:border-green-500 focus:ring-4 focus:ring-green-500/10 transition-all duration-200" />
                        </div>
                        <p class="text-xs text-slate-400 mt-2 text-center">
                            <i class="fas fa-info-circle mr-1"></i> TOTP 6 digit atau recovery code
                        </p>
                    </div>

                    {{-- Submit --}}
                    <div class="pt-1">
                        <button type="submit"
                                class="w-full relative flex justify-center items-center py-3.5 px-4 rounded-xl font-bold text-sm bg-green-600 text-white shadow-lg shadow-green-600/25 hover:bg-green-700 hover:shadow-green-700/30 hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-green-500/30 active:scale-[0.98] transition-all duration-200 group">
                            <i class="fas fa-check-circle mr-2 text-white/80 group-hover:text-white transition-colors"></i>
                            Verifikasi & Masuk
                        </button>
                    </div>
                </form>

                {{-- Footer --}}
                <div class="mt-6 flex items-center justify-center">
                    <a href="{{ route('login') }}" class="text-sm text-slate-400 hover:text-green-600 font-semibold transition-colors flex items-center gap-1.5">
                        <i class="fas fa-arrow-left text-xs"></i> Ganti akun
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
