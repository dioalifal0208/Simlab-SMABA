// 1. Impor file bootstrap bawaan Laravel.
//    File ini juga secara otomatis mengimpor dan menginisialisasi Alpine.js,
//    yang merupakan mesin di balik fungsionalitas dropdown.
import './bootstrap';
import Chart from 'chart.js/auto';
window.Chart = Chart;
// Import CSS AOS
import 'aos/dist/aos.css';
// PENAMBAHAN: Impor JavaScript AOS
import AOS from 'aos';

// 2. Impor SweetAlert2 untuk popup konfirmasi modern.
//    Kita juga mendaftarkannya ke objek `window` agar bisa diakses dari mana saja.
import Swal from 'sweetalert2';
window.Swal = Swal;

// 3. Impor semua library FullCalendar yang kita butuhkan.
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import googleCalendarPlugin from '@fullcalendar/google-calendar'; // Plugin untuk Google Calendar


// 4. Jalankan skrip ini hanya setelah seluruh halaman HTML selesai dimuat.
document.addEventListener('DOMContentLoaded', function() {
    // PENAMBAHAN: Inisialisasi AOS (Animate On Scroll) di sini.
    // Cukup panggil sekali di dalam listener utama.
    AOS.init({
        duration: 800, // Durasi animasi dalam milidetik
        once: true,    // Apakah animasi hanya berjalan sekali
    });

    // Cari elemen div dengan id 'calendar' di halaman.
    var calendarEl = document.getElementById('calendar');

    // HANYA jalankan kode kalender jika elemen 'calendar' ditemukan di halaman saat ini.
    // Ini sangat penting untuk mencegah error di halaman lain (seperti Dashboard, Inventaris, dll.).
    if (calendarEl) {
        // Buat instance kalender baru.
        var calendar = new Calendar(calendarEl, {
            // Muat semua plugin yang sudah kita impor.
            plugins: [dayGridPlugin, timeGridPlugin, listPlugin, googleCalendarPlugin],
            
            // Tampilan default saat kalender pertama kali dibuka adalah bulanan.
            initialView: 'dayGridMonth', 
            
            // Konfigurasi header kalender (tombol-tombol navigasi dan judul).
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek' // Tombol untuk ganti tampilan (bulan, minggu, daftar).
            },
            
            // Opsi untuk membuat tinggi kalender menyesuaikan kontennya secara otomatis.
            height: 'auto',

            // Mengambil Google Calendar API Key yang sudah kita kirim dari backend.
            googleCalendarApiKey: window.googleCalendarApiKey,

            // Menggabungkan beberapa sumber event.
            eventSources: [
                // Sumber event #1: Data dari aplikasi kita sendiri (peminjaman dan booking).
                {
                    id: 'lab-schedules', // <-- Beri ID
                    url: '/calendar/events',
                },
                // Sumber event #2: Data hari libur nasional Indonesia dari Google Calendar publik.
                {
                    id: 'holidays', // <-- Beri ID
                    googleCalendarId: 'en.indonesian#holiday@group.v.calendar.google.com',
                    color: '#D32F2F', // Memberi warna merah untuk hari libur.
                    className: 'gcal-event' 
                },
                // Sumber event #3: Untuk menandai Sabtu & Minggu
                {
                    id: 'weekends', // <-- Beri ID
                    daysOfWeek: [ 0, 6 ], // 0 untuk Minggu, 6 untuk Sabtu
                    display: 'background',
                    color: '#FFEBEE' 
                }
            ],

            // Fungsi yang akan dijalankan saat sebuah event di kalender di-klik.
            eventClick: function(info) {
                // Mencegah browser membuka link secara normal.
                info.jsEvent.preventDefault(); 
                
                // Jika event tersebut memiliki URL, buka URL tersebut di tab yang sama.
                if (info.event.url) {
                    window.open(info.event.url, "_self"); 
                }
            }
        });

        // "Gambar" atau render kalender di dalam div 'calendar'.
        calendar.render();

        // --- PENYEMPURNAAN: Logika untuk Filter ---
        const filterLab = document.getElementById('filter-lab');
        const filterHolidays = document.getElementById('filter-holidays');

        // Fungsi generik untuk menangani toggle event source
        const toggleEventSource = (checkbox, sourceId, sourceConfig) => {
            if (!checkbox) return; // Keluar jika elemen checkbox tidak ditemukan

            checkbox.addEventListener('change', function() {
                const source = calendar.getEventSourceById(sourceId);
                if (this.checked && !source) {
                    // Jika dicentang dan source belum ada, tambahkan.
                    calendar.addEventSource(sourceConfig);
                } else if (!this.checked && source) {
                    // Jika tidak dicentang dan source ada, hapus.
                    source.remove();
                }
            });
        };

        // Terapkan fungsi toggle ke filter Jadwal Lab
        toggleEventSource(filterLab, 'lab-schedules', { 
            id: 'lab-schedules', 
            url: '/calendar/events' 
        });

        // Terapkan fungsi toggle ke filter Hari Libur
        toggleEventSource(filterHolidays, 'holidays', { 
            id: 'holidays', 
            googleCalendarId: 'en.indonesian#holiday@group.v.calendar.google.com', 
            color: '#D32F2F', 
            className: 'gcal-event' 
        });
    }

    // PENAMBAHAN: Logika untuk konfirmasi hapus menggunakan SweetAlert2
    // Cari semua form dengan class 'delete-form'
    const deleteForms = document.querySelectorAll('.delete-form');

    deleteForms.forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Mencegah form dikirim secara langsung

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Tindakan ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Jika dikonfirmasi, kirim form
                }
            });
        });
    });
});
