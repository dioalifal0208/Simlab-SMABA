@props([
    'name',
    'id' => null,
    'label' => '',
    'type' => 'text',
    'placeholder' => '',
    'helper' => '',
    'required' => false,
    'value' => '',
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

        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $id }}" 
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge([
                'class' => 'block w-full rounded-xl transition-all duration-200 text-sm sm:text-base ' . 
                ($icon ? 'pl-10 ' : 'pl-4 ') . 
                'pr-4 py-2.5 ' .
                ($hasError 
                    ? 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-4 focus:ring-red-500/20 bg-red-50' 
                    : 'border-slate-200 text-slate-900 placeholder-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 bg-slate-50 hover:bg-white focus:bg-white')
            ]) }}
        />
        
        @if($hasError)
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
        @endif
    </div>

    @if($hasError)
        <p class="text-sm font-semibold text-red-600 mt-1 animate-pulse">{{ $errors->first($name) }}</p>
    @elseif($helper)
        <p class="text-[11px] font-medium text-slate-500 mt-1.5">{{ $helper }}</p>
    @endif
</div>
