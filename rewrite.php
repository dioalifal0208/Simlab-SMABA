<?php
$file_path = "c:\\laragon\\www\\lab-smaba\\resources\\views\\welcome.blade.php";
$text = file_get_contents($file_path);

// Extract header
preg_match('/(.*?<main)/s', $text, $head_match);
if ($head_match) {
    $head_part = substr($head_match[1], 0, -5); // remove <main
} else {
    echo "Could not find <main\n";
    exit(1);
}

// Extract footer/modals
preg_match('/(\n\s*\{\{-- MODAL LOGIN \+ 2FA.*)/s', $text, $tail_match);
if ($tail_match) {
    $tail_part = $tail_match[1];
} else {
    echo "Could not find Modals\n";
    exit(1);
}

$new_body = <<<'EOD'
        <main class="relative z-10 pt-24 pb-20 lg:pt-32 lg:pb-24 overflow-hidden">
            <!-- Hero Background Effects -->
            <div class="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-green-100/40 via-transparent to-transparent"></div>
            <div class="absolute top-0 right-0 -translate-y-12 translate-x-1/3 w-[800px] h-[600px] bg-green-500/5 blur-[120px] rounded-full pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 translate-y-1/3 -translate-x-1/3 w-[600px] h-[600px] bg-blue-500/5 blur-[120px] rounded-full pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
                {{-- HERO COPY --}}
                <div class="lg:col-span-6 space-y-8" data-aos="fade-right">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-gray-200 shadow-sm text-sm font-medium text-gray-700 hover:border-green-300 transition-colors cursor-default">
                        <span class="relative flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                        </span>
                        Sistem Manajemen Lab Generasi Baru
                    </div>
                    
                    <h1 class="text-5xl sm:text-6xl lg:text-[4rem] font-extrabold text-gray-900 tracking-tight leading-[1.1]">
                        Kelola Praktikum <br/>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-blue-600">Lebih Profesional</span>
                    </h1>
                    
                    <p class="text-lg text-gray-600 leading-relaxed max-w-xl">
                        Tinggalkan cara manual. Digitalisasi peminjaman alat, penjadwalan lab, dan pelaporan dengan mudah dalam satu platform yang terintegrasi untuk sekolah Anda.
                    </p>
                    
                    <div class="flex flex-wrap items-center gap-4 pt-4">
                        <button @click="isModalOpen = true" class="group relative px-8 py-4 bg-gray-900 text-white font-semibold rounded-xl overflow-hidden shadow-lg shadow-gray-900/20 hover:shadow-gray-900/30 hover:-translate-y-0.5 transition-all duration-300">
                            <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-green-500 to-green-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <span class="relative flex items-center gap-2">
                                {{ __('welcome.hero.cta_start') }} <i class="fas fa-arrow-right text-sm transform group-hover:translate-x-1 transition-transform"></i>
                            </span>
                        </button>
                        <button @click="showDemoModal = true; activeSlide = 0" class="px-8 py-4 text-gray-700 bg-white border-2 border-gray-200 font-semibold rounded-xl hover:border-gray-300 hover:bg-gray-50 hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2 group">
                            <i class="fas fa-play text-green-600 group-hover:scale-110 transition-transform"></i> {{ __('welcome.hero.cta_tour') }}
                        </button>
                    </div>

                    {{-- TRUST INDICATORS --}}
                    <div class="grid grid-cols-3 gap-6 pt-8 border-t border-gray-100 mt-8">
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-2 text-green-600">
                                <i class="fas fa-cubes"></i>
                                <span class="text-2xl font-bold text-gray-900">{{ number_format($inventoryCount) }}+</span>
                            </div>
                            <span class="text-sm text-gray-500 font-medium pb-1">{{ __('welcome.stats.items') }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-2 text-blue-600">
                                <i class="fas fa-users"></i>
                                <span class="text-2xl font-bold text-gray-900">{{ number_format($teacherCount) }}+</span>
                            </div>
                            <span class="text-sm text-gray-500 font-medium pb-1">{{ __('welcome.stats.teachers') }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-2 text-orange-500">
                                <i class="fas fa-bolt"></i>
                                <span class="text-2xl font-bold text-gray-900">{{ number_format($visitorCount) }}+</span>
                            </div>
                            <span class="text-sm text-gray-500 font-medium pb-1">{{ __('welcome.stats.activity') }}</span>
                        </div>
                    </div>
                </div>

                {{-- HERO DASHBOARD MOCKUP --}}
                <div class="lg:col-span-6 relative lg:ml-8 perspective-[1000px] mt-12 lg:mt-0" data-aos="fade-left" data-aos-delay="100">
                    <!-- Decorative Elements -->
                    <div class="absolute -inset-4 bg-gradient-to-tr from-green-400/20 to-blue-400/20 rounded-3xl blur-2xl z-0"></div>
                    
                    <div class="relative z-10 bg-white border border-gray-200/60 rounded-2xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.1)] backdrop-blur-xl overflow-hidden transform md:-rotate-y-12 md:rotate-x-12 hover:rotate-y-0 hover:rotate-x-0 transition-transform duration-700">
                        <!-- Browser Header -->
                        <div class="bg-gray-50/80 border-b border-gray-200/60 px-4 py-3 flex items-center backdrop-blur-sm">
                            <div class="flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            </div>
                            <div class="mx-auto flex items-center justify-center gap-2 px-3 py-1 bg-white border border-gray-200 rounded min-w-[200px] shadow-sm">
                                <i class="fas fa-lock text-[10px] text-gray-400"></i>
                                <span class="text-xs text-gray-500 font-medium font-mono">lab-smaba.sch.id/admin</span>
                            </div>
                        </div>
                        
                        <!-- Dashboard Content -->
                        <div class="p-6 bg-slate-50/50">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h3 class="font-bold text-gray-900 text-lg">Dashboard Lab</h3>
                                    <p class="text-xs text-gray-500 mt-1">Ringkasan aktivitas hari ini</p>
                                </div>
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full border border-green-200">Online</span>
                            </div>

                            <!-- Mock Stats -->
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden group">
                                    <div class="absolute top-0 right-0 w-16 h-16 bg-green-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                                    <i class="fas fa-calendar-check text-green-500 mb-2 relative z-10 text-lg"></i>
                                    <p class="text-xs text-gray-500 font-medium relative z-10">Jadwal Aktif</p>
                                    <p class="text-2xl font-bold text-gray-900 relative z-10 mt-1">3 <span class="text-sm font-normal text-gray-500">Sesi</span></p>
                                </div>
                                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden group">
                                    <div class="absolute top-0 right-0 w-16 h-16 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                                    <i class="fas fa-flask text-blue-500 mb-2 relative z-10 text-lg"></i>
                                    <p class="text-xs text-gray-500 font-medium relative z-10">Alat Dipinjam</p>
                                    <p class="text-2xl font-bold text-gray-900 relative z-10 mt-1">12 <span class="text-sm font-normal text-gray-500">Item</span></p>
                                </div>
                            </div>

                            <!-- Mock Task/Schedule -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Aktivitas Mendatang</p>
                                <div class="space-y-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold text-sm shrink-0">
                                            F
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold text-gray-900 truncate">Praktikum Fisika Kelas XII</p>
                                            <p class="text-xs text-gray-500">10:00 - 11:30 WIB</p>
                                        </div>
                                        <span class="text-[10px] bg-green-50 text-green-700 px-2.5 py-1 rounded border border-green-200 font-bold shrink-0">Aktif</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-sm shrink-0">
                                            B
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold text-gray-900 truncate">Praktikum Biologi Kelas XI</p>
                                            <p class="text-xs text-gray-500">13:00 - 14:30 WIB</p>
                                        </div>
                                        <span class="text-[10px] bg-amber-50 text-amber-600 px-2.5 py-1 rounded border border-amber-200 font-bold shrink-0">Menunggu</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating UI Elements -->
                    <div class="hidden sm:flex absolute -right-6 top-1/4 bg-white p-3.5 rounded-xl shadow-xl border border-gray-100 items-center gap-3 animate-[pulse_3s_ease-in-out_infinite] z-20">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                            <i class="fas fa-check text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-900">Pinjam Disetujui</p>
                            <p class="text-[10px] text-gray-500">Mikroskop Olympus (2x)</p>
                        </div>
                    </div>
                    
                    <div class="hidden sm:flex absolute -left-8 bottom-1/4 bg-white p-3.5 rounded-xl shadow-xl border border-gray-100 items-center gap-3 animate-[bounce_4s_infinite] z-20" style="animation-delay: 1s;">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                            <i class="fas fa-qrcode text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-900">Validasi Cepat</p>
                            <p class="text-[10px] text-gray-500">Telah di-scan Laboran</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        {{-- VALUE / BENEFIT PANEL --}}
        <section class="py-12 bg-white border-y border-gray-100 relative z-20">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="flex flex-wrap justify-center gap-4 md:gap-12">
                    <div class="flex items-center gap-4 bg-slate-50 md:bg-transparent px-6 py-4 md:p-0 rounded-2xl md:rounded-none w-full md:w-auto" data-aos="zoom-in" data-aos-delay="0">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0"><i class="fas fa-leaf text-xl"></i></div>
                        <div>
                            <p class="font-bold text-gray-900 text-base md:text-lg">100% Paperless</p>
                            <p class="text-sm text-gray-500">Ramah Lingkungan</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 bg-slate-50 md:bg-transparent px-6 py-4 md:p-0 rounded-2xl md:rounded-none w-full md:w-auto mt-4 md:mt-0" data-aos="zoom-in" data-aos-delay="100">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0"><i class="fas fa-display text-xl"></i></div>
                        <div>
                            <p class="font-bold text-gray-900 text-base md:text-lg">Multi Perangkat</p>
                            <p class="text-sm text-gray-500">Akses Kapan Saja</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 bg-slate-50 md:bg-transparent px-6 py-4 md:p-0 rounded-2xl md:rounded-none w-full md:w-auto mt-4 md:mt-0" data-aos="zoom-in" data-aos-delay="200">
                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-600 shrink-0"><i class="fas fa-shield-halved text-xl"></i></div>
                        <div>
                            <p class="font-bold text-gray-900 text-base md:text-lg">Aman & Terenkripsi</p>
                            <p class="text-sm text-gray-500">Sistem 2FA Tersedia</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- FEATURES SECTION --}}
        <section class="py-24 bg-slate-50 relative" id="features">
            <!-- Background pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjEiIGZpbGw9IiNlMmU4ZjAiLz48L3N2Zz4=')] [mask-image:linear-gradient(to_bottom,white,transparent)]"></div>
            
            <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
                <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up">
                    <h2 class="text-sm font-bold text-green-600 tracking-widest uppercase mb-3">Fitur Utama</h2>
                    <h3 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-tight">{{ __('welcome.features.title') }}</h3>
                    <p class="mt-4 text-gray-600 text-lg">{{ __('welcome.features.subtitle') }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {{-- Feature 1: Inventory --}}
                    <div class="group relative p-8 bg-white border border-gray-200 rounded-2xl hover:-translate-y-2 hover:shadow-[0_20px_40px_-15px_rgba(34,197,94,0.2)] hover:border-green-300 transition-all duration-300 flex flex-col h-full" data-aos="fade-up" data-aos-delay="50">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-green-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-125 opacity-50"></div>
                        <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6 text-green-600 relative z-10">
                            <i class="fas fa-cubes text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3 relative z-10">{{ __('welcome.features.inventory.title') }}</h4>
                        <p class="text-gray-600 leading-relaxed mb-6 relative z-10 text-sm flex-1">{{ __('welcome.features.inventory.desc') }}</p>
                        <button @click="showFeatureModal = true; activeFeature = 'inventory'" class="text-sm font-bold text-green-600 group-hover:text-green-700 flex items-center gap-2 group-hover:gap-3 transition-all relative z-10">
                            Lihat Detail <i class="fas fa-arrow-right text-xs"></i>
                        </button>
                    </div>
                    
                    {{-- Feature 2: Booking --}}
                    <div class="group relative p-8 bg-white border border-gray-200 rounded-2xl hover:-translate-y-2 hover:shadow-[0_20px_40px_-15px_rgba(59,130,246,0.2)] hover:border-blue-300 transition-all duration-300 flex flex-col h-full" data-aos="fade-up" data-aos-delay="100">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-125 opacity-50"></div>
                        <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-6 text-blue-600 relative z-10">
                            <i class="fas fa-calendar-check text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3 relative z-10">{{ __('welcome.features.booking.title') }}</h4>
                        <p class="text-gray-600 leading-relaxed mb-6 relative z-10 text-sm flex-1">{{ __('welcome.features.booking.desc') }}</p>
                        <button @click="showFeatureModal = true; activeFeature = 'booking'" class="text-sm font-bold text-blue-600 group-hover:text-blue-700 flex items-center gap-2 group-hover:gap-3 transition-all relative z-10">
                            Lihat Detail <i class="fas fa-arrow-right text-xs"></i>
                        </button>
                    </div>

                    {{-- Feature 3: Validation --}}
                    <div class="group relative p-8 bg-white border border-gray-200 rounded-2xl hover:-translate-y-2 hover:shadow-[0_20px_40px_-15px_rgba(16,185,129,0.2)] hover:border-emerald-300 transition-all duration-300 flex flex-col h-full" data-aos="fade-up" data-aos-delay="150">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-125 opacity-50"></div>
                        <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center mb-6 text-emerald-600 relative z-10">
                            <i class="fas fa-qrcode text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3 relative z-10">{{ __('welcome.features.validation.title') }}</h4>
                        <p class="text-gray-600 leading-relaxed mb-6 relative z-10 text-sm flex-1">{{ __('welcome.features.validation.desc') }}</p>
                        <button @click="showFeatureModal = true; activeFeature = 'validation'" class="text-sm font-bold text-emerald-600 group-hover:text-emerald-700 flex items-center gap-2 group-hover:gap-3 transition-all relative z-10">
                            Lihat Detail <i class="fas fa-arrow-right text-xs"></i>
                        </button>
                    </div>

                    {{-- Feature 4: Calendar --}}
                    <div class="group relative p-8 bg-white border border-gray-200 rounded-2xl hover:-translate-y-2 hover:shadow-[0_20px_40px_-15px_rgba(245,158,11,0.2)] hover:border-orange-300 transition-all duration-300 flex flex-col h-full" data-aos="fade-up" data-aos-delay="200">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-orange-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-125 opacity-50"></div>
                        <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center mb-6 text-orange-600 relative z-10">
                            <i class="fas fa-calendar-days text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3 relative z-10">{{ __('welcome.features.calendar.title') }}</h4>
                        <p class="text-gray-600 leading-relaxed mb-6 relative z-10 text-sm flex-1">{{ __('welcome.features.calendar.desc') }}</p>
                        <button @click="showFeatureModal = true; activeFeature = 'calendar'" class="text-sm font-bold text-orange-600 group-hover:text-orange-700 flex items-center gap-2 group-hover:gap-3 transition-all relative z-10">
                            Lihat Detail <i class="fas fa-arrow-right text-xs"></i>
                        </button>
                    </div>

                    {{-- Feature 5: Resources --}}
                    <div class="group relative p-8 bg-white border border-gray-200 rounded-2xl hover:-translate-y-2 hover:shadow-[0_20px_40px_-15px_rgba(168,85,247,0.2)] hover:border-purple-300 transition-all duration-300 flex flex-col h-full" data-aos="fade-up" data-aos-delay="250">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-purple-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-125 opacity-50"></div>
                        <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-6 text-purple-600 relative z-10">
                            <i class="fas fa-book-bookmark text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3 relative z-10">{{ __('welcome.features.resources.title') }}</h4>
                        <p class="text-gray-600 leading-relaxed mb-6 relative z-10 text-sm flex-1">{{ __('welcome.features.resources.desc') }}</p>
                        <button @click="showFeatureModal = true; activeFeature = 'resources'" class="text-sm font-bold text-purple-600 group-hover:text-purple-700 flex items-center gap-2 group-hover:gap-3 transition-all relative z-10">
                            Lihat Detail <i class="fas fa-arrow-right text-xs"></i>
                        </button>
                    </div>

                    {{-- Feature 6: Report --}}
                    <div class="group relative p-8 bg-white border border-gray-200 rounded-2xl hover:-translate-y-2 hover:shadow-[0_20px_40px_-15px_rgba(79,70,229,0.2)] hover:border-indigo-300 transition-all duration-300 flex flex-col h-full" data-aos="fade-up" data-aos-delay="300">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-125 opacity-50"></div>
                        <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center mb-6 text-indigo-600 relative z-10">
                            <i class="fas fa-chart-pie text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3 relative z-10">{{ __('welcome.features.report.title') }}</h4>
                        <p class="text-gray-600 leading-relaxed mb-6 relative z-10 text-sm flex-1">{{ __('welcome.features.report.desc') }}</p>
                        <button @click="showFeatureModal = true; activeFeature = 'report'" class="text-sm font-bold text-indigo-600 group-hover:text-indigo-700 flex items-center gap-2 group-hover:gap-3 transition-all relative z-10">
                            Lihat Detail <i class="fas fa-arrow-right text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        {{-- WORKFLOW / STEPS SECTION (VISUAL TIMELINE) --}}
        <section class="py-24 bg-white" id="workflow">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-20" data-aos="fade-up">
                    <h2 class="text-sm font-bold text-blue-600 tracking-widest uppercase mb-3">Alur Kerja</h2>
                    <h3 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-tight">{{ __('welcome.workflow.title') }}</h3>
                    <p class="mt-4 text-gray-600 text-lg">Proses peminjaman alat kini lebih terstruktur dengan rekam jejak digital yang jelas.</p>
                </div>

                <div class="relative">
                    <!-- Base line for desktop -->
                    <div class="hidden md:block absolute top-[50px] left-0 w-full h-1 bg-gray-100 -z-10"></div>
                    <!-- Base line for mobile -->
                    <div class="md:hidden absolute top-0 bottom-0 left-[31px] w-1 bg-gray-100 -z-10"></div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-12 md:gap-6">
                        <!-- Step 1 -->
                        <div class="relative flex md:flex-col items-start gap-6 group" data-aos="fade-up" data-aos-delay="0">
                            <div class="w-16 h-16 bg-white border-4 border-gray-100 rounded-full flex items-center justify-center text-2xl font-bold text-gray-400 group-hover:border-blue-500 group-hover:text-blue-600 group-hover:shadow-[0_0_20px_rgba(59,130,246,0.3)] transition-all duration-300 z-10 shrink-0">
                                1
                            </div>
                            <!-- Next connecting line with gradient -->
                            <div class="absolute md:top-[31px] md:left-[64px] md:right-[-24px] lg:md:right-[-48px] md:h-1 md:w-auto h-auto w-1 top-[64px] bottom-[-48px] left-[31px] bg-gradient-to-r from-blue-500 to-green-500 opacity-0 group-hover:opacity-100 transition-opacity duration-500 -z-10 bg-[length:200%_auto] animate-[gradient_2s_linear_infinite]" style="background-image: linear-gradient(90deg, #3b82f6, #22c55e, #3b82f6);"></div>
                            <div class="md:text-center mt-2 md:mt-0 md:px-2">
                                <h4 class="text-xl font-bold text-gray-900 mb-2">{{ __('welcome.workflow.step1_title') }}</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">{{ __('welcome.workflow.step1_desc') }}</p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="relative flex md:flex-col items-start gap-6 group" data-aos="fade-up" data-aos-delay="100">
                            <div class="w-16 h-16 bg-white border-4 border-gray-100 rounded-full flex items-center justify-center text-2xl font-bold text-gray-400 group-hover:border-green-500 group-hover:text-green-600 group-hover:shadow-[0_0_20px_rgba(34,197,94,0.3)] transition-all duration-300 z-10 shrink-0">
                                2
                            </div>
                            <div class="absolute md:top-[31px] md:left-[64px] md:right-[-24px] lg:md:right-[-48px] md:h-1 md:w-auto h-auto w-1 top-[64px] bottom-[-48px] left-[31px] bg-gradient-to-r from-green-500 to-amber-500 opacity-0 group-hover:opacity-100 transition-opacity duration-500 -z-10 bg-[length:200%_auto] animate-[gradient_2s_linear_infinite]" style="background-image: linear-gradient(90deg, #22c55e, #f59e0b, #22c55e);"></div>
                            <div class="md:text-center mt-2 md:mt-0 md:px-2">
                                <h4 class="text-xl font-bold text-gray-900 mb-2">{{ __('welcome.workflow.step2_title') }}</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">{{ __('welcome.workflow.step2_desc') }}</p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="relative flex md:flex-col items-start gap-6 group" data-aos="fade-up" data-aos-delay="200">
                            <div class="w-16 h-16 bg-white border-4 border-gray-100 rounded-full flex items-center justify-center text-2xl font-bold text-gray-400 group-hover:border-amber-500 group-hover:text-amber-600 group-hover:shadow-[0_0_20px_rgba(245,158,11,0.3)] transition-all duration-300 z-10 shrink-0">
                                3
                            </div>
                            <div class="absolute md:top-[31px] md:left-[64px] md:right-[-24px] lg:md:right-[-48px] md:h-1 md:w-auto h-auto w-1 top-[64px] bottom-[-48px] left-[31px] bg-gradient-to-r from-amber-500 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity duration-500 -z-10 bg-[length:200%_auto] animate-[gradient_2s_linear_infinite]" style="background-image: linear-gradient(90deg, #f59e0b, #4f46e5, #f59e0b);"></div>
                            <div class="md:text-center mt-2 md:mt-0 md:px-2">
                                <h4 class="text-xl font-bold text-gray-900 mb-2">{{ __('welcome.workflow.step3_title') }}</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">{{ __('welcome.workflow.step3_desc') }}</p>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="relative flex md:flex-col items-start gap-6 group" data-aos="fade-up" data-aos-delay="300">
                            <div class="w-16 h-16 bg-white border-4 border-gray-100 rounded-full flex items-center justify-center text-2xl font-bold text-gray-400 group-hover:border-indigo-500 group-hover:text-indigo-600 group-hover:shadow-[0_0_20px_rgba(79,70,229,0.3)] transition-all duration-300 z-10 shrink-0">
                                4
                            </div>
                            <div class="md:text-center mt-2 md:mt-0 md:px-2">
                                <h4 class="text-xl font-bold text-gray-900 mb-2">{{ __('welcome.workflow.step4_title') }}</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">{{ __('welcome.workflow.step4_desc') }}</p>
                            </div>
                        </div>
                        <style>@keyframes gradient { 0% { background-position: 0% 50%; } 100% { background-position: -200% 50%; } }</style>
                    </div>
                </div>
            </div>
        </section>

        {{-- USE CASE / PERSONAS SECTION (NEW) --}}
        <section class="py-24 bg-gray-900 text-white relative overflow-hidden">
            <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-gray-700 to-transparent"></div>
            <div class="absolute -right-60 -top-60 w-[600px] h-[600px] bg-green-500/10 blur-[100px] rounded-full pointer-events-none"></div>
            <div class="absolute -left-60 -bottom-60 w-[600px] h-[600px] bg-blue-500/10 blur-[100px] rounded-full pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
                <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up">
                    <h2 class="text-sm font-bold text-green-400 tracking-widest uppercase mb-3">Satu Aplikasi, Berbagai Peran</h2>
                    <h3 class="text-3xl md:text-4xl font-bold tracking-tight mb-4">Didesain untuk Tim Anda</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Guru Card -->
                    <div class="bg-gray-800/50 backdrop-blur border border-gray-700 p-8 rounded-2xl hover:bg-gray-800 transition-colors shadow-xl" data-aos="fade-up" data-aos-delay="0">
                        <div class="w-12 h-12 bg-gray-700 rounded-xl flex items-center justify-center text-blue-400 mb-6">
                            <i class="fas fa-chalkboard-teacher text-xl"></i>
                        </div>
                        <h4 class="text-xl font-bold mb-3 text-white">Untuk Guru</h4>
                        <ul class="space-y-4 text-sm text-gray-400">
                            <li class="flex gap-3"><i class="fas fa-check text-green-400 mt-0.5"></i> <span>Tak perlu mencari laboran untuk cek ketersediaan alat.</span></li>
                            <li class="flex gap-3"><i class="fas fa-check text-green-400 mt-0.5"></i> <span>Pemesanan dari rumah, kapan saja lewat HP.</span></li>
                            <li class="flex gap-3"><i class="fas fa-check text-green-400 mt-0.5"></i> <span>Notifikasi instan jika jadwal disetujui.</span></li>
                        </ul>
                    </div>

                    <!-- Laboran Card -->
                    <div class="bg-gray-800/50 backdrop-blur border border-gray-700 p-8 rounded-2xl hover:bg-gray-800 transition-colors shadow-xl relative" data-aos="fade-up" data-aos-delay="100">
                        <div class="absolute -top-4 -right-4 bg-green-500 text-gray-900 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest shadow-lg">Paling Terbantu</div>
                        <div class="w-12 h-12 bg-gray-700 rounded-xl flex items-center justify-center text-green-400 mb-6">
                            <i class="fas fa-wrench text-xl"></i>
                        </div>
                        <h4 class="text-xl font-bold mb-3 text-white">Untuk Laboran</h4>
                        <ul class="space-y-4 text-sm text-gray-400">
                            <li class="flex gap-3"><i class="fas fa-check text-green-400 mt-0.5"></i> <span>Rekap alat masuk/keluar otomatis & akurat.</span></li>
                            <li class="flex gap-3"><i class="fas fa-check text-green-400 mt-0.5"></i> <span>Jadwal lab terpantau satu pintu tanpa bentrok.</span></li>
                            <li class="flex gap-3"><i class="fas fa-check text-green-400 mt-0.5"></i> <span>Ekspor laporan bulanan inventaris dalam 1-klik.</span></li>
                        </ul>
                    </div>

                    <!-- Siswa / OSIS Card -->
                    <div class="bg-gray-800/50 backdrop-blur border border-gray-700 p-8 rounded-2xl hover:bg-gray-800 transition-colors shadow-xl" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-12 h-12 bg-gray-700 rounded-xl flex items-center justify-center text-orange-400 mb-6">
                            <i class="fas fa-user-graduate text-xl"></i>
                        </div>
                        <h4 class="text-xl font-bold mb-3 text-white">Untuk Siswa</h4>
                        <ul class="space-y-4 text-sm text-gray-400">
                            <li class="flex gap-3"><i class="fas fa-check text-green-400 mt-0.5"></i> <span>Akses pustaka SOP lab tanpa repot tanya guru.</span></li>
                            <li class="flex gap-3"><i class="fas fa-check text-green-400 mt-0.5"></i> <span>Alur meminjam proyektor/alat untuk ekskul lebih tertib.</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        {{-- BEFORE VS AFTER SECTION (NEW) --}}
        <section class="py-24 bg-slate-50 border-y border-gray-100 overflow-hidden">
            <div class="max-w-6xl mx-auto px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up">
                    <h2 class="text-sm font-bold text-gray-500 tracking-widest uppercase mb-3">Transformasi</h2>
                    <h3 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-tight">Tinggalkan Cara Lama</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-0 bg-white rounded-3xl shadow-xl border border-gray-200 overflow-hidden" data-aos="fade-up">
                    <!-- Before (Cara Manual) -->
                    <div class="p-10 md:p-14 bg-gray-50 border-b md:border-b-0 md:border-r border-gray-200 grayscale relative">
                        <div class="absolute top-0 right-0 p-6 opacity-10 text-gray-900"><i class="fas fa-times-circle text-6xl"></i></div>
                        <h4 class="text-2xl font-bold text-gray-500 mb-8 flex items-center gap-3">
                            <span class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-lg"><i class="fas fa-pen-nib"></i></span>
                            Cara Manual
                        </h4>
                        <ul class="space-y-6">
                            <li class="flex items-start gap-4 text-gray-500 font-medium line-through decoration-gray-300">
                                <i class="fas fa-times text-gray-400 mt-1"></i>
                                Kertas rekap tercecer & hilang
                            </li>
                            <li class="flex items-start gap-4 text-gray-500 font-medium line-through decoration-gray-300">
                                <i class="fas fa-times text-gray-400 mt-1"></i>
                                Tanda tangan Kepala Lab butuh berhari-hari
                            </li>
                            <li class="flex items-start gap-4 text-gray-500 font-medium line-through decoration-gray-300">
                                <i class="fas fa-times text-gray-400 mt-1"></i>
                                Pembaruan stok harus tulis tangan
                            </li>
                            <li class="flex items-start gap-4 text-gray-500 font-medium line-through decoration-gray-300">
                                <i class="fas fa-times text-gray-400 mt-1"></i>
                                Sulit mencari data peminjaman bulan lalu
                            </li>
                        </ul>
                    </div>

                    <!-- After (Digital) -->
                    <div class="p-10 md:p-14 bg-gradient-to-br from-white to-green-50 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-6 opacity-10 text-green-600"><i class="fas fa-check-circle text-6xl"></i></div>
                        <h4 class="text-2xl font-bold text-green-700 mb-8 flex items-center gap-3 relative z-10">
                            <span class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-lg shadow-sm"><i class="fas fa-rocket text-green-600"></i></span>
                            Dengan SimLab
                        </h4>
                        <ul class="space-y-6 relative z-10">
                            <li class="flex items-start gap-4 text-gray-900 font-bold">
                                <div class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center shrink-0 mt-0.5"><i class="fas fa-check text-xs"></i></div>
                                Data digital tersimpan rahasia & aman di cloud
                            </li>
                            <li class="flex items-start gap-4 text-gray-900 font-bold">
                                <div class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center shrink-0 mt-0.5"><i class="fas fa-check text-xs"></i></div>
                                Persetujuan (Approve) cukup sekali klik dari HP
                            </li>
                            <li class="flex items-start gap-4 text-gray-900 font-bold">
                                <div class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center shrink-0 mt-0.5"><i class="fas fa-check text-xs"></i></div>
                                Stok dan rekap masuk otomatis update
                            </li>
                            <li class="flex items-start gap-4 text-gray-900 font-bold">
                                <div class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center shrink-0 mt-0.5"><i class="fas fa-check text-xs"></i></div>
                                Filter laporan ekspor instan ke Excel
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        {{-- CTA BOTTOM --}}
        <section class="py-32 relative bg-white" data-aos="zoom-in">
            <div class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-green-500 to-transparent opacity-50"></div>
            <div class="max-w-4xl mx-auto text-center px-6 relative z-10">
                <span class="px-4 py-2 bg-green-50 text-green-700 font-bold text-xs rounded-full mb-6 inline-block border border-green-100 uppercase tracking-widest shadow-sm">Siap beralih ke era digital?</span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight mb-6">{{ __('welcome.cta_bottom.title') }}</h2>
                <p class="text-xl text-gray-600 mb-10 max-w-2xl mx-auto">{{ __('welcome.cta_bottom.subtitle') }}</p>
                
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <button @click="isModalOpen = true" class="px-10 py-4 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 hover:shadow-xl hover:shadow-green-600/20 hover:-translate-y-1 transition-all duration-300 text-lg">
                        Mulai Digitalisasi Sekarang
                    </button>
                    <button @click="isModalOpen = true" class="px-10 py-4 bg-white text-gray-900 border-2 border-gray-200 font-bold rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all duration-300 text-lg">
                        {{ __('welcome.cta_bottom.admin_login') }}
                    </button>
                </div>
            </div>
            
            <!-- Abstract decor -->
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom,_var(--tw-gradient-stops))] from-green-50 via-transparent to-transparent -z-10"></div>
        </section>

        {{-- FOOTER --}}
        <footer class="bg-slate-50 border-t border-gray-200 py-16">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="flex flex-col items-center md:items-start gap-4">
                    <div class="flex items-center gap-3">
                        <img class="h-10 w-auto" src="{{ asset('images/logo-smaba.webp') }}" alt="Logo">
                        <span class="text-xl font-bold text-gray-900 tracking-tight">LAB-SMABA</span>
                    </div>
                    <p class="text-sm text-gray-500 text-center md:text-left max-w-sm">Sistem Informasi Manajemen Laboratorium terpadu untuk efisiensi, keamanan, dan akurasi instansi sekolah.</p>
                </div>
                
                <div class="flex flex-col items-center md:items-end gap-6 text-sm">
                    <div class="flex flex-wrap justify-center md:justify-end gap-6 text-gray-600 font-medium">
                        <span class="cursor-not-allowed hover:text-green-600 transition-colors">{{ __('welcome.footer.privacy') }}</span>
                        <span class="cursor-not-allowed hover:text-green-600 transition-colors">{{ __('welcome.footer.terms') }}</span>
                        <a href="mailto:lab@smaba.sch.id" class="hover:text-green-600 transition-colors">{{ __('welcome.footer.contact') }}</a>
                    </div>
                    <p class="text-gray-400">&copy; {{ date('Y') }} SMA Negeri 1 Babat. Hak Cipta Dilindungi.</p>
                </div>
            </div>
        </footer>
EOD;

$final_text = $head_part . $new_body . $tail_part;
file_put_contents($file_path, $final_text);
echo "Rewrote blade view successfully!\n";
