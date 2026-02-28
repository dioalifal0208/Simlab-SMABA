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
             * SOLUSI LAYOUT DEFINITIF:
             * #main-wrapper menggunakan position:fixed — sama seperti sidebar & topbar.
             * Ini menjamin konten SELALU terpasang di viewport dengan posisi yang tepat.
             * overflow-y:auto membuat area ini scrollable secara independen.
             *
             * Tidak lagi terpengaruh margin collapse, parent min-height, atau CSS lainnya.
             */
            #main-wrapper {
                position: fixed;
                top: 56px;      /* tepat di bawah topbar (h-14 = 56px) */
                left: 0;
                right: 0;
                bottom: 0;
                overflow-y: auto;
                background-color: #f9fafb; /* bg-gray-50 */
            }
            @media (min-width: 1024px) {
                #main-wrapper {
                    left: 260px; /* tepat di samping kanan sidebar (w-[260px]) */
                }
                body.sidebar-collapsed #main-wrapper {
                    left: 64px;
                }
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
              toast: { visible: false, message: '', type: 'success' }
          }"
          x-init="$watch('showImportModal', value => isModalOpen = value)"
          @modal-state-changed.window="isModalOpen = $event.detail.open"
          @show-toast.window="toast.visible = true; toast.message = $event.detail.message; toast.type = $event.detail.type; setTimeout(() => toast.visible = false, 3000)">
          
        <div class="min-h-screen bg-gray-50">
            @unless($hideChrome)
                @include('layouts.navigation')
            @endunless

            {{-- MAIN WRAPPER: position:fixed, left mengikuti sidebar width --}}
            <div id="main-wrapper" class="transition-all duration-300 flex flex-col min-h-full"
                 :style="window.innerWidth >= 1024 ? 'left:' + (sidebarCollapsed ? '64px' : '260px') : ''">

                {{-- BANNER PENGUMUMAN GLOBAL --}}
                @if(isset($activeAnnouncement))
                    <div class="bg-indigo-600 text-white" 
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
                                    <span class="flex-shrink-0 flex p-2 rounded-lg bg-indigo-500">
                                        <i class="fas fa-bullhorn text-white"></i>
                                    </span>
                                    <div class="marquee-container ms-3">
                                        <p class="marquee-text font-medium text-sm">{{ $activeAnnouncement->message }}</p>
                                    </div>
                                </div>
                                <div class="order-2 flex-shrink-0 sm:order-3 sm:ms-3">
                                    <button @click="showAnnouncement = false" type="button" class="-mr-1 flex p-2 rounded-md hover:bg-indigo-500 focus:outline-none">
                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
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

                <main class="flex-grow">
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
             class="fixed top-4 right-4 z-50 max-w-sm w-full"
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
        @auth
            @include('components.chat-widget')
        @endauth
        
        {{-- Lock Screen Modal --}}
        @auth
        <div x-data="lockScreen" @keydown.window="resetTimer" @mousemove.window="resetTimer" @click.window="resetTimer" @scroll.window="resetTimer" style="display: none;" x-show="isOpen" class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            {{-- Blur Backdrop --}}
            <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80 backdrop-blur-md transition-opacity"></div>

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

