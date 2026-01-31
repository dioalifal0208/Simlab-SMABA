<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Detail Audit Log') }}
        </h2>
    </x-slot>

<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('audit-logs.index') }}" class="text-sm font-semibold text-smaba-light-blue hover:text-smaba-dark-blue transition-colors">
                &larr; Kembali ke Daftar Log
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden p-6">
            <h2 class="font-bold text-xl text-smaba-text mb-6">Detail Audit Log #{{ $auditLog->id }}</h2>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-500">User</label>
                        <p class="text-base text-gray-900">{{ $auditLog->user ? $auditLog->user->name : '-' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-500">Aksi</label>
                        <p class="text-base text-gray-900">{{ $auditLog->getActionLabel() }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-500">Model</label>
                        <p class="text-base text-gray-900">{{ $auditLog->getModelName() }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-500">Record ID</label>
                        <p class="text-base text-gray-900">{{ $auditLog->model_id ?? '-' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-500">IP Address</label>
                        <p class="text-base text-gray-900">{{ $auditLog->ip_address ?? '-' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-500">Waktu</label>
                        <p class="text-base text-gray-900">{{ $auditLog->created_at->format('d F Y, H:i:s') }}</p>
                    </div>
                </div>

                @if($auditLog->user_agent)
                <div>
                    <label class="text-sm font-semibold text-gray-500">User Agent</label>
                    <p class="text-sm text-gray-700 bg-gray-50 p-2 rounded mt-1">{{ $auditLog->user_agent }}</p>
                </div>
                @endif

                @if($auditLog->details)
                <div>
                    <label class="text-sm font-semibold text-gray-500 mb-2 block">Detail Perubahan</label>
                    
                    @if(isset($auditLog->details['old']) || isset($auditLog->details['new']))
                        <div class="bg-gray-50 rounded-lg p-4">
                            @if(isset($auditLog->details['old']) && $auditLog->action === 'updated')
                                <div class="mb-4">
                                    <h4 class="font-semibold text-sm text-red-700 mb-2">Nilai Lama:</h4>
                                    <pre class="text-xs bg-red-50 p-3 rounded border border-red-200 overflow-x-auto">{{ json_encode($auditLog->details['old'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            @endif

                            @if(isset($auditLog->details['new']))
                                <div>
                                    <h4 class="font-semibold text-sm text-green-700 mb-2">Nilai Baru:</h4>
                                    <pre class="text-xs bg-green-50 p-3 rounded border border-green-200 overflow-x-auto">{{ json_encode($auditLog->details['new'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            @endif
                        </div>
                    @else
                        <pre class="text-xs bg-gray-50 p-3 rounded border border-gray-200 overflow-x-auto">{{ json_encode($auditLog->details, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
</x-app-layout>
