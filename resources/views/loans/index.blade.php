<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                    @if (auth()->user()->role == 'admin')
                        {{ __('loans.title_admin') }}
                    @else
                        {{ __('loans.title_user') }}
                    @endif
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    @if (auth()->user()->role == 'admin')
                        {{ __('loans.subtitle_admin') }}
                    @else
                        {{ __('loans.subtitle_user') }}
                    @endif
                </p>
            </div>
            
            @unless (auth()->user()->role == 'admin')
                <a href="{{ route('loans.create') }}" class="mt-3 sm:mt-0 px-5 py-2 bg-smaba-dark-blue text-white rounded-lg hover:bg-smaba-light-blue font-semibold text-sm shadow-md transition-colors duration-300 ease-in-out transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i> {{ __('loans.actions.create_new') }}
                </a>
            @endunless
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

            <div data-aos="fade-up" data-aos-duration="500" data-aos-once="true">
                {{-- Form Filter Status Otomatis --}}
                <div class="mb-6 bg-white overflow-hidden border border-gray-100 shadow-sm sm:rounded-xl">
                    <form action="{{ route('loans.index') }}" method="GET" class="p-4 sm:p-6" id="filter-form">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:space-x-4">
                            <div class="flex items-center space-x-2">
                                <label for="status" class="text-sm font-medium text-gray-700">{{ __('common.labels.status') }}:</label>
                                <select name="status" id="status" class="w-full sm:w-auto rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue text-sm">
                                    <option value="">{{ __('loans.filters.all_status') }}</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('common.status.pending') }}</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('common.status.approved') }}</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('common.status.rejected') }}</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('common.status.completed') }}</option>
                                    <option value="Terlambat" @selected(request('status') == 'Terlambat')>{{ __('loans.status.overdue') }}</option>
                                </select>
                            </div>
                            <div class="flex items-center space-x-2">
                                <label for="laboratorium" class="text-sm font-medium text-gray-700">{{ __('common.nav.lab_services') }}:</label>
                                <select name="laboratorium" id="laboratorium" class="w-full sm:w-auto rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue text-sm">
                                    <option value="">{{ __('loans.filters.all_labs') }}</option>
                                    <option value="Biologi" @selected(request('laboratorium') === 'Biologi')>Biologi</option>
                                    <option value="Fisika" @selected(request('laboratorium') === 'Fisika')>Fisika</option>
                                    <option value="Bahasa" @selected(request('laboratorium') === 'Bahasa')>Bahasa</option>
                                </select>
                            </div>
                            <i id="loading-spinner" class="fas fa-spinner fa-spin text-gray-500 hidden"></i>
                        </div>
                    </form>
                </div>

                {{-- Tabel Daftar Peminjaman --}}
                <div class="bg-white overflow-hidden border border-gray-100 shadow-sm sm:rounded-xl">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('loans.table.id') }}</th>
                                    @if (auth()->user()->role == 'admin')
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('loans.table.borrower') }}</th>
                                    @endif
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('loans.table.borrow_date') }}</th>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">{{ __('loans.table.item_count') }}</th>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('loans.table.lab') }}</th>
                                    <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('loans.table.status') }}</th>
                                    <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('loans.table.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 divide-y divide-gray-200">
                                @forelse ($loans as $loan)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-4 px-6 text-sm font-medium text-gray-500">#{{ $loan->id }}</td>
                                        @if (auth()->user()->role == 'admin')
                                            <td class="py-4 px-6 text-sm font-semibold text-gray-900">{{ $loan->user->name }}</td>
                                        @endif
                                        <td class="py-4 px-6 text-sm">{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y') }}</td>
                                        <td class="py-4 px-6 text-sm hidden sm:table-cell">{{ $loan->items->count() }} {{ __('loans.units.item') }}</td>
                                        <td class="py-4 px-6 text-sm font-semibold text-gray-800">{{ $loan->laboratorium }}</td>
                                        <td class="py-4 px-6 text-center">
                                            @if($loan->status == 'pending')
                                                <span class="px-3 py-1 text-xs font-bold leading-none text-yellow-800 bg-yellow-100 rounded-full">{{ __('common.status.pending') }}</span>
                                            @elseif($loan->status == 'approved')
                                                <span class="px-3 py-1 text-xs font-bold leading-none text-green-800 bg-green-100 rounded-full">{{ __('common.status.approved') }}</span>
                                            @elseif($loan->status == 'rejected')
                                                <span class="px-3 py-1 text-xs font-bold leading-none text-red-800 bg-red-100 rounded-full">{{ __('common.status.rejected') }}</span>
                                            @elseif($loan->status == 'completed')
                                                <span class="px-3 py-1 text-xs font-bold leading-none text-gray-800 bg-gray-100 rounded-full">{{ __('common.status.completed') }}</span>
                                            @elseif($loan->status == 'Terlambat')
                                                <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full">{{ __('loans.status.overdue') }}</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <a href="{{ route('loans.show', $loan->id) }}" class="px-4 py-2 bg-smaba-dark-blue text-white rounded-md hover:bg-smaba-light-blue font-semibold text-xs shadow-sm transition-colors duration-300">
                                                {{ __('common.buttons.details') }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->role == 'admin' ? '7' : '6' }}" class="py-12">
                                            <div class="text-center">
                                                <div class="w-20 h-20 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                                    <i class="fas fa-clipboard-list text-3xl text-gray-400"></i>
                                                </div>
                                                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ __('loans.empty.title') }}</h3>
                                                <p class="text-sm text-gray-500 mb-4">{{ __('loans.empty.description') }}</p>
                                                @unless (auth()->user()->role == 'admin')
                                                <a href="{{ route('loans.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                                                    <i class="fas fa-plus"></i> {{ __('loans.empty.action') }}
                                                </a>
                                                @endunless
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-200">
                        {{ $loans->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const filterForm = document.getElementById('filter-form');
                const statusSelect = document.getElementById('status');
                const labSelect = document.getElementById('laboratorium');

                function submitWithSpinner() {
                    filterForm.submit();
                    document.getElementById('loading-spinner').classList.remove('hidden');
                }
                statusSelect.addEventListener('change', submitWithSpinner);
                labSelect.addEventListener('change', submitWithSpinner);
            });
        </script>
    @endpush
</x-app-layout>
