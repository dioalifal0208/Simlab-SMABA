<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Audit Trail') }}
        </h2>
    </x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    Audit Trail / Log Aktivitas
                </h2>
                <p class="text-sm text-gray-500 mt-1">Riwayat semua aktivitas dan perubahan di sistem</p>
            </div>
            <div class="mt-3 sm:mt-0">
                <a href="{{ route('audit-logs.export', request()->query()) }}" class="inline-block px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-md hover:bg-green-700 shadow-sm transition-colors">
                    <i class="fas fa-download mr-2"></i> Export CSV
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <form action="{{ route('audit-logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">User</label>
                    <select name="user_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 text-sm">
                        <option value="">Semua User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Aksi</label>
                    <select name="action" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 text-sm">
                        <option value="">Semua Aksi</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" @selected(request('action') == $action)>
                                {{ ucfirst($action) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                    <input type="text" name="model" value="{{ request('model') }}" placeholder="Cari model..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 text-sm">
                </div>

                <div class="md:col-span-5 flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 font-semibold text-sm shadow-sm transition-colors">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    <a href="{{ route('audit-logs.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold text-sm shadow-sm transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Logs Table --}}
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th class="py-3 px-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 divide-y divide-gray-200">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 text-sm">
                                    {{ $log->user ? $log->user->name : '-' }}
                                </td>
                                <td class="py-3 px-4 text-sm">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        @if($log->action == 'created') bg-green-100 text-green-800
                                        @elseif($log->action == 'updated') bg-green-100 text-green-800
                                        @elseif($log->action == 'deleted') bg-red-100 text-red-800
                                        @elseif($log->action == 'login') bg-purple-100 text-purple-800
                                        @elseif($log->action == 'logout') bg-gray-100 text-gray-800
                                        @elseif($log->action == 'failed_login') bg-orange-100 text-orange-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $log->getActionLabel() }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm">{{ $log->getModelName() }}</td>
                                <td class="py-3 px-4 text-sm text-gray-500">{{ $log->ip_address ?? '-' }}</td>
                                <td class="py-3 px-4 text-sm text-gray-500">{{ $log->created_at->format('d M Y H:i') }}</td>
                                <td class="py-3 px-4 text-center">
                                    <a href="{{ route('audit-logs.show', $log) }}" class="text-indigo-600 hover:text-indigo-500 font-semibold text-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12">
                                    <div class="text-center">
                                        <div class="w-20 h-20 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                            <i class="fas fa-history text-3xl text-gray-400"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Tidak Ada Log Ditemukan</h3>
                                        <p class="text-sm text-gray-500">Coba ubah filter pencarian Anda untuk melihat log aktivitas.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="p-4 border-t border-gray-200">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
</x-app-layout>

