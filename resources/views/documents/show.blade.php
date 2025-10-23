<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Pratinjau Dokumen') }}
    </h2>
  </x-slot>

  <div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white shadow sm:rounded-lg p-6">
        <div class="mb-4 flex items-center justify-between">
          <div>
            <h3 class="text-lg font-semibold">{{ $document->title }}</h3>
            <p class="text-sm text-gray-500">Diunggah oleh {{ $document->user->name ?? '—' }}</p>
          </div>
          <div class="space-x-3">
            <a href="{{ route('documents.download', $document) }}" class="text-indigo-600 hover:text-indigo-800">Unduh</a>
            @can('manage-documents')
              <form action="{{ route('documents.destroy', $document) }}" method="POST" class="inline"
                    onsubmit="return confirm('Hapus dokumen ini?')">
                @csrf @method('DELETE')
                <button class="text-red-600 hover:text-red-800">Hapus</button>
              </form>
            @endcan
          </div>
        </div>

        @if($isPdf)
  <iframe src="{{ route('documents.preview', $document) }}" class="w-full h-[80vh] border rounded"></iframe>
        @else
        <div class="p-6 bg-gray-50 rounded text-sm text-gray-700">
            Pratinjau tidak tersedia untuk tipe: <span class="font-mono">{{ $mime }}</span>.
            <a href="{{ route('documents.download', $document) }}" class="text-indigo-600 hover:text-indigo-800">Unduh berkas</a>.
        </div>
        @endif

      </div>
    </div>
  </div>
</x-app-layout>
