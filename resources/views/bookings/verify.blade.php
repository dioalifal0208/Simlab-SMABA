<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Dokumen - Lab SMABA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-center p-4">

    <div class="bg-white max-w-md w-full rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        
        {{-- Header Status --}}
        <div class="text-center p-8 {{ $booking->status == 'approved' ? 'bg-green-50' : ($booking->status == 'completed' ? 'bg-gray-50' : 'bg-red-50') }}">
            
            <img src="{{ asset('images/logo-smaba.webp') }}" alt="Logo SMABA" class="h-16 w-auto mx-auto mb-4">
            
            @if($booking->status == 'approved' || $booking->status == 'completed')
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4 animate-bounce">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Dokumen Valid</h1>
                <p class="text-green-600 font-medium mt-1">Disetujui Secara Resmi</p>
                <p class="text-xs text-gray-500 mt-2">Diverifikasi Sistem Lab SMABA</p>
            @else
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Status Tidak Valid</h1>
                <p class="text-red-600 font-medium mt-1">Dokumen ini belum disetujui atau dibatalkan.</p>
            @endif
        </div>

        {{-- Detail Dokumen --}}
        <div class="p-6 space-y-4">
            <div class="border-b border-gray-100 pb-4">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Nomor Dokumen</p>
                <p class="font-mono text-lg text-gray-800">BOOKING-#{{ $booking->id }}</p>
            </div>

            <div class="border-b border-gray-100 pb-4">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Peminjam</p>
                <div class="flex items-center">
                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold mr-3">
                        {{ substr($booking->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $booking->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $booking->user->nomor_induk ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 border-b border-gray-100 pb-4">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Laboratorium</p>
                    <p class="text-gray-800 font-medium">{{ $booking->laboratorium }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Tanggal</p>
                    <p class="text-gray-800 font-medium">{{ $booking->waktu_mulai->format('d M Y') }}</p>
                </div>
            </div>
            
            <div class="pt-2">
                 <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Ditandatangani Secara Elektronik Oleh:</p>
                 <p class="text-sm font-semibold text-gray-800">Kepala Laboratorium SMA Negeri 1 Babat</p>
                 <p class="text-xs text-gray-500 mt-1">Tanggal Tanda Tangan: {{ $booking->updated_at->format('d F Y H:i:s') }}</p>
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-4 text-center">
             <p class="text-xs text-gray-400">© {{ date('Y') }} SMA Negeri 1 Babat. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
