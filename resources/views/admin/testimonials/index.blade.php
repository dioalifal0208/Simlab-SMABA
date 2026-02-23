<x-app-layout>
    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
    @endpush
    
    @push('scripts')
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });
    </script>
    @endpush
    
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">Moderasi Testimoni</h2>
                <p class="text-sm text-gray-500 mt-1">Setujui atau tolak testimoni sebelum ditampilkan di landing page.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white border border-gray-100 shadow-sm sm:rounded-xl" data-aos="fade-up">
                    <div class="p-5 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Menunggu Persetujuan</h3>
                        <p class="text-xs text-gray-500 mt-1">Testimoni baru yang belum ditampilkan.</p>
                    </div>
                    <div class="p-5 space-y-4">
                        @forelse($pending as $item)
                            <div class="p-4 rounded-lg border border-gray-100 bg-gray-50 hover:shadow-md hover:border-smaba-dark-blue/30 transition-all" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $item->nama }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $item->peran ?? 'Pengguna' }}
                                            @if($item->laboratorium) • {{ $item->laboratorium }} @endif
                                        </p>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded-full bg-amber-50 text-amber-700">Menunggu</span>
                                </div>
                                <p class="mt-3 text-sm text-gray-700">“{{ $item->pesan }}”</p>
                                <div class="mt-4 flex items-center gap-2">
                                    <form action="{{ route('admin.testimonials.update-status', $item) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition">Setujui</button>
                                    </form>
                                    <form action="{{ route('admin.testimonials.update-status', $item) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition">Tolak</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Tidak ada testimoni pending.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white border border-gray-100 shadow-sm sm:rounded-xl" data-aos="fade-up" data-aos-delay="200">
                    <div class="p-5 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Disetujui</h3>
                        <p class="text-xs text-gray-500 mt-1">Testimoni yang tampil di landing page.</p>
                    </div>
                    <div class="p-5 space-y-4">
                        @forelse($approved as $item)
                            <div class="p-4 rounded-lg border border-gray-100 hover:shadow-md hover:border-smaba-dark-blue/30 transition-all" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $item->nama }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $item->peran ?? 'Pengguna' }}
                                            @if($item->laboratorium) • {{ $item->laboratorium }} @endif
                                        </p>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded-full bg-emerald-50 text-emerald-700">Disetujui</span>
                                </div>
                                <p class="mt-3 text-sm text-gray-700">“{{ $item->pesan }}”</p>
                                <div class="mt-4 flex items-center gap-2">
                                    <form action="{{ route('admin.testimonials.update-status', $item) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition">Sembunyikan</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada testimoni disetujui.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
