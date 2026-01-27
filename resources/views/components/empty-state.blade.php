{{-- Empty State Component --}}
{{-- Usage: @include('components.empty-state', ['type' => 'items', 'message' => 'Custom message', 'action' => route('items.create')]) --}}

@php
    $type = $type ?? 'default';
    $message = $message ?? null;
    $actionText = $actionText ?? null;
    $action = $action ?? null;
    
    $emptyStates = [
        'items' => [
            'icon' => '<svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>',
            'title' => 'Belum Ada Item',
            'message' => 'Mulai tambahkan alat dan bahan laboratorium ke inventaris.',
            'actionText' => '+ Tambah Item',
        ],
        'loans' => [
            'icon' => '<svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
            'title' => 'Belum Ada Peminjaman',
            'message' => 'Ajukan peminjaman alat dan bahan untuk praktikum Anda.',
            'actionText' => '+ Buat Peminjaman',
        ],
        'bookings' => [
            'icon' => '<svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
            'title' => 'Belum Ada Booking',
            'message' => 'Reservasi jadwal laboratorium untuk kegiatan praktikum.',
            'actionText' => '+ Ajukan Booking',
        ],
        'documents' => [
            'icon' => '<svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
            'title' => 'Belum Ada Dokumen',
            'message' => 'Pustaka digital Anda masih kosong. Unggah dokumen untuk memulai.',
            'actionText' => '+ Unggah Dokumen',
        ],
        'search' => [
            'icon' => '<svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>',
            'title' => 'Tidak Ada Hasil',
            'message' => 'Tidak menemukan data yang cocok. Coba ubah filter pencarian.',
            'actionText' => null,
        ],
        'default' => [
            'icon' => '<svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>',
            'title' => 'Tidak Ada Data',
            'message' => 'Belum ada data yang tersedia.',
            'actionText' => null,
        ],
    ];
    
    $state = $emptyStates[$type] ?? $emptyStates['default'];
    $finalMessage = $message ?? $state['message'];
    $finalActionText = $actionText ?? $state['actionText'];
@endphp

<div class="text-center py-12 px-4">
    <div class="inline-block">
        {!! $state['icon'] !!}
    </div>
    <h3 class="mt-4 text-lg font-semibold text-gray-900">{{ $state['title'] }}</h3>
    <p class="mt-2 text-sm text-gray-500 max-w-md mx-auto">{{ $finalMessage }}</p>
    
    @if($action && $finalActionText)
        <div class="mt-6">
            <a href="{{ $action }}" class="inline-flex items-center px-4 py-2 bg-smaba-dark-blue text-white text-sm font-semibold rounded-lg hover:bg-smaba-light-blue shadow-sm transition-colors">
                {{ $finalActionText }}
            </a>
        </div>
    @endif
</div>
