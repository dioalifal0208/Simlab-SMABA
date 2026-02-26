<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">Percakapan</h2>
                <p class="text-sm text-gray-500 mt-1">Balas pesan pengguna.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white border border-gray-100 shadow-sm sm:rounded-xl">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $conversation->user->name ?? 'Pengguna' }}</p>
                        <p class="text-xs text-gray-500">{{ $conversation->user->email ?? '-' }}</p>
                    </div>
                    <span class="text-xs px-3 py-1 rounded-full bg-gray-100 text-gray-700">{{ $conversation->status === 'closed' ? 'Selesai' : 'Aktif' }}</span>
                </div>

                <div class="p-5 space-y-4 max-h-[60vh] overflow-y-auto">
                    @foreach($conversation->messages as $message)
                        <div class="flex {{ $message->sender_type === 'admin' ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-xl px-4 py-3 rounded-lg border {{ $message->sender_type === 'admin' ? 'bg-indigo-500/10 border-indigo-600/30' : 'bg-gray-50 border-gray-100' }}">
                                <p class="text-sm text-gray-900">{{ $message->body }}</p>
                                <p class="text-[11px] text-gray-500 mt-1">
                                    {{ $message->sender_type === 'admin' ? 'Admin' : ($conversation->user->name ?? 'Pengguna') }}
                                    • {{ $message->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="p-5 border-t border-gray-100">
                    <form action="{{ route('admin.contact-conversations.reply', $conversation) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label for="pesan" class="block text-sm font-medium text-gray-700">Balasan</label>
                            <textarea id="pesan" name="pesan" rows="3" required maxlength="500" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">{{ old('pesan') }}</textarea>
                            @error('pesan')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg shadow hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

