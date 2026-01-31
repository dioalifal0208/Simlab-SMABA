<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Validasi Dokumen Resmi - Lab SMABA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-pattern {
            background-color: #f8fafc;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23e2e8f0' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-pattern min-h-screen flex items-center justify-center p-4">

    <div class="max-w-lg w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100 relative">
        
        {{-- Status Indicator Bar --}}
        <div class="h-2 w-full {{ $booking->status == 'approved' || $booking->status == 'completed' ? 'bg-gradient-to-r from-green-400 to-emerald-600' : 'bg-gradient-to-r from-red-500 to-pink-600' }}"></div>

        <div class="p-8 pb-6 text-center relative">
            {{-- Watermark Effect --}}
            <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center pointer-events-none opacity-[0.03]">
                 <img src="{{ asset('images/logo-smaba.webp') }}" class="w-64 h-64 grayscale" alt="Watermark">
            </div>

            {{-- Main Logo --}}
            <div class="relative z-10">
                <img src="{{ asset('images/logo-smaba.webp') }}" alt="Logo SMABA" class="h-20 w-auto mx-auto mb-6 drop-shadow-sm hover:scale-105 transition-transform duration-300">
                
                @if($booking->status == 'approved' || $booking->status == 'completed')
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-bold mb-4">
                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        TERVERIFIKASI / VALID
                    </div>
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Dokumen Resmi</h1>
                    <p class="text-slate-500 mt-2 text-sm leading-relaxed">Dokumen ini telah diterbitkan dan ditandatangani secara elektronik oleh sistem Lab SMABA.</p>
                @else
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-red-50 border border-red-100 text-red-700 text-sm font-bold mb-4">
                        <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                        TIDAK VALID / DIBATALKAN
                    </div>
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Dokumen Tidak Valid</h1>
                    <p class="text-slate-500 mt-2 text-sm">Maaf, dokumen yang Anda cari tidak ditemukan atau statusnya tidak valid.</p>
                @endif
            </div>
        </div>

        {{-- Details Card --}}
        <div class="px-8 py-6 bg-slate-50 border-t border-slate-100">
            <div class="grid gap-6 relative z-10">
                
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-start gap-4">
                    <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">PEMINJAM</p>
                        <p class="font-bold text-slate-800 text-lg">{{ $booking->user->name }}</p>
                        <p class="text-xs text-slate-500">{{ $booking->user->nomor_induk ?? 'NIP/NIS Tidak Tersedia' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">LABORATORIUM</p>
                        <p class="font-semibold text-slate-800">{{ $booking->laboratorium }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">TANGGAL</p>
                        <p class="font-semibold text-slate-800">{{ $booking->waktu_mulai->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="text-center pt-4 border-t border-slate-200/60">
                    <p class="text-xs text-slate-400 mb-1">Signed Digitally By</p>
                    <p class="font-bold text-slate-800">Kepala Laboratorium SMA Negeri 1 Babat</p>
                    <p class="font-mono text-[10px] text-slate-400 mt-2 tracking-widest">{{ strtoupper($booking->updated_at->format('d M Y H:i:s')) }} • ID: {{ $booking->id }}</p>
                </div>

            </div>
        </div>

        {{-- Footer --}}
        <div class="bg-slate-100 px-6 py-4 flex items-center justify-between text-xs text-slate-500">
             <span>&copy; {{ date('Y') }} Lab SMABA</span>
             <a href="{{ url('/') }}" class="hover:text-blue-600 font-medium transition-colors">Ke Website Utama &rarr;</a>
        </div>
    </div>

</body>
</html>
