import Shepherd from 'shepherd.js';
import 'shepherd.js/dist/css/shepherd.css';

// Custom styling for Shepherd tour to match LAB-SMABA bright theme
const tourStyles = `
    .shepherd-element {
        z-index: 9999;
    }
    
    .shepherd-modal-overlay-container {
        z-index: 9998;
    }
    
    .shepherd-content {
        border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border: 1px solid #e5e7eb;
    }
    
    .shepherd-header {
        background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%);
        padding: 16px 20px;
        border-radius: 12px 12px 0 0;
    }
    
    .shepherd-title {
        color: white;
        font-weight: 700;
        font-size: 16px;
        margin: 0;
    }
    
    .shepherd-cancel-icon {
        color: white;
        opacity: 0.8;
        transition: opacity 0.2s;
    }
    
    .shepherd-cancel-icon:hover {
        opacity: 1;
    }
    
    .shepherd-text {
        padding: 20px;
        color: #374151;
        font-size: 14px;
        line-height: 1.6;
    }
    
    .shepherd-footer {
        padding: 16px 20px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f9fafb;
        border-radius: 0 0 12px 12px;
    }
    
    .shepherd-button {
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }
    
    .shepherd-button-primary {
        background: #1d4ed8;
        color: white;
    }
    
    .shepherd-button-primary:hover {
        background: #1e40af;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .shepherd-button-secondary {
        background: white;
        color: #6b7280;
        border: 1px solid #d1d5db;
    }
    
    .shepherd-button-secondary:hover {
        background: #f3f4f6;
        color: #374151;
    }
    
    .shepherd-progress {
        color: #9ca3af;
        font-size: 12px;
        font-weight: 500;
    }
    
    .shepherd-arrow:before {
        background: white;
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
