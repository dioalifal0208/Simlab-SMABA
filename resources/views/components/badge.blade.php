{{-- Status Badge Component --}}
{{-- Usage: @include('components.badge', ['status' => 'approved', 'text' => 'Disetujui']) --}}

@php
    $status = $status ?? 'default';
    $text = $text ?? ucfirst($status);
    $size = $size ?? 'md'; // sm, md, lg
    $withIcon = $withIcon ?? true;
    
    $badgeConfig = [
        // Loan statuses
        'pending' => [
            'bg' => 'bg-gradient-to-r from-yellow-400 to-yellow-600',
            'text' => 'text-white',
            'icon' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        ],
        'approved' => [
            'bg' => 'bg-gradient-to-r from-green-400 to-green-600',
            'text' => 'text-white',
            'icon' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
        ],
        'rejected' => [
            'bg' => 'bg-gradient-to-r from-red-400 to-red-600',
            'text' => 'text-white',
            'icon' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
        ],
        'completed' => [
            'bg' => 'bg-gradient-to-r from-gray-400 to-gray-600',
            'text' => 'text-white',
            'icon' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        ],
        'Terlambat' => [
            'bg' => 'bg-gradient-to-r from-red-500 to-red-700',
            'text' => 'text-white',
            'icon' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
        ],
        
        // Condition statuses
        'Baik' => [
            'bg' => 'bg-gradient-to-r from-emerald-400 to-emerald-600',
            'text' => 'text-white',
            'icon' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        ],
        'Rusak' => [
            'bg' => 'bg-gradient-to-r from-red-400 to-red-600',
            'text' => 'text-white',
            'icon' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
        ],
        
        // Generic statuses
        'active' => [
            'bg' => 'bg-gradient-to-r from-blue-400 to-blue-600',
            'text' => 'text-white',
            'icon' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>',
        ],
        'inactive' => [
            'bg' => 'bg-gradient-to-r from-gray-300 to-gray-500',
            'text' => 'text-white',
            'icon' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>',
        ],
        'default' => [
            'bg' => 'bg-gradient-to-r from-gray-400 to-gray-600',
            'text' => 'text-white',
            'icon' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>',
        ],
    ];
    
    $config = $badgeConfig[$status] ?? $badgeConfig['default'];
    
    $sizeClasses = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-xs',
        'lg' => 'px-4 py-1.5 text-sm',
    ];
    
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<span class="inline-flex items-center gap-1 {{ $sizeClass }} {{ $config['bg'] }} {{ $config['text'] }} font-semibold rounded-full shadow-sm">
    @if($withIcon)
        <span class="flex-shrink-0">
            {!! $config['icon'] !!}
        </span>
    @endif
    <span>{{ $text }}</span>
</span>

