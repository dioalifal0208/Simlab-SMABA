import Shepherd from 'shepherd.js';
import 'shepherd.js/dist/css/shepherd.css';

// Custom styling for Shepherd tour with Glassmorphism effect
const tourStyles = `
    .shepherd-element {
        z-index: 9999 !important;
        max-width: 400px !important;
    }
    
    .shepherd-modal-overlay-container {
        z-index: 9998 !important;
        background: rgba(0, 0, 0, 0.4) !important;
        backdrop-filter: blur(2px) !important;
    }
    
    /* Glassmorphism Effect */
    .shepherd-content {
        background: rgba(255, 255, 255, 0.85) !important;
        backdrop-filter: blur(20px) saturate(180%) !important;
        -webkit-backdrop-filter: blur(20px) saturate(180%) !important;
        border-radius: 16px !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        box-shadow: 
            0 25px 50px -12px rgba(0, 0, 0, 0.25),
            0 0 0 1px rgba(255, 255, 255, 0.1) inset,
            0 1px 2px rgba(0, 0, 0, 0.05) inset !important;
        overflow: hidden !important;
    }
    
    /* Header with Glass Effect */
    .shepherd-header {
        background: linear-gradient(135deg, 
            rgba(29, 78, 216, 0.95) 0%, 
            rgba(59, 130, 246, 0.95) 100%) !important;
        backdrop-filter: blur(10px) !important;
        padding: 18px 24px !important;
        border-radius: 16px 16px 0 0 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
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
    
    /* Content Area */
    .shepherd-text {
        padding: 24px !important;
        color: #1f2937 !important;
        font-size: 15px !important;
        line-height: 1.7 !important;
        background: rgba(255, 255, 255, 0.5) !important;
    }
    
    /* Footer with Glass Effect */
    .shepherd-footer {
        padding: 18px 24px !important;
        border-top: 1px solid rgba(229, 231, 235, 0.5) !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        background: rgba(249, 250, 251, 0.7) !important;
        backdrop-filter: blur(10px) !important;
        border-radius: 0 0 16px 16px !important;
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
const hasSeenTour = localStorage.getItem('lab-smaba-tour-completed');

// Initialize Shepherd tour
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

// Tour steps
tour.addStep({
    id: 'welcome',
    title: 'Selamat Datang di LAB-SMABA! 👋',
    text: 'Kami akan memandu Anda melalui fitur-fitur utama sistem manajemen laboratorium digital kami. Tour ini hanya memakan waktu 1-2 menit.',
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
    id: 'hero',
    title: 'Dashboard Terpusat',
    text: 'Kelola semua inventaris laboratorium, jadwal peminjaman, dan laporan dalam satu platform yang mudah digunakan.',
    attachTo: {
        element: '.max-w-2xl.space-y-8',
        on: 'right'
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
    id: 'stats',
    title: 'Statistik Real-time',
    text: 'Pantau jumlah alat, guru aktif, dan transparansi data secara langsung.',
    attachTo: {
        element: '.grid.grid-cols-3.gap-8.pt-8',
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
    id: 'features',
    title: 'Fitur Unggulan',
    text: 'Jelajahi 4 fitur utama: Inventaris Digital, Validasi QR, Kalender Cerdas, dan Laporan Excel. Klik "Lihat Detail" untuk preview lebih lanjut.',
    attachTo: {
        element: '#features',
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
    id: 'workflow',
    title: 'Workflow Sederhana',
    text: 'Proses peminjaman hanya dalam 4 langkah mudah: Ajukan → Validasi → Pelaksanaan → Laporan Otomatis.',
    attachTo: {
        element: '#workflow',
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
    id: 'language',
    title: 'Pilihan Bahasa',
    text: 'Ganti bahasa antara Indonesia (ID) dan English (EN) sesuai preferensi Anda.',
    attachTo: {
        element: '.flex.items-center.gap-2.text-sm',
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
    id: 'login',
    title: 'Akses Admin',
    text: 'Klik tombol "Login" untuk masuk ke dashboard admin dan mulai mengelola laboratorium Anda.',
    attachTo: {
        element: 'button[\\@click="isModalOpen = true"]',
        on: 'bottom'
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
    localStorage.setItem('lab-smaba-tour-completed', 'true');
});

tour.on('cancel', () => {
    localStorage.setItem('lab-smaba-tour-completed', 'true');
});

// Export tour instance and start function
export function startProductTour() {
    tour.start();
}

// Auto-start tour for first-time visitors
export function initProductTour() {
    if (!hasSeenTour) {
        // Delay tour start to allow page to fully load
        setTimeout(() => {
            tour.start();
        }, 1000);
    }
}

// Export tour instance for manual control
export { tour };
