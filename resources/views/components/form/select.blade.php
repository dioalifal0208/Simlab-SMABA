@props([
    'name',
    'id' => null,
    'label' => '',
    'helper' => '',
    'required' => false,
    'icon' => null
])

@php
    $id = $id ?? $name;
    $hasError = $errors->has($name);
@endphp

<div class="space-y-1">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-bold text-slate-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-0.5" title="Wajib diisi">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fas {{ $icon }}"></i>
            </div>
        @endif

        <select 
            name="{{ $name }}" 
            id="{{ $id }}" 
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge([
                'class' => 'block w-full rounded-xl transition-all duration-200 text-sm sm:text-base appearance-none ' . 
                ($icon ? 'pl-10 ' : 'pl-4 ') . 
                'pr-10 py-2.5 ' .
                ($hasError 
                    ? 'border-red-300 text-red-900 focus:border-red-500 focus:ring-4 focus:ring-red-500/20 bg-red-50' 
                    : 'border-slate-200 text-slate-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 bg-slate-50 hover:bg-white focus:bg-white')
            ]) }}
        >
            {{ $slot }}
        </select>
        
        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
            <i class="fas fa-chevron-down text-xs"></i>
        </div>
    </div>

    @if($hasError)
        <p class="text-sm font-semibold text-red-600 mt-1">{{ $errors->first($name) }}</p>
    @elseif($helper)
        <p class="text-[11px] font-medium text-slate-500 mt-1.5">{{ $helper }}</p>
    @endif
</div>
