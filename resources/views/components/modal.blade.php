@props(['show' => false])

<div
    x-show="{{ $show }}"
    x-on:keydown.escape.window="$dispatch('close')"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0"
    style="display: none;"
>
    {{-- Latar Belakang Gelap --}}
    <div x-show="{{ $show }}" x-transition.opacity.duration.300ms class="absolute inset-0 bg-gray-500/75"></div>

    {{-- Konten Modal --}}
    <div x-show="{{ $show }}" x-transition.scale.duration.300ms
         class="bg-white rounded-lg overflow-hidden shadow-xl sm:w-full sm:max-w-md"
         @click.away="$dispatch('close')">
        {{ $slot }}
    </div>
</div>
