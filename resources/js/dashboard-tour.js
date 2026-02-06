import Shepherd from 'shepherd.js';
import 'shepherd.js/dist/css/shepherd.css';

// Custom styling for Shepherd tour with Pure Clear Glass effect
const tourStyles = `
    .shepherd-element {
        z-index: 9999 !important;
        max-width: 400px !important;
    }
    
    .shepherd-modal-overlay-container {
        z-index: 9998 !important;
        background: rgba(0, 0, 0, 0.5) !important;
        backdrop-filter: blur(4px) !important;
    }
    
    /* Pure Clear Glass Effect - Natural Glass Look */
    .shepherd-content {
        background: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(30px) saturate(150%) brightness(1.1) !important;
        -webkit-backdrop-filter: blur(30px) saturate(150%) brightness(1.1) !important;
        border-radius: 20px !important;
        border: 1.5px solid rgba(255, 255, 255, 0.5) !important;
        box-shadow: 
            0 30px 60px -15px rgba(0, 0, 0, 0.35),
            0 0 0 1px rgba(255, 255, 255, 0.2) inset,
            0 2px 4px rgba(255, 255, 255, 0.3) inset !important;
        overflow: hidden !important;
    }
    
    /* Header with Glass Effect - Keep Blue for Branding */
    .shepherd-header {
        background: linear-gradient(135deg, 
            rgba(29, 78, 216, 0.95) 0%, 
            rgba(59, 130, 246, 0.95) 100%) !important;
        backdrop-filter: blur(15px) !important;
        padding: 20px 24px !important;
        border-radius: 20px 20px 0 0 !important;
        border-bottom: 1.5px solid rgba(255, 255, 255, 0.3) !important;
    }
    
    .shepherd-title {
        color: white !important;
        font-weight: 700 !important;
        font-size: 17px !important;
        margin: 0 !important;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    }
    
    .shepherd-cancel-icon {
        color: white !important;
        opacity: 0.9 !important;
        transition: all 0.3s ease !important;
        width: 24px !important;
        height: 24px !important;
    }
    
    .shepherd-cancel-icon:hover {
        opacity: 1 !important;
        transform: scale(1.1) !important;
    }
    
    /* Content Area - Clear Glass */
    .shepherd-text {
        padding: 24px !important;
        color: #111827 !important;
        font-size: 15px !important;
        line-height: 1.7 !important;
        font-weight: 500 !important;
        background: rgba(255, 255, 255, 0.1) !important;
        text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8) !important;
    }
    
    /* Footer with Clear Glass Effect */
    .shepherd-footer {
        padding: 18px 24px !important;
        border-top: 1.5px solid rgba(255, 255, 255, 0.3) !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        background: rgba(255, 255, 255, 0.08) !important;
        backdrop-filter: blur(20px) !important;
        border-radius: 0 0 20px 20px !important;
    }
    
    /* Buttons with Premium Style */
    .shepherd-button {
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .shepherd-button::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .shepherd-button:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .shepherd-button-primary {
        background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(29, 78, 216, 0.3);
    }
    
    .shepherd-button-primary:hover {
        background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(29, 78, 216, 0.4);
    }
    
    .shepherd-button-primary:active {
        transform: translateY(0);
    }
    
    .shepherd-button-secondary {
        background: rgba(255, 255, 255, 0.8);
        color: #6b7280;
        border: 1px solid rgba(209, 213, 219, 0.5);
        backdrop-filter: blur(10px);
    }
    
    .shepherd-button-secondary:hover {
        background: rgba(243, 244, 246, 0.9);
        color: #374151;
        border-color: rgba(156, 163, 175, 0.5);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* Progress Indicator */
    .shepherd-progress {
        color: #9ca3af;
        font-size: 13px;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.5);
        padding: 4px 12px;
        border-radius: 20px;
        backdrop-filter: blur(10px);
    }
    
    /* Arrow with Glass Effect */
    .shepherd-arrow:before {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    /* Highlighted Element Styling */
    .shepherd-target {
        animation: pulse-highlight 2s ease-in-out infinite;
    }
    
    @keyframes pulse-highlight {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(29, 78, 216, 0.4);
        }
        50% {
            box-shadow: 0 0 0 10px rgba(29, 78, 216, 0);
        }
    }
    
    /* Smooth entrance animation */
    .shepherd-element {
        animation: shepherd-entrance 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    @keyframes shepherd-entrance {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(-10px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    
    /* Responsive sizing */
    @media (max-width: 640px) {
        .shepherd-element {
            max-width: 90vw;
        }
        
        .shepherd-text {
            font-size: 14px;
            padding: 20px;
        }
        
        .shepherd-header {
            padding: 16px 20px;
        }
        
        .shepherd-footer {
            padding: 16px 20px;
        }
    }
`;

