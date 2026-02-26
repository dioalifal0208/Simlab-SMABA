{{-- Inline Edit Component --}}
{{-- Usage: @include('components.inline-edit', ['value' => $item->jumlah, 'route' => route('items.update', $item), 'field' => 'jumlah']) --}}

@php
    $value = $value ?? '';
    $route = $route ?? '#';
    $field = $field ?? 'value';
    $type = $type ?? 'text'; // text, number, textarea
@endphp

<div 
    x-data="inlineEdit('{{ $value }}', '{{ $route }}', '{{ $field }}')"
    class="inline-block"
>
    {{-- Display Mode --}}
    <div x-show="!editing" @click="startEdit()" class="cursor-pointer hover:bg-gray-100 px-2 py-1 rounded transition-colors">
        <span x-text="value || '-'"></span>
        <i class="fas fa-pencil-alt text-xs text-gray-400 ml-2"></i>
    </div>
    
    {{-- Edit Mode --}}
    <div x-show="editing" x-cloak class="inline-flex items-center gap-2">
        @if($type === 'textarea')
            <textarea 
                x-ref="input"
                x-model="value"
                @keydown="handleKeydown($event)"
                @blur="save()"
                rows="2"
                class="border border-green-500 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
            ></textarea>
        @else
            <input 
                type="{{ $type }}"
                x-ref="input"
                x-model="value"
                @keydown="handleKeydown($event)"
                @blur="save()"
                class="border border-green-500 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-green-500 w-32 text-sm"
            >
        @endif
        
        <div class="flex gap-1">
            <button 
                @click="save()" 
                :disabled="saving"
                class="text-green-600 hover:text-green-700 disabled:opacity-50"
                title="Save (Enter)"
            >
                <i class="fas fa-check"></i>
            </button>
            <button 
                @click="cancel()" 
                :disabled="saving"
                class="text-red-600 hover:text-red-700 disabled:opacity-50"
                title="Cancel (Esc)"
            >
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <span x-show="saving" class="text-sm text-gray-500">
            <i class="fas fa-spinner fa-spin"></i>
        </span>
    </div>
    
    {{-- Error Message --}}
    <div x-show="error" x-text="error" class="text-xs text-red-600 mt-1"></div>
</div>

