@props([
    'title',
    'description' => null,
    'icon' => null
])

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8" data-aos="fade-up" data-aos-once="true">
    @if($title || $description)
    <div class="p-6 border-b border-slate-50/50 bg-slate-50/30">
        <h3 class="text-lg font-bold text-slate-800 tracking-tight flex items-center gap-2">
            @if($icon)
                <i class="fas {{ $icon }} text-indigo-500"></i>
            @endif
            {{ $title }}
        </h3>
        @if($description)
            <p class="text-sm text-slate-500 mt-1 font-medium">{{ $description }}</p>
        @endif
    </div>
    @endif
    
    <div class="p-6 sm:p-8">
        {{ $slot }}
    </div>
</div>
