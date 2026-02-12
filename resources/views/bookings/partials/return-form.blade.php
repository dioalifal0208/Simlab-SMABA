<div class="bg-white overflow-hidden shadow-sm border border-gray-100 sm:rounded-xl transition-all duration-200 hover:shadow-md">
    <div class="p-6">
        <div class="flex items-center space-x-3 mb-6">
            <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-smaba-text">Laporan Pengembalian</h3>
        </div>
        
        @if($booking->waktu_pengembalian)
            <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-100 rounded-xl p-4 flex items-start space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h4 class="font-bold text-green-800">Laporan Telah Disimpan</h4>
                    <p class="text-sm text-green-700 mt-1">
                        Dikembalikan pada: <span class="font-semibold">{{ $booking->waktu_pengembalian->format('d M Y, H:i') }}</span>
                    </p>
                </div>
            </div>
        @else
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                <p class="text-sm text-green-700">
                    <span class="font-bold block mb-1">Perhatian:</span>
                    Mohon isi checklist kondisi ruangan di bawah ini setelah kegiatan selesai. Data ini akan otomatis masuk ke Surat Peminjaman.
                </p>
            </div>
        @endif

        <form action="{{ route('bookings.return', $booking->id) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Option 1: Bersih --}}
                <label class="cursor-pointer group relative">
                    <input type="checkbox" name="kondisi[]" value="Bersih dan Rapi" class="peer sr-only" 
                        {{ in_array('Bersih dan Rapi', $booking->kondisi_lab ?? []) ? 'checked' : '' }}
                        {{ ($booking->status == 'completed' || $booking->waktu_pengembalian) ? 'disabled' : '' }}>
                    
                    <div class="p-4 rounded-xl border-2 border-gray-200 bg-white transition-all duration-200 hover:border-green-300 peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:shadow-sm">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 rounded-full bg-gray-100 text-gray-500 group-hover:bg-green-100 group-hover:text-green-600 peer-checked:bg-green-100 peer-checked:text-green-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-700 peer-checked:text-green-900">Bersih dan Rapi</span>
                        </div>
                        <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 transition-opacity text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </label>

                {{-- Option 2: Sampah --}}
                <label class="cursor-pointer group relative">
                    <input type="checkbox" name="kondisi[]" value="Ada Sampah / Belum Dibersihkan" class="peer sr-only" 
                        {{ in_array('Ada Sampah / Belum Dibersihkan', $booking->kondisi_lab ?? []) ? 'checked' : '' }}
                        {{ ($booking->status == 'completed' || $booking->waktu_pengembalian) ? 'disabled' : '' }}>
                    
                    <div class="p-4 rounded-xl border-2 border-gray-200 bg-white transition-all duration-200 hover:border-orange-300 peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:shadow-sm">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 rounded-full bg-gray-100 text-gray-500 group-hover:bg-orange-100 group-hover:text-orange-600 peer-checked:bg-orange-100 peer-checked:text-orange-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-700 peer-checked:text-orange-900">Ada Sampah</span>
                        </div>
                        <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 transition-opacity text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </label>

                {{-- Option 3: Alat --}}
                <label class="cursor-pointer group relative">
                    <input type="checkbox" name="kondisi[]" value="Alat Tidak Kembali ke Posisi Semula" class="peer sr-only" 
                        {{ in_array('Alat Tidak Kembali ke Posisi Semula', $booking->kondisi_lab ?? []) ? 'checked' : '' }}
                        {{ ($booking->status == 'completed' || $booking->waktu_pengembalian) ? 'disabled' : '' }}>
                    
                    <div class="p-4 rounded-xl border-2 border-gray-200 bg-white transition-all duration-200 hover:border-yellow-300 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 peer-checked:shadow-sm">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 rounded-full bg-gray-100 text-gray-500 group-hover:bg-yellow-100 group-hover:text-yellow-600 peer-checked:bg-yellow-100 peer-checked:text-yellow-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-700 peer-checked:text-yellow-900">Alat Berantakan</span>
                        </div>
                        <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 transition-opacity text-yellow-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </label>

                {{-- Option 4: Rusak --}}
                <label class="cursor-pointer group relative">
                    <input type="checkbox" name="kondisi[]" value="Kerusakan Pada fasilitas (Kursi, Meja, Alat, dll.)" class="peer sr-only" 
                        {{ in_array('Kerusakan Pada fasilitas (Kursi, Meja, Alat, dll.)', $booking->kondisi_lab ?? []) ? 'checked' : '' }}
                        {{ ($booking->status == 'completed' || $booking->waktu_pengembalian) ? 'disabled' : '' }}>
                    
                    <div class="p-4 rounded-xl border-2 border-gray-200 bg-white transition-all duration-200 hover:border-red-300 peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:shadow-sm">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 rounded-full bg-gray-100 text-gray-500 group-hover:bg-red-100 group-hover:text-red-600 peer-checked:bg-red-100 peer-checked:text-red-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-700 peer-checked:text-red-900">Ada Kerusakan</span>
                        </div>
                        <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 transition-opacity text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </label>
            </div>

            @if($booking->status != 'completed' && !$booking->waktu_pengembalian)
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-smaba-dark-blue border border-transparent rounded-lg font-semibold text-white uppercase tracking-widest hover:bg-smaba-light-blue active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-lg transform hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Simpan Laporan
                    </button>
                </div>
            @endif
        </form>
    </div>
</div>
