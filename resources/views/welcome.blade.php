<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'LAB-SMABA') }}</title>
        <link rel="icon" href="{{ asset('images/logo-smaba.webp') }}" type="image/png">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="antialiased font-sans bg-gray-50 text-gray-900 overflow-x-hidden"
          data-authenticated="{{ auth()->check() ? '1' : '0' }}"
          x-data="{ isModalOpen: false, activeTab: 'login', showTestimonialModal: false }"
          @keydown.escape.window="isModalOpen = false; showTestimonialModal = false">
        {{-- HERO --}}
        <div class="relative min-h-screen bg-white">
            <div class="absolute inset-0 bg-white"></div>
            <div class="absolute -left-10 -top-6 w-64 h-64 rounded-full bg-smaba-light-blue/10 blur-3xl"></div>
            <div class="absolute right-10 top-20 w-72 h-72 rounded-full bg-smaba-dark-blue/5 blur-3xl"></div>

            <header class="relative z-10">
                <nav class="max-w-7xl mx-auto flex items-center justify-between px-6 lg:px-10 py-6 rounded-2xl bg-white/60 backdrop-blur shadow-sm border border-white/60" data-aos="fade-down">
                    <div class="flex items-center gap-3">
                        <a href="/" class="flex items-center gap-3">
                            <img class="h-12 w-auto" src="{{ asset('images/logo-smaba.webp') }}" alt="Logo SMABA">
                            <div class="hidden sm:block">
                                <p class="text-xs uppercase tracking-[0.2em] text-smaba-dark-blue font-semibold">LABORATORIUM SMABA</p>
                                <p class="text-sm font-semibold text-gray-800">Lab. Biologi - Lab. Fisika - Lab. Bahasa</p>
                            </div>
                        </a>
                    </div>
                    @if (Route::has('login'))
                        <div class="flex items-center gap-3">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-gray-800 hover:text-smaba-dark-blue">Dashboard</a>
                            @else
                                <button id="open-login-btn" @click="isModalOpen = true; activeTab = 'login'" class="px-4 py-2 text-sm font-semibold text-smaba-dark-blue bg-white border border-smaba-dark-blue rounded-lg hover:bg-smaba-dark-blue hover:text-white transition-colors">Masuk</button>
                                @if (Route::has('register'))
                                    <button @click="isModalOpen = true; activeTab = 'signup'" class="px-4 py-2 text-sm font-semibold text-white bg-smaba-dark-blue rounded-lg shadow hover:bg-smaba-light-blue transition-colors">Daftar</button>
                                @endif
                            @endauth
                        </div>
                    @endif
                </nav>
            </header>

            <main class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 pt-16 pb-20 lg:pt-24 lg:pb-24 grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                <div class="space-y-8" data-aos="fade-right">
                    <div class="space-y-4 max-w-3xl">
                        <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 leading-tight">
                            Sistem Informasi & Manajemen Laboratorium 
                        </h1>
                        <p class="text-lg text-gray-700 max-w-2xl">
                            Portal terpadu dan formal untuk peminjaman alat, booking ruang, inventaris, dan notifikasi petugas. Dirancang agar alur praktikum tertib dan terdokumentasi.
                        </p>
                        <ul class="space-y-2 text-sm text-gray-800">
                            <li class="flex items-start gap-2"><span class="mt-0.5 h-5 w-5 rounded-full bg-smaba-dark-blue text-white flex items-center justify-center text-xs"><i class="fas fa-check"></i></span> Approval transparan dan jejak aktivitas yang tercatat.</li>
                            <li class="flex items-start gap-2"><span class="mt-0.5 h-5 w-5 rounded-full bg-smaba-dark-blue text-white flex items-center justify-center text-xs"><i class="fas fa-check"></i></span> Kalender anti-bentrok untuk Biologi, Fisika, dan Bahasa.</li>
                            <li class="flex items-start gap-2"><span class="mt-0.5 h-5 w-5 rounded-full bg-smaba-dark-blue text-white flex items-center justify-center text-xs"><i class="fas fa-check"></i></span> Inventaris, laporan kerusakan, dan modul praktikum terhubung.</li>
                        </ul>
                    </div>
                    <div class="flex flex-wrap items-center gap-4" data-aos="fade-right" data-aos-delay="150">
                        <button @click="isModalOpen = true; activeTab = 'login'" class="px-6 py-3 bg-smaba-dark-blue text-white font-semibold rounded-xl shadow-md hover:bg-smaba-light-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-smaba-light-blue transition">Masuk ke Aplikasi</button>
                        <button @click="isModalOpen = true; activeTab = 'signup'" class="px-4 py-3 text-sm font-semibold text-smaba-dark-blue bg-white border border-smaba-dark-blue/70 rounded-xl hover:bg-smaba-dark-blue hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-smaba-light-blue transition">
                            Daftar Akun <span aria-hidden="true">&rarr;</span>
                        </button>
                    </div>
                </div>

                <div class="relative max-w-xl w-full mx-auto" data-aos="fade-left">
                    <div class="absolute -inset-6 bg-white/50 backdrop-blur rounded-3xl shadow-2xl border border-white"></div>
                    <div class="relative bg-white rounded-3xl shadow-xl p-6 md:p-8 space-y-5 transition-all duration-200 border border-gray-100 hover:-translate-y-1 hover:shadow-2xl hover:border-smaba-dark-blue/30">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-smaba-dark-blue uppercase tracking-wide">Informasi penting</p>
                                <h3 class="text-xl font-bold text-gray-900">Ringkasan Hari Ini</h3>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700">Aktif</span>
                        </div>

                        <div class="space-y-3 text-sm text-gray-700">
                            <div class="p-3 rounded-xl border border-gray-100 bg-gray-50 transition duration-200 hover:-translate-y-0.5 hover:border-smaba-dark-blue/30 hover:bg-smaba-light-blue/5">
                                <p class="font-semibold text-gray-900">Jam Operasional</p>
                                <p>Senin – Jumat, 07:30 – 14:20 WIB</p>
                                <p class="text-xs text-gray-500">Pengajuan setelah jam operasional akan diproses esok hari.</p>
                            </div>
                            <div class="p-3 rounded-xl border border-gray-100 bg-white transition duration-200 hover:-translate-y-0.5 hover:border-smaba-dark-blue/30 hover:bg-smaba-light-blue/5">
                                <p class="font-semibold text-gray-900">Lab yang tersedia hari ini</p>
                                @if(isset($todayBookings) && $todayBookings->isNotEmpty())
                                    <ul class="mt-1 space-y-1 list-disc list-inside">
                                        @foreach($todayBookings as $booking)
                                            <li>
                                                {{ $booking->laboratorium ?? 'Lab' }} &mdash; {{ $booking->tujuan_kegiatan }} ({{ $booking->waktu_mulai->format('H:i') }} - {{ $booking->waktu_selesai->format('H:i') }})
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-sm text-gray-600">Belum ada jadwal tersetujui hari ini.</p>
                                @endif
                            </div>
                            <div class="p-3 rounded-xl border border-gray-100 bg-gray-50 transition duration-200 hover:-translate-y-0.5 hover:border-smaba-dark-blue/30 hover:bg-smaba-light-blue/5">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-gray-900">Kontak petugas</p>
                                        <p class="text-sm text-gray-700">Gunakan widget chat setelah login untuk ngobrol langsung dengan petugas.</p>
                                        <p class="text-xs text-gray-500">Admin Lab: lab@smaba.sch.id &mdash; Balasan muncul di widget chat kanan bawah.</p>
                                        <p class="text-xs text-emerald-700 mt-1">Tip: klik “Masuk ke Aplikasi”, lalu buka widget chat di pojok kanan bawah.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        {{-- FEATURES --}}
        <section class="bg-gray-50 py-20">
            <div class="max-w-7xl mx-auto px-6 lg:px-10 space-y-12">
                <div class="max-w-3xl">
                    <p class="text-sm font-semibold text-smaba-light-blue uppercase tracking-wide">Fitur inti</p>
                    <h2 class="mt-2 text-3xl sm:text-4xl font-bold text-gray-900">Semua alur laboratorium dalam tampilan yang rapi</h2>
                    <p class="mt-4 text-lg text-gray-600">Monitoring stok, alur approval peminjaman, kalender anti-bentrok, sampai modul praktikum digital terhubung ke item yang tersedia.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="p-6 rounded-2xl bg-white shadow-sm border border-gray-100 transition-all duration-200 hover:-translate-y-1 hover:shadow-md hover:border-smaba-dark-blue/30 hover:bg-smaba-light-blue/5" data-aos="fade-up">
                        <div class="h-12 w-12 rounded-xl bg-sky-50 text-sky-700 flex items-center justify-center mb-4"><i class="fas fa-boxes-stacked"></i></div>
                        <h3 class="text-xl font-semibold text-gray-900">Inventaris cerdas</h3>
                        <p class="mt-2 text-sm text-gray-600">Stok, kondisi, lokasi simpan, dan threshold per item; notifikasi otomatis saat menipis.</p>
                    </div>
                    <div class="p-6 rounded-2xl bg-white shadow-sm border border-gray-100 transition-all duration-200 hover:-translate-y-1 hover:shadow-md hover:border-smaba-dark-blue/30 hover:bg-smaba-light-blue/5" data-aos="fade-up" data-aos-delay="50">
                        <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-4"><i class="fas fa-hand-holding-hand"></i></div>
                        <h3 class="text-xl font-semibold text-gray-900">Booking & approval</h3>
                        <p class="mt-2 text-sm text-gray-600">Approval berjenjang dengan log aktivitas; status selalu terpantau.</p>
                    </div>
                    <div class="p-6 rounded-2xl bg-white shadow-sm border border-gray-100 transition-all duration-200 hover:-translate-y-1 hover:shadow-md hover:border-smaba-dark-blue/30 hover:bg-smaba-light-blue/5" data-aos="fade-up" data-aos-delay="100">
                        <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center mb-4"><i class="fas fa-calendar-check"></i></div>
                        <h3 class="text-xl font-semibold text-gray-900">Kalender anti-bentrok</h3>
                        <p class="mt-2 text-sm text-gray-600">Jadwal Biologi, Fisika, dan Bahasa dalam satu tampilan; filter kelas dan pengampu.</p>
                    </div>
                    <div class="p-6 rounded-2xl bg-white shadow-sm border border-gray-100 transition-all duration-200 hover:-translate-y-1 hover:shadow-md hover:border-smaba-dark-blue/30 hover:bg-smaba-light-blue/5" data-aos="fade-up" data-aos-delay="150">
                        <div class="h-12 w-12 rounded-xl bg-slate-100 text-slate-800 flex items-center justify-center mb-4"><i class="fas fa-lock"></i></div>
                        <h3 class="text-xl font-semibold text-gray-900">Keamanan & audit</h3>
                        <p class="mt-2 text-sm text-gray-600">Role-based access, opsi 2FA, dan jejak audit untuk aksi penting.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- WORKFLOW --}}
        <section class="bg-white py-16" data-aos="fade-up">
            <div class="max-w-7xl mx-auto px-6 lg:px-10 space-y-10">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-smaba-light-blue uppercase tracking-wide">Alur ringkas</p>
                        <h2 class="text-3xl font-bold text-gray-900">Tiga langkah kerja lab yang konsisten</h2>
                        <p class="mt-3 text-gray-600">Ajukan, setujui, jalankan. Semua transparan dengan notifikasi di setiap tahap.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-6 rounded-2xl border border-gray-100 bg-gray-50 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md hover:border-smaba-dark-blue/30 hover:bg-smaba-light-blue/5">
                        <div class="flex items-center gap-3">
                            <span class="h-10 w-10 rounded-full bg-smaba-dark-blue text-white flex items-center justify-center font-bold">1</span>
                            <h3 class="text-lg font-semibold text-gray-900">Ajukan</h3>
                        </div>
                        <p class="mt-3 text-sm text-gray-600">Guru memilih alat atau booking slot. Modul praktikum bisa auto-memasukkan daftar alat.</p>
                    </div>
                    <div class="p-6 rounded-2xl border border-gray-100 bg-gray-50 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md hover:border-smaba-dark-blue/30 hover:bg-smaba-light-blue/5">
                        <div class="flex items-center gap-3">
                            <span class="h-10 w-10 rounded-full bg-smaba-dark-blue text-white flex items-center justify-center font-bold">2</span>
                            <h3 class="text-lg font-semibold text-gray-900">Setujui</h3>
                        </div>
                        <p class="mt-3 text-sm text-gray-600">Petugas lab memproses dalam satu panel: cek stok, tandai prioritas, dan kirim notifikasi otomatis.</p>
                    </div>
                    <div class="p-6 rounded-2xl border border-gray-100 bg-gray-50 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md hover:border-smaba-dark-blue/30 hover:bg-smaba-light-blue/5">
                        <div class="flex items-center gap-3">
                            <span class="h-10 w-10 rounded-full bg-smaba-dark-blue text-white flex items-center justify-center font-bold">3</span>
                            <h3 class="text-lg font-semibold text-gray-900">Kelola</h3>
                        </div>
                        <p class="mt-3 text-sm text-gray-600">Pantau pengembalian, status kerusakan, dan laporan analitik untuk perencanaan pengadaan.</p>
                    </div>
                </div>
                <div class="flex justify-center pt-4">
                    <button @click="isModalOpen = true; activeTab = 'login'" class="px-6 py-3 bg-smaba-dark-blue text-white font-semibold rounded-xl shadow hover:bg-smaba-light-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-smaba-light-blue transition">
                        Coba sekarang
                    </button>
                </div>
            </div>
        </section>

        {{-- TESTIMONIALS --}}
        <section class="bg-gray-50 py-16" data-aos="fade-up">
            <div class="max-w-7xl mx-auto px-6 lg:px-10 space-y-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-smaba-light-blue uppercase tracking-wide">Apa kata pengguna</p>
                        <h2 class="text-3xl font-bold text-gray-900 mt-2">Dipakai guru untuk praktikum harian</h2>
                    </div>
                    <div class="flex items-center gap-3">
                        @if (session('testimonial_submitted'))
                            <span class="text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-2 rounded-lg">{{ session('testimonial_submitted') }}</span>
                        @endif
                        <button @click="showTestimonialModal = true" class="px-4 py-2 text-sm font-semibold text-white bg-smaba-dark-blue rounded-lg shadow-sm hover:bg-smaba-light-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-smaba-light-blue transition">
                            Kirim testimoni
                        </button>
                    </div>
                </div>

                @if ($errors->testimonial?->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm p-3 rounded-lg">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->testimonial->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($testimonials as $testimonial)
                        <div class="p-6 rounded-2xl bg-white shadow-sm border border-gray-100">
                            <div class="flex items-center gap-3">
                                @php $initial = strtoupper(mb_substr($testimonial->nama, 0, 1)); @endphp
                                <div class="h-12 w-12 rounded-full bg-smaba-dark-blue text-white flex items-center justify-center font-bold">{{ $initial }}</div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $testimonial->nama }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $testimonial->peran ?? 'Pengguna' }}
                                        @if($testimonial->laboratorium) • {{ $testimonial->laboratorium }} @endif
                                    </p>
                                </div>
                            </div>
                            <p class="mt-4 text-sm text-gray-700">“{{ $testimonial->pesan }}”</p>
                        </div>
                    @empty
                        <div class="p-6 rounded-2xl bg-white shadow-sm border border-gray-100 md:col-span-2">
                            <p class="text-center text-gray-600 text-sm">Belum ada testimoni. Jadilah yang pertama berbagi pengalaman.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
        <footer class="bg-gray-900 text-gray-200 py-10">
            <div class="max-w-7xl mx-auto px-6 lg:px-10 flex flex-col sm:flex-row justify-between gap-4 items-center">
                <div class="flex items-center gap-3">
                    <img class="h-10 w-auto" src="{{ asset('images/logo-smaba.webp') }}" alt="Logo Sekolah">
                    <div>
                        <p class="font-semibold">LAB-SMABA</p>
                        <p class="text-xs text-gray-400">Sistem Informasi Laboratorium SMA Negeri 1 Babat</p>
                    </div>
                </div>
                <p class="text-xs text-gray-400">&copy; {{ date('Y') }} SMA Negeri 1 Babat. Hak Cipta Dilindungi.</p>
            </div>
        </footer>

        {{-- MODAL LOGIN/REGISTER/FORGOT --}}
        <div x-show="isModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" style="display: none;">
            <div @click.outside="isModalOpen = false" x-show="isModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" class="w-full max-w-md bg-white p-8 rounded-xl shadow-2xl relative">
                <button @click="isModalOpen = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <h2 class="text-2xl font-bold text-smaba-text text-center" x-show="activeTab !== 'forgot'">Selamat Datang</h2>
                <h2 class="text-2xl font-bold text-smaba-text text-center" x-show="activeTab === 'forgot'">Lupa Password</h2>

                <div x-show="activeTab !== 'forgot'" class="mt-6 mb-6 flex rounded-lg bg-gray-100 p-1">
                    <button @click="activeTab = 'login'" :class="{ 'bg-white shadow-md text-smaba-dark-blue': activeTab === 'login', 'text-gray-500 hover:bg-gray-200': activeTab !== 'login' }" class="w-1/2 py-2 px-4 rounded-md text-sm font-semibold text-center transition-colors duration-300 focus:outline-none">Login</button>
                    <button @click="activeTab = 'signup'" :class="{ 'bg-white shadow-md text-smaba-dark-blue': activeTab === 'signup', 'text-gray-500 hover:bg-gray-200': activeTab !== 'signup' }" class="w-1/2 py-2 px-4 rounded-md text-sm font-semibold text-center transition-colors duration-300 focus:outline-none">Register</button>
                </div>

                <div id="auth-error-message" class="hidden mb-4 bg-red-50 border-l-4 border-red-400 text-red-700 p-3 text-sm" role="alert"></div>
                <div id="auth-success-message" class="hidden mb-4 bg-green-50 border-l-4 border-green-400 text-green-700 p-3 text-sm" role="alert"></div>

                {{-- Form Login --}}
                <div x-show="activeTab === 'login'" x-transition>
                    <form id="login-form" method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                            <input id="email" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" type="email" name="email" required autofocus value="{{ old('email') }}" />
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <div x-data="{ showPassword: false }" class="relative">
                                <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required class="block mt-1 w-full pr-10 rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" />
                                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">
                                    <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="flex items-center">
                                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-smaba-dark-blue shadow-sm focus:ring-smaba-light-blue" name="remember">
                                <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                            </label>
                            <button type="button" @click.prevent="activeTab = 'forgot'" class="text-sm text-smaba-light-blue hover:text-smaba-dark-blue hover:underline focus:outline-none">Lupa password?</button>
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="w-full justify-center py-3 px-4 bg-smaba-dark-blue text-white font-semibold rounded-lg shadow-md hover:bg-smaba-light-blue transition-colors duration-300">Log In</button>
                        </div>
                    </form>
                </div>

                {{-- Form Register --}}
                <div x-show="activeTab === 'signup'" x-transition style="display: none;">
                    <form id="register-form" method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input id="name" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" type="text" name="name" required value="{{ old('name') }}" />
                        </div>
                        <div>
                            <label for="register_email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                            <input id="register_email" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" type="email" name="email" required value="{{ old('email') }}" />
                        </div>
                        <div>
                            <label for="register_password" class="block text-sm font-medium text-gray-700">Password</label>
                            <div x-data="{ showPassword: false }" class="relative">
                                <input id="register_password" :type="showPassword ? 'text' : 'password'" name="password" required class="block mt-1 w-full pr-10 rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" />
                                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">
                                    <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                            <div x-data="{ showPassword: false }" class="relative">
                                <input id="password_confirmation" :type="showPassword ? 'text' : 'password'" name="password_confirmation" required class="block mt-1 w-full pr-10 rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" />
                                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">
                                    <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="w-full justify-center py-3 px-4 bg-smaba-dark-blue text-white font-semibold rounded-lg shadow-md hover:bg-smaba-light-blue transition-colors duration-300">Register</button>
                        </div>
                    </form>
                </div>

                {{-- Form Lupa Password --}}
                <div x-show="activeTab === 'forgot'" x-transition style="display: none;">
                    <p class="text-sm text-center text-gray-600 mb-4">Lupa password? Masukkan email Anda dan kami akan mengirimkan link untuk mengatur ulang password.</p>
                    <form id="forgot-password-form" method="POST" action="{{ route('password.email') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="forgot_email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                            <input id="forgot_email" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" type="email" name="email" required autofocus />
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="w-full justify-center py-3 px-4 bg-smaba-dark-blue text-white font-semibold rounded-lg shadow-md hover:bg-smaba-light-blue transition-colors duration-300">
                                Kirim Link Reset Password
                            </button>
                        </div>
                        <div class="text-center pt-2">
                            <button type="button" @click.prevent="activeTab = 'login'" class="text-sm text-gray-600 hover:text-smaba-dark-blue hover:underline focus:outline-none">
                                &larr; Kembali ke Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL TESTIMONI --}}
        <div x-show="showTestimonialModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" style="display: none;">
            <div @click.outside="showTestimonialModal = false" x-show="showTestimonialModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="w-full max-w-md bg-white p-6 rounded-xl shadow-2xl relative">
                <button @click="showTestimonialModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Kirim Testimoni</h3>
                <form action="{{ route('testimonials.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input id="nama" name="nama" type="text" required maxlength="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="peran" class="block text-sm font-medium text-gray-700">Peran</label>
                            <select id="peran" name="peran" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                <option value="">Pilih peran</option>
                                <option value="Guru">Guru</option>
                                <option value="Staf">Staf</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label for="laboratorium" class="block text-sm font-medium text-gray-700">Laboratorium</label>
                            <select id="laboratorium" name="laboratorium" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                                <option value="">Tidak spesifik</option>
                                <option value="Biologi">Biologi</option>
                                <option value="Fisika">Fisika</option>
                                <option value="Bahasa">Bahasa</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="pesan" class="block text-sm font-medium text-gray-700">Pesan</label>
                        <textarea id="pesan" name="pesan" rows="3" required maxlength="500" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Maksimal 500 karakter. Testimoni akan ditinjau admin sebelum ditampilkan.</p>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="showTestimonialModal = false" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</button>
                        <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-smaba-dark-blue rounded-lg shadow hover:bg-smaba-light-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-smaba-light-blue">Kirim</button>
                    </div>
                </form>
            </div>
        </div>

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
                                method: 'POST',
                                body: formData,
                                credentials: 'include',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            });

                            if (response.redirected) {
                                window.location.href = response.url;
                                return;
                            }

                            const data = await response.json();
                            if (!response.ok) {
                                let errorText = 'Terjadi kesalahan.';
                                if (response.status === 422 && data.errors) {
                                    errorText = Object.values(data.errors).map(e => `<li>${e[0]}</li>`).join('');
                                    errorText = `<ul>${errorText}</ul>`;
                                } else if (data.message) {
                                    errorText = data.message;
                                }
                                showError(errorText);
                            } else {
                                window.location.href = '{{ route('dashboard') }}';
                            }
                        } catch (error) {
                            showError('Tidak dapat terhubung ke server.');
                        } finally {
                            submitButton.innerHTML = originalButtonText;
                            submitButton.disabled = false;
                        }
                    });
                }

                const registerForm = document.getElementById('register-form');
                if (registerForm) {
                    registerForm.addEventListener('submit', async function (event) {
                        event.preventDefault();
                        const submitButton = this.querySelector('button[type="submit"]');
                        const originalButtonText = submitButton.innerHTML;
                        submitButton.innerHTML = 'Memproses...';
                        submitButton.disabled = true;
                        errorMessageDiv.classList.add('hidden');
                        successMessageDiv.classList.add('hidden');
                        const formData = new FormData(this);
                        try {
                            const response = await fetch('{{ route('register') }}', {
                                method: 'POST',
                                body: formData,
                                credentials: 'include',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            });

                            if (response.redirected) {
                                window.location.href = response.url;
                                return;
                            }

                            const data = await response.json();
                            if (!response.ok) {
                                let errorText = 'Terjadi kesalahan.';
                                if (response.status === 422 && data.errors) {
                                    errorText = Object.values(data.errors).map(e => `<li>${e[0]}</li>`).join('');
                                    errorText = `<ul>${errorText}</ul>`;
                                } else if (data.message) {
                                    errorText = data.message;
                                }
                                showError(errorText);
                            } else {
                                window.location.href = '{{ route('dashboard') }}';
                            }
                        } catch (error) {
                            showError('Tidak dapat terhubung ke server.');
                        } finally {
                            submitButton.innerHTML = originalButtonText;
                            submitButton.disabled = false;
                        }
                    });
                }

                const forgotForm = document.getElementById('forgot-password-form');
                if (forgotForm) {
                    forgotForm.addEventListener('submit', async function (event) {
                        event.preventDefault();
                        const submitButton = this.querySelector('button[type="submit"]');
                        const originalButtonText = submitButton.innerHTML;
                        submitButton.innerHTML = 'Memproses...';
                        submitButton.disabled = true;
                        errorMessageDiv.classList.add('hidden');
                        successMessageDiv.classList.add('hidden');

                        const formData = new FormData(this);
                        try {
                            const response = await fetch('{{ route('password.email') }}', {
                                method: 'POST',
                                body: formData,
                                credentials: 'include',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            });

                            const data = await response.json();

                            if (!response.ok) {
                                let errorText = data.message || 'Gagal mengirim email.';
                                if (response.status === 422 && data.errors && data.errors.email) {
                                    errorText = data.errors.email[0];
                                }
                                showError(errorText);
                            } else {
                                showSuccess(data.message);
                                this.reset();
                            }
                        } catch (error) {
                            showError('Tidak dapat terhubung ke server.');
                        } finally {
                            submitButton.innerHTML = originalButtonText;
                            submitButton.disabled = false;
                        }
                    });
                }
            });
        </script>

    </body>
</html>
