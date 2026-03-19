@props([
    'submitText' => 'Simpan Data',
    'cancelUrl' => '#',
    'submitIcon' => 'fa-check',
    'cancelText' => 'Batal',
    'isFormValidObject' => null // Optional alpine binding to disable
])

<div class="sticky bottom-0 z-40 bg-white/80 backdrop-blur-md border-t border-slate-200 p-4 sm:px-8 sm:py-5 flex flex-col-reverse sm:flex-row justify-between items-center gap-4 shadow-[0_-4px_20px_-10px_rgba(0,0,0,0.1)] mt-12 transition-all">
    <div class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest flex items-center gap-2 w-full sm:w-auto justify-center sm:justify-start">
        <i class="fas fa-shield-alt text-slate-300"></i> Sistem Aman terenkripsi
    </div>

    <div class="flex items-center gap-3 w-full sm:w-auto">
        <a href="{{ $cancelUrl }}" class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-colors text-center shadow-sm">
            {{ $cancelText }}
        </a>
        
        <button type="submit" 
            {{ $isFormValidObject ? "x-bind:disabled=!{$isFormValidObject}" : '' }}
            {{ $attributes->merge(['class' => 'w-full sm:w-auto inline-flex justify-center items-center px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-500 focus:bg-indigo-700 shadow-[0_4px_14px_0_rgba(79,70,229,0.39)] hover:shadow-[0_6px_20px_rgba(79,70,229,0.23)] transition-all transform hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none']) }}>
            <i class="fas {{ $submitIcon }} mr-2"></i> {{ $submitText }}
        </button>
    </div>
</div>
