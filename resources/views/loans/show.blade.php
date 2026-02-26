<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-smaba-text leading-tight">
                    {{ __('loans.details.title') }} #{{ $loan->id }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('loans.labels.diajukan_oleh') ?? __('bookings.labels.diajukan_oleh') }}: <span class="font-semibold">{{ $loan->user->name }}</span></p>
            </div>
            <a href="{{ route('loans.index') }}" class="mt-3 sm:mt-0 text-sm font-semibold text-smaba-light-blue hover:text-smaba-dark-blue transition-colors">
                &larr; {{ __('loans.details.back_to_list') }}
            </a>
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

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8" data-aos="fade-in" data-aos-once="true">

                {{-- Kolom Kiri: Detail Pengajuan & Daftar Item --}}
                <div class="lg:col-span-3 space-y-6">
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-smaba-text mb-4">{{ __('loans.details.info') }}</h3>
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                                <div><dt class="font-medium text-gray-500">{{ __('loans.details.applicant') }}</dt><dd class="mt-1 font-semibold text-gray-800">{{ $loan->user->name }}</dd></div>
                                <div><dt class="font-medium text-gray-500">{{ __('loans.details.submission_date') }}</dt><dd class="mt-1 font-semibold text-gray-800">{{ $loan->created_at->format('d F Y, H:i') }}</dd></div>
                                <div><dt class="font-medium text-gray-500">{{ __('bookings.form.lab') }}</dt><dd class="mt-1 font-semibold text-gray-800">{{ $loan->laboratorium }}</dd></div>
                                <div><dt class="font-medium text-gray-500">{{ __('loans.details.plan_borrow_date') }}</dt><dd class="mt-1 font-semibold text-gray-800">{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d F Y') }}</dd></div>
                                @if($loan->tanggal_kembali)
                                <div><dt class="font-medium text-gray-500">{{ __('loans.details.return_date') }}</dt><dd class="mt-1 font-semibold text-gray-800">{{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d F Y') }}</dd></div>
                                @endif
                                <div class="sm:col-span-2"><dt class="font-medium text-gray-500">{{ __('loans.details.borrower_notes') }}</dt><dd class="mt-1 text-gray-700 bg-gray-50 p-3 rounded-md whitespace-pre-wrap">{{ $loan->catatan ?? __('loans.messages.no_notes') }}</dd></div>
                                @if($loan->admin_notes)
                                <div class="sm:col-span-2"><dt class="font-medium text-gray-500">{{ __('loans.details.admin_notes') }}</dt><dd class="mt-1 text-gray-700 bg-yellow-50 p-3 rounded-md whitespace-pre-wrap">{{ $loan->admin_notes }}</dd></div>
                                @endif
                            </dl>
                        </div>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                         <div class="p-6">
                            <h3 class="text-xl font-bold text-smaba-text mb-4">{{ __('loans.details.requested_items') }}</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">{{ __('loans.details.item_name') }}</th>
                                            <th class="py-2 px-4 text-center text-xs font-medium text-gray-500 uppercase">{{ __('loans.details.quantity') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($loan->items as $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="py-3 px-4 text-sm font-semibold text-gray-800">{{ $item->nama_alat }}</td>
                                            <td class="py-3 px-4 text-sm text-center">{{ $item->pivot->jumlah }} {{ $item->satuan }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Status & Aksi Admin --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-smaba-text mb-4">{{ __('loans.details.submission_status') }}</h3>
                             <div class="text-center">
                                @if($loan->status == 'pending') <span class="px-4 py-2 text-sm font-bold leading-none text-yellow-800 bg-yellow-100 rounded-full">{{ __('loans.messages.waiting_approval') }}</span>
                                @elseif($loan->status == 'approved') <span class="px-4 py-2 text-sm font-bold leading-none text-green-800 bg-green-100 rounded-full">{{ __('common.status.approved') }}</span>
                                @elseif($loan->status == 'rejected') <span class="px-4 py-2 text-sm font-bold leading-none text-red-800 bg-red-100 rounded-full">{{ __('common.status.rejected') }}</span>
                                @elseif($loan->status == 'completed') <span class="px-4 py-2 text-sm font-bold leading-none text-gray-800 bg-gray-100 rounded-full">{{ __('common.status.completed') }}</span>
                                @elseif($loan->status == 'Terlambat')
                                <span class="px-4 py-2 text-sm font-bold leading-none text-red-800 bg-red-100 rounded-full">{{ __('loans.status.overdue') }}</span>
                                @endif
                            </div>

                            @can('is-admin')
                                @if($loan->status == 'pending')
                                    <form action="{{ route('loans.update', $loan->id) }}" method="POST" class="mt-6 border-t pt-6 space-y-4">
                                        @csrf @method('PATCH')
                                        <h4 class="font-semibold text-gray-700">{{ __('loans.details.follow_up') }}</h4>
                                        <div><label for="admin_notes" class="block text-sm font-medium text-gray-700">{{ __('loans.details.optional_notes') }}</label><textarea name="admin_notes" id="admin_notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue" placeholder="{{ __('loans.details.notes_placeholder') }}"></textarea></div>
                                        <div class="flex space-x-3">
                                            <button type="submit" name="status" value="approved" class="w-full py-2 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 shadow-md transition-colors">{{ __('loans.actions.approve') }}</button>
                                            <button type="submit" name="status" value="rejected" class="w-full py-2 px-4 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 shadow-md transition-colors">{{ __('loans.actions.reject') }}</button>
                                        </div>
                                    </form>
                                @elseif($loan->status == 'approved')
                                    <form action="{{ route('loans.update', $loan->id) }}" method="POST" class="mt-6 border-t pt-6">
                                        @csrf @method('PATCH')
                                        <h4 class="font-semibold text-gray-700 mb-2">{{ __('loans.details.return_actions') }}</h4>
                                        <button type="submit" name="status" value="completed" class="w-full py-3 px-4 bg-smaba-dark-blue text-white font-semibold rounded-lg hover:bg-smaba-light-blue shadow-md transition-colors">{{ __('loans.details.mark_as_returned') }}</button>
                                    </form>
                                @endif
                            @endcan
                        </div>
                    </div>

                    {{-- KARTU AKSI ADMIN TAMBAHAN --}}
                    @can('is-admin')
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-smaba-text mb-4">{{ __('loans.details.other_actions') }}</h3>
                            <div class="flex space-x-2 mt-4">
                                {{-- Tombol Edit (jika ada route 'items.edit') --}}
                                {{-- <a href="{{ route('items.edit', $item->id) }}" class="flex-1 text-center py-2 px-3 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-md transition-colors text-sm">Edit</a> --}}
                                
                                {{-- PENAMBAHAN: Tombol Hapus --}}
                                {{-- Hanya tampil jika status 'pending' atau 'rejected' --}}
                                @if(in_array($loan->status, ['pending', 'rejected']))
                                <form action="{{ route('loans.destroy', $loan->id) }}" method="POST" class="delete-form flex-1" onsubmit="return confirm('{{ __('loans.messages.delete_confirm') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-center py-2 px-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md transition-colors text-sm">{{ __('loans.actions.delete_request') ?? __('loans.actions.delete') }}</button>
                                </form>
                                @else
                                <p class="text-xs text-gray-400 italic flex-1 text-center py-2">{{ __('loans.messages.no_other_actions') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
