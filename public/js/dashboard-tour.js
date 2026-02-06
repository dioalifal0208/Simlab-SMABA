/**
 * Dashboard Product Tour - Pure Vanilla JavaScript
 * No external dependencies required
 */

class DashboardTour {
    constructor() {
        this.currentStep = 0;
        this.steps = [
            {
                title: 'Selamat Datang di Dashboard! 👋',
                content: 'Kami akan memandu Anda melalui fitur-fitur utama dashboard LAB-SMABA. Tour ini hanya memakan waktu 1-2 menit.',
                target: null,
                position: 'center'
            },
            {
                title: 'Metrik Utama',
                content: 'Di sini Anda dapat melihat statistik penting seperti total item, pengguna, dan transaksi bulan ini secara real-time.',
                target: '.grid.grid-cols-1.sm\\:grid-cols-3.gap-6',
                position: 'bottom'
            },
            {
                title: 'Aksi Cepat',
                content: 'Gunakan tombol-tombol ini untuk mengakses fitur yang sering digunakan seperti menambah item atau memproses peminjaman.',
                target: '.mt-8.flex.flex-wrap.gap-3',
                position: 'top'
            },
            {
                title: 'Kartu Aksi',
                content: 'Pantau peminjaman pending, booking yang menunggu, laporan kerusakan, dan jadwal minggu ini. Klik kartu untuk melihat detail.',
                target: '.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-4.gap-6',
                position: 'top'
            },
            {
                title: 'Aktivitas Terbaru',
                content: 'Lihat semua aktivitas terbaru di sistem, termasuk peminjaman, booking, dan perubahan data.',
                target: '.bg-white.rounded-xl.border.border-gray-100.shadow-sm[x-data]',
                position: 'top'
            }
        ];
        
        this.overlay = null;
        this.tooltip = null;
        this.hasSeenTour = localStorage.getItem('lab-smaba-dashboard-tour-completed') === 'true';
    }

    init() {
        if (!this.hasSeenTour) {
            setTimeout(() => this.start(), 1000);
        }
    }

    start() {
        this.currentStep = 0;
        this.createOverlay();
        this.createTooltip();
        this.showStep(0);
    }

    createOverlay() {
        this.overlay = document.createElement('div');
        this.overlay.className = 'tour-overlay';
        this.overlay.innerHTML = '<div class="tour-backdrop"></div>';
        document.body.appendChild(this.overlay);
    }

    createTooltip() {
        this.tooltip = document.createElement('div');
        this.tooltip.className = 'tour-tooltip';
        this.tooltip.innerHTML = `
            <div class="tour-tooltip-header">
                <h3 class="tour-tooltip-title"></h3>
                <button class="tour-tooltip-close" onclick="dashboardTour.end()">&times;</button>
            </div>
            <div class="tour-tooltip-content"></div>
            <div class="tour-tooltip-footer">
                <div class="tour-tooltip-progress"></div>
                <div class="tour-tooltip-buttons">
                    <button class="tour-btn tour-btn-secondary" onclick="dashboardTour.skip()">Lewati</button>
                    <button class="tour-btn tour-btn-primary" onclick="dashboardTour.next()">Lanjut</button>
                </div>
            </div>
        `;
        document.body.appendChild(this.tooltip);
    }

    showStep(index) {
        if (index < 0 || index >= this.steps.length) return;
        
        this.currentStep = index;
        const step = this.steps[index];
        
        // Update content
        this.tooltip.querySelector('.tour-tooltip-title').textContent = step.title;
        this.tooltip.querySelector('.tour-tooltip-content').textContent = step.content;
        this.tooltip.querySelector('.tour-tooltip-progress').textContent = `${index + 1} / ${this.steps.length}`;
        
        // Update buttons
        const buttons = this.tooltip.querySelector('.tour-tooltip-buttons');
        if (index === 0) {
            buttons.innerHTML = `
                <button class="tour-btn tour-btn-secondary" onclick="dashboardTour.skip()">Lewati</button>
                <button class="tour-btn tour-btn-primary" onclick="dashboardTour.next()">Mulai Tour</button>
            `;
        } else if (index === this.steps.length - 1) {
            buttons.innerHTML = `
                <button class="tour-btn tour-btn-secondary" onclick="dashboardTour.previous()">Kembali</button>
                <button class="tour-btn tour-btn-primary" onclick="dashboardTour.complete()">Selesai</button>
            `;
        } else {
            buttons.innerHTML = `
                <button class="tour-btn tour-btn-secondary" onclick="dashboardTour.previous()">Kembali</button>
                <button class="tour-btn tour-btn-primary" onclick="dashboardTour.next()">Lanjut</button>
            `;
        }
        
        // Position tooltip
        this.positionTooltip(step);
        
        // Highlight target
        this.highlightTarget(step.target);
        
        // Show tooltip with animation
        setTimeout(() => {
            this.tooltip.classList.add('tour-tooltip-visible');
        }, 50);
    }

