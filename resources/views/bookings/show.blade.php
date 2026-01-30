<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                    Detail Booking #{{ $booking->id }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Diajukan oleh: <span class="font-semibold">{{ $booking->user->name }}</span></p>
            </div>
            <a href="{{ route('bookings.index') }}" class="mt-3 sm:mt-0 text-sm font-semibold text-smaba-light-blue hover:text-smaba-dark-blue transition-colors">
                &larr; Kembali ke Daftar Booking
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">Sukses</p>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border-l-4 border-red-400 text-red-700 p-4 text-sm rounded-lg" role="alert">
                    <p class="font-bold">Oops! Ada yang salah:</p>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8" data-aos="fade-in" data-aos-once="true">

                {{-- Kolom Kiri: Detail Booking --}}
                <div class="lg:col-span-3 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm border border-gray-100 sm:rounded-xl transition-all duration-200 hover:shadow-md">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-smaba-text mb-4">Informasi Booking</h3>
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                                <div><dt class="font-medium text-gray-500">Pemohon</dt><dd class="mt-1 font-semibold text-gray-800">{{ $booking->user->name }}</dd></div>
                                <div><dt class="font-medium text-gray-500">Tanggal Pengajuan</dt><dd class="mt-1 font-semibold text-gray-800">{{ $booking->created_at->format('d F Y, H:i') }}</dd></div>
                                <div><dt class="font-medium text-gray-500">Laboratorium</dt><dd class="mt-1 font-semibold text-gray-800">{{ $booking->laboratorium }}</dd></div>
                                <div><dt class="font-medium text-gray-500">Guru Pengampu</dt><dd class="mt-1 font-semibold text-gray-800">{{ $booking->guru_pengampu }}</dd></div>
                                <div><dt class="font-medium text-gray-500">Mata Pelajaran</dt><dd class="mt-1 font-semibold text-gray-800">{{ $booking->mata_pelajaran ?? '-' }}</dd></div>
                                <div><dt class="font-medium text-gray-500">Jumlah Peserta</dt><dd class="mt-1 font-semibold text-gray-800">{{ $booking->jumlah_peserta ?? '-' }} orang</dd></div>
                                <div class="sm:col-span-2"><dt class="font-medium text-gray-500">Waktu Pelaksanaan</dt><dd class="mt-1 font-semibold text-gray-800">{{ $booking->waktu_mulai->format('d M Y') }}, Pukul {{ $booking->waktu_mulai->format('H:i') }} - {{ $booking->waktu_selesai->format('H:i') }}</dd></div>
                                <div class="sm:col-span-2"><dt class="font-medium text-gray-500">Tujuan Kegiatan</dt><dd class="mt-1 text-gray-700 bg-gray-50 p-3 rounded-md whitespace-pre-wrap">{{ $booking->tujuan_kegiatan }}</dd></div>
                                @if($booking->admin_notes)
                                <div class="sm:col-span-2"><dt class="font-medium text-gray-500">Catatan dari Admin</dt><dd class="mt-1 text-gray-700 bg-yellow-50 p-3 rounded-md whitespace-pre-wrap">{{ $booking->admin_notes }}</dd></div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
                
                {{-- Laporan Pengembalian (Hanya muncul jika disetujui) --}}
                @if($booking->status == 'approved' || $booking->status == 'completed')
                <div class="bg-white overflow-hidden shadow-sm border border-gray-100 sm:rounded-xl transition-all duration-200 hover:shadow-md">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-smaba-text mb-4">Laporan Pengembalian</h3>
                        
                        @if($booking->waktu_pengembalian)
                            <div class="bg-green-50 text-green-800 p-4 rounded-lg mb-4">
                                <p class="font-semibold">Laporan Tersimpan</p>
                                <p class="text-sm">Dikembalikan pada: {{ $booking->waktu_pengembalian->format('d M Y, H:i') }}</p>
                            </div>
                        @else
                            <div class="bg-yellow-50 text-yellow-800 p-4 rounded-lg mb-4">
                                <p class="text-sm">Silakan isi checklist kondisi ruangan setelah kegiatan selesai.</p>
                            </div>
                        @endif

                        <form action="{{ route('bookings.return', $booking->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            
                            <div class="space-y-3">
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" name="kondisi[]" value="Bersih dan Rapi" class="form-checkbox h-5 w-5 text-smaba-dark-blue rounded focus:ring-smaba-light-blue" 
                                    {{ in_array('Bersih dan Rapi', $booking->kondisi_lab ?? []) ? 'checked' : '' }}
                                    {{ $booking->status == 'completed' ? 'disabled' : '' }}>
                                    <span class="text-gray-700">Bersih dan Rapi</span>
                                </label>
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" name="kondisi[]" value="Ada Sampah / Belum Dibersihkan" class="form-checkbox h-5 w-5 text-red-600 rounded focus:ring-red-500" 
                                    {{ in_array('Ada Sampah / Belum Dibersihkan', $booking->kondisi_lab ?? []) ? 'checked' : '' }}
                                    {{ $booking->status == 'completed' ? 'disabled' : '' }}>
                                    <span class="text-gray-700">Ada Sampah / Belum Dibersihkan</span>
                                </label>
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" name="kondisi[]" value="Alat Tidak Kembali ke Posisi Semula" class="form-checkbox h-5 w-5 text-red-600 rounded focus:ring-red-500" 
                                    {{ in_array('Alat Tidak Kembali ke Posisi Semula', $booking->kondisi_lab ?? []) ? 'checked' : '' }}
                                    {{ $booking->status == 'completed' ? 'disabled' : '' }}>
                                    <span class="text-gray-700">Alat Tidak Kembali ke Posisi Semula</span>
                                </label>
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" name="kondisi[]" value="Kerusakan Pada fasilitas (Kursi, Meja, Alat, dll.)" class="form-checkbox h-5 w-5 text-red-600 rounded focus:ring-red-500" 
                                    {{ in_array('Kerusakan Pada fasilitas (Kursi, Meja, Alat, dll.)', $booking->kondisi_lab ?? []) ? 'checked' : '' }}
                                    {{ $booking->status == 'completed' ? 'disabled' : '' }}>
                                    <span class="text-gray-700">Kerusakan Pada fasilitas (Kursi, Meja, Alat, dll.)</span>
                                </label>
                            </div>

                            @if($booking->status != 'completed')
                                <div class="mt-6">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Simpan Laporan
                                    </button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
                @endif

                {{-- Kolom Kanan: Status & Aksi Admin --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm border border-gray-100 sm:rounded-xl transition-all duration-200 hover:shadow-md" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-smaba-text mb-4">Status Booking</h3>
                             <div class="text-center">
                                @if($booking->status == 'pending') <span class="px-4 py-2 text-sm font-bold leading-none text-yellow-800 bg-yellow-100 rounded-full">Menunggu Persetujuan</span>
                                @elseif($booking->status == 'approved') <span class="px-4 py-2 text-sm font-bold leading-none text-green-800 bg-green-100 rounded-full">Disetujui</span>
                                @elseif($booking->status == 'rejected') <span class="px-4 py-2 text-sm font-bold leading-none text-red-800 bg-red-100 rounded-full">Ditolak</span>
                                @elseif($booking->status == 'completed') <span class="px-4 py-2 text-sm font-bold leading-none text-gray-800 bg-gray-100 rounded-full">Selesai</span>
                                @endif
                            </div>

                            @if($booking->status == 'approved' && (auth()->user()->role === 'admin' || auth()->id() === $booking->user_id))
                                <div class="mt-6 text-center">
                                    <a href="{{ route('bookings.surat', $booking->id) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        Cetak Surat Izin
                                    </a>
                                </div>
                            @endif

                            @can('is-admin')
                                @if($booking->status == 'pending')
                                    <form action="{{ route('bookings.update', $booking->id) }}" method="POST" class="mt-6 border-t pt-6 space-y-4">
                                        @csrf @method('PATCH')
                                        <h4 class="font-semibold text-gray-700">Tindak Lanjut Booking</h4>
                                        <div><label for="admin_notes" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label><textarea name="admin_notes" id="admin_notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" placeholder="Contoh: Jadwal bentrok, silakan ajukan ulang."></textarea></div>
                                        <div class="flex space-x-3">
                                            <button type="submit" name="status" value="approved" class="w-full py-2 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 shadow-md transition-colors">Setujui</button>
                                            <button type="submit" name="status" value="rejected" class="w-full py-2 px-4 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 shadow-md transition-colors">Tolak</button>
                                        </div>
                                    </form>
                                @elseif($booking->status == 'approved')
                                    <form action="{{ route('bookings.update', $booking->id) }}" method="POST" class="mt-6 border-t pt-6">
                                        @csrf @method('PATCH')
                                        <h4 class="font-semibold text-gray-700 mb-2">Aksi Selesai</h4>
                                        <button type="submit" name="status" value="completed" class="w-full py-3 px-4 bg-smaba-dark-blue text-white font-semibold rounded-lg hover:bg-smaba-light-blue shadow-md transition-colors">Tandai Kegiatan Selesai</button>
                                    </form>
                                @endif

                                {{-- PENAMBAHAN: Form Tombol Hapus --}}
                                @if(in_array($booking->status, ['pending', 'rejected']))
                                <div class="mt-4 border-t pt-4">
                                    <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-center py-2 px-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md transition-colors text-sm">Hapus Booking Ini</button>
                                    </form>
                                </div>
                                @endif
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
