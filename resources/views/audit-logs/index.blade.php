<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">Audit Trail</h2>
                <p class="text-sm font-medium text-slate-500 mt-1">Pantau seluruh aktivitas pengguna dan perubahan data di dalam sistem.</p>
            </div>
            <a href="{{ route('audit-logs.export', request()->query()) }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-500 font-bold text-sm shadow-[0_4px_14px_0_rgba(16,185,129,0.39)] transition-all hover:-translate-y-0.5">
                <i class="fas fa-download mr-2"></i> Export CSV
            </a>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ view: 'table' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- STICKY FILTER BAR --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sticky top-16 z-30" data-aos="fade-up">
                <form action="{{ route('audit-logs.index') }}" method="GET">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">User</label>
                            <select name="user_id" class="w-full rounded-xl text-sm border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 hover:bg-white focus:bg-white transition-all font-medium text-slate-600 py-2.5">
                                <option value="">Semua User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Aksi</label>
                            <select name="action" class="w-full rounded-xl text-sm border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 hover:bg-white focus:bg-white transition-all font-medium text-slate-600 py-2.5">
                                <option value="">Semua Aksi</option>
                                @foreach($actions as $action)
                                    <option value="{{ $action }}" @selected(request('action') == $action)>{{ ucfirst($action) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Model / Modul</label>
                            <input type="text" name="model" value="{{ request('model') }}" placeholder="Cari modul..." class="w-full rounded-xl text-sm border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 hover:bg-white focus:bg-white transition-all placeholder-slate-400 py-2.5">
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Dari</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-xl text-sm border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 hover:bg-white focus:bg-white transition-all font-medium text-slate-600 py-2.5">
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Sampai</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-xl text-sm border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 hover:bg-white focus:bg-white transition-all font-medium text-slate-600 py-2.5">
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <button type="submit" class="px-5 py-2.5 bg-slate-800 text-white rounded-xl font-bold text-sm hover:bg-slate-700 transition-colors shadow-sm"><i class="fas fa-filter mr-2"></i>Filter</button>
                            @if(request()->hasAny(['user_id', 'action', 'model', 'date_from', 'date_to']))
                                <a href="{{ route('audit-logs.index') }}" class="px-4 py-2.5 border border-slate-200 text-slate-500 rounded-xl font-bold text-sm hover:bg-slate-50 transition-colors">Reset</a>
                            @endif
                        </div>
                        {{-- VIEW TOGGLE --}}
                        <div class="flex items-center bg-slate-100 p-1 rounded-xl">
                            <button type="button" @click="view = 'table'" :class="view === 'table' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-400 hover:text-slate-600'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all"><i class="fas fa-table-list mr-1"></i> Tabel</button>
                            <button type="button" @click="view = 'timeline'" :class="view === 'timeline' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-400 hover:text-slate-600'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all"><i class="fas fa-timeline mr-1"></i> Timeline</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- TABLE VIEW --}}
            <div x-show="view === 'table'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-white overflow-hidden shadow-sm border border-slate-100 sm:rounded-2xl" data-aos="fade-up">
                    <div class="overflow-x-auto">
                        @if($logs->count() > 0)
                        <table class="w-full text-left border-collapse whitespace-nowrap">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">User</th>
                                    <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Aksi</th>
                                    <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Modul</th>
                                    <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">IP Address</th>
                                    <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Waktu</th>
                                    <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-right">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($logs as $log)
                                    <tr class="hover:bg-slate-50/80 transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-700 text-xs font-bold flex-shrink-0">
                                                    {{ $log->user ? strtoupper(substr($log->user->name, 0, 1)) : '?' }}
                                                </div>
                                                <span class="text-sm font-bold text-slate-800">{{ $log->user ? $log->user->name : 'System' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $actionStyles = [
                                                    'created' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                    'updated' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                    'deleted' => 'bg-red-50 text-red-600 border-red-100',
                                                    'login' => 'bg-purple-50 text-purple-600 border-purple-100',
                                                    'logout' => 'bg-slate-100 text-slate-500 border-slate-200',
                                                    'failed_login' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                ];
                                                $actionIcons = [
                                                    'created' => 'fa-plus',
                                                    'updated' => 'fa-pen',
                                                    'deleted' => 'fa-trash',
                                                    'login' => 'fa-arrow-right-to-bracket',
                                                    'logout' => 'fa-arrow-right-from-bracket',
                                                    'failed_login' => 'fa-triangle-exclamation',
                                                ];
                                                $style = $actionStyles[$log->action] ?? 'bg-slate-100 text-slate-500 border-slate-200';
                                                $icon = $actionIcons[$log->action] ?? 'fa-circle';
                                            @endphp
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold tracking-wide border {{ $style }}">
                                                <i class="fas {{ $icon }} text-[9px]"></i> {{ strtoupper($log->action) }}
                                            </span>
                                            @if($log->action === 'deleted' || $log->action === 'failed_login')
                                                <i class="fas fa-exclamation-circle text-red-400 text-[10px] ml-1" title="Aksi kritis"></i>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ $log->getModelName() }}</td>
                                        <td class="px-6 py-4 text-xs text-slate-400 font-mono">{{ $log->ip_address ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-slate-700">{{ $log->created_at->format('d M Y') }}</div>
                                            <div class="text-[11px] text-slate-400 font-medium">{{ $log->created_at->format('H:i:s') }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('audit-logs.show', $log) }}" class="inline-flex items-center justify-center p-2 text-slate-400 hover:text-indigo-600 hover:bg-slate-100 rounded-lg transition-colors" title="Lihat Detail">
                                                <i class="fas fa-chevron-right text-xs"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <div class="text-center py-16 px-4">
                                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                    <i class="fas fa-clock-rotate-left text-2xl text-slate-300"></i>
                                </div>
                                <h3 class="text-sm font-bold text-slate-700">Tidak Ada Log Ditemukan</h3>
                                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Coba ubah filter pencarian untuk melihat log aktivitas.</p>
                            </div>
                        @endif
                    </div>
                    @if ($logs->hasPages())
                        <div class="p-6 border-t border-slate-50 bg-slate-50/50">{{ $logs->links() }}</div>
                    @endif
                </div>
            </div>

            {{-- TIMELINE VIEW --}}
            <div x-show="view === 'timeline'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8" data-aos="fade-up">
                    @if($logs->count() > 0)
                    <div class="relative">
                        {{-- Vertical Line --}}
                        <div class="absolute left-5 top-0 bottom-0 w-0.5 bg-slate-200"></div>

                        <div class="space-y-0">
                            @foreach($logs as $log)
                                @php
                                    $dotColors = [
                                        'created' => 'bg-emerald-500 ring-emerald-100',
                                        'updated' => 'bg-blue-500 ring-blue-100',
                                        'deleted' => 'bg-red-500 ring-red-100',
                                        'login' => 'bg-purple-500 ring-purple-100',
                                        'logout' => 'bg-slate-400 ring-slate-100',
                                        'failed_login' => 'bg-amber-500 ring-amber-100',
                                    ];
                                    $dotColor = $dotColors[$log->action] ?? 'bg-slate-400 ring-slate-100';
                                @endphp
                                <div class="relative flex gap-5 pb-8 last:pb-0 group">
                                    {{-- Dot --}}
                                    <div class="relative z-10 flex-shrink-0 w-10 h-10 rounded-full {{ $dotColor }} ring-4 flex items-center justify-center text-white text-xs shadow-sm group-hover:scale-110 transition-transform">
                                        <i class="fas {{ $actionIcons[$log->action] ?? 'fa-circle' }} text-[10px]"></i>
                                    </div>
                                    {{-- Content --}}
                                    <div class="flex-1 bg-slate-50/50 hover:bg-slate-50 rounded-xl p-4 border border-slate-100 transition-colors -mt-1">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                            <div>
                                                <span class="text-sm font-bold text-slate-800">{{ $log->user ? $log->user->name : 'System' }}</span>
                                                <span class="text-sm text-slate-500 font-medium ml-1">melakukan <strong class="text-slate-700">{{ $log->getActionLabel() }}</strong> pada <strong class="text-slate-700">{{ $log->getModelName() }}</strong></span>
                                                @if($log->model_id) <span class="text-xs text-slate-400 font-mono">#{{ $log->model_id }}</span> @endif
                                            </div>
                                            <div class="flex items-center gap-3 flex-shrink-0">
                                                <span class="text-[11px] font-medium text-slate-400 whitespace-nowrap">{{ $log->created_at->diffForHumans() }}</span>
                                                <a href="{{ route('audit-logs.show', $log) }}" class="text-slate-400 hover:text-indigo-600 transition-colors text-xs"><i class="fas fa-arrow-right"></i></a>
                                            </div>
                                        </div>
                                        @if($log->ip_address)
                                            <div class="mt-2 text-[11px] text-slate-400 font-mono"><i class="fas fa-globe text-[9px] mr-1"></i>{{ $log->ip_address }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100"><i class="fas fa-clock-rotate-left text-2xl text-slate-300"></i></div>
                            <h3 class="text-sm font-bold text-slate-700">Tidak Ada Log Ditemukan</h3>
                        </div>
                    @endif
                    @if ($logs->hasPages())
                        <div class="pt-6 mt-6 border-t border-slate-100">{{ $logs->links() }}</div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
