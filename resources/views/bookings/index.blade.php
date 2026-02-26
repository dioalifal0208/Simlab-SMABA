<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    @if (auth()->user()->role == 'admin')
                        {{ __('bookings.title_admin') }}
                    @else
                        {{ __('bookings.title_user') }}
                    @endif
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    @if (auth()->user()->role == 'admin')
                        {{ __('bookings.subtitle_admin') }}
                    @else
                        {{ __('bookings.subtitle_user') }}
                    @endif
                </p>
            </div>
            
            <a href="{{ route('bookings.create') }}" class="mt-3 sm:mt-0 px-6 py-2.5 bg-green-700 text-white rounded-lg hover:bg-green-800 font-bold text-sm shadow-lg transition-all border-2 border-green-800 flex items-center gap-2">
                <i class="fas fa-plus"></i> {{ __('bookings.actions.create_new') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">{{ __('common.messages.success') }}</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div data-aos="fade-up" data-aos-duration="500" data-aos-once="true">
                {{-- Form Filter Status Otomatis --}}
                <div class="mb-6 bg-white overflow-hidden border border-gray-100 shadow-sm sm:rounded-xl">
                    <form action="{{ route('bookings.index') }}" method="GET" class="p-4 sm:p-6 space-y-4" id="filter-form">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 space-y-4 sm:space-y-0">
                            <div class="flex items-center space-x-3">
                                <label for="status" class="text-sm font-medium text-gray-700">{{ __('common.labels.status') }}:</label>
                                <select name="status" id="status" class="w-full sm:w-48 rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 text-sm">
                                    <option value="">{{ __('bookings.filters.all') }}</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('common.status.pending') }}</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('common.status.approved') }}</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('common.status.rejected') }}</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('common.status.completed') }}</option>
                                </select>
                            </div>
                            <div class="flex items-center space-x-3">
                                <label for="laboratorium" class="text-sm font-medium text-gray-700">{{ __('common.nav.lab_services') }}:</label>
                                <select name="laboratorium" id="laboratorium" class="w-full sm:w-48 rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 text-sm">
                                    <option value="">{{ __('bookings.filters.all_labs') }}</option>
                                    <option value="Biologi" {{ request('laboratorium') == 'Biologi' ? 'selected' : '' }}>Biologi</option>
                                    <option value="Fisika" {{ request('laboratorium') == 'Fisika' ? 'selected' : '' }}>Fisika</option>
                                    <option value="Bahasa" {{ request('laboratorium') == 'Bahasa' ? 'selected' : '' }}>Bahasa</option>
                                </select>
                            </div>
                            <i id="loading-spinner" class="fas fa-spinner fa-spin text-gray-500 hidden"></i>
                        </div>
                    </form>
                </div>

                {{-- Daftar Booking Cards --}}
                <div class="space-y-4">
                    @forelse ($bookings as $booking)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 border-l-4 transition-all duration-200 hover:-translate-y-1 hover:shadow-md 
                            @if($booking->status == 'approved') border-l-green-500 @elseif($booking->status == 'pending') border-l-yellow-500 @elseif($booking->status == 'rejected') border-l-red-500 @else border-l-indigo-400 @endif">
                            <div class="p-4 sm:p-6">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex-grow">
                                        <div class="flex items-center space-x-3 flex-wrap gap-2">
                                            <span class="px-3 py-1 text-xs font-bold leading-none rounded-full
                                                @if($booking->status == 'pending') text-yellow-800 bg-yellow-100
                                                @elseif($booking->status == 'approved') text-green-800 bg-green-100
                                                @elseif($booking->status == 'rejected') text-red-800 bg-red-100
                                                @else text-gray-800 bg-gray-100 @endif">
                                                {{ match($booking->status) {
                                                    'pending' => __('common.status.pending'),
                                                    'approved' => __('common.status.approved'),
                                                    'rejected' => __('common.status.rejected'),
                                                    'completed' => __('common.status.completed'),
                                                    default => ucfirst($booking->status)
                                                } }}
                                            </span>
                                            <p class="text-sm font-semibold text-indigo-700">{{ $booking->tujuan_kegiatan }}</p>
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-50 text-green-700">
                                                {{ $booking->laboratorium }}
                                            </span>
                                        </div>
                                        @if (auth()->user()->role == 'admin')
                                            <p class="mt-2 text-sm text-gray-600">
                                                <i class="fas fa-user-circle fa-fw mr-1 text-gray-400"></i> {{ __('bookings.labels.diajukan_oleh') }}: <span class="font-medium">{{ $booking->user->name }}</span>
                                            </p>
                                        @endif
                                    </div>
                                    <div class="mt-4 sm:mt-0 sm:ml-4 flex-shrink-0">
                                        <a href="{{ route('bookings.show', $booking->id) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-semibold text-xs shadow-sm transition-colors duration-300">
                                            <i class="fas fa-eye mr-2"></i> {{ __('common.buttons.details') }}
                                        </a>
                                    </div>
                                </div>
                                <div class="mt-4 pt-4 border-t border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt fa-fw mr-2 text-gray-400"></i>
                                        <span class="font-medium">{{ $booking->waktu_mulai->translatedFormat('l, d F Y') }}</span>
                                    </div>
                                    <div class="mt-2 sm:mt-0 flex items-center">
                                        <i class="fas fa-clock fa-fw mr-2 text-gray-400"></i>
                                        <span>{{ $booking->waktu_mulai->format('H:i') }} - {{ $booking->waktu_selesai->format('H:i') }} WIB</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 bg-white rounded-xl border border-gray-100 shadow-sm">
                            <div class="w-20 h-20 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                <i class="fas fa-calendar-times text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ __('bookings.empty.title') }}</h3>
                            <p class="text-sm text-gray-500 mb-4">{{ __('bookings.empty.description') }}</p>
                            <a href="{{ route('bookings.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                                <i class="fas fa-plus"></i> {{ __('bookings.empty.action') }}
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- Paginasi --}}
                <div class="mt-6">
                    {{ $bookings->withQueryString()->links() }}
                </div>
            </div>
        </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const filterForm = document.getElementById('filter-form');
                const statusSelect = document.getElementById('status');
                const labSelect = document.getElementById('laboratorium');

                const submitFilters = () => {
                    filterForm.submit();
                    const spinner = document.getElementById('loading-spinner');
                    if (spinner) spinner.classList.remove('hidden');
                };

                if (statusSelect) {
                    statusSelect.addEventListener('change', submitFilters);
                }

                if (labSelect) {
                    labSelect.addEventListener('change', submitFilters);
                }
            });
        </script>
    @endpush
</x-app-layout>