// Inject custom styles
const styleSheet = document.createElement('style');
styleSheet.textContent = tourStyles;
document.head.appendChild(styleSheet);

// Check if user has completed the tour before
const hasSeenTour = localStorage.getItem('lab-smaba-dashboard-tour-completed');

// Initialize Shepherd tour for Dashboard
const tour = new Shepherd.Tour({
    useModalOverlay: true,
    defaultStepOptions: {
        cancelIcon: {
            enabled: true
        },
        classes: 'shepherd-theme-custom',
        scrollTo: { behavior: 'smooth', block: 'center' }
    }
});

// Tour steps for Dashboard
tour.addStep({
    id: 'welcome',
    title: 'Selamat Datang di Dashboard! 👋',
    text: 'Kami akan memandu Anda melalui fitur-fitur utama dashboard LAB-SMABA. Tour ini hanya memakan waktu 1-2 menit.',
    buttons: [
        {
            text: 'Lewati',
            classes: 'shepherd-button-secondary',
            action: tour.cancel
        },
        {
            text: 'Mulai Tour',
            classes: 'shepherd-button-primary',
            action: tour.next
        }
    ]
});

tour.addStep({
    id: 'hero-metrics',
    title: 'Metrik Utama',
    text: 'Di sini Anda dapat melihat statistik penting seperti total item, pengguna, dan transaksi bulan ini secara real-time.',
    attachTo: {
        element: '.grid.grid-cols-1.sm\\:grid-cols-3.gap-6',
        on: 'bottom'
    },
    buttons: [
        {
            text: 'Kembali',
            classes: 'shepherd-button-secondary',
            action: tour.back
        },
        {
            text: 'Lanjut',
            classes: 'shepherd-button-primary',
            action: tour.next
        }
    ]
});

tour.addStep({
    id: 'quick-actions',
    title: 'Aksi Cepat',
    text: 'Gunakan tombol-tombol ini untuk mengakses fitur yang sering digunakan seperti menambah item atau memproses peminjaman.',
    attachTo: {
        element: '.mt-8.flex.flex-wrap.gap-3',
        on: 'top'
    },
    buttons: [
        {
            text: 'Kembali',
            classes: 'shepherd-button-secondary',
            action: tour.back
        },
        {
            text: 'Lanjut',
            classes: 'shepherd-button-primary',
            action: tour.next
        }
    ]
});

tour.addStep({
    id: 'action-cards',
    title: 'Kartu Aksi',
    text: 'Pantau peminjaman pending, booking yang menunggu, laporan kerusakan, dan jadwal minggu ini. Klik kartu untuk melihat detail.',
    attachTo: {
        element: '.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-4.gap-6',
        on: 'top'
    },
    buttons: [
        {
            text: 'Kembali',
            classes: 'shepherd-button-secondary',
            action: tour.back
        },
        {
            text: 'Lanjut',
            classes: 'shepherd-button-primary',
            action: tour.next
        }
    ]
});

tour.addStep({
    id: 'recent-activity',
    title: 'Aktivitas Terbaru',
    text: 'Lihat semua aktivitas terbaru di sistem, termasuk peminjaman, booking, dan perubahan data.',
    attachTo: {
        element: '.bg-white.rounded-xl.border.border-gray-100.shadow-sm[x-data]',
        on: 'top'
    },
    buttons: [
        {
            text: 'Kembali',
            classes: 'shepherd-button-secondary',
            action: tour.back
        },
        {
            text: 'Selesai',
            classes: 'shepherd-button-primary',
            action: tour.complete
        }
    ]
});

// Mark tour as completed when finished
tour.on('complete', () => {
    localStorage.setItem('lab-smaba-dashboard-tour-completed', 'true');
});

tour.on('cancel', () => {
    localStorage.setItem('lab-smaba-dashboard-tour-completed', 'true');
});

// Export tour instance and start function
export function startDashboardTour() {
    tour.start();
}

// Auto-start tour for first-time visitors
export function initDashboardTour() {
    if (!hasSeenTour) {
        // Delay tour start to allow page to fully load
        setTimeout(() => {
            tour.start();
        }, 1000);
    }
}

// Export tour instance for manual control
export { tour };
