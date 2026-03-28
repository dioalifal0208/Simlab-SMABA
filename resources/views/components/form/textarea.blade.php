@props([
    'name',
    'id' => null,
    'label' => '',
    'placeholder' => '',
    'helper' => '',
    'required' => false,
    'value' => '',
    'rows' => 4
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
                <span class="text-red-500 ml-0.5" title="Wajib">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <textarea 
            name="{{ $name }}" 
            id="{{ $id }}" 
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge([
                'class' => 'block w-full rounded-xl transition-all duration-200 text-sm sm:text-base px-4 py-3 resize-y ' .
                ($hasError 
                    ? 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-4 focus:ring-red-500/20 bg-red-50' 
                    : 'border-slate-200 text-slate-900 placeholder-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 bg-slate-50 hover:bg-white focus:bg-white')
            ]) }}
        >{{ old($name, $value) }}</textarea>
        
        @if($hasError)
            <div class="absolute top-3 right-3 flex items-center pointer-events-none">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
        @endif
    </div>

    @if($hasError)
        <p class="text-sm font-semibold text-red-600 mt-1">{{ $errors->first($name) }}</p>
    @elseif($helper)
        <p class="text-[11px] font-medium text-slate-500 mt-1.5">{{ $helper }}</p>
    @endif
</div>
