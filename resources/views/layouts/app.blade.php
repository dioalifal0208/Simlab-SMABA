<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="icon" href="{{ asset('images/logo-smaba.webp') }}" type="image/png">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- AOS CSS + JS via CDN --}}
        <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Init AOS
                AOS.init({
                    once: true,
                    duration: 600,
                    offset: 50,
                    easing: 'ease-out-cubic',
                    disable: false
                });

                // PENTING: Karena #main-wrapper sekarang position:fixed + overflow-y:auto,
                // scroll terjadi di DALAM #main-wrapper, bukan di window.
                // AOS secara default mendengarkan scroll di window — jadi kita harus
                // manual trigger AOS.refresh() saat #main-wrapper di-scroll.
                var mainWrapper = document.getElementById('main-wrapper');
                if (mainWrapper) {
                    // Debounce agar tidak terlalu sering refresh
                    var scrollTimeout;
                    mainWrapper.addEventListener('scroll', function () {
                        clearTimeout(scrollTimeout);
                        scrollTimeout = setTimeout(function () {
                            AOS.refresh();
                        }, 50);
                    });
                    // Initial refresh untuk elemen yang sudah di viewport
                    AOS.refresh();
                }
            });
        </script>
        
        {{-- TAMBAHAN 1: Memuat style yang dibutuhkan oleh Livewire --}}
        @livewireStyles

        {{-- Layout: Main content area sebagai fixed scrollable container --}}
        <style>
            /*
             * LAYOUT SYSTEM (STABILIZED):
             * #main-wrapper is position:fixed and ALWAYS spans full viewport width
             * (left:0; right:0). Sidebar offset is handled via padding-left,
             * NOT left positioning. This prevents container resize/reflow during
             * sidebar toggle, eliminating the visual "pulling" effect.
             *
             * The sidebar sits on top (z-40) and the padding simply pushes
             * content to the right of it.
             */
            #main-wrapper {
                position: fixed;
                top: 56px;      /* below topbar (h-14 = 56px) */
                left: 0;
                right: 0;
                bottom: 0;
                overflow-y: auto;
                background-color: #f8fafc; /* bg-slate-50 */
                transition: padding-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                will-change: padding-left;
            }
            @media (min-width: 1024px) {
                #main-wrapper {
                    padding-left: 240px; /* sidebar expanded width */
                }
                body.sidebar-collapsed #main-wrapper {
                    padding-left: 72px;  /* sidebar collapsed width */
                }
            }

            /* ============================================
             * GLOBAL RESPONSIVE LAYOUT SYSTEM
             * Normalizes padding & max-width across all pages.
             * Applied via <main> to avoid touching individual templates.
             * ============================================ */

            /* --- Responsive Container Normalization --- */
            /* Ensure all max-w-7xl containers inherit consistent padding */
            #main-wrapper .max-w-7xl,
            #main-wrapper .max-w-\[1400px\] {
                padding-left: max(1rem, env(safe-area-inset-left));
                padding-right: max(1rem, env(safe-area-inset-right));
            }
            @media (min-width: 640px) {
                #main-wrapper .max-w-7xl,
                #main-wrapper .max-w-\[1400px\] {
                    padding-left: 1.5rem;
                    padding-right: 1.5rem;
                }
            }
            @media (min-width: 1024px) {
                #main-wrapper .max-w-7xl,
                #main-wrapper .max-w-\[1400px\] {
                    padding-left: 2rem;
                    padding-right: 2rem;
                }
            }

            /* --- Table Responsive: horizontal scroll on tablet --- */
            @media (max-width: 1023px) {
                #main-wrapper table {
                    display: block;
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                }
                #main-wrapper thead,
                #main-wrapper tbody,
                #main-wrapper tr {
                    display: table;
                    width: 100%;
                    table-layout: fixed;
                }
                #main-wrapper table {
                    min-width: 640px;
                }
            }

            /* --- Mobile card-style table rows (< 640px) --- */
            @media (max-width: 639px) {
                #main-wrapper table.responsive-cards thead {
                    display: none;
                }
                #main-wrapper table.responsive-cards tbody tr {
                    display: flex;
                    flex-direction: column;
                    padding: 1rem;
                    margin-bottom: 0.75rem;
                    border: 1px solid #e2e8f0;
                    border-radius: 0.75rem;
                    background: #fff;
                    box-shadow: 0 1px 3px 0 rgba(0,0,0,.05);
                }
                #main-wrapper table.responsive-cards tbody td {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 0.375rem 0;
                    border: none;
                }
                #main-wrapper table.responsive-cards tbody td::before {
                    content: attr(data-label);
                    font-weight: 600;
                    font-size: 0.75rem;
                    color: #64748b;
                    flex-shrink: 0;
                    margin-right: 1rem;
                }
            }

            /* --- Smooth layout transitions for grid columns --- */
            @media (min-width: 768px) and (max-width: 1023px) {
                /* Force 2-col max on tablet for grids that use 3+ cols */
                #main-wrapper .lg\:grid-cols-3 {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
                #main-wrapper .lg\:grid-cols-4 {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
                #main-wrapper .lg\:grid-cols-12 {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
                /* 2-column detail pages: stack on tablet */
                #main-wrapper .lg\:col-span-8,
                #main-wrapper .lg\:col-span-4,
                #main-wrapper .lg\:col-span-2 {
                    grid-column: span 2 / span 2;
                }
            }

            /* --- Spacing normalization --- */
            @media (max-width: 639px) {
                /* Reduce vertical padding on mobile for tighter feel */
                #main-wrapper .py-12 {
                    padding-top: 1.5rem;
                    padding-bottom: 1.5rem;
                }
                #main-wrapper .py-8 {
                    padding-top: 1rem;
                    padding-bottom: 1rem;
                }
                #main-wrapper .gap-8 {
                    gap: 1rem;
                }
                /* Page header tighter on mobile */
                #main-wrapper .px-6 {
                    padding-left: 1rem;
                    padding-right: 1rem;
                }
            }

            /* ============================================
             * SAAS PAGE CONTAINER
             * Self-contained centered content area.
             * Prevents full-width stretching on wide screens.
             * Includes its own responsive padding.
             * ============================================ */
            .items-page-container,
            .saas-page-container {
                width: 100%;
                max-width: 1280px;
                margin-left: auto;
                margin-right: auto;
                padding-left: 1rem;
                padding-right: 1rem;
            }
            @media (min-width: 640px) {
                .items-page-container,
                .saas-page-container {
                    padding-left: 1.5rem;
                    padding-right: 1.5rem;
                }
            }
            @media (min-width: 1024px) {
                .items-page-container,
                .saas-page-container {
                    padding-left: 2rem;
                    padding-right: 2rem;
                }
            }

            /* ============================================
             * LAYOUT STABILITY HELPERS
             * ============================================ */

            /* Prevent layout shift on initial load */
            #main-wrapper > main {
                min-height: calc(100vh - 56px);
            }

            /* Tour target stability — elements used as tour
               anchors must not depend on animated positioning */
            [data-tour],
            [id^="tour-"] {
                position: relative;
                z-index: 1;
            }
        </style>

        {{-- PERBAIKAN 1: Menambahkan script Alpine.js di <head> --}}
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    
    {{-- PERBAIKAN 2: Menambahkan 'x-data' global di <body> --}}
    <body class="font-sans antialiased text-gray-900" 
          data-user-role="{{ Auth::user()->role ?? 'guest' }}"
          data-user-id="{{ Auth::id() ?? '' }}"
          x-data="{ 
              showAnnouncement: true, 
              showImportModal: false,
              isModalOpen: false,
              sidebarOpen: false,
              sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
              isSidebarMounted: false,
              toast: { visible: false, message: '', type: 'success' }
          }"
          x-init="
            $watch('showImportModal', value => isModalOpen = value);
            setTimeout(() => isSidebarMounted = true, 50);
          "
          @modal-state-changed.window="isModalOpen = $event.detail.open"
          @show-toast.window="toast.visible = true; toast.message = $event.detail.message; toast.type = $event.detail.type; setTimeout(() => toast.visible = false, 3000)">
          
        <div class="min-h-screen bg-slate-50">
            @unless($hideChrome)
                @include('layouts.navigation')
            @endunless

            {{-- MAIN WRAPPER: position:fixed, padding-left mengikuti sidebar width --}}
            <div id="main-wrapper" class="flex flex-col"
                 :class="{ 'transition-all duration-300': isSidebarMounted }"
                 :style="window.innerWidth >= 1024 ? 'padding-left:' + (sidebarCollapsed ? '72px' : '240px') : ''">

                {{-- BANNER PENGUMUMAN GLOBAL --}}
                @if(isset($activeAnnouncement))
                    <div class="bg-white border-b border-gray-100 text-gray-800" 
                        x-show="showAnnouncement" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-y-full"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-full">
                        <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
                            <div class="flex items-center justify-between flex-wrap">
                                <div class="w-0 flex-1 flex items-center">
                                    <span class="flex-shrink-0 flex p-2 rounded-lg bg-green-50">
                                        <i class="fas fa-bullhorn text-green-600"></i>
                                    </span>
                                    <div class="marquee-container ms-3">
                                        <p class="marquee-text font-medium text-sm">{{ $activeAnnouncement->message }}</p>
                                    </div>
                                </div>
                                <div class="order-2 flex-shrink-0 sm:order-3 sm:ms-3">
                                    <button @click="showAnnouncement = false" type="button" class="-mr-1 flex p-2 rounded-md hover:bg-gray-100 focus:outline-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (isset($header))
                    {{-- Slim page header — cocok untuk sidebar layout --}}
                    <div class="bg-white border-b border-gray-100 px-6 py-3">
                        {{ $header }}
                    </div>
                @endif

                <main class="flex-grow shrink-0">
                    {{ $slot }}
                </main>

                @unless($hideChrome ?? false || $hideFooter ?? false)
                    <footer class="bg-white border-t border-gray-200 mt-auto">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                            <p class="text-center text-xs text-gray-400">&copy; {{ date('Y') }} SMA Negeri 1 Babat — Sistem Manajemen Laboratorium.</p>
                        </div>
                    </footer>
                @endunless

            </div>{{-- /main-wrapper --}}

        {{-- Toast Notification Component --}}
        <div x-show="toast.visible"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2"
             class="fixed top-4 right-4 z-[60] max-w-sm w-full"
             style="display: none;">
            <div :class="{
                'bg-green-50 border-green-200 text-green-800': toast.type === 'success',
                'bg-red-50 border-red-200 text-red-800': toast.type === 'error',
                'bg-yellow-50 border-yellow-200 text-yellow-800': toast.type === 'warning',
                'bg-green-50 border-green-200 text-green-800': toast.type === 'info'
            }" class="flex items-center gap-3 p-4 rounded-lg border-2 shadow-lg">
                <div class="flex-shrink-0">
                    <template x-if="toast.type === 'success'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </template>
                    <template x-if="toast.type === 'warning'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </template>
                    <template x-if="toast.type === 'info'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </template>
                </div>
                <p class="flex-1 text-sm font-medium" x-text="toast.message"></p>
                <button @click="toast.visible = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { message: '{{ session('success') }}', type: 'success' }
                    }));
                });
            </script>
        @endif
        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { message: '{{ session('error') }}', type: 'error' }
                    }));
                });
            </script>
        @endif

        {{-- Floating chat widget --}}
        @unless($hideChrome)
        @auth
            @include('components.chat-widget')
        @endauth
        @endunless

        {{-- ============================================ --}}
        {{-- Notification Email Setup Modal (Popup) --}}
        {{-- ============================================ --}}
        @auth
        @if(!auth()->user()->notification_email && !session('notification_email_skipped'))
        <div x-data="notificationEmailSetup" 
             x-show="isOpen" 
             x-cloak
             class="fixed inset-0 z-[9998] overflow-y-auto" 
             aria-labelledby="notif-email-title" 
             role="dialog" 
             aria-modal="true">
            
            {{-- Blur Backdrop --}}
            <div x-show="isOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="isOpen" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">

                    {{-- Top Accent Bar (sama dengan login page) --}}
                    <div class="h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>

                    {{-- Step 1: Input Email --}}
                    <div x-show="step === 'input'" class="px-6 pb-6 pt-6 sm:px-8 sm:pt-8 sm:pb-8">
                        
                        {{-- Header --}}
                        <div class="text-center mb-6">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 border border-blue-100 shadow-sm mb-4">
                                <i class="fas fa-envelope text-2xl text-blue-600"></i>
                            </div>
                            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight" id="notif-email-title">Email Notifikasi</h3>
                            <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                                Masukkan alamat email untuk menerima notifikasi peminjaman, booking, dan pengingat.
                            </p>
                        </div>

                        {{-- Error --}}
                        <div x-show="error" x-cloak class="mb-4 bg-red-50 border border-red-200 rounded-xl p-3 flex items-start gap-2.5">
                            <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center text-red-500 flex-shrink-0 border border-red-200 shadow-sm mt-0.5">
                                <i class="fas fa-exclamation-triangle text-[10px]"></i>
                            </div>
                            <p class="text-sm text-red-600 font-medium" x-text="error"></p>
                        </div>

                        {{-- Success --}}
                        <div x-show="success" x-cloak class="mb-4 bg-green-50 border border-green-200 rounded-xl p-3 flex items-start gap-2.5">
                            <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center text-green-600 flex-shrink-0 border border-green-200 shadow-sm mt-0.5">
                                <i class="fas fa-check text-[10px]"></i>
                            </div>
                            <p class="text-sm text-green-700 font-medium" x-text="success"></p>
                        </div>

                        {{-- Info panel --}}
                        <div class="mb-5 bg-slate-50 border border-slate-200 rounded-xl p-4">
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">
                                <i class="fas fa-bell text-slate-400 mr-1"></i> Anda akan menerima:
                            </p>
                            <ul class="space-y-1 text-[13px] text-slate-600">
                                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-[10px]"></i> Peminjaman disetujui / ditolak</li>
                                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-[10px]"></i> Pengingat pengembalian H-1</li>
                                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 text-[10px]"></i> Notifikasi booking lab</li>
                            </ul>
                        </div>

                        {{-- Email Input --}}
                        <div class="mb-5">
                            <label for="notif_email_input" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">
                                <i class="fas fa-at text-slate-400 mr-1"></i> Alamat Email Notifikasi
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                                </div>
                                <input id="notif_email_input"
                                       type="email"
                                       x-model="email"
                                       @keydown.enter="submit"
                                       required
                                       autofocus
                                       placeholder="email.anda@gmail.com"
                                       class="block w-full pl-11 pr-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium text-slate-800 placeholder-slate-400 shadow-sm hover:border-slate-300 hover:bg-white focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200" />
                            </div>
                            <p class="text-xs text-slate-400 mt-2">
                                <i class="fas fa-info-circle mr-1"></i> Berbeda dari email login ({{ auth()->user()->email }})
                            </p>
                        </div>

                        {{-- Submit --}}
                        <button @click="submit" 
                                :disabled="isLoading"
                                class="w-full relative flex justify-center items-center py-3.5 px-4 rounded-xl font-bold text-sm bg-blue-600 text-white shadow-lg shadow-blue-600/25 hover:bg-blue-700 hover:shadow-blue-700/30 hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-blue-500/30 active:scale-[0.98] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 group">
                            <span x-show="isLoading" class="mr-2"><i class="fas fa-spinner animate-spin"></i></span>
                            <i x-show="!isLoading" class="fas fa-paper-plane mr-2 text-white/80 group-hover:text-white transition-colors"></i>
                            <span x-text="isLoading ? 'Mengirim...' : 'Simpan & Kirim Verifikasi'"></span>
                        </button>

                        {{-- Skip --}}
                        <div class="mt-5 text-center">
                            <button @click="skip" 
                                    class="text-sm text-slate-400 hover:text-slate-600 font-semibold transition-colors inline-flex items-center gap-1.5">
                                <i class="fas fa-forward text-xs"></i> Lewati untuk saat ini
                            </button>
                        </div>

                        {{-- Footer --}}
                        <div class="mt-5 text-center">
                            <p class="text-[11px] text-slate-300">
                                <i class="fas fa-shield-halved mr-1"></i> Email Anda dilindungi dan tidak akan dibagikan
                            </p>
                        </div>
                    </div>

                    {{-- Step 2: Verify Pending --}}
                    <div x-show="step === 'pending'" x-cloak class="px-6 pb-6 pt-6 sm:px-8 sm:pt-8 sm:pb-8">
                        
                        {{-- Header --}}
                        <div class="text-center mb-6">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-50 border border-amber-100 shadow-sm mb-4">
                                <i class="fas fa-envelope-open-text text-2xl text-amber-600"></i>
                            </div>
                            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Cek Email Anda</h3>
                            <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                                Email verifikasi telah dikirim ke alamat di bawah.
                            </p>
                        </div>

                        {{-- Success --}}
                        <div x-show="success" x-cloak class="mb-4 bg-green-50 border border-green-200 rounded-xl p-3 flex items-start gap-2.5">
                            <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center text-green-600 flex-shrink-0 border border-green-200 shadow-sm mt-0.5">
                                <i class="fas fa-check text-[10px]"></i>
                            </div>
                            <p class="text-sm text-green-700 font-medium" x-text="success"></p>
                        </div>

                        {{-- Error --}}
                        <div x-show="error" x-cloak class="mb-4 bg-red-50 border border-red-200 rounded-xl p-3 flex items-start gap-2.5">
                            <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center text-red-500 flex-shrink-0 border border-red-200 shadow-sm mt-0.5">
                                <i class="fas fa-exclamation-triangle text-[10px]"></i>
                            </div>
                            <p class="text-sm text-red-600 font-medium" x-text="error"></p>
                        </div>

                        {{-- Email Display --}}
                        <div class="mb-5 bg-blue-50 border border-blue-200 rounded-xl p-5 text-center">
                            <p class="text-[10px] font-bold text-blue-400 uppercase tracking-wider mb-1">Email Notifikasi</p>
                            <p class="text-base font-bold text-blue-800" x-text="email"></p>
                        </div>

                        {{-- Steps --}}
                        <div class="mb-5 space-y-2.5">
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                                    <span class="text-[10px] font-bold">1</span>
                                </div>
                                <p class="text-[13px] text-slate-600">Buka inbox email Anda</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                                    <span class="text-[10px] font-bold">2</span>
                                </div>
                                <p class="text-[13px] text-slate-600">Cari email dari <strong>Lab SMABA Security</strong></p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                                    <span class="text-[10px] font-bold">3</span>
                                </div>
                                <p class="text-[13px] text-slate-600">Klik <strong>"Verifikasi Email Saya"</strong></p>
                            </div>
                        </div>

                        {{-- Resend --}}
                        <button @click="resend" 
                                :disabled="isLoading || resendCooldown > 0"
                                class="w-full relative flex justify-center items-center py-3.5 px-4 rounded-xl font-bold text-sm bg-amber-500 text-white shadow-lg shadow-amber-500/25 hover:bg-amber-600 hover:shadow-amber-600/30 hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-amber-500/30 active:scale-[0.98] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 group">
                            <span x-show="isLoading" class="mr-2"><i class="fas fa-spinner animate-spin"></i></span>
                            <i x-show="!isLoading" class="fas fa-redo mr-2 text-white/80 group-hover:text-white transition-colors"></i>
                            <span x-text="resendCooldown > 0 ? 'Tunggu ' + resendCooldown + ' detik...' : (isLoading ? 'Mengirim...' : 'Kirim Ulang Email Verifikasi')"></span>
                        </button>

                        {{-- Continue to Dashboard --}}
                        <button @click="skip"
                                class="w-full mt-3 relative flex justify-center items-center py-3 px-4 rounded-xl font-semibold text-sm border-2 border-slate-200 text-slate-500 hover:bg-slate-50 hover:border-slate-300 hover:text-slate-700 focus:outline-none focus:ring-4 focus:ring-slate-200/50 transition-all duration-200">
                            <i class="fas fa-arrow-right mr-2"></i>
                            Lanjut ke Dashboard
                        </button>

                        {{-- Change Email --}}
                        <div class="mt-4 text-center">
                            <button @click="step = 'input'; error = ''; success = ''" 
                                    class="text-sm text-slate-400 hover:text-blue-600 font-semibold transition-colors inline-flex items-center gap-1.5">
                                <i class="fas fa-pencil-alt text-xs"></i> Ganti email
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('notificationEmailSetup', () => ({
                isOpen: true,
                step: 'input',
                email: '',
                error: '',
                success: '',
                isLoading: false,
                resendCooldown: 0,
                resendTimer: null,

                async submit() {
                    this.error = '';
                    this.success = '';

                    if (!this.email || !this.email.includes('@')) {
                        this.error = 'Masukkan email yang valid.';
                        return;
                    }

                    this.isLoading = true;
                    try {
                        const response = await fetch('{{ route("notification-email.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ notification_email: this.email }),
                        });

                        const data = await response.json();

                        if (response.ok) {
                            this.success = data.message || 'Email verifikasi telah dikirim!';
                            this.step = 'pending';
                            this.startResendCooldown();
                        } else {
                            this.error = data.errors?.notification_email?.[0] || data.message || 'Terjadi kesalahan.';
                        }
                    } catch (e) {
                        this.error = 'Gagal mengirim. Periksa koneksi internet Anda.';
                    }
                    this.isLoading = false;
                },

                async resend() {
                    this.error = '';
                    this.success = '';
                    this.isLoading = true;

                    try {
                        const response = await fetch('{{ route("notification-email.resend") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                        });

                        const data = await response.json();

                        if (response.ok) {
                            this.success = data.message || 'Email verifikasi dikirim ulang!';
                            this.startResendCooldown();
                        } else {
                            this.error = data.errors?.notification_email?.[0] || data.message || 'Terlalu banyak percobaan.';
                        }
                    } catch (e) {
                        this.error = 'Gagal mengirim ulang.';
                    }
                    this.isLoading = false;
                },

                async skip() {
                    try {
                        await fetch('{{ route("notification-email.skip") }}', {
                            headers: { 'Accept': 'application/json' },
                        });
                    } catch (e) {}
                    this.isOpen = false;
                },

                startResendCooldown() {
                    this.resendCooldown = 60;
                    if (this.resendTimer) clearInterval(this.resendTimer);
                    this.resendTimer = setInterval(() => {
                        this.resendCooldown--;
                        if (this.resendCooldown <= 0) {
                            clearInterval(this.resendTimer);
                        }
                    }, 1000);
                },
            }));
        });
        </script>
        @endif
        @endauth
        
        {{-- MODAL PREVIEW DOKUMEN (ALPINE.JS STANDALONE COMPONENT) --}}
        <div x-data="{ 
                showDocModal: false, 
                docUrl: '', 
                docTitle: '',
                docDownloadUrl: '',
                docDeleteUrl: '',
                canDelete: false,
                localObjUrl: '',
                isLoading: false,
                isNotPdf: false,

                async loadPdf() {
                    if (!this.docUrl) return;
                    this.isLoading = true;
                    this.isNotPdf = false;
                    
                    // Cleanup previous URL
                    if (this.localObjUrl && this.localObjUrl.startsWith('blob:')) {
                        URL.revokeObjectURL(this.localObjUrl);
                    }
                    this.localObjUrl = '';

                    try {
                        // AJAX Fetch JSON Base64 Bypassing IDM
                        const targetUrl = this.docUrl + (this.docUrl.includes('?') ? '&' : '?') + 'json=1';
                        const response = await fetch(targetUrl);
                        const contentType = response.headers.get('content-type');
                        
                        if (contentType && contentType.indexOf('application/json') !== -1) {
                            const resData = await response.json();
                            if(resData.data) {
                                // Convert base64 to blob to prevent iframe data URI limits/blocks
                                const binaryText = atob(resData.data);
                                const bytes = new Uint8Array(binaryText.length);
                                for (let i = 0; i < binaryText.length; i++) {
                                    bytes[i] = binaryText.charCodeAt(i);
                                }
                                const blob = new Blob([bytes], {type: 'application/pdf'});
                                this.localObjUrl = URL.createObjectURL(blob);
                                return;
                            }
                        }
                        
                        // Periksa apakah ini file selain PDF (seperti word/excel yang tidak didukung browser native preview)
                        if (contentType && !contentType.includes('pdf') && !contentType.includes('json')) {
                            this.isNotPdf = true;
                            return;
                        }

                        // Fallback blob
                        const blob = await response.blob();
                        this.localObjUrl = URL.createObjectURL(new Blob([blob], {type: 'application/pdf'}));
                    } catch (err) {
                        console.error('Gagal memuat pratinjau:', err);
                        // Fallback terakhir: gunakan URL langsung jika AJAX gagal
                        this.localObjUrl = this.docUrl;
                    } finally {
                        this.isLoading = false;
                    }
                }
             }"
             @buka-dokumen.window="
                docUrl = $event.detail.url;
                docTitle = $event.detail.title;
                docDownloadUrl = $event.detail.download;
                docDeleteUrl = $event.detail.delete;
                canDelete = $event.detail.canDelete;
                showDocModal = true;
                setTimeout(() => loadPdf(), 50);
             "
             @keydown.escape.window="showDocModal = false"
             x-show="showDocModal" 
             style="display: none;"
             class="fixed inset-0 z-[1000] flex items-center justify-center p-4 sm:p-6" x-cloak>
             
            <div x-show="showDocModal" 
                 x-transition.opacity 
                 class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" @click="showDocModal = false"></div>
            
            <div x-show="showDocModal" 
                 x-transition 
                 class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-4xl h-[90vh] flex flex-col relative z-50 overflow-hidden">
                
                {{-- Header Modal --}}
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="font-extrabold text-lg text-slate-800 flex items-center gap-3 truncate pr-4">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm bg-blue-100 text-blue-600 shrink-0"><i class="fas fa-file-alt"></i></div>
                        <span x-text="docTitle" class="truncate">Pratinjau Dokumen</span>
                    </h3>
                    <div class="flex gap-2">
                        <button @click="showDocModal = false" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 flex justify-center items-center rounded-lg hover:bg-slate-200"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                
                {{-- Body Modal (Dokumen) --}}
                <div class="flex-grow w-full bg-slate-200 overflow-hidden relative">
                    
                    {{-- Indikator Loading --}}
                    <div x-show="isLoading" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-200 z-10 transition-opacity">
                        <i class="fas fa-circle-notch fa-spin text-4xl text-blue-500 mb-3"></i>
                        <span class="text-slate-500 font-medium text-sm animate-pulse">Merender Dokumen...</span>
                    </div>

                    {{-- Pesan File Tidak Dapat Ditampilkan --}}
                    <div x-show="isNotPdf && !isLoading" class="absolute inset-0 flex flex-col items-center justify-center bg-white z-10 p-8 text-center" style="display: none;">
                        <div class="w-20 h-20 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center text-3xl mb-4">
                            <i class="fas fa-file-word"></i>
                        </div>
                        <h4 class="text-xl font-bold text-slate-800 mb-2">Pratinjau Tidak Tersedia</h4>
                        <p class="text-slate-500 max-w-md">File ini berformat non-PDF (misalnya Word/Excel) yang tidak didukung untuk pratinjau langsung di dalam browser. Silakan unduh dokumen untuk melihat isinya.</p>
                        <a :href="docDownloadUrl" class="mt-6 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-md transition-all flex items-center gap-2">
                            <i class="fas fa-download"></i> Unduh Sekarang
                        </a>
                    </div>

                    {{-- Preview iframe (Lebih konsisten daripada object/embed untuk data URIs) --}}
                    <template x-if="localObjUrl && !isNotPdf">
                        <iframe :src="localObjUrl" class="w-full h-full border-0 absolute inset-0 bg-white"></iframe>
                    </template>
                </div>

                {{-- Footer Action Bar --}}
                <div class="px-6 py-4 border-t border-slate-100 bg-white flex justify-between items-center mt-auto">
                    <div>
                        <form :action="docDeleteUrl" method="POST" class="delete-form m-0" x-show="canDelete">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-4 py-2.5 bg-red-50 text-red-600 hover:bg-red-500 hover:text-white rounded-xl font-bold text-sm transition-colors border border-red-100 flex items-center gap-2 shadow-sm">
                                <i class="fas fa-trash-alt"></i> Hapus Permanen
                            </button>
                        </form>
                    </div>
                    <div class="flex gap-3">
                        <button @click="showDocModal = false" class="px-5 py-2.5 bg-slate-50 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-100 transition-colors text-sm shadow-sm relative z-50">Batalkan</button>
                        <a :href="docDownloadUrl" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-md shadow-blue-600/20 transition-all flex items-center gap-2 hover:-translate-y-0.5 relative z-50">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lock Screen Modal --}}
        @auth
        <div x-data="lockScreen" @keydown.window="resetTimer" @mousemove.window="resetTimer" @click.window="resetTimer" @scroll.window="resetTimer" style="display: none;" x-show="isOpen" class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            {{-- Blur Backdrop --}}
            <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">
                    
                    <div class="px-4 pb-4 pt-5 sm:p-6 sm:pb-4 text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-50 mb-4 animate-pulse">
                            <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold leading-6 text-gray-900" id="modal-title">Sesi Terkunci</h3>
                        <p class="text-sm text-gray-500 mt-2">Demi keamanan, sesi Anda dikunci karena tidak ada aktivitas selama 5 menit.</p>
                        
                        <div class="mt-6">
                            <div class="text-left mb-2">
                                <label class="text-xs font-semibold text-gray-500 uppercase">User</label>
                                <p class="font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            </div>
                            <input type="password" x-model="password" @keydown.enter="unlock" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" placeholder="Masukkan Password Anda...">
                            <p x-show="error" x-text="error" class="text-red-600 text-xs mt-2 text-left"></p>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" @click="unlock" :disabled="isLoading" class="inline-flex w-full justify-center rounded-lg bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:ml-3 sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="isLoading" class="mr-2 animate-spin"><i class="fas fa-spinner"></i></span>    
                            Buka Kunci
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endauth

        {{-- @stack('scripts') dipindahkan ke akhir <body> agar dimuat setelah Alpine --}}
        @stack('scripts')

        {{-- TAMBAHAN 2: Memuat script yang dibutuhkan oleh Livewire --}}
        @livewireScripts
    </body>
</html>

