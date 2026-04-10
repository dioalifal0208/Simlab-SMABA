<?php

$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

// Replace colors
$content = str_replace('bg-gray-', 'bg-slate-', $content);
$content = str_replace('text-gray-', 'text-slate-', $content);
$content = str_replace('border-gray-', 'border-slate-', $content);
$content = str_replace('ring-gray-', 'ring-slate-', $content);
$content = str_replace('shadow-gray-', 'shadow-slate-', $content);

$content = str_replace('bg-blue-', 'bg-green-', $content);
$content = str_replace('text-blue-', 'text-green-', $content);
$content = str_replace('border-blue-', 'border-green-', $content);
$content = str_replace('ring-blue-', 'ring-green-', $content);
$content = str_replace('shadow-blue-', 'shadow-green-', $content);

// specific fixes to avoid weird colors
$content = str_replace('bg-green-700', 'bg-green-700', $content); // just in case

// Fix hero layout (Grid)
// From: max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center
$content = str_replace('<div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">', '<div class="max-w-[1200px] mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">', $content);

$content = str_replace('{{-- HERO COPY --}}
                <div class="flex flex-col gap-8" data-aos="fade-up" data-aos-duration="700">', '{{-- HERO COPY --}}
                <div class="lg:col-span-7 flex flex-col gap-8 lg:pr-8" data-aos="fade-up" data-aos-duration="700">', $content);

$content = str_replace('{{-- HERO DASHBOARD MOCKUP --}}
                <div class="relative" data-aos="fade-left" data-aos-duration="700">', '{{-- HERO DASHBOARD MOCKUP --}}
                <div class="lg:col-span-5 relative" data-aos="fade-left" data-aos-duration="700">', $content);

// Hero Heading
$content = preg_replace('/<h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold text-slate-900 tracking-tight leading-tight">\s*Kelola Praktikum <br\/>\s*<span class="text-green-600">Lebih Profesional<\/span>\s*<\/h1>/', '<h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold text-slate-900 tracking-[-0.02em] leading-[1.15]">Kelola Praktikum <br/><span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-500">Lebih Profesional</span></h1>', $content);

// Spacing
$content = str_replace('pt-32 pb-24 lg:pt-40 lg:pb-32', 'pt-32 pb-24 lg:pt-44 lg:pb-32', $content);
$content = str_replace('py-16 bg-slate-50 border-y border-slate-200', 'py-20 bg-slate-50/50 border-y border-slate-100', $content);
$content = str_replace('py-20', 'py-28', $content);

// Features Title
$content = str_replace('<h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">', '<h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4 tracking-tight">', $content);

// Feature cards base modification
$content = str_replace('bg-white border border-slate-200 rounded-xl shadow-sm p-8 hover:shadow-md', 'bg-white border border-slate-200 rounded-2xl shadow-sm p-8 hover:shadow-xl hover:-translate-y-1 hover:border-slate-300', $content);

// CTA section spacing
$content = str_replace('py-24 bg-white', 'py-32 bg-white', $content);
$content = str_replace('<span class="px-4 py-1.5 bg-slate-50 text-slate-500 font-bold text-xs rounded-full mb-6 inline-block border border-slate-200 uppercase tracking-widest">', '<span class="px-5 py-2 bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 font-bold text-xs rounded-full mb-6 inline-block border border-green-100 uppercase tracking-widest">', $content);

file_put_contents($file, $content);
echo "Done";

