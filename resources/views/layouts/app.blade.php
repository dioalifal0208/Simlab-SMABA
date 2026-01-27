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
        
        {{-- TAMBAHAN 1: Memuat style yang dibutuhkan oleh Livewire --}}
        @livewireStyles

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
              toast: { visible: false, message: '', type: 'success' }
          }"
          @show-toast.window="toast.visible = true; toast.message = $event.detail.message; toast.type = $event.detail.type; setTimeout(() => toast.visible = false, 3000)">
          
        <div class="min-h-screen bg-gray-50 flex flex-col">
            @unless($hideChrome)
                @include('layouts.navigation')

                {{-- BANNER PENGUMUMAN GLOBAL --}}
                @if(isset($activeAnnouncement))
                    {{-- PERBAIKAN 3: Menggunakan 'showAnnouncement' dari global x-data --}}
                    <div class="bg-smaba-dark-blue text-white" 
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
                                    <span class="flex-shrink-0 flex p-2 rounded-lg bg-smaba-light-blue">
                                        <i class="fas fa-bullhorn text-white"></i>
                                    </span>
                                    <div class="marquee-container ms-3">
                                        <p class="marquee-text font-medium text-sm">
                                            {{ $activeAnnouncement->message }}
                                        </p>
                                    </div>
                                </div>
                                <div class="order-2 flex-shrink-0 sm:order-3 sm:ms-3">
                                    <button @click="showAnnouncement = false" type="button" class="-mr-1 flex p-2 rounded-md hover:bg-smaba-light-blue focus:outline-none focus:ring-2 focus:ring-white sm:-mr-2">
                                        <span class="sr-only">Tutup</span>
                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endunless

            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="flex-grow">
                {{ $slot }}
            </main>

            @unless($hideChrome || $hideFooter)
                <footer class="bg-white border-t border-gray-200 mt-auto">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <div class="flex flex-col sm:flex-row justify-between items-center text-sm text-gray-500">
                            <p>&copy; {{ date('Y') }} SMA Negeri 1 Babat. Sistem Manajemen Laboratorium.</p>
                        </div>
                    </div>
                </footer>
            @endunless
        </div>

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
                'bg-blue-50 border-blue-200 text-blue-800': toast.type === 'info'
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
        
        {{-- @stack('scripts') dipindahkan ke akhir <body> agar dimuat setelah Alpine --}}
        @stack('scripts')

        {{-- TAMBAHAN 2: Memuat script yang dibutuhkan oleh Livewire --}}
        @livewireScripts
    </body>
</html>
