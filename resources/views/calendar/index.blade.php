<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kalender Jadwal Laboratorium') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- PERUBAHAN DI SINI: Menambahkan atribut data-aos --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" data-aos="fade-up" data-aos-once="true">
                <div class="p-6 text-gray-900">
                    {{-- Kalender akan ditampilkan di dalam div ini --}}
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Catatan: Blok script di bawah ini mungkin sudah tidak relevan jika Anda menggunakan FullCalendar --}}
{{-- Namun, saya tetap menyertakannya sesuai kode asli yang Anda berikan --}}
<script>
    // Mengirim API Key dari backend Laravel ke JavaScript di frontend
    window.googleCalendarApiKey = "{{ config('services.google.calendar_api_key') }}";
</script>