<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <a href="{{ route('loans.index') }}" class="text-xs font-bold text-slate-400 hover:text-indigo-600 transition-colors uppercase tracking-widest flex items-center gap-1 mb-2">
                    <i class="fas fa-arrow-left"></i> {{ __('loans.details.back_to_list') }}
                </a>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-3">
                    {{ __('loans.details.title') }} 
                    <span class="text-slate-300 font-light">#{{ $loan->id }}</span>
                </h2>
            </div>
            
            {{-- Status Badge di Header --}}
            <div>
                @if($loan->status == 'pending')
                    <span class="px-4 py-2 inline-flex text-sm font-bold rounded-xl bg-amber-50 text-amber-700 border border-amber-200 shadow-sm whitespace-nowrap">
                        <i class="fas fa-clock mr-2 object-center mt-[2px]"></i> {{ __('common.status.pending') }}
                    </span>
                @elseif($loan->status == 'approved')
                    <span class="px-4 py-2 inline-flex text-sm font-bold rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-sm whitespace-nowrap">
                        <i class="fas fa-check-circle mr-2 object-center mt-[2px]"></i> {{ __('common.status.approved') }}
                    </span>
                @elseif($loan->status == 'rejected')
                    <span class="px-4 py-2 inline-flex text-sm font-bold rounded-xl bg-red-50 text-red-700 border border-red-200 shadow-sm whitespace-nowrap">
                        <i class="fas fa-times-circle mr-2 object-center mt-[2px]"></i> {{ __('common.status.rejected') }}
                    </span>
                @elseif($loan->status == 'completed')
                    <span class="px-4 py-2 inline-flex text-sm font-bold rounded-xl bg-slate-100 text-slate-700 border border-slate-300 shadow-sm whitespace-nowrap">
                        <i class="fas fa-box-check mr-2 object-center mt-[2px]"></i> {{ __('common.status.completed') }}
                    </span>
                @elseif($loan->status == 'Terlambat')
                    <span class="px-4 py-2 inline-flex text-sm font-bold rounded-xl bg-rose-50 text-rose-700 border border-rose-200 shadow-sm whitespace-nowrap animate-pulse">
                        <i class="fas fa-exclamation-triangle mr-2 object-center mt-[2px]"></i> {{ __('loans.status.overdue') }}
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-emerald-500 mr-3 text-lg"></i>
                        <p class="font-bold text-emerald-800">{{ __('common.messages.success') }}: <span class="font-normal text-emerald-700">{{ session('success') }}</span></p>
                    </div>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm" role="alert">
                    <div class="flex">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3 text-lg mt-0.5"></i>
                        <div>
                            <p class="font-bold text-red-800">{{ __('common.messages.error_title') }}:</p>
                            <ul class="mt-1 list-disc list-inside text-red-700 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" data-aos="fade-up" data-aos-once="true">
                
                {{-- KOLOM KIRI: Informasi & Item (Lebar 2 Kolom) --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Card: Informasi Peminjam --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
                        <div class="p-6 sm:p-8">
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <i class="fas fa-user-circle text-indigo-500 text-lg"></i> {{ __('loans.details.info') }}
                            </h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                {{-- Profil Peminjam --}}
                                <div class="flex items-start gap-4 col-span-1 sm:col-span-2 bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-400 to-blue-600 flex items-center justify-center text-white font-bold text-xl shadow-sm flex-shrink-0">
                                        {{ substr($loan->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">{{ __('loans.details.applicant') }}</div>
                                        <div class="text-lg font-bold text-slate-800">{{ $loan->user->name }}</div>
                                        <div class="text-sm text-slate-500">{{ $loan->user->email }}</div>
                                    </div>
                                </div>
                                
                                {{-- Detail Data --}}
                                <div>
                                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ __('bookings.form.lab') }}</div>
                                    <div class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                        <i class="fas fa-flask text-indigo-400"></i> {{ $loan->laboratorium }}
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ __('loans.details.submission_date') }}</div>
                                    <div class="text-sm font-bold text-slate-800">
                                        {{ $loan->created_at->format('d F Y, H:i') }}
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ __('loans.details.plan_borrow_date') }}</div>
                                    <div class="text-sm font-bold text-slate-800">
                                        {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d F Y') }}
                                    </div>
                                </div>
                                
                                @if($loan->tanggal_kembali)
                                <div>
                                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ __('loans.details.return_date') }}</div>
                                    <div class="text-sm font-bold text-emerald-600">
                                        {{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d F Y') }}
                                    </div>
                                </div>
                                @endif
                                
                                {{-- Notes --}}
                                <div class="col-span-1 sm:col-span-2 mt-2">
                                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">{{ __('loans.details.borrower_notes') }}</div>
                                    <div class="bg-white border border-slate-200 text-slate-700 text-sm p-4 rounded-xl leading-relaxed whitespace-pre-wrap shadow-sm">
                                        @if($loan->catatan)
                                            {{ $loan->catatan }}
                                        @else
                                            <span class="italic text-slate-400">{{ __('loans.messages.no_notes') }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($loan->admin_notes)
                                <div class="col-span-1 sm:col-span-2 mt-2">
                                    <div class="text-[11px] font-bold text-amber-500 uppercase tracking-widest mb-2 flex items-center gap-1">
                                        <i class="fas fa-comment-dots"></i> {{ __('loans.details.admin_notes') }}
                                    </div>
                                    <div class="bg-amber-50 border border-amber-200 text-amber-900 text-sm p-4 rounded-xl leading-relaxed whitespace-pre-wrap shadow-sm">
                                        {{ $loan->admin_notes }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    {{-- Card: Daftar Item --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative">
                        <div class="p-6 sm:p-8">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest flex items-center gap-2">
                                    <i class="fas fa-boxes text-indigo-500 text-lg"></i> {{ __('loans.details.requested_items') }}
                                </h3>
                                <div class="bg-slate-100 text-slate-600 text-xs font-bold px-3 py-1 rounded-lg">
                                    {{ $loan->items->count() }} Item
                                </div>
                            </div>
                            
                            <div class="overflow-hidden border border-slate-100 rounded-xl">
                                <table class="min-w-full text-left">
                                    <thead class="bg-slate-50 border-b border-slate-100">
                                        <tr>
                                            <th class="py-3 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ __('loans.details.item_name') }}</th>
                                            <th class="py-3 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">{{ __('loans.details.quantity') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @foreach($loan->items as $item)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="py-4 px-5">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">
                                                        <i class="fas fa-flask"></i>
                                                    </div>
                                                    <div>
                                                        <div class="font-bold text-sm text-slate-800">{{ $item->nama_alat }}</div>
                                                        <div class="text-xs text-slate-500">{{ $item->laboratorium }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-5 text-right">
                                                <span class="inline-flex items-center justify-center bg-indigo-50 text-indigo-700 font-bold px-3 py-1 rounded-lg border border-indigo-100 text-sm shadow-sm">
                                                    {{ $item->pivot->jumlah }} <span class="ml-1 text-xs opacity-80">{{ $item->satuan }}</span>
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: Timeline & Aksi Admin (Lebar 1 Kolom) --}}
                <div class="space-y-6">
                    
                    {{-- Card: Visual Timeline Alur --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative">
                        <div class="p-6">
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <i class="fas fa-stream text-indigo-500 text-lg"></i> Alur Peminjaman
                            </h3>
                            
                            <div class="relative ms-4">
                                {{-- Garis vertikal background --}}
                                <div class="absolute left-[11px] top-4 bottom-4 w-0.5 bg-slate-100"></div>

                                {{-- STEP 1: Pengajuan (Selalu Aktif) --}}
                                <div class="relative flex gap-5 pb-8 group">
                                    <div class="absolute left-0 top-1 w-6 h-6 rounded-full {{ in_array($loan->status, ['pending', 'approved', 'completed', 'Terlambat', 'rejected']) ? 'bg-emerald-500 ring-4 ring-emerald-50' : 'bg-slate-200' }} flex items-center justify-center z-10">
                                        <i class="fas fa-check text-[10px] text-white"></i>
                                    </div>
                                    <div class="ml-10">
                                        <h4 class="text-sm font-bold text-slate-800">Pengajuan Dibuat</h4>
                                        <p class="text-xs text-slate-500 mt-1">{{ $loan->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>

                                {{-- STEP 2: Persetujuan Admin --}}
                                @php
                                    $step2Active = in_array($loan->status, ['approved', 'completed', 'Terlambat']);
                                    $step2Rejected = $loan->status === 'rejected';
                                    $step2Color = $step2Rejected ? 'bg-red-500 ring-4 ring-red-50' : ($step2Active ? 'bg-emerald-500 ring-4 ring-emerald-50' : 'bg-slate-200 ring-4 ring-white');
                                    $step2Icon = $step2Rejected ? 'fa-times' : ($step2Active ? 'fa-check' : 'fa-hourglass-half');
                                    $step2IconColor = $step2Rejected || $step2Active ? 'text-white' : 'text-slate-400';
                                @endphp
                                <div class="relative flex gap-5 pb-8 group">
                                    {{-- Coretan garis progres ke step 2 jika aktif --}}
                                    @if($step2Active || $step2Rejected)
                                        <div class="absolute left-[11px] -top-8 bottom-4 w-0.5 bg-emerald-500"></div>
                                    @endif
                                    <div class="absolute left-0 top-1 w-6 h-6 rounded-full {{ $step2Color }} flex items-center justify-center z-10 transition-colors">
                                        <i class="fas {{ $step2Icon }} text-[10px] {{ $step2IconColor }}"></i>
                                    </div>
                                    <div class="ml-10">
                                        @if($step2Rejected)
                                            <h4 class="text-sm font-bold text-red-600">Pengajuan Ditolak</h4>
                                            <p class="text-xs text-slate-500 mt-1">{{ $loan->updated_at->format('d M Y, H:i') }}</p>
                                        @elseif($step2Active)
                                            <h4 class="text-sm font-bold text-emerald-600">Disetujui Admin</h4>
                                            <p class="text-xs text-slate-500 mt-1">Siap dipinjam pada {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M') }}</p>
                                        @else
                                            <h4 class="text-sm font-bold text-slate-400">Persetujuan Admin</h4>
                                            <p class="text-xs text-slate-500 mt-1">Menunggu konfirmasi admin</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- STEP 3: Pengembalian --}}
                                @if(!$step2Rejected)
                                    @php
                                        $step3Active = $loan->status === 'completed';
                                        $step3Late = $loan->status === 'Terlambat';
                                        $step3Color = $step3Late ? 'bg-rose-500 ring-4 ring-rose-50' : ($step3Active ? 'bg-emerald-500 ring-4 ring-emerald-50' : 'bg-slate-200 ring-4 ring-white');
                                        $step3Icon = $step3Late ? 'fa-exclamation' : ($step3Active ? 'fa-check' : 'fa-box');
                                        $step3IconColor = $step3Late || $step3Active ? 'text-white' : 'text-slate-400';
                                    @endphp
                                    <div class="relative flex gap-5 group">
                                        {{-- Coretan garis progres ke step 3 jika aktif --}}
                                        @if($step3Active || $step3Late)
                                            <div class="absolute left-[11px] -top-8 bottom-4 w-0.5 bg-emerald-500"></div>
                                        @endif
                                        <div class="absolute left-0 top-1 w-6 h-6 rounded-full {{ $step3Color }} flex items-center justify-center z-10 transition-colors">
                                            <i class="fas {{ $step3Icon }} text-[10px] {{ $step3IconColor }}"></i>
                                        </div>
                                        <div class="ml-10">
                                            @if($step3Late)
                                                <h4 class="text-sm font-bold text-rose-600">Terlambat Dikembalikan</h4>
                                                <p class="text-xs text-slate-400 mt-1">Melewati tenggat waktu</p>
                                            @elseif($step3Active)
                                                <h4 class="text-sm font-bold text-emerald-600">Sudah Dikembalikan</h4>
                                                <p class="text-xs text-slate-500 mt-1">{{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M Y, H:i') }}</p>
                                            @else
                                                <h4 class="text-sm font-bold text-slate-400">Pengembalian Item</h4>
                                                <p class="text-xs text-slate-500 mt-1">Belum dikembalikan</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Card: Aksi Admin --}}
                    @can('is-admin')
                        @if(in_array($loan->status, ['pending', 'approved']))
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative" data-aos="fade-up" data-aos-delay="100">
                            <div class="p-6">
                                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <i class="fas fa-cog text-indigo-500 text-lg"></i> {{ __('loans.details.follow_up') }}
                                </h3>
                                
                                @if($loan->status == 'pending')
                                    <form action="{{ route('loans.update', $loan->id) }}" method="POST" class="space-y-4">
                                        @csrf @method('PATCH')
                                        <div>
                                            <label for="admin_notes" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">{{ __('loans.details.optional_notes') }}</label>
                                            <textarea name="admin_notes" id="admin_notes" rows="3" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500/30 text-sm p-3 bg-slate-50 hover:bg-white focus:bg-white transition-colors" placeholder="{{ __('loans.details.notes_placeholder') }}"></textarea>
                                        </div>
                                        <div class="flex gap-3">
                                            <button type="submit" name="status" value="rejected" class="flex-1 py-2.5 px-4 bg-white border border-red-200 text-red-600 font-bold rounded-xl hover:bg-red-50 hover:border-red-300 transition-all shadow-sm text-sm">
                                                <i class="fas fa-times mr-1"></i> {{ __('loans.actions.reject') }}
                                            </button>
                                            <button type="submit" name="status" value="approved" class="flex-1 py-2.5 px-4 bg-emerald-600 text-white font-bold rounded-xl shadow-[0_4px_14px_0_rgb(16,185,129,0.39)] hover:shadow-[0_6px_20px_rgba(16,185,129,0.23)] hover:-translate-y-0.5 transition-all text-sm">
                                                <i class="fas fa-check mr-1"></i> {{ __('loans.actions.approve') }}
                                            </button>
                                        </div>
                                    </form>
                                @elseif($loan->status == 'approved')
                                    <form action="{{ route('loans.update', $loan->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <p class="text-sm text-slate-500 mb-4">{{ __('loans.details.return_actions') }}</p>
                                        <button type="submit" name="status" value="completed" class="w-full py-3 px-4 bg-indigo-600 text-white font-bold rounded-xl shadow-[0_4px_14px_0_rgb(79,70,229,0.39)] hover:shadow-[0_6px_20px_rgba(79,70,229,0.23)] hover:-translate-y-0.5 transition-all">
                                            <i class="fas fa-box-check mr-2"></i> {{ __('loans.details.mark_as_returned') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- Card Aksi Hapus (Danger Zone) --}}
                        @if(in_array($loan->status, ['pending', 'rejected']))
                        <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden relative" data-aos="fade-up" data-aos-delay="200">
                            <div class="p-6">
                                <h3 class="text-sm font-bold text-red-600 uppercase tracking-widest mb-2 flex items-center gap-2">
                                    <i class="fas fa-exclamation-triangle"></i> Danger Zone
                                </h3>
                                <p class="text-xs text-slate-500 mb-4">{{ __('loans.messages.delete_confirm') }} Data yang dihapus tidak dapat dikembalikan.</p>
                                
                                <form action="{{ route('loans.destroy', $loan->id) }}" method="POST" class="delete-form" onsubmit="return confirm('{{ __('loans.messages.delete_confirm') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-center py-2.5 px-4 bg-white border border-red-200 text-red-600 hover:bg-red-50 hover:border-red-300 font-bold rounded-xl shadow-sm transition-all text-sm">
                                        <i class="fas fa-trash-alt mr-2"></i> {{ __('loans.actions.delete_request') ?? __('loans.actions.delete') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif
                    @endcan

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
