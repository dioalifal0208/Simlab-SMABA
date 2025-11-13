<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Saya perbarui title agar mengambil dari .env --}}
        <title>{{ config('app.name', 'LAB-SMABA') }}</title>
        <link rel="icon" href="{{ asset('images/logo-smaba.webp') }}" type="image/png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    <body class="antialiased font-sans overflow-x-hidden" 
          x-data="{ isModalOpen: false, activeTab: 'login' }"
          @keydown.escape.window="isModalOpen = false">

        <div class="bg-gray-100">
            <header class="absolute inset-x-0 top-0 z-50">
                <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
                    <div class="flex lg:flex-1"><a href="/" class="-m-1.5 p-1.5"><span class="sr-only">SMA NEGERI 1 BABAT</span><img class="h-12 w-auto" src="{{ asset('images/logo-smaba.webp') }}" alt="Logo Sekolah"></a></div>
                    @if (Route::has('login'))
                        <div class="text-right">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-semibold leading-6 text-gray-900">Dashboard</a>
                            @else
                                <button @click="isModalOpen = true; activeTab = 'login'" class="text-sm font-semibold leading-6 text-gray-900">Log in <span aria-hidden="true">&rarr;</span></button>
                                @if (Route::has('register'))
                                    <button @click="isModalOpen = true; activeTab = 'signup'" class="ml-4 text-sm font-semibold leading-6 text-gray-900">Register</button>
                                @endif
                            @endauth
                        </div>
                    @endif
                </nav>
            </header>
            
            <main class="relative isolate px-6 pt-14 lg:px-8 flex items-center min-h-screen">
                <div class="mx-auto max-w-7xl w-full">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-16 items-center">
                        <div class="lg:col-span-1 text-center lg:text-left">
                            <h1 class="text-4xl font-bold tracking-tight text-smaba-text sm:text-5xl lg:text-6xl" data-aos="fade-right">Sistem Informasi & Manajemen Laboratorium SMABA</h1>
                            <p class="mt-6 text-xl leading-8 text-gray-600" data-aos="fade-right" data-aos-delay="200">Manajemen inventaris, peminjaman alat, dan penjadwalan laboratorium menjadi lebih mudah dan terorganisir.</p>
                            <div class="mt-10 flex items-center justify-center lg:justify-start gap-x-6" data-aos="fade-right" data-aos-delay="400">
                                <button @click="isModalOpen = true; activeTab = 'login'" class="rounded-md bg-smaba-dark-blue px-5 py-3 text-base font-semibold text-white shadow-sm hover:bg-smaba-light-blue transition-colors duration-300 ease-in-out">Masuk ke Aplikasi</button>
                                <button @click="isModalOpen = true; activeTab = 'signup'" class="text-sm font-semibold leading-6 text-gray-900">Daftar Akun <span aria-hidden="true">→</span></button>
                            </div>
                        </div>
                        <div class="lg:col-span-1 mt-10 lg:mt-0" data-aos="fade-left" data-aos-delay="200">
                            <div class="relative h-80 md:h-96" x-data="carousel">
                                <div x-show="activeSlide === 1" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0" x-transition:leave="transition ease-in duration-1000" x-transition:leave-end="opacity-0" class="absolute inset-0"><img src="{{ asset('images/astro.svg') }}" alt="Ilustrasi Laboratorium 1" class="w-full h-full object-contain"></div>
                                <div x-show="activeSlide === 2" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0" x-transition:leave="transition ease-in duration-1000" x-transition:leave-end="opacity-0" class="absolute inset-0" style="display: none;"><img src="{{ asset('images/science.svg') }}" alt="Ilustrasi Laboratorium 2" class="w-full h-full object-contain"></div>
                                <div x-show="activeSlide === 3" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0" x-transition:leave="transition ease-in duration-1000" x-transition:leave-end="opacity-0" class="absolute inset-0" style="display: none;"><img src="{{ asset('images/fisika.svg') }}" alt="Ilustrasi Laboratorium 3" class="w-full h-full object-contain"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <div class="bg-white py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl lg:text-center" data-aos="fade-up">
                    <h2 class="text-base font-semibold leading-7 text-smaba-light-blue">Fitur Lengkap</h2>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-smaba-text sm:text-4xl">Semua yang Anda Butuhkan untuk Laboratorium Modern</p>
                    <p class="mt-6 text-lg leading-8 text-gray-600">Dari manajemen inventaris hingga modul praktikum interaktif, semua terintegrasi dalam satu platform yang mudah digunakan.</p>
                </div>
                <div class="mt-16 sm:mt-24 grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-16 items-center">
                    <div data-aos="fade-right"><h3 class="text-2xl font-bold tracking-tight text-smaba-text">Manajemen Lab Terpusat</h3><p class="mt-4 text-gray-600">Otomatiskan tugas-tugas administratif yang memakan waktu. Kelola inventaris, setujui peminjaman, dan atur jadwal booking lab dari satu dashboard yang intuitif.</p><dl class="mt-8 grid grid-cols-1 gap-x-6 gap-y-4"><div class="relative ps-9"><dt class="inline font-semibold text-gray-900"><i class="fas fa-boxes-stacked absolute left-0 top-1 text-smaba-dark-blue"></i> Inventaris Cerdas</dt><dd class="inline text-gray-600"> Lacak stok (termasuk bahan habis pakai) dan kondisi alat secara real-time.</dd></div><div class="relative ps-9"><dt class="inline font-semibold text-gray-900"><i class="fas fa-hand-holding-hand absolute left-0 top-1 text-smaba-dark-blue"></i> Alur Peminjaman</dt><dd class="inline text-gray-600"> Sistem approval yang jelas untuk peminjaman alat oleh siswa dan guru.</dd></div><div class="relative ps-9"><dt class="inline font-semibold text-gray-900"><i class="fas fa-calendar-alt absolute left-0 top-1 text-smaba-dark-blue"></i> Booking Anti-Bentrok</dt><dd class="inline text-gray-600"> Lihat ketersediaan lab di kalender interaktif dan cegah jadwal ganda.</dd></div></dl></div>
                    <img src="{{ asset('images/dashboard.svg') }}" alt="Ilustrasi Manajemen" class="w-full max-w-lg mx-auto h-80 object-contain" data-aos="fade-left">
                </div>
                <div class="mt-16 sm:mt-24 grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-16 items-center">
                    <img src="{{ asset('images/book.svg') }}" alt="Ilustrasi Modul" class="w-full max-w-lg mx-auto lg:order-first h-80 object-contain" data-aos="fade-right">
                    <div data-aos="fade-left"><h3 class="text-2xl font-bold tracking-tight text-smaba-text">Platform Pembelajaran Interaktif</h3><p class="mt-4 text-gray-600">Ubah laboratorium Anda dari sekadar ruang penyimpanan menjadi pusat pembelajaran. Integrasikan materi ajar langsung dengan alat yang tersedia.</p><dl class="mt-8 grid grid-cols-1 gap-x-6 gap-y-4"><div class="relative ps-9"><dt class="inline font-semibold text-gray-900"><i class="fas fa-book-open-reader absolute left-0 top-1 text-smaba-dark-blue"></i> Modul Praktikum</dt><dd class="inline text-gray-600"> Guru dapat membuat modul SOP digital dan menautkan langsung ke alat yang dibutuhkan.</dd></div><div class="relative ps-9"><dt class="inline font-semibold text-gray-900"><i class="fas fa-file-pdf absolute left-0 top-1 text-smaba-dark-blue"></i> Pustaka Digital</dt><dd class="inline text-gray-600"> Unggah dan bagikan e-book, jurnal, dan panduan praktikum dengan mudah.</dd></div><div class="relative ps-9"><dt class="inline font-semibold text-gray-900"><i class="fas fa-bolt absolute left-0 top-1 text-smaba-dark-blue"></i> Peminjaman 1-Klik</dt><dd class="inline text-gray-600"> Siswa bisa meminjam semua alat untuk sebuah modul praktikum hanya dengan satu tombol.</dd></div></dl></div>
                </div>
                <div class="mt-16 sm:mt-24 grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-16 items-center">
                    <div data-aos="fade-right"><h3 class="text-2xl font-bold tracking-tight text-smaba-text">Akuntabilitas & Komunikasi Real-time</h3><p class="mt-4 text-gray-600">Pastikan semua alat terawat dan setiap pengguna mendapatkan informasi terbaru secara instan.</p><dl class="mt-8 grid grid-cols-1 gap-x-6 gap-y-4"><div class="relative ps-9"><dt class="inline font-semibold text-gray-900"><i class="fas fa-triangle-exclamation absolute left-0 top-1 text-smaba-dark-blue"></i> Laporan Kerusakan</dt><dd class="inline text-gray-600"> Siswa dapat dengan mudah melaporkan alat yang rusak, lengkap dengan foto, langsung dari ponsel mereka.</dd></div><div class="relative ps-9"><dt class="inline font-semibold text-gray-900"><i class="fas fa-bell absolute left-0 top-1 text-smaba-dark-blue"></i> Sistem Notifikasi</dt><dd class="inline text-gray-600"> Dapatkan pemberitahuan instan untuk pengajuan baru, laporan kerusakan, dan peminjaman yang terlambat.</dd></div><div class="relative ps-9"><dt class="inline font-semibold text-gray-900"><i class="fas fa-chart-pie absolute left-0 top-1 text-smaba-dark-blue"></i> Laporan Analitik</dt><dd class="inline text-gray-600"> Pantau item yang paling sering dipinjam dan tren penggunaan lab melalui dashboard analitik.</dd></div></dl></div>
                    <img src="{{ asset('images/notification.svg') }}" alt="Ilustrasi Laporan" class="w-full max-w-lg mx-auto h-80 object-contain" data-aos="fade-left">
                </div>
            </div>
        </div>

        <div class="bg-gray-100">
            <div class="mx-auto max-w-7xl px-6 py-24 sm:py-32 lg:px-8">
                <div class="mx-auto max-w-2xl text-center" data-aos="fade-up">
                    <h2 class="text-3xl font-bold tracking-tight text-smaba-text sm:text-4xl">Sudah Siap Memulai?</h2>
                    <p class="mt-6 text-lg leading-8 text-gray-600">Masuk untuk mengajukan peminjaman, melihat jadwal lab, atau mengakses modul praktikum Anda.</p>
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        <button @click="isModalOpen = true; activeTab = 'login'" class="rounded-md bg-smaba-dark-blue px-5 py-3 text-base font-semibold text-white shadow-sm hover:bg-smaba-light-blue transition-colors duration-300 ease-in-out">
                            Masuk ke Aplikasi
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <footer class="bg-white border-t border-gray-200">
            <div class="mx-auto max-w-7xl px-6 py-8 lg:px-8">
                <div class="flex flex-col sm:flex-row justify-between items-center text-center">
                    <div class="flex items-center justify-center space-x-3">
                        <img class="h-8 w-auto" src="{{ asset('images/logo-smaba.webp') }}" alt="Logo Sekolah">
                        <span class="font-semibold text-gray-900">LAB-SMABA</span>
                    </div>
                    <p class="mt-4 sm:mt-0 text-sm leading-5 text-gray-500">&copy; {{ date('Y') }} SMA Negeri 1 Babat. Hak Cipta Dilindungi.</p>
                </div>
            </div>
        </footer>


        {{-- =============================================== --}}
        {{-- ##       MODAL LOGIN/REGISTER/FORGOT         ## --}}
        {{-- =============================================== --}}
        <div x-show="isModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" style="display: none;">
            <div @click.outside="isModalOpen = false" x-show="isModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg relative">
                <button @click="isModalOpen = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></button>
                
                {{-- Judul (berubah jika 'forgot') --}}
                <h2 class="text-2xl font-bold text-smaba-text text-center" x-show="activeTab !== 'forgot'">Selamat Datang</h2>
                <h2 class="text-2xl font-bold text-smaba-text text-center" x-show="activeTab === 'forgot'">Lupa Password</h2>
                
                {{-- Tab Switcher (disembunyikan jika 'forgot') --}}
                <div x-show="activeTab !== 'forgot'" class="mt-6 mb-6 flex rounded-lg bg-gray-100 p-1">
                    <button @click="activeTab = 'login'" :class="{ 'bg-white shadow-md text-smaba-dark-blue': activeTab === 'login', 'text-gray-500 hover:bg-gray-200': activeTab !== 'login' }" class="w-1/2 py-2 px-4 rounded-md text-sm font-semibold text-center transition-colors duration-300 focus:outline-none">Login</button>
                    <button @click="activeTab = 'signup'" :class="{ 'bg-white shadow-md text-smaba-dark-blue': activeTab === 'signup', 'text-gray-500 hover:bg-gray-200': activeTab !== 'signup' }" class="w-1/2 py-2 px-4 rounded-md text-sm font-semibold text-center transition-colors duration-300 focus:outline-none">Register</button>
                </div>
                
                {{-- Area Notifikasi Error --}}
                <div id="auth-error-message" class="hidden mb-4 bg-red-50 border-l-4 border-red-400 text-red-700 p-3 text-sm" role="alert"></div>
                {{-- Area Notifikasi Sukses (untuk Lupa Password) --}}
                <div id="auth-success-message" class="hidden mb-4 bg-green-50 border-l-4 border-green-400 text-green-700 p-3 text-sm" role="alert"></div>

                {{-- Form Login --}}
                <div x-show="activeTab === 'login'" x-transition>
                    <form id="login-form" method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <div><label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label><input id="email" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" type="email" name="email" required autofocus value="{{ old('email') }}" /></div>
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
                            <label for="remember_me" class="flex items-center"><input id="remember_me" type="checkbox" class="rounded border-gray-300 text-smaba-dark-blue shadow-sm focus:ring-smaba-light-blue" name="remember"><span class="ml-2 text-sm text-gray-600">Ingat saya</span></label>
                            
                            {{-- PERUBAHAN: Link Lupa Password diubah menjadi tombol --}}
                            <button type="button" @click.prevent="activeTab = 'forgot'" class="text-sm text-smaba-light-blue hover:text-smaba-dark-blue hover:underline focus:outline-none">Lupa password?</button>
                        </div>
                        <div class="pt-2"><button type="submit" class="w-full justify-center py-3 px-4 bg-smaba-dark-blue text-white font-semibold rounded-lg shadow-md hover:bg-smaba-light-blue transition-colors duration-300">Log In</button></div>
                    </form>
                </div>

                {{-- Form Register --}}
                <div x-show="activeTab === 'signup'" x-transition style="display: none;">
                    <form id="register-form" method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf
                        <div><label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label><input id="name" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" type="text" name="name" required value="{{ old('name') }}" /></div>
                        <div><label for="register_email" class="block text-sm font-medium text-gray-700">Alamat Email</label><input id="register_email" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" type="email" name="email" required value="{{ old('email') }}" /></div>
                        <div>
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
                        <div>
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
                        <div class="pt-2"><button type="submit" class="w-full justify-center py-3 px-4 bg-smaba-dark-blue text-white font-semibold rounded-lg shadow-md hover:bg-smaba-light-blue transition-colors duration-300">Register</button></div>
                    </form>
                </div>
                
                {{-- PENAMBAHAN: Form Lupa Password --}}
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

        {{-- SCRIPT UNTUK CAROUSEL ALPINE.JS & AJAX--}}
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('carousel', () => ({
                    activeSlide: 1,
                    totalSlides: 3,
                    init() {
                        setInterval(() => {
                            this.activeSlide = this.activeSlide < this.totalSlides ? this.activeSlide + 1 : 1;
                        }, 5000);
                    }
                }));

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const errorMessageDiv = document.getElementById('auth-error-message');
                const successMessageDiv = document.getElementById('auth-success-message'); // <-- Variabel baru

                function showError(message) {
                    successMessageDiv.classList.add('hidden'); // Sembunyikan sukses
                    errorMessageDiv.classList.remove('hidden');
                    errorMessageDiv.innerHTML = message;
                }
                
                function showSuccess(message) {
                    errorMessageDiv.classList.add('hidden'); // Sembunyikan error
                    successMessageDiv.classList.remove('hidden');
                    successMessageDiv.innerHTML = message;
                }

                // --- Menangani Form Login ---
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
                                method: 'POST', body: formData,
                                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken }
                            });
                            const data = await response.json();
                            if (!response.ok) {
                                let errorText = 'Terjadi kesalahan.';
                                if (response.status === 422 && data.errors) {
                                    errorText = Object.values(data.errors).map(e => `<li>${e[0]}</li>`).join('');
                                    errorText = `<ul>${errorText}</ul>`;
                                } else if (data.message) { errorText = data.message; }
                                showError(errorText);
                            } else { window.location.href = '{{ route('dashboard') }}'; }
                        } catch (error) { showError('Tidak dapat terhubung ke server.');
                        } finally { submitButton.innerHTML = originalButtonText; submitButton.disabled = false; }
                    });
                }

                // --- Menangani Form Register ---
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
                                method: 'POST', body: formData,
                                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken }
                            });
                            const data = await response.json();
                            if (!response.ok) {
                                let errorText = 'Terjadi kesalahan.';
                                if (response.status === 422 && data.errors) {
                                    errorText = Object.values(data.errors).map(e => `<li>${e[0]}</li>`).join('');
                                    errorText = `<ul>${errorText}</ul>`;
                                } else if (data.message) { errorText = data.message; }
                                showError(errorText);
                            } else { window.location.href = '{{ route('dashboard') }}'; }
                        } catch (error) { showError('Tidak dapat terhubung ke server.');
                        } finally { submitButton.innerHTML = originalButtonText; submitButton.disabled = false; }
                    });
                }

                // --- PENAMBAHAN: Menangani Form Lupa Password ---
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
                            const response = await fetch('{{ route('password.email') }}', { // Mengarah ke route password.email
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            });

                            const data = await response.json();

                            if (!response.ok) {
                                // Jika Gagal (misal: email tidak ditemukan)
                                let errorText = data.message || 'Gagal mengirim email.';
                                if(response.status === 422 && data.errors && data.errors.email) {
                                    errorText = data.errors.email[0];
                                }
                                showError(errorText);
                            } else {
                                // Jika BERHASIL
                                showSuccess(data.message); // Tampilkan pesan "Link telah dikirim"
                                this.reset(); // Kosongkan form email
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