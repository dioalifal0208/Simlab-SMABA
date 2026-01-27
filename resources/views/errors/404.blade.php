<x-app-layout :hide-chrome="true">
    <div class="w-full max-w-xl mx-auto flex items-center justify-center py-16 px-4">
        <div class="w-full text-center" data-aos="fade-up" data-aos-duration="700">
            {{-- Badge status --}}
            <div class="inline-flex items-center justify-center rounded-full bg-smaba-dark-blue/5 text-smaba-dark-blue mb-6 px-3 py-1 text-xs font-semibold">
                <span class="mr-2 flex h-6 w-6 items-center justify-center rounded-full bg-smaba-dark-blue text-white">
                    <i class="fas fa-triangle-exclamation text-xs"></i>
                </span>
                <span>Ups, halaman tidak ditemukan</span>
            </div>

            {{-- Kode 404 yang menonjol --}}
            <h1 class="text-5xl sm:text-6xl font-extrabold text-gray-900 tracking-tight">
                4<span class="text-smaba-dark-blue">0</span>4
            </h1>

            {{-- Pesan utama --}}
            <p class="mt-4 text-base sm:text-lg text-gray-600">
                Halaman yang Anda cari tidak tersedia atau mungkin sudah dipindahkan.
            </p>
            <p class="mt-1 text-sm text-gray-500">
                Periksa kembali alamat URL, atau gunakan tombol di bawah ini untuk melanjutkan.
            </p>

            {{-- Aksi utama dan sekunder --}}
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-smaba-dark-blue text-white text-sm font-semibold shadow-sm hover:bg-smaba-light-blue transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Kembali ke Dashboard
                </a>
                <button
                    type="button"
                    onclick="window.history.back()"
                    class="inline-flex items-center px-5 py-2.5 rounded-lg border border-gray-300 bg-white text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition-colors"
                >
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Halaman Sebelumnya
                </button>
            </div>

            {{-- Bantuan tambahan --}}
            <div class="mt-10 max-w-md mx-auto">
                <div class="flex items-start gap-3 text-left text-xs sm:text-sm text-gray-500 bg-white/60 border border-gray-200 rounded-lg p-3 sm:p-4">
                    <span class="mt-0.5 text-smaba-dark-blue">
                        <i class="fas fa-lightbulb"></i>
                    </span>
                    <p>
                        Jika Anda merasa ini adalah kesalahan sistem, silakan hubungi admin laboratorium dan sertakan alamat halaman yang Anda akses.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
