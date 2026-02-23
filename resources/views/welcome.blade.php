<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'LAB-SMABA') }}</title>
        <link rel="icon" href="{{ asset('images/logo-smaba.webp') }}" type="image/png">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <style>
            body { font-family: 'Inter', sans-serif; }
            .bg-grid-pattern {
                background-image: linear-gradient(to right, #e2e8f0 1px, transparent 1px),
                                  linear-gradient(to bottom, #e2e8f0 1px, transparent 1px);
                background-size: 40px 40px;
                /* Spotlight Effect Configuration */
                mask-image: radial-gradient(400px circle at var(--mouse-x, 0) var(--mouse-y, 0), black, transparent);
                -webkit-mask-image: radial-gradient(400px circle at var(--mouse-x, 0) var(--mouse-y, 0), black, transparent);
                opacity: 0; /* Hidden by default until JS kicks in to prevent flash */
                transition: opacity 0.5s ease;
            }
            .bg-grid-demo {
                background-image: linear-gradient(to right, #e2e8f0 1px, transparent 1px),
                                  linear-gradient(to bottom, #e2e8f0 1px, transparent 1px);
                background-size: 40px 40px;
                opacity: 0.5;
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const grid = document.querySelector('.bg-grid-pattern');
                if (grid) {
                    // Show grid after load
                    grid.style.opacity = '1';
                    
                    document.addEventListener('mousemove', (e) => {
                        grid.style.setProperty('--mouse-x', `${e.clientX}px`);
                        grid.style.setProperty('--mouse-y', `${e.clientY}px`);
                    });
                }
            });
        </script>
        @vite(['resources/js/app.js'])
    </head>

    <body class="landing-page antialiased bg-white text-gray-900 overflow-x-hidden selection:bg-green-100 selection:text-green-900"
          data-authenticated="{{ auth()->check() ? '1' : '0' }}"
          x-data="{ isModalOpen: false, showDemoModal: false, showFeatureModal: false, activeSlide: 0, activeFeature: 'inventory' }"
          @keydown.escape.window="isModalOpen = false; showDemoModal = false; showFeatureModal = false">

        {{-- BACKGROUND GRID (SPOTLIGHT) --}}
        <div class="fixed inset-0 z-0 pointer-events-none bg-grid-pattern"></div>

        {{-- NAVBAR --}}
        <header class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
            <nav class="max-w-7xl mx-auto flex items-center justify-between px-6 lg:px-8 py-4">
                <div class="flex items-center gap-3">
                    <a href="/" class="flex items-center gap-2">
                        <img class="h-9 w-auto" src="{{ asset('images/logo-smaba.webp') }}" alt="Logo SMABA">
                        <div class="hidden sm:block">
                            <p class="text-sm font-bold text-gray-900 tracking-tight">LAB-SMABA</p>
                        </div>
                    </a>
                </div>
                
                <div class="flex items-center gap-4">

                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-green-700 transition-colors">{{ __('welcome.nav.dashboard') }}</a>
                        @else
                            <button @click="isModalOpen = true" class="text-sm font-medium text-gray-600 hover:text-gray-900 px-3 py-2 transition-colors">{{ __('welcome.nav.login') }}</button>
                        @endauth
                    @endif
                </div>
            </nav>
        </header>

        <main class="relative z-10 pt-24 pb-20 lg:pt-32 lg:pb-24">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                {{-- HERO COPY --}}
                <div class="max-w-2xl space-y-8" data-aos="fade-right">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-50 border border-green-100 text-green-700 text-xs font-semibold uppercase tracking-wide">
                        <span class="w-2 h-2 rounded-full bg-green-600 animate-pulse"></span>
                        {{ __('welcome.hero.badge') }}
                    </div>
                    
                    <h1 class="text-5xl sm:text-6xl font-bold text-gray-900 tracking-tight leading-tight">
                        {{ __('welcome.hero.title') }} <br/><span class="text-green-700">{{ __('welcome.hero.title_highlight') }}</span>
                    </h1>
                    
                    <p class="text-lg text-gray-600 leading-relaxed">
                        {{ __('welcome.hero.description') }}
                    </p>
                    
                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <button @click="isModalOpen = true" class="px-8 py-3.5 bg-green-700 text-white font-semibold rounded-lg shadow-sm hover:bg-green-800 hover:shadow transition-all duration-200">
                            {{ __('welcome.hero.cta_start') }}
                        </button>
                        <button @click="showDemoModal = true; activeSlide = 0" class="px-8 py-3.5 text-gray-700 bg-white border border-gray-200 font-semibold rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all duration-200">
                            <i class="fas fa-play-circle mr-2 text-gray-400"></i> {{ __('welcome.hero.cta_tour') }}
                        </button>
                    </div>

                    {{-- STATS GRID (CLEAN) --}}
                    <div class="grid grid-cols-3 gap-8 pt-8 border-t border-gray-100">
                        <div>
                            <div class="text-2xl font-bold text-gray-900">500+</div>
                            <div class="text-sm text-gray-500 font-medium">{{ __('welcome.stats.items') }}</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">50+</div>
                            <div class="text-sm text-gray-500 font-medium">{{ __('welcome.stats.teachers') }}</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">100%</div>
                            <div class="text-sm text-gray-500 font-medium">{{ __('welcome.stats.transparent') }}</div>
                        </div>
                    </div>
                </div>

                {{-- HERO DASHBOARD MOCKUP --}}
                <div class="relative" data-aos="fade-left">
                    {{-- Abstract decoration --}}
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-100 to-gray-100 rounded-2xl blur opacity-30"></div>
                    
                    <div class="relative bg-white border border-gray-200 rounded-xl shadow-xl overflow-hidden">
                        {{-- Mockup Browser Header --}}
                        <div class="bg-gray-50 border-b border-gray-200 px-4 py-3 flex items-center gap-2">
                            <div class="flex gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            </div>
                            <div class="ml-4 px-3 py-1 bg-white border border-gray-200 rounded text-xs text-gray-400 flex-1 text-center font-mono">lab-smaba.sch.id/dashboard</div>
                        </div>
                        
                        {{-- Mockup Content --}}
                        <div class="p-6 space-y-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Ringkasan Hari Ini</h3>
                                    <p class="text-sm text-gray-500">Senin, 24 Januari 2026</p>
                                </div>
                                <span class="px-3 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full border border-green-100">Lab Aktif</span>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 bg-green-50/50 border border-green-100 rounded-lg">
                                    <p class="text-xs text-green-600 font-medium uppercase">Total Peminjaman</p>
                                    <p class="text-2xl font-bold text-green-900 mt-1">24</p>
                                </div>
                                <div class="p-4 bg-gray-50 border border-gray-100 rounded-lg">
                                    <p class="text-xs text-gray-600 font-medium uppercase">Pemeliharaan Alat</p>
                                    <p class="text-2xl font-bold text-gray-900 mt-1">3</p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Jadwal Terkini</p>
                                @if(isset($todayBookings) && $todayBookings->isNotEmpty())
                                    @foreach($todayBookings->take(2) as $booking)
                                    <div class="flex items-center gap-4 p-3 bg-white border border-gray-100 rounded-lg shadow-sm">
                                        <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold text-xs">
                                            {{ substr($booking->laboratorium, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $booking->tujuan_kegiatan }}</p>
                                            <p class="text-xs text-gray-500">{{ $booking->waktu_mulai->format('H:i') }} - {{ $booking->waktu_selesai->format('H:i') }} WIB</p>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="p-4 text-center text-sm text-gray-500 border border-dashed border-gray-200 rounded-lg">Tidak ada jadwal aktif saat ini.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        {{-- FEATURES SECTION --}}
        <section class="py-24 bg-white border-t border-gray-100" id="features">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">{{ __('welcome.features.title') }}</h2>
                    <p class="mt-4 text-gray-600">{{ __('welcome.features.subtitle') }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    {{-- Feature 1 --}}
                    <div class="group p-6 bg-white border border-gray-200 rounded-xl hover:border-green-600 transition-colors duration-300" data-aos="fade-up">
                        <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center mb-4 text-green-600 group-hover:bg-green-600 group-hover:text-white transition-colors">
                            <i class="fas fa-boxes-stacked text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('welcome.features.inventory.title') }}</h3>
                        <p class="text-sm text-gray-600 leading-relaxed mb-4">{{ __('welcome.features.inventory.desc') }}</p>
                        <button @click="showFeatureModal = true; activeFeature = 'inventory'" class="text-sm font-semibold text-green-700 hover:text-green-800 flex items-center gap-1 group-hover:gap-2 transition-all">Lihat Detail <i class="fas fa-search-plus text-xs"></i></button>
                    </div>
                    
                    {{-- Feature 2 --}}
                    <div class="group p-6 bg-white border border-gray-200 rounded-xl hover:border-green-600 transition-colors duration-300" data-aos="fade-up" data-aos-delay="100">
                        <div class="w-12 h-12 bg-emerald-50 rounded-lg flex items-center justify-center mb-4 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <i class="fas fa-qrcode text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('welcome.features.validation.title') }}</h3>
                        <p class="text-sm text-gray-600 leading-relaxed mb-4">{{ __('welcome.features.validation.desc') }}</p>
                        <button @click="showFeatureModal = true; activeFeature = 'validation'" class="text-sm font-semibold text-green-700 hover:text-green-800 flex items-center gap-1 group-hover:gap-2 transition-all">Lihat Detail <i class="fas fa-search-plus text-xs"></i></button>
                    </div>

                    {{-- Feature 3 --}}
                    <div class="group p-6 bg-white border border-gray-200 rounded-xl hover:border-green-600 transition-colors duration-300" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center mb-4 text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                            <i class="fas fa-calendar-alt text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('welcome.features.calendar.title') }}</h3>
                        <p class="text-sm text-gray-600 leading-relaxed mb-4">{{ __('welcome.features.calendar.desc') }}</p>
                        <button @click="showFeatureModal = true; activeFeature = 'calendar'" class="text-sm font-semibold text-green-700 hover:text-green-800 flex items-center gap-1 group-hover:gap-2 transition-all">Lihat Detail <i class="fas fa-search-plus text-xs"></i></button>
                    </div>

                    {{-- Feature 4 --}}
                    <div class="group p-6 bg-white border border-gray-200 rounded-xl hover:border-green-600 transition-colors duration-300" data-aos="fade-up" data-aos-delay="300">
                        <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center mb-4 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <i class="fas fa-file-excel text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('welcome.features.report.title') }}</h3>
                        <p class="text-sm text-gray-600 leading-relaxed mb-4">{{ __('welcome.features.report.desc') }}</p>
                        <button @click="showFeatureModal = true; activeFeature = 'report'" class="text-sm font-semibold text-green-700 hover:text-green-800 flex items-center gap-1 group-hover:gap-2 transition-all">Lihat Detail <i class="fas fa-search-plus text-xs"></i></button>
                    </div>
                </div>
            </div>
        </section>

        {{-- WORKFLOW / STEPS --}}
        <section class="py-24 bg-gray-50 border-y border-gray-200" id="workflow">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div data-aos="fade-right">
                        <h2 class="text-3xl font-bold text-gray-900 tracking-tight mb-6">{{ __('welcome.workflow.title') }}</h2>
                        <div class="space-y-8">
                            <div class="flex gap-4" data-aos="fade-up" data-aos-delay="100">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center font-bold text-sm">1</div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">{{ __('welcome.workflow.step1_title') }}</h4>
                                    <p class="text-gray-600 text-sm mt-1">{{ __('welcome.workflow.step1_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex gap-4" data-aos="fade-up" data-aos-delay="200">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-sm">2</div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">{{ __('welcome.workflow.step2_title') }}</h4>
                                    <p class="text-gray-600 text-sm mt-1">{{ __('welcome.workflow.step2_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex gap-4" data-aos="fade-up" data-aos-delay="300">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-sm">3</div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">{{ __('welcome.workflow.step3_title') }}</h4>
                                    <p class="text-gray-600 text-sm mt-1">{{ __('welcome.workflow.step3_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex gap-4" data-aos="fade-up" data-aos-delay="400">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-sm">4</div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">{{ __('welcome.workflow.step4_title') }}</h4>
                                    <p class="text-gray-600 text-sm mt-1">{{ __('welcome.workflow.step4_desc') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm" data-aos="fade-left">
                        <div class="space-y-4">
                            <div class="flex items-center gap-3 p-3 bg-green-50 border border-green-100 rounded-lg">
                                <i class="fas fa-mobile-alt text-green-600 w-5 text-center"></i>
                                <span class="text-sm font-medium text-green-900">Akses Multi-Device (HP/Laptop)</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-emerald-50 border border-emerald-100 rounded-lg">
                                <i class="fas fa-leaf text-emerald-600 w-5 text-center"></i>
                                <span class="text-sm font-medium text-emerald-900">Paperless & Hemat Kertas</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                <i class="fas fa-shield-alt text-gray-500 w-5 text-center"></i>
                                <span class="text-sm font-medium text-gray-600">Keamanan Privasi (Auto-Lock)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- CTA BOTTOM --}}
        <section class="py-24 bg-white" data-aos="zoom-in">
            <div class="max-w-4xl mx-auto text-center px-6">
                <h2 class="text-4xl font-bold text-gray-900 tracking-tight mb-4">Siap Digitalisasi Lab Anda?</h2>
                <p class="text-lg text-gray-600 mb-8">Bergabung dengan ekosistem manajemen laboratorium yang terstruktur.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <button @click="isModalOpen = true" class="px-8 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition-colors">Login Admin</button>
                </div>
            </div>
        </section>

        {{-- FOOTER --}}
        <footer class="bg-white border-t border-gray-200 py-12">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-2">
                    <img class="h-8 w-auto grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all" src="{{ asset('images/logo-smaba.webp') }}" alt="Logo">
                    <span class="text-sm font-semibold text-gray-500">LAB-SMABA</span>
                </div>
                <div class="flex gap-6 text-sm text-gray-500">
                    <span class="cursor-not-allowed hover:text-gray-900">{{ __('welcome.footer.privacy') }}</span>
                    <span class="cursor-not-allowed hover:text-gray-900">{{ __('welcome.footer.terms') }}</span>
                    <a href="mailto:lab@smaba.sch.id" class="hover:text-gray-900">{{ __('welcome.footer.contact') }}</a>
                </div>
                <p class="text-sm text-gray-400">&copy; {{ date('Y') }} SMA Negeri 1 Babat.</p>
            </div>
        </footer>

        {{-- MODAL LOGIN/REGISTER/FORGOT (RESTYLED SHARP) --}}
        <div x-show="isModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm p-4" style="display: none;">
            <div @click.outside="isModalOpen = false" x-show="isModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4" class="w-full max-w-md bg-white p-8 rounded-lg shadow-xl border border-gray-200 relative">
                
                <h2 class="text-xl font-bold text-gray-900 text-center mb-6">{{ __('welcome.auth.welcome') }}</h2>

                <div id="auth-error-message" class="hidden mb-4 bg-red-50 border border-red-200 text-red-600 p-3 text-sm rounded-md" role="alert"></div>
                <div id="auth-success-message" class="hidden mb-4 bg-green-50 border border-green-200 text-green-600 p-3 text-sm rounded-md" role="alert"></div>

                {{-- Form Login --}}
                <div>
                    <form id="login-form" method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" type="email" name="email" required autofocus />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" type="password" name="password" required />
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500" name="remember">
                                <span class="ml-2 text-sm text-gray-600">{{ __('welcome.auth.remember') }}</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="text-sm text-green-600 hover:text-green-800 font-medium">{{ __('welcome.auth.forgot_password') }}</a>
                        </div>
                        <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all">{{ __('welcome.auth.login_btn') }}</button>
                    </form>
                </div>


            </div>
        </div>

        {{-- PRODUCT TOUR MODAL (INTERACTIVE) --}}
        <div x-show="showDemoModal" x-transition.opacity class="fixed inset-0 z-[60] overflow-y-auto bg-gray-900/80 backdrop-blur-sm" style="display: none;">
            <div class="flex min-h-full items-center justify-center p-4">
                <div @click.outside="showDemoModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl h-auto md:h-[600px] flex overflow-hidden border border-gray-200 flex-col md:flex-row relative">
                
                {{-- Sidebar Navigation --}}
                <div class="w-full md:w-1/3 bg-gray-50 border-r border-gray-200 p-6 flex flex-col">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="font-bold text-xl text-gray-900">Tur Fitur</h3>
                        <button @click="showDemoModal = false" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
                    </div>
                    
                    <div class="space-y-2 flex-1">
                        <button @click="activeSlide = 0" :class="activeSlide === 0 ? 'bg-white shadow-sm border-green-200 ring-1 ring-green-500' : 'hover:bg-gray-100 border-transparent'" class="w-full text-left p-4 rounded-xl border transition-all flex items-start gap-3 group">
                            <div :class="activeSlide === 0 ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-500'" class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shrink-0 transition-colors">1</div>
                            <div>
                                <p :class="activeSlide === 0 ? 'text-green-700' : 'text-gray-700'" class="font-semibold text-sm">Dashboard Realtime</p>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">Pantau stok, notifikasi, dan lab aktif dalam satu layar.</p>
                            </div>
                        </button>

                        <button @click="activeSlide = 1" :class="activeSlide === 1 ? 'bg-white shadow-sm border-green-200 ring-1 ring-green-500' : 'hover:bg-gray-100 border-transparent'" class="w-full text-left p-4 rounded-xl border transition-all flex items-start gap-3 group">
                            <div :class="activeSlide === 1 ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-500'" class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shrink-0 transition-colors">2</div>
                            <div>
                                <p :class="activeSlide === 1 ? 'text-green-700' : 'text-gray-700'" class="font-semibold text-sm">Peminjaman Mudah</p>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">Cari barang, pilih tanggal, dan ajukan dalam 3 klik.</p>
                            </div>
                        </button>

                        <button @click="activeSlide = 2" :class="activeSlide === 2 ? 'bg-white shadow-sm border-green-200 ring-1 ring-green-500' : 'hover:bg-gray-100 border-transparent'" class="w-full text-left p-4 rounded-xl border transition-all flex items-start gap-3 group">
                            <div :class="activeSlide === 2 ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-500'" class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shrink-0 transition-colors">3</div>
                            <div>
                                <p :class="activeSlide === 2 ? 'text-green-700' : 'text-gray-700'" class="font-semibold text-sm">Approval Digital</p>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">Validasi booking dari mana saja tanpa kertas.</p>
                            </div>
                        </button>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <button @click="isModalOpen = true; showDemoModal = false" class="w-full py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition">Login Sekarang &rarr;</button>
                    </div>
                </div>

                {{-- Demo Content --}}
                <div class="w-full md:w-2/3 bg-gray-100 relative overflow-hidden flex items-center justify-center p-8 bg-grid-demo">
                    
                    {{-- SLIDE 1: DASHBOARD --}}
                    <div x-show="activeSlide === 0" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" class="w-full max-w-lg bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="h-6 bg-gray-50 border-b flex items-center px-4 gap-2">
                            <div class="w-2 h-2 rounded-full bg-red-400"></div><div class="w-2 h-2 rounded-full bg-yellow-400"></div><div class="w-2 h-2 rounded-full bg-green-400"></div>
                        </div>
                        <div class="p-6">
                            <h4 class="font-bold text-gray-900 mb-4">Statistik Lab</h4>
                            <div class="flex gap-4 items-end h-32 pl-2 border-b border-l border-gray-200">
                                <div class="w-1/4 bg-green-200 rounded-t-lg h-0 animate-[grow_1s_ease-out_forwards]" style="--h: 60%"></div>
                                <div class="w-1/4 bg-green-300 rounded-t-lg h-0 animate-[grow_1s_ease-out_0.2s_forwards]" style="--h: 80%"></div>
                                <div class="w-1/4 bg-green-500 rounded-t-lg h-0 animate-[grow_1s_ease-out_0.4s_forwards]" style="--h: 40%"></div>
                                <div class="w-1/4 bg-green-700 rounded-t-lg h-0 animate-[grow_1s_ease-out_0.6s_forwards]" style="--h: 90%"></div>
                            </div>
                            <style> @keyframes grow { to { height: var(--h); } } </style>
                            <div class="flex justify-between mt-4">
                                <div class="flex items-center gap-2 p-3 bg-red-50 rounded-lg w-full mr-2 transform hover:scale-105 transition duration-500 animate-[bounce_2s_infinite]">
                                    <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                    <span class="text-xs font-semibold text-red-700">Stok Menipis!</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SLIDE 2: BOOKING FORM --}}
                    {{-- SLIDE 2: BOOKING FORM --}}
                    <div x-show="activeSlide === 1" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" class="w-full max-w-lg bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="h-6 bg-gray-50 border-b flex items-center px-4 gap-2">
                             <span class="text-xs text-gray-400 font-mono">Form Peminjaman</span>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="space-y-1">
                                <div class="h-2 w-16 bg-gray-200 rounded"></div>
                                <div class="h-10 w-full border border-gray-200 rounded-lg bg-gray-50 flex items-center px-3 text-sm text-gray-500 animate-pulse">Mikroskop Cahaya X-200</div>
                            </div>
                            <div class="flex gap-4">
                                <div class="flex-1 space-y-1">
                                    <div class="h-2 w-12 bg-gray-200 rounded"></div>
                                    <div class="h-10 w-full border border-gray-200 rounded-lg bg-gray-50"></div>
                                </div>
                                <div class="flex-1 space-y-1">
                                    <div class="h-2 w-12 bg-gray-200 rounded"></div>
                                    <div class="h-10 w-full border border-gray-200 rounded-lg bg-gray-50"></div>
                                </div>
                            </div>
                            <button class="w-full py-2 bg-green-600 text-white rounded-lg text-sm font-semibold transform hover:scale-105 transition">Ajukan Peminjaman</button>
                            
                            {{-- Cursor Animation --}}
                            <div class="absolute top-1/2 left-1/2 pointer-events-none transform -translate-x-1/2 -translate-y-1/2 animate-[moveCursor_3s_infinite]">
                                <img src="https://img.icons8.com/ios-filled/50/000000/cursor.png" class="w-6 h-6 opacity-80">
                            </div>
                            <style> @keyframes moveCursor { 0% { transform: translate(50px, 50px); } 50% { transform: translate(-20px, 20px) scale(0.9); } 100% { transform: translate(50px, 50px); } } </style>
                        </div>
                    </div>

                    {{-- SLIDE 3: APPROVAL --}}
                    {{-- SLIDE 3: APPROVAL --}}
                    <div x-show="activeSlide === 2" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" class="w-full max-w-lg">
                        <div class="bg-white rounded-lg shadow-md mb-3 p-4 border border-l-4 border-l-yellow-400 animate-[approve_3s_infinite]">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h5 class="font-bold text-gray-800">Peminjaman #INV-2026</h5>
                                    <p class="text-sm text-gray-500">Pak Budi - Fisika</p>
                                </div>
                                <span class="badge px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded font-bold uppercase" id="status-badge">Menunggu</span>
                            </div>
                        </div>
                        <div class="flex justify-center mt-4">
                            <div class="bg-green-900 text-white px-4 py-2 rounded-full text-sm shadow-lg flex items-center gap-2 animate-bounce">
                                <i class="fas fa-check"></i> Disetujui Kepala Lab
                            </div>
                        </div>
                        <style> 
                            @keyframes approve { 
                                0% { border-left-color: #facc15; } 
                                40% { border-left-color: #facc15; transform: scale(1); }
                                50% { transform: scale(1.02); }
                                100% { border-left-color: #22c55e; } 
                            } 
                        </style>
                    </div>

                </div>
                </div>
            </div>
        </div>

        {{-- FEATURE SPOTLIGHT MODAL --}}
        <div x-show="showFeatureModal" x-transition.opacity class="fixed inset-0 z-[70] overflow-y-auto bg-gray-900/80 backdrop-blur-sm" style="display: none;">
            <div class="flex min-h-full items-center justify-center p-4">
                <div @click.outside="showFeatureModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden border border-gray-200 flex flex-col relative animate-[popIn_0.3s_ease-out] my-8">
                
                {{-- Header --}}
                <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900" x-text="activeFeature === 'inventory' ? 'Inventaris Digital' : (activeFeature === 'validation' ? 'Validasi Dokumen QR' : (activeFeature === 'calendar' ? 'Kalender Laboratorium' : 'Laporan & Analitik'))"></h3>
                        <p class="text-gray-500 text-sm mt-1">Preview fitur unggulan SIMLAB SMABA</p>
                    </div>
                    <button @click="showFeatureModal = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- Body Content --}}
                <div class="p-8 bg-gray-50/30 min-h-[400px] flex items-center justify-center">
                    
                    {{-- 1. INVENTORY MOCKUP --}}
                    <div x-show="activeFeature === 'inventory'" class="w-full max-w-3xl space-y-4">
                        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 flex gap-4">
                            <div class="flex-1 relative">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                                <input disabled type="text" value="Mikroskop" class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700">
                            </div>
                            <button class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-semibold hover:bg-green-700"><i class="fas fa-plus mr-2"></i> Tambah Alat</button>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 text-gray-500 font-medium">
                                    <tr><th class="px-6 py-3">Nama Alat</th><th class="px-6 py-3">Kondisi</th><th class="px-6 py-3">Stok</th></tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr class="hover:bg-green-50/50 transition">
                                        <td class="px-6 py-4 font-medium text-gray-900">Mikroskop Binokuler X-200</td>
                                        <td class="px-6 py-4"><span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-bold">Baik</span></td>
                                        <td class="px-6 py-4 text-gray-600">12 Unit</td>
                                    </tr>
                                    <tr class="hover:bg-green-50/50 transition">
                                        <td class="px-6 py-4 font-medium text-gray-900">Gelas Ukur 100ml</td>
                                        <td class="px-6 py-4"><span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-bold">Baik</span></td>
                                        <td class="px-6 py-4 text-gray-600">25 Unit</td>
                                    </tr>
                                    <tr class="hover:bg-green-50/50 transition">
                                        <td class="px-6 py-4 font-medium text-gray-900">Tabung Reaksi Pyrex</td>
                                        <td class="px-6 py-4"><span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-bold">Perlu Cek</span></td>
                                        <td class="px-6 py-4 text-gray-600">48 Unit</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- 2. VALIDATION UI MOCKUP --}}
                    <div x-show="activeFeature === 'validation'" class="w-full max-w-sm bg-white p-8 rounded-xl shadow-lg border border-gray-200 text-center relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 to-emerald-500"></div>
                        <div class="w-24 h-24 mx-auto bg-white p-2 rounded-lg border-2 border-dashed border-gray-300 mb-6">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=Validasi-SMABA-Official" class="w-full h-full opacity-80">
                        </div>
                        <h4 class="text-lg font-bold text-gray-900">Dokumen Valid</h4>
                        <p class="text-sm text-gray-500 mb-6">Surat Peminjaman #BOOK-2026-001</p>
                        
                        <div class="space-y-3 text-left bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Peminjam:</span>
                                <span class="font-medium text-gray-900">Drs. Sutrisno</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Status:</span>
                                <span class="text-emerald-600 font-bold flex items-center gap-1"><i class="fas fa-check-circle"></i> Disetujui</span>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-center">
                            <img class="h-8 opacity-50" src="{{ asset('images/logo-smaba.webp') }}">
                        </div>
                    </div>

                    {{-- 3. CALENDAR UI MOCKUP --}}
                    <div x-show="activeFeature === 'calendar'" class="w-full max-w-3xl bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-white border-b border-gray-200 p-4 flex justify-between items-center">
                            <h4 class="font-bold text-gray-800">Januari 2026</h4>
                            <div class="flex gap-2">
                                <button class="p-1 px-3 text-sm bg-gray-100 rounded hover:bg-gray-200">Bulan</button>
                                <button class="p-1 px-3 text-sm text-gray-500 hover:bg-gray-50">Minggu</button>
                            </div>
                        </div>
                        <div class="grid grid-cols-7 gap-px bg-gray-200 border-b border-gray-200 text-center text-xs font-semibold text-gray-500 bg-gray-50">
                            <div class="py-2">Sen</div><div class="py-2">Sel</div><div class="py-2">Rab</div><div class="py-2">Kam</div><div class="py-2">Jum</div><div class="py-2">Sab</div><div class="py-2 text-red-500">Mig</div>
                        </div>
                        <div class="grid grid-cols-7 grid-rows-2 h-64 gap-px bg-gray-200">
                             {{-- Dummy Calendar Cells --}}
                             <div class="bg-white p-2 min-h-[100px] text-sm text-gray-400">29</div>
                             <div class="bg-white p-2 min-h-[100px] text-sm text-gray-400">30</div>
                             <div class="bg-white p-2 min-h-[100px] text-sm font-bold">1</div>
                             <div class="bg-white p-2 min-h-[100px]">
                                 <span class="text-sm font-bold">2</span>
                                 <div class="mt-1 px-2 py-1 bg-green-100 text-green-700 text-[10px] rounded border border-green-200 font-medium truncate">10:00 - Fisika XII</div>
                             </div>
                             <div class="bg-white p-2 min-h-[100px] text-sm font-bold">3</div>
                             <div class="bg-white p-2 min-h-[100px] text-sm font-bold">4</div>
                             <div class="bg-red-50 p-2 min-h-[100px] text-sm text-red-500 font-bold">5</div>
                             
                             <div class="bg-white p-2 min-h-[100px] text-sm font-bold">6</div>
                             <div class="bg-white p-2 min-h-[100px]">
                                 <span class="text-sm font-bold">7</span>
                                 <div class="mt-1 px-2 py-1 bg-purple-100 text-purple-700 text-[10px] rounded border border-purple-200 font-medium truncate">08:00 - Kimia X</div>
                                 <div class="mt-1 px-2 py-1 bg-amber-100 text-amber-700 text-[10px] rounded border border-amber-200 font-medium truncate">13:00 - Biologi XI</div>
                             </div>
                             <div class="bg-white p-2 min-h-[100px] text-sm font-bold">8</div>
                             <div class="bg-white p-2 min-h-[100px] text-sm font-bold">9</div>
                             <div class="bg-white p-2 min-h-[100px] text-sm font-bold">10</div>
                             <div class="bg-white p-2 min-h-[100px] text-sm font-bold">11</div>
                             <div class="bg-red-50 p-2 min-h-[100px] text-sm text-red-500 font-bold">12</div>
                        </div>
                    </div>

                    {{-- 4. EXCEL/REPORT UI MOCKUP --}}
                    <div x-show="activeFeature === 'report'" class="w-full max-w-3xl flex flex-col md:flex-row gap-6">
                         <div class="flex-1 bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col items-center justify-center text-center space-y-4">
                             <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center text-green-600 mb-2">
                                 <i class="fas fa-file-excel text-3xl"></i>
                             </div>
                             <h4 class="text-lg font-bold text-gray-900">Export Laporan Bulanan</h4>
                             <p class="text-sm text-gray-500">Unduh rekapitulasi peminjaman lengkap dengan detail alat dan penanggung jawab.</p>
                             <button class="w-full py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition shadow-md"><i class="fas fa-download mr-2"></i> Unduh .XLSX</button>
                         </div>
                         <div class="flex-1 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                             <h5 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wide">Statistik Peminjaman</h5>
                             <div class="space-y-4">
                                 <div>
                                     <div class="flex justify-between text-xs mb-1"><span class="font-medium">Lab Fisika</span> <span class="text-gray-500">85%</span></div>
                                     <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-green-600 h-2 rounded-full" style="width: 85%"></div></div>
                                 </div>
                                 <div>
                                     <div class="flex justify-between text-xs mb-1"><span class="font-medium">Lab Biologi</span> <span class="text-gray-500">62%</span></div>
                                     <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-emerald-500 h-2 rounded-full" style="width: 62%"></div></div>
                                 </div>
                                 <div>
                                     <div class="flex justify-between text-xs mb-1"><span class="font-medium">Lab Kimia</span> <span class="text-gray-500">45%</span></div>
                                     <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-amber-500 h-2 rounded-full" style="width: 45%"></div></div>
                                 </div>
                                  <div>
                                     <div class="flex justify-between text-xs mb-1"><span class="font-medium">Lab Komputer</span> <span class="text-gray-500">92%</span></div>
                                     <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-purple-600 h-2 rounded-full" style="width: 92%"></div></div>
                                 </div>
                             </div>
                         </div>
                    </div>

                </div>
                
                {{-- Footer --}}
                <div class="bg-gray-50 p-4 border-t border-gray-200 text-center">
                    <button @click="isModalOpen = true; showFeatureModal = false" class="text-green-600 font-semibold text-sm hover:underline hover:text-green-800">Coba Langsung di Dashboard &rarr;</button>
                </div>
            </div>
            <style>
                @keyframes popIn {
                    0% { transform: scale(0.95); opacity: 0; }
                    100% { transform: scale(1); opacity: 1; }
                }
            </style>
            </div>
        </div>

        {{-- AOS ANIMATION SCRIPT --}}
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            AOS.init({
                once: true,
                duration: 800,
                offset: 50,
                easing: 'ease-out-cubic',
            });
        </script>

        {{-- KEEPING ORIGINAL JAVASCRIPT LOGIC --}}
        <script>
            document.addEventListener('alpine:init', () => {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const errorMessageDiv = document.getElementById('auth-error-message');
                const successMessageDiv = document.getElementById('auth-success-message');
                const isAuthenticated = document.body.dataset.authenticated === '1';

                function showError(message) {
                    successMessageDiv.classList.add('hidden');
                    errorMessageDiv.classList.remove('hidden');
                    errorMessageDiv.innerHTML = message;
                }

                function showSuccess(message) {
                    errorMessageDiv.classList.add('hidden');
                    successMessageDiv.classList.remove('hidden');
                    successMessageDiv.innerHTML = message;
                }

                const loginForm = document.getElementById('login-form');
                if (loginForm) {
                    loginForm.addEventListener('submit', async function (event) {
                        event.preventDefault();
                        const submitButton = this.querySelector('button[type="submit"]');
                        const originalButtonText = submitButton.innerHTML;
                        submitButton.innerHTML = 'Memproses...';
                        submitButton.disabled = true;
                        errorMessageDiv.classList.add('hidden');
                        successMessageDiv.classList.add('hidden');
                        try {
                            const formData = new FormData(this);
                            const response = await fetch('{{ route('login') }}', {
                                method: 'POST', body: formData, credentials: 'include',
                                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken }
                            });
                            if (response.redirected) { window.location.href = response.url; return; }
                            const data = await response.json();
                            if (!response.ok) { showError(data.message || 'Terjadi kesalahan.'); } 
                            else { window.location.href = '{{ route('dashboard') }}'; }
                        } catch (error) { showError('Koneksi gagal. Periksa jaringan Anda.'); } 
                        finally { submitButton.innerHTML = originalButtonText; submitButton.disabled = false; }
                    });
                }


            });
        </script>
    </body>
</html>
