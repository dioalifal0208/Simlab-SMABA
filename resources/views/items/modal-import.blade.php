<button @click="showImportModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></button>
            
<h2 class="text-2xl font-bold text-smaba-text text-center">Import Data Item</h2>
<p class="text-center text-sm text-gray-500 mt-2">Upload file Excel (.xlsx) atau CSV untuk menambahkan banyak item sekaligus.</p>

{{-- Area Notifikasi Error/Sukses di dalam Modal --}}
<div id="import-feedback" class="hidden mt-4 text-sm"></div>

<form x-data="importFormHandler()" @submit.prevent="submitForm" class="mt-6 space-y-4">
    @csrf
    <div>
        {{-- PERBAIKAN: Mengubah tampilan input file menjadi area dropzone --}}
        <div x-ref="dropzone" class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10" :class="{ 'border-smaba-dark-blue bg-blue-50': isDragging }">
            <div class="text-center" x-show="!fileName">
                <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                </svg>
                <div class="mt-4 flex text-sm leading-6 text-gray-600">
                    <label for="import-file" class="relative cursor-pointer rounded-md bg-white font-semibold text-smaba-dark-blue focus-within:outline-none focus-within:ring-2 focus-within:ring-smaba-light-blue focus-within:ring-offset-2 hover:text-smaba-light-blue">
                        <span>Upload sebuah file</span>
                        <input id="import-file" name="file" type="file" class="sr-only" @change="handleFileSelect" x-ref="fileInput" required>
                    </label>
                    <p class="pl-1">atau tarik dan lepas</p>
                </div>
                <p class="text-xs leading-5 text-gray-600">XLSX, XLS, CSV hingga 10MB</p>
            </div>
            <div class="text-center" x-show="fileName">
                <i class="fas fa-file-excel text-4xl text-green-500"></i>
                <p class="mt-2 text-sm font-semibold text-gray-700" x-text="fileName"></p>
                <button type="button" @click="removeFile" class="mt-2 text-xs text-red-500 hover:underline">Hapus file</button>
            </div>
        </div>
    </div>
    <div class="pt-2">
        <button type="submit" :disabled="isLoading" class="w-full flex items-center justify-center py-3 px-4 bg-smaba-dark-blue text-white font-semibold rounded-lg shadow-md hover:bg-smaba-light-blue transition-colors duration-300 disabled:bg-gray-400">
            {{-- Tampilkan spinner jika isLoading true --}}
            <i x-show="isLoading" class="fas fa-spinner fa-spin mr-2"></i>
            {{-- Tampilkan teks asli jika isLoading false --}}
            <span x-show="!isLoading"><i class="fas fa-upload mr-2"></i> Upload dan Import</span>
        </button>
    </div>
</form>
<div class="mt-4 text-center">
    <a href="{{ route('items.template.export') }}" class="text-sm text-smaba-light-blue hover:underline">
        <i class="fas fa-download mr-1"></i> Unduh Template Excel
    </a>
</div>

@push('scripts')
<script>
    function importFormHandler() {
        return {
            isLoading: false,
            isDragging: false,
            fileName: '',
            file: null,
            feedbackDiv: document.getElementById('import-feedback'),

            init() {
                const dropzone = this.$refs.dropzone;
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropzone.addEventListener(eventName, (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                    });
                });

                dropzone.addEventListener('dragenter', () => this.isDragging = true);
                dropzone.addEventListener('dragleave', () => this.isDragging = false);
                dropzone.addEventListener('drop', (e) => {
                    this.isDragging = false;
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        this.$refs.fileInput.files = files;
                        this.handleFileSelect({ target: { files: files } });
                    }
                });
            },

            handleFileSelect(event) {
                if (event.target.files.length > 0) {
                    this.file = event.target.files[0];
                    this.fileName = this.file.name;
                }
            },

            removeFile() {
                this.file = null;
                this.fileName = '';
                this.$refs.fileInput.value = '';
            },

            async submitForm() {
                if (!this.file) {
                    this.showFeedback('Pilih file terlebih dahulu.', 'error');
                    return;
                }

                this.isLoading = true;
                this.feedbackDiv.className = 'hidden';

                const formData = new FormData();
                formData.append('file', this.file);
                formData.append('_token', document.querySelector('input[name="_token"]').value);

                try {
                    const response = await fetch("{{ route('items.import.handle') }}", { method: 'POST', body: formData, headers: { 'Accept': 'application/json' } });
                    const data = await response.json();

                    if (!response.ok) {
                        this.showFeedback(data.message + (data.errors ? '<ul class="mt-2 list-disc list-inside"><li>' + data.errors.join('</li><li>') + '</li></ul>' : ''), 'error');
                    } else {
                        // PERBAIKAN: Hapus event Livewire dan langsung reload halaman
                        this.showFeedback(data.message + ' Halaman akan dimuat ulang secara otomatis.', 'success');
                        setTimeout(() => {
                            window.location.reload(); // Muat ulang halaman untuk menampilkan data baru
                        }, 2000); // Beri waktu 2 detik agar pengguna bisa membaca pesan sukses
                    }
                } catch (error) {
                    this.showFeedback('Tidak dapat terhubung ke server. Periksa koneksi Anda.', 'error');
                } finally {
                    this.isLoading = false;
                }
            },

            showFeedback(message, type) {
                this.feedbackDiv.innerHTML = `<p class="font-bold">${type === 'success' ? 'Sukses' : 'Error'}</p><div>${message}</div>`;
                this.feedbackDiv.className = `mt-4 p-3 border-l-4 text-sm ${type === 'success' ? 'bg-green-50 border-green-400 text-green-700' : 'bg-red-50 border-red-400 text-red-700'}`;
            }
        }
    }
</script>
@endpush