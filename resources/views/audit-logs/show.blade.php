<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('audit-logs.index') }}" class="text-slate-400 hover:text-indigo-600 transition-colors w-10 h-10 flex items-center justify-center bg-white rounded-full shadow-sm border border-slate-200">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">Detail Audit Log</h2>
                <p class="text-sm font-medium text-slate-500 mt-1">Log #{{ $auditLog->id }} — {{ $auditLog->created_at->format('d F Y, H:i:s') }}</p>
            </div>
        </div>
    </x-slot>

    @php
        $actionStyles = [
            'created' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100', 'icon' => 'fa-plus', 'label' => 'CREATED'],
            'updated' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-100', 'icon' => 'fa-pen', 'label' => 'UPDATED'],
            'deleted' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'border' => 'border-red-100', 'icon' => 'fa-trash', 'label' => 'DELETED'],
            'login' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-100', 'icon' => 'fa-arrow-right-to-bracket', 'label' => 'LOGIN'],
            'logout' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-500', 'border' => 'border-slate-200', 'icon' => 'fa-arrow-right-from-bracket', 'label' => 'LOGOUT'],
            'failed_login' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-100', 'icon' => 'fa-triangle-exclamation', 'label' => 'FAILED LOGIN'],
        ];
        $s = $actionStyles[$auditLog->action] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-500', 'border' => 'border-slate-200', 'icon' => 'fa-circle', 'label' => strtoupper($auditLog->action)];
    @endphp

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- SUMMARY CARD --}}
            <x-form.section title="Informasi Log" icon="fa-info-circle">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">User</p>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-slate-500 to-slate-700 flex items-center justify-center text-white text-sm font-bold">{{ $auditLog->user ? strtoupper(substr($auditLog->user->name, 0, 1)) : '?' }}</div>
                            <div class="text-sm font-bold text-slate-800">{{ $auditLog->user ? $auditLog->user->name : 'System' }}</div>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Aksi</p>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold tracking-wide border {{ $s['bg'] }} {{ $s['text'] }} {{ $s['border'] }}">
                            <i class="fas {{ $s['icon'] }} text-[9px]"></i> {{ $s['label'] }}
                        </span>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Modul / Model</p>
                        <div class="text-sm font-bold text-slate-800">{{ $auditLog->getModelName() }} <span class="text-slate-400 font-mono text-xs">#{{ $auditLog->model_id ?? '-' }}</span></div>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Waktu</p>
                        <div class="text-sm font-bold text-slate-800">{{ $auditLog->created_at->format('d F Y, H:i:s') }}</div>
                        <div class="text-[11px] text-slate-400 font-medium">{{ $auditLog->created_at->diffForHumans() }}</div>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">IP Address</p>
                        <div class="text-sm text-slate-700 font-mono">{{ $auditLog->ip_address ?? '-' }}</div>
                    </div>
                    @if($auditLog->user_agent)
                    <div class="md:col-span-2">
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">User Agent</p>
                        <div class="text-xs text-slate-500 bg-slate-50 p-3 rounded-xl border border-slate-100 font-mono break-all">{{ $auditLog->user_agent }}</div>
                    </div>
                    @endif
                </div>
            </x-form.section>

            {{-- DIFF VIEWER --}}
            @if($auditLog->details)
            <x-form.section title="Detail Perubahan (Diff Viewer)" icon="fa-code-compare">
                @if(isset($auditLog->details['old']) || isset($auditLog->details['new']))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- BEFORE --}}
                        @if(isset($auditLog->details['old']) && $auditLog->action === 'updated')
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center"><i class="fas fa-minus text-red-500 text-[10px]"></i></div>
                                <h4 class="text-xs font-extrabold text-red-700 uppercase tracking-widest">Nilai Lama (Before)</h4>
                            </div>
                            <div class="bg-red-50/50 rounded-xl border border-red-100 overflow-hidden">
                                @foreach($auditLog->details['old'] as $key => $value)
                                    <div class="px-4 py-2.5 border-b border-red-100/50 last:border-b-0 flex justify-between items-start gap-4">
                                        <span class="text-[11px] font-bold text-red-400 uppercase flex-shrink-0">{{ $key }}</span>
                                        <span class="text-xs font-medium text-red-700 text-right break-all">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- AFTER --}}
                        @if(isset($auditLog->details['new']))
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center"><i class="fas fa-plus text-emerald-500 text-[10px]"></i></div>
                                <h4 class="text-xs font-extrabold text-emerald-700 uppercase tracking-widest">Nilai Baru (After)</h4>
                            </div>
                            <div class="bg-emerald-50/50 rounded-xl border border-emerald-100 overflow-hidden">
                                @foreach($auditLog->details['new'] as $key => $value)
                                    @php
                                        $hasChanged = isset($auditLog->details['old'][$key]) && $auditLog->details['old'][$key] != $value;
                                    @endphp
                                    <div class="px-4 py-2.5 border-b border-emerald-100/50 last:border-b-0 flex justify-between items-start gap-4 {{ $hasChanged ? 'bg-emerald-100/50' : '' }}">
                                        <span class="text-[11px] font-bold text-emerald-400 uppercase flex-shrink-0">{{ $key }}</span>
                                        <span class="text-xs font-medium text-emerald-700 text-right break-all {{ $hasChanged ? 'font-extrabold' : '' }}">{{ is_array($value) ? json_encode($value) : $value }}
                                            @if($hasChanged) <i class="fas fa-circle text-[6px] text-emerald-500 ml-1"></i> @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                @else
                    <pre class="text-xs bg-slate-50 p-4 rounded-xl border border-slate-100 overflow-x-auto font-mono text-slate-600">{{ json_encode($auditLog->details, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                @endif
            </x-form.section>
            @endif

            {{-- BACK --}}
            <div class="flex justify-start">
                <a href="{{ route('audit-logs.index') }}" class="inline-flex items-center px-5 py-2.5 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-colors text-sm"><i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Log</a>
            </div>
        </div>
    </div>
</x-app-layout>
