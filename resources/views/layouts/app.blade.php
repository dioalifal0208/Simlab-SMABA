<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="icon" href="{{ asset('images/logo-smaba.webp') }}" type="image/png">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        {{-- TAMBAHAN 1: Memuat style yang dibutuhkan oleh Livewire --}}
        @livewireStyles

        {{-- PERBAIKAN 1: Menambahkan script Alpine.js di <head> --}}
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    
    {{-- PERBAIKAN 2: Menambahkan 'x-data' global di <body> --}}
    <body class="font-sans antialiased" 
          data-user-role="{{ Auth::user()->role ?? 'guest' }}"
          data-user-id="{{ Auth::id() ?? '' }}"
          x-data="{ 
              showAnnouncement: true, 
              showImportModal: false 
          }">
          
        <div class="min-h-screen bg-gray-100 flex flex-col">
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

            @unless($hideChrome)
                <footer class="bg-white shadow-inner mt-auto">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        <div class="flex flex-col sm:flex-row justify-between items-center text-sm text-gray-500">
                            <p>&copy; {{ date('Y') }} SMA Negeri 1 Babat. Hak Cipta Dilindungi.</p>
                            <p class="mt-2 sm:mt-0">Dikelola oleh Tim Lab SMABA</p>
                        </div>
                    </div>
                </footer>
            @endunless
        </div>

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
