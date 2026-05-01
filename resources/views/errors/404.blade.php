<x-app-layout :hide-chrome="true">
    {{-- Full viewport centering: override main-wrapper styles for error pages --}}
    <style>
        #main-wrapper {
            top: 0 !important;
            padding-left: 0 !important;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f0f4ff 0%, #f8fafc 40%, #faf5ff 100%);
        }
        #main-wrapper > main {
            min-height: auto !important;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        /* Floating particles animation */
        .error-particle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.12;
            animation: float-particle 8s ease-in-out infinite;
        }
        .error-particle:nth-child(1) {
            width: 180px; height: 180px;
            background: linear-gradient(135deg, #818cf8, #a78bfa);
            top: 10%; left: 8%;
            animation-delay: 0s;
        }
        .error-particle:nth-child(2) {
            width: 120px; height: 120px;
            background: linear-gradient(135deg, #c084fc, #e879f9);
            top: 60%; right: 12%;
            animation-delay: 2s;
        }
        .error-particle:nth-child(3) {
            width: 80px; height: 80px;
            background: linear-gradient(135deg, #6366f1, #818cf8);
            bottom: 20%; left: 20%;
            animation-delay: 4s;
        }
        @keyframes float-particle {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }

        /* Glowing 404 text */
        .error-code {
            font-size: clamp(6rem, 18vw, 12rem);
            font-weight: 900;
            line-height: 1;
            letter-spacing: -0.04em;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 4px 24px rgba(99, 102, 241, 0.18));
            animation: subtle-glow 4s ease-in-out infinite alternate;
        }
        @keyframes subtle-glow {
            0% { filter: drop-shadow(0 4px 24px rgba(99, 102, 241, 0.15)); }
            100% { filter: drop-shadow(0 4px 32px rgba(139, 92, 246, 0.25)); }
        }

        /* Divider line animation */
        .animated-divider {
            width: 48px;
            height: 3px;
            border-radius: 999px;
            background: linear-gradient(90deg, #818cf8, #a78bfa, #c084fc);
            background-size: 200% 100%;
            animation: shimmer-divider 3s ease-in-out infinite;
        }
        @keyframes shimmer-divider {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Button hover glow */
        .btn-glow {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .btn-glow::before {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: inherit;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            opacity: 0;
            z-index: -1;
            transition: opacity 0.3s ease;
            filter: blur(8px);
        }
        .btn-glow:hover::before {
            opacity: 0.4;
        }
    </style>

    <div class="relative w-full flex items-center justify-center min-h-full px-4 py-8">
        {{-- Background particles --}}
        <div class="error-particle" aria-hidden="true"></div>
        <div class="error-particle" aria-hidden="true"></div>
        <div class="error-particle" aria-hidden="true"></div>

        {{-- Main content card --}}
        <div class="relative z-10 w-full max-w-lg text-center" data-aos="fade-up" data-aos-duration="700">

            {{-- Error code --}}
            <div class="error-code select-none" aria-hidden="true">404</div>

            {{-- Animated divider --}}
            <div class="flex justify-center mt-2 mb-6">
                <div class="animated-divider"></div>
            </div>

            {{-- Message --}}
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">
                Halaman tidak ditemukan
            </h1>
            <p class="mt-3 text-sm sm:text-base text-gray-500 leading-relaxed max-w-sm mx-auto">
                Halaman yang Anda cari tidak tersedia, mungkin sudah dipindahkan atau alamat URL salah.
            </p>

            {{-- Action buttons --}}
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('dashboard') }}" 
                   class="btn-glow inline-flex items-center gap-2.5 px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-semibold shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 hover:-translate-y-0.5 transition-all duration-300">
                    <i class="fas fa-home text-white/80"></i>
                    Kembali ke Dashboard
                </a>
                <button
                    type="button"
                    onclick="window.history.back()"
                    class="inline-flex items-center gap-2.5 px-6 py-3 rounded-xl bg-white/80 backdrop-blur border border-gray-200 text-sm font-semibold text-gray-600 shadow-sm hover:bg-white hover:border-gray-300 hover:text-gray-800 hover:-translate-y-0.5 transition-all duration-300">
                    <i class="fas fa-arrow-left text-gray-400"></i>
                    Halaman Sebelumnya
                </button>
            </div>

            {{-- Help tip --}}
            <div class="mt-10">
                <div class="inline-flex items-center gap-2 text-xs text-gray-400">
                    <i class="fas fa-info-circle text-indigo-300"></i>
                    <span>Jika Anda merasa ini kesalahan sistem, hubungi admin laboratorium.</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