    positionTooltip(step) {
        if (!step.target) {
            // Center position for welcome step
            this.tooltip.style.position = 'fixed';
            this.tooltip.style.top = '50%';
            this.tooltip.style.left = '50%';
            this.tooltip.style.transform = 'translate(-50%, -50%)';
            this.tooltip.style.maxWidth = '500px';
            return;
        }

        const target = document.querySelector(step.target);
        if (!target) {
            console.warn('Target not found:', step.target);
            return;
        }

        const rect = target.getBoundingClientRect();
        const tooltipRect = this.tooltip.getBoundingClientRect();
        
        this.tooltip.style.position = 'fixed';
        this.tooltip.style.maxWidth = '400px';
        
        // Scroll target into view
        target.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Position based on step.position
        switch (step.position) {
            case 'top':
                this.tooltip.style.left = rect.left + (rect.width / 2) + 'px';
                this.tooltip.style.top = (rect.top - 20) + 'px';
                this.tooltip.style.transform = 'translate(-50%, -100%)';
                break;
            case 'bottom':
                this.tooltip.style.left = rect.left + (rect.width / 2) + 'px';
                this.tooltip.style.top = (rect.bottom + 20) + 'px';
                this.tooltip.style.transform = 'translateX(-50%)';
                break;
            case 'left':
                this.tooltip.style.left = (rect.left - 20) + 'px';
                this.tooltip.style.top = rect.top + (rect.height / 2) + 'px';
                this.tooltip.style.transform = 'translate(-100%, -50%)';
                break;
            case 'right':
                this.tooltip.style.left = (rect.right + 20) + 'px';
                this.tooltip.style.top = rect.top + (rect.height / 2) + 'px';
                this.tooltip.style.transform = 'translateY(-50%)';
                break;
            default:
                this.tooltip.style.left = '50%';
                this.tooltip.style.top = '50%';
                this.tooltip.style.transform = 'translate(-50%, -50%)';
        }
    }

    highlightTarget(selector) {
        // Remove previous highlights
        document.querySelectorAll('.tour-highlight').forEach(el => {
            el.classList.remove('tour-highlight');
        });
        
        if (!selector) return;
        
        const target = document.querySelector(selector);
        if (target) {
            target.classList.add('tour-highlight');
        }
    }

    next() {
        if (this.currentStep < this.steps.length - 1) {
            this.tooltip.classList.remove('tour-tooltip-visible');
            setTimeout(() => {
                this.showStep(this.currentStep + 1);
            }, 300);
        }
    }

    previous() {
        if (this.currentStep > 0) {
            this.tooltip.classList.remove('tour-tooltip-visible');
            setTimeout(() => {
                this.showStep(this.currentStep - 1);
            }, 300);
        }
    }

    skip() {
        this.complete();
    }

    complete() {
        localStorage.setItem('lab-smaba-dashboard-tour-completed', 'true');
        this.end();
    }

    end() {
        if (this.tooltip) {
            this.tooltip.classList.remove('tour-tooltip-visible');
            setTimeout(() => {
                this.tooltip.remove();
                this.tooltip = null;
            }, 300);
        }
        
        if (this.overlay) {
            this.overlay.remove();
            this.overlay = null;
        }
        
        // Remove highlights
        document.querySelectorAll('.tour-highlight').forEach(el => {
            el.classList.remove('tour-highlight');
        });
    }
}

// Initialize tour when DOM is ready
let dashboardTour;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        dashboardTour = new DashboardTour();
        dashboardTour.init();
        window.startDashboardTour = () => dashboardTour.start();
    });
} else {
    dashboardTour = new DashboardTour();
    dashboardTour.init();
    window.startDashboardTour = () => dashboardTour.start();
}
