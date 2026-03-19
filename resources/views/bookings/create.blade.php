<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('bookings.index') }}" class="text-slate-400 hover:text-indigo-600 transition-colors w-10 h-10 flex items-center justify-center bg-white rounded-full shadow-sm border border-slate-200">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">{{ __('bookings.actions.create') }}</h2>
                <p class="text-sm font-medium text-slate-500 mt-1">Ajukan jadwal penggunaan laboratorium secara sistematis.</p>
            </div>
        </div>
    </x-slot>

    {{-- ALPINE CONTEXT UNTUK STEPPER & MODAL --}}
    <div x-data="bookingForm()" class="max-w-4xl mx-auto py-8 sm:px-6 lg:px-8 relative pb-20">

        {{-- Validation Errors (Global) --}}
        @if ($errors->any())
            <div class="mb-8 bg-red-50 border border-red-100 p-4 rounded-xl shadow-sm flex items-start gap-4" data-aos="fade-in">
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 flex-shrink-0 mt-0.5"><i class="fas fa-exclamation-triangle"></i></div>
                <div>
                    <h4 class="font-bold text-red-800">{{ __('common.messages.error_title') }}</h4>
                    <ul class="mt-1 text-sm text-red-700 list-inside list-disc">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Stepper Progress Bar --}}
        <div class="mb-10 flex items-center justify-center max-w-xl mx-auto">
            <div class="flex items-center w-full justify-between relative">
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-slate-200 rounded-full z-0"></div>
                <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-indigo-500 rounded-full z-0 transition-all duration-500" :style="'width: ' + ((step - 1) * 100) + '%'"></div>
                
                {{-- Step 1 --}}
                <div class="relative z-10 flex flex-col items-center gap-2 cursor-pointer group" @click="step = 1">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300 shadow-sm"
                         :class="step >= 1 ? 'bg-indigo-600 text-white ring-4 ring-indigo-50 shadow-indigo-200/50' : 'bg-white text-slate-400 border border-slate-300 group-hover:border-indigo-400'">
                        1
                    </div>
                    <span class="text-[11px] font-extrabold uppercase tracking-widest" :class="step >= 1 ? 'text-indigo-600' : 'text-slate-400'">Informasi</span>
                </div>
                
                {{-- Step 2 --}}
                <div class="relative z-10 flex flex-col items-center gap-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300 shadow-sm"
                         :class="step >= 2 ? 'bg-indigo-600 text-white ring-4 ring-indigo-50 shadow-indigo-200/50' : 'bg-white text-slate-400 border border-slate-300'">
                        2
                    </div>
                    <span class="text-[11px] font-extrabold uppercase tracking-widest" :class="step >= 2 ? 'text-indigo-600' : 'text-slate-400'">Penjadwalan</span>
                </div>
            </div>
        </div>

        <form id="bookingCreateForm" action="{{ route('bookings.store') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">

            {{-- STEP 1: INFORMASI KEGIATAN --}}
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                <x-form.section title="Informasi Kegiatan" description="Berikan rincian mengenai tujuan penggunaan lab." icon="fa-info-circle">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="col-span-1 md:col-span-2">
                            <x-form.input 
                                name="tujuan_kegiatan" 
                                label="{{ __('bookings.form.purpose') }}" 
                                placeholder="Misal: Praktikum Enzim Katalase" 
                                icon="fa-bullseye"
                                x-model="formData.tujuan"
                                required />
                        </div>

                        <div class="col-span-1">
                            <x-form.input 
                                name="mata_pelajaran" 
                                label="{{ __('bookings.form.subject') }}" 
                                placeholder="Misal: Biologi" 
                                icon="fa-book"
                                x-model="formData.mapel" />
                        </div>

                        <div class="col-span-1">
                            <x-form.select 
                                name="laboratorium" 
                                label="{{ __('bookings.form.lab') }}" 
                                icon="fa-flask" 
                                x-model="formData.lab"
                                required>
                                <option value="">{{ __('bookings.form.select_lab') }}</option>
                                <option value="Komputer" {{ old('laboratorium') == 'Komputer' ? 'selected' : '' }}>Lab Komputer</option>
                                <option value="Biologi" {{ old('laboratorium') == 'Biologi' ? 'selected' : '' }}>Lab Biologi</option>
                                <option value="Fisika" {{ old('laboratorium') == 'Fisika' ? 'selected' : '' }}>Lab Fisika</option>
                                <option value="Kimia" {{ old('laboratorium') == 'Kimia' ? 'selected' : '' }}>Lab Kimia</option>
                                <option value="Bahasa" {{ old('laboratorium') == 'Bahasa' ? 'selected' : '' }}>Lab Bahasa</option>
                            </x-form.select>
                        </div>
                    </div>
                    
                </x-form.section>

                <div class="sticky bottom-0 z-40 bg-white/80 backdrop-blur-md border border-slate-200 rounded-2xl p-4 sm:px-6 sm:py-4 flex justify-between items-center shadow-[0_-4px_20px_-10px_rgba(0,0,0,0.1)] transition-all">
                    <a href="{{ route('bookings.index') }}" class="text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors">Batal</a>
                    <button type="button" @click="if(isStep1Valid()) step = 2; else alert('Harap isi Tujuan Kegiatan dan Laboratorium terlebih dahulu.');" class="inline-flex items-center px-6 py-2.5 bg-slate-900 border border-transparent rounded-xl font-bold text-sm text-white tracking-wide hover:bg-slate-800 focus:bg-slate-700 active:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 shadow-lg transition ease-in-out duration-150">
                        Langkah Selanjutnya <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </button>
                </div>
            </div>

            {{-- STEP 2: PENJADWALAN --}}
            <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                <x-form.section title="Pengaturan Waktu" description="Tentukan tanggal dan jam pelaksanaan kegiatan di laboratorium." icon="fa-clock">
                    
                    <div class="bg-indigo-50 rounded-xl p-4 mb-8 flex gap-4 border border-indigo-100">
                        <i class="fas fa-lightbulb text-indigo-500 text-xl mt-1"></i>
                        <div>
                            <h4 class="font-bold text-indigo-900 text-sm">Pro Tip Penjadwalan</h4>
                            <p class="text-[13px] text-indigo-700 mt-0.5 leading-relaxed">Pastikan mengecek kalender laboratorium terlebih dahulu untuk menghindari bentrok jadwal. Jam kerja efektif lab adalah pukul 07:00 hingga 16:00 WIB.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="col-span-1 md:col-span-2">
                            <x-form.input 
                                type="date"
                                name="tanggal" 
                                id="tanggal_booking"
                                label="{{ __('bookings.form.date') }}" 
                                icon="fa-calendar-day"
                                x-model="formData.tanggal"
                                required />
                        </div>

                        <div class="col-span-1">
                            <x-form.input 
                                type="time"
                                name="waktu_mulai" 
                                id="waktu_mulai"
                                label="{{ __('bookings.form.start_time') }}" 
                                icon="fa-hourglass-start"
                                x-model="formData.waktu_mulai"
                                required />
                        </div>

                        <div class="col-span-1">
                            <x-form.input 
                                type="time"
                                name="waktu_selesai" 
                                id="waktu_selesai"
                                label="{{ __('bookings.form.end_time') }}" 
                                icon="fa-hourglass-end"
                                x-model="formData.waktu_selesai"
                                required />
                        </div>
                    </div>

                </x-form.section>

                <div class="sticky bottom-0 z-40 bg-white/80 backdrop-blur-md border border-slate-200 rounded-2xl p-4 sm:px-6 sm:py-4 flex justify-between items-center shadow-[0_-4px_20px_-10px_rgba(0,0,0,0.1)] transition-all">
                    <button type="button" @click="step = 1" class="text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </button>
                    <button type="button" @click="if(isStep2Valid()) showConfirm = true; else alert('Harap lengkapi tanggal dan jam pelaksanaan.');" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 rounded-xl font-bold text-sm text-white shadow-[0_4px_14px_0_rgba(79,70,229,0.39)] hover:shadow-[0_6px_20px_rgba(79,70,229,0.23)] transition-all transform hover:-translate-y-0.5">
                        <i class="fas fa-paper-plane mr-2 text-xs"></i> Ajukan Booking
                    </button>
                </div>
            </div>

            {{-- CONFIRMATION MODAL --}}
            <template x-teleport="body">
                <div x-show="showConfirm" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0" x-cloak>
                    <div x-show="showConfirm" 
                        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showConfirm = false"></div>
                    
                    <div x-show="showConfirm" 
                        x-transition:enter="ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95" 
                        class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-lg w-full relative z-10 overflow-hidden">
                        
                        <div class="px-6 py-5 border-b border-indigo-100 bg-indigo-50/50 flex justify-between items-center">
                            <h3 class="font-extrabold text-lg text-indigo-900 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm bg-indigo-100 text-indigo-600"><i class="fas fa-check-double"></i></div>
                                Konfirmasi Pengajuan
                            </h3>
                        </div>
                        
                        <div class="p-6">
                            <p class="text-sm text-slate-600 mb-6 font-medium leading-relaxed">Pastikan ringkasan data booking Anda di bawah ini sudah benar sebelum dikirim untuk persetujuan Admin Lab.</p>
                            
                            <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 space-y-3">
                                <div class="flex justify-between border-b border-slate-200 pb-2">
                                    <span class="text-xs font-bold text-slate-400 uppercase">Tujuan</span>
                                    <span class="text-sm font-bold text-slate-800" x-text="formData.tujuan"></span>
                                </div>
                                <div class="flex justify-between border-b border-slate-200 pb-2">
                                    <span class="text-xs font-bold text-slate-400 uppercase">Lab</span>
                                    <span class="text-sm font-bold text-indigo-600" x-text="formData.lab"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-xs font-bold text-slate-400 uppercase">Pelaksanaan</span>
                                    <span class="text-sm font-bold text-emerald-600">
                                        <span x-text="formData.tanggal"></span> (<span x-text="formData.waktu_mulai"></span> - <span x-text="formData.waktu_selesai"></span>)
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-100">
                            <button type="button" @click="showConfirm = false" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-colors text-sm">Batal</button>
                            <button type="button" @click="$refs.submitBtnHidden.click(); showConfirm = false" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-[0_4px_14px_0_rgba(79,70,229,0.39)] hover:bg-indigo-500 transition-all hover:-translate-y-0.5">Ya, Kirim Sekarang</button>
                            {{-- Native Form Submit Button Hidden --}}
                            <button type="submit" x-ref="submitBtnHidden" class="hidden"></button>
                        </div>
                    </div>
                </div>
            </template>
        </form>
    </div>

    @push('scripts')
    <script>
        function bookingForm() {
            return {
                step: {{ old('step') ?: 1 }},
                showConfirm: false,
                formData: {
                    tujuan: '{{ old('tujuan_kegiatan') }}',
                    mapel: '{{ old('mata_pelajaran') }}',
                    lab: '{{ old('laboratorium') }}',
                    tanggal: '{{ old('tanggal', request('tanggal')) }}',
                    waktu_mulai: '{{ old('waktu_mulai') }}',
                    waktu_selesai: '{{ old('waktu_selesai') }}'
                },
                init() {
                    // Pre-fill logic or DOM watchers can go here
                },
                isStep1Valid() {
                    return this.formData.tujuan.trim() !== '' && this.formData.lab !== '';
                },
                isStep2Valid() {
                    return this.formData.tanggal !== '' && this.formData.waktu_mulai !== '' && this.formData.waktu_selesai !== '';
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
