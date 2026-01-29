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
    </head>

    <body class="antialiased bg-white text-gray-900 overflow-x-hidden selection:bg-blue-100 selection:text-blue-900"
          data-authenticated="{{ auth()->check() ? '1' : '0' }}"
          x-data="{ isModalOpen: false, showTestimonialModal: false, showDemoModal: false, activeSlide: 0 }"
          @keydown.escape.window="isModalOpen = false; showTestimonialModal = false; showDemoModal = false">

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
                            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-blue-700 transition-colors">Dashboard</a>
                        @else
                            <button @click="isModalOpen = true" class="text-sm font-medium text-gray-600 hover:text-gray-900 px-3 py-2 transition-colors">Masuk</button>
                        @endauth
                    @endif
                </div>
            </nav>
        </header>

        <main class="relative z-10 pt-24 pb-20 lg:pt-32 lg:pb-24">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                {{-- HERO COPY --}}
                <div class="max-w-2xl space-y-8" data-aos="fade-right">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-blue-700 text-xs font-semibold uppercase tracking-wide">
                        <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                        Sistem Manajemen Laboratorium Sekolah
                    </div>
                    
                    <h1 class="text-5xl sm:text-6xl font-bold text-gray-900 tracking-tight leading-tight">
                        Kelola Praktikum <br/><span class="text-blue-700">Lebih Profesional</span>
                    </h1>
                    
                    <p class="text-lg text-gray-600 leading-relaxed">
                        Platform terintegrasi untuk inventaris, peminjaman alat, dan penjadwalan laboratorium. 
                        Singkirkan birokrasi kertas, beralih ke sistem digital yang transparan & efisien.
                    </p>
                    
                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <button @click="isModalOpen = true" class="px-8 py-3.5 bg-blue-700 text-white font-semibold rounded-lg shadow-sm hover:bg-blue-800 hover:shadow transition-all duration-200">
                            Mulai Sekarang
                        </button>
                        <button @click="showDemoModal = true; activeSlide = 0" class="px-8 py-3.5 text-gray-700 bg-white border border-gray-200 font-semibold rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all duration-200">
                            <i class="fas fa-play-circle mr-2 text-gray-400"></i> Tur Produk
                        </button>
                    </div>

                    {{-- STATS GRID (CLEAN) --}}
                    <div class="grid grid-cols-3 gap-8 pt-8 border-t border-gray-100">
                        <div>
                            <div class="text-2xl font-bold text-gray-900">500+</div>
                            <div class="text-sm text-gray-500 font-medium">Item Inventaris</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">50+</div>
                            <div class="text-sm text-gray-500 font-medium">Guru Aktif</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">100%</div>
                            <div class="text-sm text-gray-500 font-medium">Transparan</div>
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
                                <div class="p-4 bg-blue-50/50 border border-blue-100 rounded-lg">
                                    <p class="text-xs text-blue-600 font-medium uppercase">Total Peminjaman</p>
                                    <p class="text-2xl font-bold text-blue-900 mt-1">24</p>
                                </div>
                                <div class="p-4 bg-gray-50 border border-gray-100 rounded-lg">
                                    <p class="text-xs text-gray-600 font-medium uppercase">Item Maintenance</p>
                                    <p class="text-2xl font-bold text-gray-900 mt-1">3</p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Jadwal Terkini</p>
                                @if(isset($todayBookings) && $todayBookings->isNotEmpty())
                                    @foreach($todayBookings->take(2) as $booking)
                                    <div class="flex items-center gap-4 p-3 bg-white border border-gray-100 rounded-lg shadow-sm">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
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
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Semua Fitur dalam Satu Platform</h2>
                    <p class="mt-4 text-gray-600">Sistem yang dirancang khusus untuk kebutuhan laboratorium sekolah modern.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    {{-- Feature 1 --}}
                    <div class="group p-6 bg-white border border-gray-200 rounded-xl hover:border-blue-600 transition-colors duration-300" data-aos="fade-up">
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mb-4 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <i class="fas fa-boxes-stacked text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Inventaris Digital</h3>
                        <p class="text-sm text-gray-600 leading-relaxed mb-4">Database terpusat untuk semua alat dan bahan. Lacak kondisi dan lokasi dengan mudah.</p>
                        <a href="#workflow" class="text-sm font-semibold text-blue-700 hover:text-blue-800 flex items-center gap-1 group-hover:gap-2 transition-all">Lihat Alur <i class="fas fa-arrow-right text-xs"></i></a>
                    </div>
                    
                    {{-- Feature 2 --}}
                    <div class="group p-6 bg-white border border-gray-200 rounded-xl hover:border-blue-600 transition-colors duration-300" data-aos="fade-up" data-aos-delay="100">
                        <div class="w-12 h-12 bg-emerald-50 rounded-lg flex items-center justify-center mb-4 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Approval Berjenjang</h3>
                        <p class="text-sm text-gray-600 leading-relaxed mb-4">Sistem validasi peminjaman oleh kepala lab untuk memastikan keamanan alat.</p>
                        <a href="#workflow" class="text-sm font-semibold text-blue-700 hover:text-blue-800 flex items-center gap-1 group-hover:gap-2 transition-all">Lihat Alur <i class="fas fa-arrow-right text-xs"></i></a>
                    </div>

                    {{-- Feature 3 --}}
                    <div class="group p-6 bg-white border border-gray-200 rounded-xl hover:border-blue-600 transition-colors duration-300" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center mb-4 text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                            <i class="fas fa-calendar-alt text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Kalender Cerdas</h3>
                        <p class="text-sm text-gray-600 leading-relaxed mb-4">Cegah bentrok jadwal antar guru mata pelajaran Fisika, Biologi, dan Bahasa.</p>
                        <a href="#workflow" class="text-sm font-semibold text-blue-700 hover:text-blue-800 flex items-center gap-1 group-hover:gap-2 transition-all">Lihat Alur <i class="fas fa-arrow-right text-xs"></i></a>
                    </div>

                    {{-- Feature 4 --}}
                    <div class="group p-6 bg-white border border-gray-200 rounded-xl hover:border-blue-600 transition-colors duration-300" data-aos="fade-up" data-aos-delay="300">
                        <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center mb-4 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <i class="fas fa-chart-pie text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Laporan Otomatis</h3>
                        <p class="text-sm text-gray-600 leading-relaxed mb-4">Export data penggunaan lab dan kondisi alat ke format Excel/PDF untuk administrasi.</p>
                        <a href="#workflow" class="text-sm font-semibold text-blue-700 hover:text-blue-800 flex items-center gap-1 group-hover:gap-2 transition-all">Lihat Alur <i class="fas fa-arrow-right text-xs"></i></a>
                    </div>
                </div>
            </div>
        </section>

        {{-- WORKFLOW / STEPS --}}
        <section class="py-24 bg-gray-50 border-y border-gray-200" id="workflow">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div data-aos="fade-right">
                        <h2 class="text-3xl font-bold text-gray-900 tracking-tight mb-6">Workflow Sederhana</h2>
                        <div class="space-y-8">
                            <div class="flex gap-4" data-aos="fade-up" data-aos-delay="100">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm">1</div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">Guru Mengajukan</h4>
                                    <p class="text-gray-600 text-sm mt-1">Pilih tanggal, alat, dan jam pelajaran melalui dashboard.</p>
                                </div>
                            </div>
                            <div class="flex gap-4" data-aos="fade-up" data-aos-delay="200">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-sm">2</div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">Validasi Petugas</h4>
                                    <p class="text-gray-600 text-sm mt-1">Kepala Lab menyetujui jika alat tersedia dan kondisi baik.</p>
                                </div>
                            </div>
                            <div class="flex gap-4" data-aos="fade-up" data-aos-delay="300">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-sm">3</div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">Pelaksanaan</h4>
                                    <p class="text-gray-600 text-sm mt-1">Praktikum berjalan, pengembalian alat dicatat kondisi akhirnya.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm" data-aos="fade-left">
                        <div class="space-y-4">
                            <div class="flex items-center gap-3 p-3 bg-blue-50 border border-blue-100 rounded-lg">
                                <div class="w-2 h-2 rounded-full bg-blue-600"></div>
                                <span class="text-sm font-medium text-blue-900">Notifikasi WhatsApp (Coming Soon)</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-emerald-50 border border-emerald-100 rounded-lg">
                                <div class="w-2 h-2 rounded-full bg-emerald-600"></div>
                                <span class="text-sm font-medium text-emerald-900">Cetak Surat Peminjaman</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                                <span class="text-sm font-medium text-gray-600">Riwayat Digital</span>
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
                    <span class="cursor-not-allowed hover:text-gray-900">Kebijakan Privasi</span>
                    <span class="cursor-not-allowed hover:text-gray-900">Syarat & Ketentuan</span>
                    <a href="mailto:lab@smaba.sch.id" class="hover:text-gray-900">Hubungi Kami</a>
                </div>
                <p class="text-sm text-gray-400">&copy; {{ date('Y') }} SMA Negeri 1 Babat.</p>
            </div>
        </footer>

        {{-- MODAL LOGIN/REGISTER/FORGOT (RESTYLED SHARP) --}}
        <div x-show="isModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm p-4" style="display: none;">
            <div @click.outside="isModalOpen = false" x-show="isModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4" class="w-full max-w-md bg-white p-8 rounded-lg shadow-xl border border-gray-200 relative">
                
                <h2 class="text-xl font-bold text-gray-900 text-center mb-6">Selamat Datang</h2>

                <div id="auth-error-message" class="hidden mb-4 bg-red-50 border border-red-200 text-red-600 p-3 text-sm rounded-md" role="alert"></div>
                <div id="auth-success-message" class="hidden mb-4 bg-green-50 border border-green-200 text-green-600 p-3 text-sm rounded-md" role="alert"></div>

                {{-- Form Login --}}
                <div>
                    <form id="login-form" method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" type="email" name="email" required autofocus />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" type="password" name="password" required />
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                                <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Lupa password?</a>
                        </div>
                        <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">Masuk</button>
                    </form>
                </div>


            </div>
        </div>

        {{-- PRODUCT TOUR MODAL (INTERACTIVE) --}}
        <div x-show="showDemoModal" x-transition.opacity class="fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm p-4" style="display: none;">
            <div @click.outside="showDemoModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl h-[600px] flex overflow-hidden border border-gray-200 flex-col md:flex-row">
                
                {{-- Sidebar Navigation --}}
                <div class="w-full md:w-1/3 bg-gray-50 border-r border-gray-200 p-6 flex flex-col">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="font-bold text-xl text-gray-900">Tur Fitur</h3>
                        <button @click="showDemoModal = false" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
                    </div>
                    
                    <div class="space-y-2 flex-1">
                        <button @click="activeSlide = 0" :class="activeSlide === 0 ? 'bg-white shadow-sm border-blue-200 ring-1 ring-blue-500' : 'hover:bg-gray-100 border-transparent'" class="w-full text-left p-4 rounded-xl border transition-all flex items-start gap-3 group">
                            <div :class="activeSlide === 0 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500'" class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shrink-0 transition-colors">1</div>
                            <div>
                                <p :class="activeSlide === 0 ? 'text-blue-700' : 'text-gray-700'" class="font-semibold text-sm">Dashboard Realtime</p>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">Pantau stok, notifikasi, dan lab aktif dalam satu layar.</p>
                            </div>
                        </button>

                        <button @click="activeSlide = 1" :class="activeSlide === 1 ? 'bg-white shadow-sm border-blue-200 ring-1 ring-blue-500' : 'hover:bg-gray-100 border-transparent'" class="w-full text-left p-4 rounded-xl border transition-all flex items-start gap-3 group">
                            <div :class="activeSlide === 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500'" class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shrink-0 transition-colors">2</div>
                            <div>
                                <p :class="activeSlide === 1 ? 'text-blue-700' : 'text-gray-700'" class="font-semibold text-sm">Peminjaman Mudah</p>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">Cari barang, pilih tanggal, dan ajukan dalam 3 klik.</p>
                            </div>
                        </button>

                        <button @click="activeSlide = 2" :class="activeSlide === 2 ? 'bg-white shadow-sm border-blue-200 ring-1 ring-blue-500' : 'hover:bg-gray-100 border-transparent'" class="w-full text-left p-4 rounded-xl border transition-all flex items-start gap-3 group">
                            <div :class="activeSlide === 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500'" class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shrink-0 transition-colors">3</div>
                            <div>
                                <p :class="activeSlide === 2 ? 'text-blue-700' : 'text-gray-700'" class="font-semibold text-sm">Approval Digital</p>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">Validasi booking dari mana saja tanpa kertas.</p>
                            </div>
                        </button>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <button @click="isModalOpen = true; showDemoModal = false" class="w-full py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">Login Sekarang &rarr;</button>
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
                                <div class="w-1/4 bg-blue-200 rounded-t-lg h-0 animate-[grow_1s_ease-out_forwards]" style="--h: 60%"></div>
                                <div class="w-1/4 bg-blue-300 rounded-t-lg h-0 animate-[grow_1s_ease-out_0.2s_forwards]" style="--h: 80%"></div>
                                <div class="w-1/4 bg-blue-500 rounded-t-lg h-0 animate-[grow_1s_ease-out_0.4s_forwards]" style="--h: 40%"></div>
                                <div class="w-1/4 bg-blue-700 rounded-t-lg h-0 animate-[grow_1s_ease-out_0.6s_forwards]" style="--h: 90%"></div>
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
                            <button class="w-full py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold transform hover:scale-105 transition">Ajukan Peminjaman</button>
                            
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
                                <span class="badge px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded font-bold uppercase" id="status-badge">Pending</span>
                            </div>
                        </div>
                        <div class="flex justify-center mt-4">
                            <div class="bg-blue-900 text-white px-4 py-2 rounded-full text-sm shadow-lg flex items-center gap-2 animate-bounce">
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
                        submitButton.innerHTML = 'Loading...';
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
                            if (!response.ok) { showError(data.message || 'Error occurred.'); } 
                            else { window.location.href = '{{ route('dashboard') }}'; }
                        } catch (error) { showError('Network error.'); } 
                        finally { submitButton.innerHTML = originalButtonText; submitButton.disabled = false; }
                    });
                }

                const registerForm = document.getElementById('register-form');
                // Register form removed - registration is now admin-only
            });
        </script>
    </body>
</html>
