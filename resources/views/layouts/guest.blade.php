<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{ asset('images/logo-smaba.webp') }}" type="image/png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @livewireStyles
    </head>
    <body class="font-sans text-gray-900 antialiased overflow-x-hidden">
        <div class="h-screen lg:grid lg:grid-cols-2 overflow-hidden">
            
            {{-- Left Panel: Branding --}}
            <div class="hidden lg:flex flex-col items-center justify-center bg-gradient-to-br from-green-700 via-green-600 to-emerald-600 p-12 text-white h-full relative overflow-hidden" data-aos="fade-right">
                
                {{-- Subtle Background Pattern --}}
                <div class="absolute inset-0 opacity-[0.04]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                
                {{-- Float decorations --}}
                <div class="absolute top-20 left-16 w-24 h-24 bg-white/5 rounded-full blur-2xl"></div>
                <div class="absolute bottom-32 right-20 w-32 h-32 bg-white/5 rounded-full blur-3xl"></div>
                
                <a href="/" class="relative z-10">
                    <div class="w-32 h-32 rounded-3xl bg-white/10 backdrop-blur-sm flex items-center justify-center border border-white/20 shadow-2xl shadow-green-900/30 hover:scale-105 transition-transform duration-300">
                        <img src="{{ asset('images/logo-smaba.webp') }}" alt="Logo Smaba" class="w-24 h-24 drop-shadow-lg">
                    </div>
                </a>
                
                <h1 class="text-3xl font-extrabold mt-8 text-center tracking-tight relative z-10">
                    Sistem Informasi Laboratorium
                </h1>
                <p class="text-base text-green-200 mt-3 font-medium relative z-10">
                    SMA Negeri 1 Babat
                </p>

                {{-- Version Badge --}}
                <div class="mt-6 px-4 py-1.5 bg-white/10 backdrop-blur-sm rounded-full border border-white/20 relative z-10">
                    <span class="text-xs font-bold text-green-100 tracking-wider uppercase">LAB-SMABA v2.0</span>
                </div>
            </div>

            {{-- Right Panel: Auth Form --}}
            <div class="flex items-center justify-center p-6 sm:p-12 bg-slate-50 h-full overflow-y-auto">
                {{ $slot }}
            </div>
        </div>

        @livewireScripts
    </body>
</html>
