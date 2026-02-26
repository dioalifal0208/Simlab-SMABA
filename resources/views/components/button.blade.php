{{-- Button Component --}}
{{-- Usage: @include('components.button', ['variant' => 'primary', 'text' => 'Save', 'href' => route('items.index')]) --}}

@php
    $variant = $variant ?? 'primary';
    $size = $size ?? 'md';
    $type = $type ?? 'button'; // button, submit, link
    $href = $href ?? null;
    $text = $text ?? 'Button';
    $icon = $icon ?? null; // fa-save, fa-plus, etc
    $iconPosition = $iconPosition ?? 'left'; // left, right
    $fullWidth = $fullWidth ?? false;
    $disabled = $disabled ?? false;
    $class = $class ?? '';
    
    $variants = [
        'primary' => 'bg-blue-700 hover:bg-blue-800 text-white shadow-sm border border-blue-800',
        'secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-800 shadow-sm',
        'success' => 'bg-green-600 hover:bg-green-700 text-white shadow-sm',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white shadow-sm',
        'warning' => 'bg-yellow-500 hover:bg-yellow-600 text-white shadow-sm',
        'info' => 'bg-green-500 hover:bg-green-600 text-white shadow-sm',
        'outline' => 'bg-white border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-600 hover:text-white',
        'ghost' => 'bg-transparent hover:bg-gray-100 text-gray-700',
    ];
    
    $sizes = [
        'xs' => 'px-2 py-1 text-xs',
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
        'xl' => 'px-8 py-4 text-lg',
    ];
    
    $variantClass = $variants[$variant] ?? $variants['primary'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $widthClass = $fullWidth ? 'w-full justify-center' : '';
    $disabledClass = $disabled ? 'opacity-50 cursor-not-allowed pointer-events-none' : '';
    
    $baseClasses = "inline-flex items-center gap-2 font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500";
    $finalClasses = trim("$baseClasses $variantClass $sizeClass $widthClass $disabledClass $class");
@endphp

@if($type === 'link' && $href)
    <a href="{{ $href }}" class="{{ $finalClasses }}">
        @if($icon && $iconPosition === 'left')
            <i class="fas {{ $icon }}"></i>
        @endif
        <span>{{ $text }}</span>
        @if($icon && $iconPosition === 'right')
            <i class="fas {{ $icon }}"></i>
        @endif
    </a>
@else
    <button type="{{ $type }}" class="{{ $finalClasses }}" {{ $disabled ? 'disabled' : '' }}>
        @if($icon && $iconPosition === 'left')
            <i class="fas {{ $icon }}"></i>
        @endif
        <span>{{ $text }}</span>
        @if($icon && $iconPosition === 'right')
            <i class="fas {{ $icon }}"></i>
        @endif
    </button>
@endif

