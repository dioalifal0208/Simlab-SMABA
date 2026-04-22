<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('SOP Laboratorium') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola dokumen Standar Operasional Prosedur (SOP) untuk masing-masing laboratorium.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Alert --}}
            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md shadow-sm flex justify-between items-center" x-data="{ show: true }" x-show="show">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3 text-lg"></i>
                    <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-green-500 hover:text-green-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md shadow-sm">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3 text-lg"></i>
                    <p class="text-sm text-red-700 font-bold">Terjadi Kesalahan</p>
                </div>
                <ul class="list-disc list-inside text-sm text-red-600 ml-7">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $labs = ['Biologi', 'Fisika', 'Bahasa', 'Komputer 1', 'Komputer 2', 'Komputer 3', 'Komputer 4'];
                @endphp

                @foreach($labs as $lab)
                    @php
                        $sop = $sopLaboratories->where('laboratorium', $lab)->first();
                    @endphp
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden flex flex-col h-full transition-all hover:shadow-md">
                        <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                                <i class="fas fa-flask text-blue-500 mr-2 w-5 text-center"></i>
                                {{ $lab }}
                            </h3>
                            @if($sop)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i> Tersedia
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-times mr-1"></i> Kosong
                                </span>
                            @endif
                        </div>
                        
                        <div class="p-6 flex-grow flex flex-col justify-between">
                            @if($sop)
                                <div class="mb-6 bg-gray-50 rounded-lg p-4 text-center border border-gray-100">
                                    <i class="fas fa-file-pdf text-4xl text-red-500 mb-2"></i>
                                    <p class="text-sm text-gray-600 font-medium truncate" title="{{ basename($sop->file_path) }}">
                                        {{ basename($sop->file_path) }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        Diunggah: {{ $sop->updated_at->format('d M Y, H:i') }}
                                    </p>
                                    <div class="mt-4 flex justify-center space-x-2">
                                        <a href="{{ Storage::url($sop->file_path) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 bg-opacity-90">
                                            <i class="fas fa-eye mr-1.5"></i> Lihat PDF
                                        </a>
                                        <form action="{{ route('admin.sop-laboratories.destroy', $sop->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SOP untuk Lab {{ $lab }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-red-50 hover:text-red-700 hover:border-red-300 transition-colors">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="mt-auto">
                                    <form action="{{ route('admin.sop-laboratories.store') }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 p-4 rounded-lg border border-gray-200 border-dashed">
                                        @csrf
                                        <input type="hidden" name="laboratorium" value="{{ $lab }}">
                                        <label class="block text-xs font-semibold text-gray-700 mb-2">Ganti Dokumen SOP (PDF)</label>
                                        <input type="file" name="file" accept=".pdf" required
                                            class="block w-full text-xs text-gray-500
                                            file:mr-3 file:py-1.5 file:px-3
                                            file:rounded-md file:border-0
                                            file:text-xs file:font-semibold
                                            file:bg-blue-50 file:text-blue-700
                                            hover:file:bg-blue-100 mb-3"
                                        />
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gray-800 hover:bg-gray-900 transition-colors">
                                            <i class="fas fa-upload mr-2"></i> Update SOP
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="flex-grow flex flex-col items-center justify-center text-center py-6 mb-4">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                        <i class="fas fa-file-circle-xmark text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-sm text-gray-500">Belum ada dokumen SOP yang diunggah untuk lab ini.</p>
                                </div>
                                <div class="mt-auto">
                                    <form action="{{ route('admin.sop-laboratories.store') }}" method="POST" enctype="multipart/form-data" class="bg-blue-50 p-4 rounded-lg border border-blue-100 border-dashed">
                                        @csrf
                                        <input type="hidden" name="laboratorium" value="{{ $lab }}">
                                        <label class="block text-xs font-bold text-blue-800 mb-2">Unggah Dokumen SOP Baru</label>
                                        <input type="file" name="file" accept=".pdf" required
                                            class="block w-full text-xs text-gray-500
                                            file:mr-3 file:py-1.5 file:px-3
                                            file:rounded-md file:border-0
                                            file:text-xs file:font-semibold
                                            file:bg-white file:text-blue-700
                                            file:shadow-sm
                                            hover:file:bg-gray-50 mb-3"
                                        />
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-cloud-upload-alt mr-2"></i> Simpan SOP
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
