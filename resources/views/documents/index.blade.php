<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                    {{ __('Pustaka Digital') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Jelajahi, pratinjau, dan unduh materi pembelajaran digital.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">Sukses</p>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Form Unggah (Admin & Guru) --}}
            @can('manage-documents')
            <div class="bg-white border border-gray-100 shadow-sm sm:rounded-xl" data-aos="fade-up" data-aos-once="true">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-smaba-text mb-4">Unggah Dokumen Baru</h3>
                    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Judul Dokumen</label>
                            <input type="text" name="title" id="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        @if (auth()->user()->role === 'admin')
                            <div>
                                <label for="target_user_id" class="block text-sm font-medium text-gray-700">Ditujukan untuk Guru</label>
                                <select name="target_user_id" id="target_user_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="" disabled {{ old('target_user_id') ? '' : 'selected' }}>Pilih guru tujuan</option>
                                    @foreach ($targetUsers as $targetUser)
                                        <option value="{{ $targetUser->id }}" {{ old('target_user_id') == $targetUser->id ? 'selected' : '' }}>{{ $targetUser->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Guru hanya melihat dokumen admin yang ditujukan untuk dirinya.</p>
                                @error('target_user_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        @else
                            <p class="text-sm text-gray-500">Admin dapat melihat unggahan Anda. Guru lain tidak bisa.</p>
                        @endif
                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700">Pilih File (pdf, doc, docx, ppt, pptx)</label>
                            <input type="file" name="file" id="file" required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition-colors">
                            @error('file') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 shadow-md transition-colors duration-300">
                            Unggah Dokumen
                        </button>
                    </form>
                </div>
            </div>
            @endcan

            <div data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                {{-- Form untuk Live Search --}}
                <div class="mb-6">
                    <form action="{{ route('documents.index') }}" method="GET" id="search-form">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                            </div>
                            <input type="text" name="search" id="search" placeholder="Ketik untuk mencari judul dokumen..." 
                                   class="w-full pl-10 pr-4 py-2 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                   value="{{ request('search') }}">
                        </div>
                    </form>
                </div>

                {{-- Grid Kartu Dokumen --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($documents as $document)
                        @php
                            $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
                            $iconClass = 'fa-file';
                            $iconColor = 'text-gray-500';
                            if (in_array($extension, ['pdf'])) {
                                $iconClass = 'fa-file-pdf'; $iconColor = 'text-red-500';
                            } elseif (in_array($extension, ['doc', 'docx'])) {
                                $iconClass = 'fa-file-word'; $iconColor = 'text-green-500';
                            } elseif (in_array($extension, ['ppt', 'pptx'])) {
                                $iconClass = 'fa-file-powerpoint'; $iconColor = 'text-orange-500';
                            }
                        @endphp
                        <div class="bg-white overflow-hidden border border-gray-100 shadow-sm rounded-xl flex flex-col group transform hover:-translate-y-1 transition-transform duration-300">
                            <div class="p-6 flex-grow">
                                <div class="flex items-start space-x-4">
                                    <i class="fas {{ $iconClass }} {{ $iconColor }} text-4xl mt-1"></i>
                                    <div>
                                        <h3 class="font-bold text-lg text-smaba-text leading-tight group-hover:text-indigo-600 transition-colors duration-300">{{ $document->title }}</h3>
                                        <p class="text-xs text-gray-500 mt-2">Diunggah oleh {{ $document->user->name ?? '-' }}</p>
                                        @if ($document->targetUser)
                                            <p class="text-xs text-gray-500">Ditujukan untuk {{ $document->targetUser->name }}</p>
                                        @elseif (optional($document->user)->role === 'admin')
                                            <p class="text-xs text-gray-500">Ditujukan untuk semua guru</p>
                                        @endif
                                        <p class="text-xs text-gray-400">{{ $document->created_at?->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-3 border-t flex items-center justify-end space-x-2">
                                <a href="#" onclick="openDocModal('{{ route('documents.preview', $document) }}', '{{ addslashes($document->title) }}'); return false;" class="px-3 py-1 bg-gray-200 text-gray-700 text-xs font-semibold rounded-full hover:bg-gray-300 transition-colors">Lihat</a>
                                <a href="{{ route('documents.download', $document) }}" class="px-3 py-1 bg-indigo-600 text-white text-xs font-semibold rounded-full hover:bg-indigo-700 transition-colors">Unduh</a>
                                @can('manage-documents')
                                    @if (auth()->user()->role === 'admin' || $document->user_id === auth()->id())
                                    <form action="{{ route('documents.destroy', $document) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-600 text-white text-xs font-semibold rounded-full hover:bg-red-700 transition-colors">Hapus</button>
                                    </form>
                                    @endif
                                @endcan
                            </div>
                        </div>
                    @empty
                        <div class="sm:col-span-2 lg:col-span-3">
                            <div class="text-center py-12 bg-white rounded-xl border border-gray-100 shadow-sm">
                                <div class="w-20 h-20 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                    <i class="fas fa-file-alt text-3xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">Dokumen Tidak Ditemukan</h3>
                                <p class="text-sm text-gray-500">Coba gunakan kata kunci lain atau unggah dokumen baru.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- Paginasi --}}
                <div class="mt-8">
                    {{ $documents->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Pratinjau Dokumen --}}
    <div id="docModal" class="hidden fixed inset-0 z-50 bg-black/60 items-center justify-center" role="dialog" aria-modal="true" aria-labelledby="docModalTitle">
        <div class="bg-white w-11/12 max-w-5xl rounded-lg shadow-lg overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 id="docModalTitle" class="text-lg font-semibold">Pratinjau Dokumen</h3>
                <button type="button" id="closeModalButton" class="text-gray-500 hover:text-gray-800 text-xl font-bold" aria-label="Tutup">&times;</button>
            </div>
            <div class="p-4">
                <iframe id="docFrame" class="w-full h-[80vh] border rounded" src="" title="Pratinjau Dokumen"></iframe>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function openDocModal(url, title) {
                const modal = document.getElementById('docModal');
                if (!modal) return;
                document.getElementById('docModalTitle').textContent = title;
                document.getElementById('docFrame').src = url;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }

            function closeDocModal() {
                const modal = document.getElementById('docModal');
                if (!modal) return;
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.getElementById('docFrame').src = '';
                document.body.style.overflow = '';
            }

            document.addEventListener('DOMContentLoaded', function () {
                const closeModalBtn = document.getElementById('closeModalButton');
                if (closeModalBtn) {
                    closeModalBtn.addEventListener('click', closeDocModal);
                }
                const modalOverlay = document.getElementById('docModal');
                if(modalOverlay) {
                    modalOverlay.addEventListener('click', function(e) {
                        if (e.target === modalOverlay) closeDocModal();
                    });
                }
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') closeDocModal();
                });

                const searchForm = document.getElementById('search-form');
                const searchInput = document.getElementById('search');
                let debounceTimer;
                if (searchForm && searchInput) {
                    searchInput.addEventListener('keyup', () => {
                        clearTimeout(debounceTimer);
                        debounceTimer = setTimeout(() => {
                            searchForm.submit();
                        }, 500);
                    });
                }

                const deleteForms = document.querySelectorAll('.delete-form');
                deleteForms.forEach(form => {
                    form.addEventListener('submit', function (event) {
                        event.preventDefault();
                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "Dokumen yang dihapus tidak dapat dikembalikan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
