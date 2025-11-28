<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">Percakapan Pengguna</h2>
                <p class="text-sm text-gray-500 mt-1">Daftar percakapan guru atau staf dengan admin.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white border border-gray-100 shadow-sm sm:rounded-xl">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Kotak Percakapan</h3>
                        <p class="text-xs text-gray-500 mt-1">Klik satu percakapan untuk melihat dan membalas.</p>
                    </div>
                    <span class="text-xs px-3 py-1 rounded-full bg-gray-100 text-gray-700">{{ $conversations->total() }} percakapan</span>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($conversations as $conversation)
                        <a href="{{ route('admin.contact-conversations.show', $conversation) }}" class="block p-5 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $conversation->user->name ?? 'Pengguna' }}</p>
                                    <p class="text-xs text-gray-500">{{ $conversation->user->email ?? '-' }}</p>
                                    <p class="mt-2 text-sm text-gray-700 line-clamp-2">
                                        {{ optional($conversation->messages()->latest()->first())->body ?? 'Belum ada pesan.' }}
                                    </p>
                                </div>
                                <div class="flex flex-col items-end gap-2">
                                    <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-700">
                                        {{ $conversation->status === 'closed' ? 'Selesai' : 'Aktif' }}
                                    </span>
                                    @php
                                        $latest = $conversation->messages()->latest()->first();
                                    @endphp
                                    @if($latest)
                                        <span class="text-xs text-gray-500">{{ $latest->created_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-6 text-sm text-gray-600">Belum ada percakapan.</div>
                    @endforelse
                </div>

                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $conversations->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
