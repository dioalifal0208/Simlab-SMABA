// 1. Impor file bootstrap bawaan Laravel.
//    File ini juga secara otomatis mengimpor dan menginisialisasi Alpine.js,
//    yang merupakan mesin di balik fungsionalitas dropdown.
import './bootstrap';
import Chart from 'chart.js/auto';
window.Chart = Chart;
// AOS dikelola via CDN langsung di layouts/app.blade.php
// (bukan di bundle ini, agar timing lebih terkontrol)
import './lock-screen'; // Import Lock Screen Logic


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
    // Cari elemen div dengan id 'calendar' di halaman.
    var calendarEl = document.getElementById('calendar');

    // HANYA jalankan kode kalender jika elemen 'calendar' ditemukan di halaman saat ini.
    // Ini sangat penting untuk mencegah error di halaman lain (seperti Dashboard, Inventaris, dll.).
    if (calendarEl) {
        const labFilterSelect = document.getElementById('lab-selector');
        
        // Konfigurasi sumber event Lab & Loans
        const labEventSourceConfig = {
            id: 'lab-schedules',
            url: '/calendar/events',
            extraParams: () => ({
                type: 'lab',
                laboratorium: labFilterSelect?.value || ''
            }),
        };

        // Konfigurasi sumber event Hari Libur (Internal API)
        const holidayEventSourceConfig = {
            id: 'holidays',
            url: '/calendar/events',
            extraParams: () => ({
                type: 'holiday'
            }),
            color: '#D32F2F'
        };

        // Buat instance kalender baru.
        var calendar = new Calendar(calendarEl, {
            // Muat plugin dasar (tanpa google-calendar)
            plugins: [dayGridPlugin, timeGridPlugin, listPlugin],
            
            initialView: 'dayGridMonth', 
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            height: 'auto',

            eventSources: [
                labEventSourceConfig,
                holidayEventSourceConfig,
                // Saturday & Sunday background
                {
                    id: 'weekends',
                    daysOfWeek: [ 0, 6 ],
                    display: 'background',
                    color: '#FFEBEE' 
                }
            ],

            eventClick: function(info) {
                info.jsEvent.preventDefault(); 
                if (info.event.url) {
                    window.open(info.event.url, "_self"); 
                }
            }
        });

        // Tampilkan kalender
        calendar.render();

        // Logic Filter
        const filterLab = document.getElementById('filter-lab');
        const filterHolidays = document.getElementById('filter-holidays');

        const toggleEventSource = (checkbox, sourceId, sourceConfig) => {
            if (!checkbox) return;

            checkbox.addEventListener('change', function() {
                const source = calendar.getEventSourceById(sourceId);
                if (this.checked && !source) {
                    calendar.addEventSource(sourceConfig);
                } else if (!this.checked && source) {
                    source.remove();
                }
            });
        };

        toggleEventSource(filterLab, 'lab-schedules', labEventSourceConfig);
        toggleEventSource(filterHolidays, 'holidays', holidayEventSourceConfig);

        if (labFilterSelect) {
            labFilterSelect.addEventListener('change', () => {
                const source = calendar.getEventSourceById('lab-schedules');
                if (source) {
                    source.refetch();
                }
            });
        }
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

    const currentUserId = document.body ? (document.body.dataset.userId || 'guest') : 'guest';
    const notificationDotKey = `notifDotShown:${currentUserId}`;
    const chatBadgeKey = `chatBadgeShown:${currentUserId}`;
    const logoutForm = document.getElementById('logout-form');
    if (logoutForm) {
        logoutForm.addEventListener('submit', () => {
            sessionStorage.removeItem(notificationDotKey);
            sessionStorage.removeItem(chatBadgeKey);
        });
    }

    // PENAMBAHAN: Polling sederhana untuk update ikon notifikasi (mendekati real-time)
    const bellButton = document.getElementById('notification-bell');

    if (bellButton && window.axios) {
        const listContainer = document.getElementById('notification-list');
        let hasShownNotificationDot = sessionStorage.getItem(notificationDotKey) === '1';
        let poller = null;

        const markDotShown = () => {
            hasShownNotificationDot = true;
            sessionStorage.setItem(notificationDotKey, '1');
        };

        const updateBell = (count) => {
            let dot = bellButton.querySelector('[data-role="notification-dot"]');

            if (count > 0 && !hasShownNotificationDot) {
                if (!dot) {
                    dot = document.createElement('span');
                    dot.dataset.role = 'notification-dot';
                    dot.className = 'absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-600 ring-2 ring-white';
                    bellButton.appendChild(dot);
                }
                markDotShown();
            } else if (count <= 0 && dot) {
                dot.remove();
            }

            bellButton.dataset.unread = count;
        };

        const renderNotificationList = (items) => {
            if (!listContainer) {
                return;
            }

            // Bersihkan isi lama
            while (listContainer.firstChild) {
                listContainer.removeChild(listContainer.firstChild);
            }

            if (!items || !items.length) {
                const emptyDiv = document.createElement('div');
                emptyDiv.className = 'px-4 py-3 text-sm text-gray-500 text-center';
                emptyDiv.textContent = 'Tidak ada notifikasi baru.';
                listContainer.appendChild(emptyDiv);
                return;
            }

            items.forEach((item) => {
                const link = document.createElement('a');
                link.href = item.url;
                link.className = 'block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 border-b border-gray-100';

                const message = document.createElement('p');
                message.className = 'font-medium text-gray-800';
                message.textContent = item.message || '';

                const time = document.createElement('span');
                time.className = 'text-xs text-gray-500';
                time.textContent = item.created_at_human || '';

                link.appendChild(message);
                link.appendChild(time);

                // PENAMBAHAN: Intercept klik untuk SPA behavior (Chat)
                link.addEventListener('click', function(e) {
                    // Cek jika ini adalah link chat dan kita sedang di halaman chat
                    const currentPath = window.location.pathname;
                    if (item.target_url && item.target_url.includes('contact-conversations') && currentPath.includes('contact-conversations')) {
                        e.preventDefault();
                        
                        // 1. Tandai sudah dibaca via AJAX (background)
                        if (item.url) {
                            axios.get(item.url).catch(err => console.error('Gagal tandai baca:', err));
                        }

                        // 2. Ambil ID dari query param 'open' di target_url
                        try {
                            const urlObj = new URL(item.target_url);
                            const openId = urlObj.searchParams.get('open');
                            if (openId) {
                                // 3. Dispatch event agar Alpine menangkap
                                window.dispatchEvent(new CustomEvent('open-chat', { detail: { id: openId } }));
                            }
                        } catch (err) {
                            console.error('Invalid URL:', err);
                        }
                    }
                });

                listContainer.appendChild(link);
            });
        };

        // Inisialisasi dari atribut data-unread
        const initial = parseInt(bellButton.dataset.unread || '0', 10);
        if (!Number.isNaN(initial)) {
            updateBell(initial);
        }

        const fetchSummary = () => {
            window.axios
                .get('/notifications/summary')
                .then((response) => {
                    if (response.data) {
                        if (typeof response.data.unread_count !== 'undefined') {
                            const count = parseInt(response.data.unread_count, 10);
                            if (!Number.isNaN(count)) {
                                updateBell(count);
                            }
                        }

                        if (Array.isArray(response.data.notifications)) {
                            renderNotificationList(response.data.notifications);
                        }
                    }
                })
                .catch(() => {
                    // Diamkan saja jika error (misal: belum login)
                });
        };

        const startPolling = () => {
            if (poller) return;
            fetchSummary();
            poller = setInterval(fetchSummary, 10000);
        };

        const stopPolling = () => {
            if (poller) {
                clearInterval(poller);
                poller = null;
            }
        };

        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                startPolling();
            } else {
                stopPolling();
            }
        });

        if (logoutForm) {
            logoutForm.addEventListener('submit', stopPolling);
        }

        startPolling();
    }
});

// AOS diinisialisasi via inline script di layouts/app.blade.php
// agar berjalan lebih awal (sebelum bundle JS ini ter-load)
// Lihat: resources/views/layouts/app.blade.php


// Menambahkan listener untuk event Livewire
document.addEventListener('livewire:load', function () {
    Livewire.on('itemImported', () => {
        // Optional: Tampilkan notifikasi sukses global jika diinginkan
    });
});
