<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
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
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Unggah Dokumen Baru</h3>
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
                                        <h3 class="font-bold text-lg text-gray-900 leading-tight group-hover:text-indigo-600 transition-colors duration-300">{{ $document->title }}</h3>
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
                                @php
                                    $canManageDoc = false;
                                    if(auth()->user()->can('manage-documents') && (auth()->user()->role === 'admin' || $document->user_id === auth()->id())) {
                                        $canManageDoc = true;
                                    }
                                @endphp
                                <button type="button" 
                                        @click="$dispatch('buka-dokumen', {
                                            url: '{{ route('documents.preview', $document) }}',
                                            title: '{{ htmlspecialchars($document->title, ENT_QUOTES) }}',
                                            download: '{{ route('documents.download', $document) }}',
                                            delete: '{{ route('documents.destroy', $document) }}',
                                            canDelete: {{ $canManageDoc ? 'true' : 'false' }}
                                        })"
                                        class="px-3 py-1 bg-gray-200 text-gray-700 text-xs font-semibold rounded-full hover:bg-gray-300 transition-colors">Lihat</button>
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

    </div> {{-- Penutup py-12 div --}}

        {{-- MODAL PREVIEW DOKUMEN (ALPINE.JS STANDALONE COMPONENT) --}}
        <div x-data="{ 
                showDocModal: false, 
                docUrl: '', 
                docTitle: '',
                docDownloadUrl: '',
                docDeleteUrl: '',
                canDelete: false 
             }"
             @buka-dokumen.window="
                docUrl = $event.detail.url;
                docTitle = $event.detail.title;
                docDownloadUrl = $event.detail.download;
                docDeleteUrl = $event.detail.delete;
                canDelete = $event.detail.canDelete;
                showDocModal = true;
             "
             @keydown.escape.window="showDocModal = false"
             x-show="showDocModal" 
             style="display: none;"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" x-cloak>
             
            <div x-show="showDocModal" 
                 x-transition.opacity 
                 class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" @click="showDocModal = false"></div>
            
            <div x-show="showDocModal" 
                 x-transition 
                 class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-4xl h-[90vh] flex flex-col relative z-50 overflow-hidden">
                
                {{-- Header Modal --}}
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="font-extrabold text-lg text-slate-800 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm bg-blue-100 text-blue-600"><i class="fas fa-file-alt"></i></div>
                        <span x-text="docTitle">Pratinjau Dokumen</span>
                    </h3>
                    <div class="flex gap-2">
                        <button @click="showDocModal = false" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 flex justify-center items-center rounded-lg hover:bg-slate-200"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                
                {{-- Body Modal (Dokumen) --}}
                <div class="flex-grow w-full bg-slate-200 overflow-hidden relative"
                     x-data="{ 
                         localObjUrl: '', 
                         isLoading: false,
                         async loadPdf() {
                             if (!this.docUrl) return;
                             this.isLoading = true;
                             if (this.localObjUrl) {
                                 URL.revokeObjectURL(this.localObjUrl);
                                 this.localObjUrl = '';
                             }
                             try {
                                 // AJAX Fetch JSON Base64 Bypassing IDM
                                 const response = await fetch(this.docUrl + '?json=1');
                                 const contentType = response.headers.get('content-type');
                                 
                                 if (contentType && contentType.indexOf('application/json') !== -1) {
                                     const resData = await response.json();
                                     if(resData.data) {
                                         // Menggunakan data URI langsung untuk menghindari isu blank pada blob iFrame Chrome 
                                         this.localObjUrl = 'data:application/pdf;base64,' + resData.data;
                                         return;
                                     }
                                 }
                                 
                                 // Fallback blob
                                 const blob = await response.blob();
                                 this.localObjUrl = URL.createObjectURL(new Blob([blob], {type: 'application/pdf'}));
                             } catch (err) {
                                 console.error('Gagal memuat pratinjau', err);
                             } finally {
                                 this.isLoading = false;
                             }
                         }
                     }"
                     x-init="$watch('docUrl', () => loadPdf())"
                     @buka-dokumen.window="setTimeout(() => loadPdf(), 50)">
                    
                    {{-- Indikator Loading --}}
                    <div x-show="isLoading" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-200 z-10 transition-opacity">
                        <i class="fas fa-circle-notch fa-spin text-4xl text-blue-500 mb-3"></i>
                        <span class="text-slate-500 font-medium text-sm animate-pulse">Merender Dokumen...</span>
                    </div>

                    {{-- Preview Objek --}}
                    <template x-if="localObjUrl">
                        <object :data="localObjUrl" type="application/pdf" class="w-full h-full border-0 absolute inset-0 bg-white">
                            <embed :src="localObjUrl" type="application/pdf" class="w-full h-full"/>
                        </object>
                    </template>
                </div>

                {{-- Footer Action Bar --}}
                <div class="px-6 py-4 border-t border-slate-100 bg-white flex justify-between items-center mt-auto">
                    <div>
                        <form :action="docDeleteUrl" method="POST" class="delete-form m-0" x-show="canDelete">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-4 py-2.5 bg-red-50 text-red-600 hover:bg-red-500 hover:text-white rounded-xl font-bold text-sm transition-colors border border-red-100 flex items-center gap-2 shadow-sm">
                                <i class="fas fa-trash-alt"></i> Hapus Permanen
                            </button>
                        </form>
                    </div>
                    <div class="flex gap-3">
                        <button @click="showDocModal = false" class="px-5 py-2.5 bg-slate-50 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-100 transition-colors text-sm shadow-sm relative z-50">Batalkan</button>
                        <a :href="docDownloadUrl" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-md shadow-blue-600/20 transition-all flex items-center gap-2 hover:-translate-y-0.5 relative z-50">
                            <i class="fas fa-download"></i> Download Dokumen
                        </a>
                    </div>
                </div>
            </div>
        </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
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
            });
        </script>
    @endpush
</x-app-layout>

