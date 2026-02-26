<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('bookings.details.title') }} #{{ $booking->id }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('bookings.labels.diajukan_oleh') }}: <span class="font-semibold">{{ $booking->user->name }}</span></p>
            </div>
            
            <div class="flex items-center space-x-3">
                {{-- Tombol Cetak (Pindah ke Header) --}}
                @if(($booking->status == 'approved' || $booking->status == 'completed') && (auth()->user()->role === 'admin' || auth()->id() === $booking->user_id))
                    <button onclick="openDocModal('{{ route('bookings.surat', $booking->id) }}', 'Surat Booking #{{ $booking->id }}')" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        {{ __('bookings.letter.view') }}
                    </button>
                    {{-- Hidden link for direct download if needed --}}
                @endif

                <a href="{{ route('bookings.index') }}" class="text-sm font-semibold text-indigo-500 hover:text-indigo-600 transition-colors">
                    &larr; {{ __('common.buttons.back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">{{ __('common.messages.success') }}</p>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border-l-4 border-red-400 text-red-700 p-4 text-sm rounded-lg" role="alert">
                    <p class="font-bold">{{ __('common.messages.error_title') }}:</p>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" data-aos="fade-in" data-aos-once="true">

                {{-- Kolom Kiri: Detail Booking (Lebih Lebar) --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm border border-gray-100 sm:rounded-xl">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                {{ __('bookings.details.info') }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div><label class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('bookings.details.applicant') }}</label><p class="font-semibold text-gray-800 text-lg">{{ $booking->user->name }}</p></div>
                                    <div><label class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('bookings.details.submission_date') }}</label><p class="text-gray-700">{{ $booking->created_at->format('d F Y, H:i') }}</p></div>
                                    <div><label class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('bookings.details.subject') }}</label><p class="text-gray-700">{{ $booking->mata_pelajaran ?? '-' }}</p></div>
                                </div>
                                <div class="space-y-4">
                                    <div><label class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('bookings.form.lab') }}</label><p class="font-semibold text-green-600">{{ $booking->laboratorium }}</p></div>
                                    <div><label class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('bookings.details.execution_time') }}</label>
                                        <div class="flex items-center mt-1 text-gray-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="font-medium">{{ $booking->waktu_mulai->format('d M Y') }}</span>
                                        </div>
                                        <div class="flex items-center mt-1 text-gray-600 text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 ml-0.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $booking->waktu_mulai->format('H:i') }} - {{ $booking->waktu_selesai->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="md:col-span-2 pt-4 border-t border-gray-100">
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('bookings.details.purpose') }}</label>
                                    <p class="mt-2 text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-lg">{{ $booking->tujuan_kegiatan }}</p>
                                </div>
                                @if($booking->admin_notes)
                                <div class="md:col-span-2">
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <label class="text-xs font-bold text-yellow-600 uppercase tracking-wider flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                                            {{ __('bookings.details.admin_notes') }}
                                        </label>
                                        <p class="mt-1 text-yellow-800">{{ $booking->admin_notes }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Include Laporan Form (Reused Logic) --}}
                    @if($booking->status == 'approved' || $booking->status == 'completed')
                        @include('bookings.partials.return-form')
                    @endif
                </div>

                {{-- Kolom Kanan: Status & Control Panel --}}
                <div class="space-y-6">
                    
                    {{-- Card Status --}}
                    <div class="bg-white overflow-hidden shadow-sm border border-gray-100 sm:rounded-xl">
                        <div class="p-6 text-center">
                            <h3 class="text-gray-500 font-medium text-sm uppercase tracking-wider mb-4">{{ __('bookings.details.current_status') }}</h3>
                            @if($booking->status == 'pending') 
                                <div class="inline-flex flex-col items-center justify-center bg-yellow-100 text-yellow-800 rounded-full h-32 w-32 mb-2 ring-4 ring-yellow-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span class="font-bold text-lg">{{ __('common.status.pending') }}</span>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">{{ __('bookings.messages.waiting_approval') }}</p>
                            @elseif($booking->status == 'approved') 
                                <div class="inline-flex flex-col items-center justify-center bg-green-100 text-green-800 rounded-full h-32 w-32 mb-2 ring-4 ring-green-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span class="font-bold text-lg">{{ __('common.status.approved') }}</span>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">{{ __('bookings.messages.print_permit') }}</p>
                            @elseif($booking->status == 'rejected')
                                <div class="inline-flex flex-col items-center justify-center bg-red-100 text-red-800 rounded-full h-32 w-32 mb-2 ring-4 ring-red-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    <span class="font-bold text-lg">{{ __('common.status.rejected') }}</span>
                                </div>
                            @elseif($booking->status == 'completed')
                                <div class="inline-flex flex-col items-center justify-center bg-gray-100 text-gray-800 rounded-full h-32 w-32 mb-2 ring-4 ring-gray-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    <span class="font-bold text-lg">{{ __('common.status.completed') }}</span>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">{{ __('bookings.messages.event_ended') }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Admin Control Panel --}}
                    @can('is-admin')
                    <div class="bg-gradient-to-br from-white to-gray-50 overflow-hidden shadow-sm border border-gray-200 sm:rounded-xl">
                        <div class="p-4 border-b border-gray-100 bg-gray-50">
                            <h3 class="font-bold text-gray-800 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                {{ __('bookings.details.admin_control') }}
                            </h3>
                        </div>
                        <div class="p-5 space-y-4">
                            @if($booking->status == 'pending')
                                <form action="{{ route('bookings.update', $booking->id) }}" method="POST" class="space-y-4">
                                    @csrf @method('PATCH')
                                    <div>
                                        <label for="admin_notes" class="block text-xs font-medium text-gray-500 uppercase mb-1">{{ __('bookings.details.review_notes') }}</label>
                                        <textarea name="admin_notes" id="admin_notes" rows="2" class="block w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="{{ __('bookings.details.reject_placeholder') }}"></textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <button type="submit" name="status" value="approved" class="w-full flex justify-center items-center py-2 px-4 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-sm transition-all transform active:scale-95 text-sm">
                                            ✔ {{ __('bookings.actions.approve') }}
                                        </button>
                                        <button type="submit" name="status" value="rejected" class="w-full flex justify-center items-center py-2 px-4 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-sm transition-all transform active:scale-95 text-sm">
                                            ✖ {{ __('bookings.actions.reject') }}
                                        </button>
                                    </div>
                                </form>
                            @elseif($booking->status == 'approved')
                                <form action="{{ route('bookings.update', $booking->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" name="status" value="completed" class="w-full flex justify-center items-center py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-md transition-all transform hover:-translate-y-0.5 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        {{ __('bookings.actions.complete') }}
                                    </button>
                                    <p class="text-xs text-gray-400 mt-2 text-center">{{ __('bookings.messages.click_to_complete') }}</p>
                                </form>
                            @endif

                            {{-- Form Hapus --}}
                            @if(in_array($booking->status, ['pending', 'rejected', 'completed']))
                                <div class="border-t border-gray-200 pt-4 mt-2">
                                    <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('{{ __('bookings.messages.delete_confirm') }}');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full text-gray-400 hover:text-red-600 text-xs font-medium transition-colors flex justify-center items-center p-2 rounded hover:bg-red-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            {{ __('bookings.messages.delete_action') }}
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endcan
                </div>

            </div>
        </div>
    </div>
    {{-- Modal Pratinjau Surat --}}
    <div id="docModal" class="hidden fixed inset-0 z-50 bg-black/60 items-center justify-center backdrop-blur-sm transition-opacity duration-300" role="dialog" aria-modal="true" aria-labelledby="docModalTitle">
        <div class="bg-white w-full h-full md:w-11/12 md:h-[90vh] md:max-w-5xl md:rounded-2xl shadow-2xl flex flex-col overflow-hidden transform transition-all scale-100">
            
            {{-- Header Modal --}}
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 id="docModalTitle" class="text-lg font-bold text-gray-800">{{ __('bookings.letter.preview_title') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('bookings.letter.preview_subtitle') }}</p>
                    </div>
                </div>
                <button type="button" onclick="closeDocModal()" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-full transition-colors focus:outline-none" aria-label="{{ __('common.buttons.close') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Content Iframe --}}
            <div class="flex-grow bg-gray-100 relative">
                <div id="loadingSpinner" class="absolute inset-0 flex items-center justify-center bg-white z-10">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
                </div>
                <iframe id="docFrame" class="w-full h-full" src="" title="Pratinjau Surat" onload="document.getElementById('loadingSpinner').classList.add('hidden')"></iframe>
            </div>

            {{-- Footer Actions --}}
            <div class="px-6 py-4 border-t border-gray-100 bg-white flex justify-end space-x-3">
                <button type="button" onclick="closeDocModal()" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    {{ __('common.buttons.close') }}
                </button>
                <button type="button" onclick="printFrame()" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    {{ __('bookings.letter.download_print') }}
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openDocModal(url, title) {
            const modal = document.getElementById('docModal');
            const frame = document.getElementById('docFrame');
            const spinner = document.getElementById('loadingSpinner');
            
            if (!modal) return;
            
            // Set title and title attribute
            document.getElementById('docModalTitle').textContent = title || 'Pratinjau Dokumen';
            
            // Show loading spinner
            if(spinner) spinner.classList.remove('hidden');
            
            // Set styles to show modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling

            // Load URL into iframe
            frame.src = url;
        }

        function closeDocModal() {
            const modal = document.getElementById('docModal');
            if (!modal) return;
            
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('docFrame').src = ''; // Stop loading
            document.body.style.overflow = ''; // Restore scrolling
        }

        function printFrame() {
            const frame = document.getElementById('docFrame');
            if (frame && frame.contentWindow) {
                frame.contentWindow.focus();
                frame.contentWindow.print();
            }
        }

        // Close on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDocModal();
            }
        });

        // Close on clicking outside modal content
        document.getElementById('docModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeDocModal();
            }
        });
    </script>
    @endpush
</x-app-layout>

