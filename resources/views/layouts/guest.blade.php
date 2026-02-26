<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{ asset('images/logo-smaba.webp') }}" type="image/png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @livewireStyles
    </head>
    <body class="font-sans text-gray-900 antialiased overflow-x-hidden">
        <div class="h-screen lg:grid lg:grid-cols-2 overflow-hidden">
            
            <div class="hidden lg:flex flex-col items-center justify-center bg-indigo-600 p-12 text-white h-full" data-aos="fade-right">
                <a href="/">
                    {{-- Logo diperbesar --}}
                    <img src="{{ asset('images/logo-smaba.webp') }}" alt="Logo Smaba" class="w-40 h-40">
                </a>
                {{-- Teks ditambahkan di bawah logo --}}
                <h1 class="text-3xl font-bold mt-6 text-center">
                    Sistem Informasi Laboratorium
                </h1>
                <p class="text-lg text-indigo-500 mt-2">
                    SMA Negeri 1 Babat
                </p>
            </div>

            <div class="flex justify-center p-6 sm:p-12 bg-gray-100 h-full overflow-y-auto">
                {{ $slot }}
            </div>
        </div>

        @livewireScripts
    </body>
</html>
