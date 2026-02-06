/**
 * Dashboard Product Tour - Pure Vanilla JavaScript with Spotlight
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
        this.spotlight = null;
        this.hasSeenTour = localStorage.getItem('lab-smaba-dashboard-tour-completed') === 'true';
    }

    init() {
        if (!this.hasSeenTour) {
            setTimeout(() => this.start(), 1000);
        }
    }

    start() {
        this.currentStep = 0;
        document.body.classList.add('tour-active');
        document.documentElement.classList.add('tour-active');
        this.createOverlay();
        this.createSpotlight();
        this.createTooltip();
        this.showStep(0);
    }

    createOverlay() {
        this.overlay = document.createElement('div');
        this.overlay.className = 'tour-overlay';
        this.overlay.innerHTML = '<div class="tour-backdrop"></div>';
        document.body.appendChild(this.overlay);
    }

    createSpotlight() {
        this.spotlight = document.createElement('div');
        this.spotlight.className = 'tour-spotlight';
        this.spotlight.style.display = 'none';
        document.body.appendChild(this.spotlight);
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
        
        // Update position attribute for arrow
        this.tooltip.setAttribute('data-position', step.position);
        
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
        
        // Position spotlight and tooltip
        this.positionElements(step);
        
        // Show tooltip with animation
        setTimeout(() => {
            this.tooltip.classList.add('tour-tooltip-visible');
        }, 50);
    }

    positionElements(step) {
        if (!step.target) {
            // Center position for welcome step
            this.spotlight.style.display = 'none';
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
            this.spotlight.style.display = 'none';
            return;
        }

        // Scroll target into view smoothly
        target.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Wait for scroll to complete
        setTimeout(() => {
            const rect = target.getBoundingClientRect();
            const padding = 12;
            
            // Position spotlight
            this.spotlight.style.display = 'block';
            this.spotlight.style.left = (rect.left - padding) + 'px';
            this.spotlight.style.top = (rect.top - padding) + 'px';
            this.spotlight.style.width = (rect.width + padding * 2) + 'px';
            this.spotlight.style.height = (rect.height + padding * 2) + 'px';
            
            // Position tooltip
            this.positionTooltip(rect, step.position);
        }, 300);
    }

    positionTooltip(targetRect, position) {
        this.tooltip.style.position = 'fixed';
        this.tooltip.style.maxWidth = '420px';
        
        const tooltipRect = this.tooltip.getBoundingClientRect();
        const spacing = 30; // Space between tooltip and target
        const viewportPadding = 20; // Padding from viewport edges
        
        let left, top, transform;
        
        switch (position) {
            case 'top':
                left = targetRect.left + (targetRect.width / 2);
                top = targetRect.top - spacing;
                transform = 'translate(-50%, -100%)';
                
                // Adjust if tooltip goes off-screen
                if (top - tooltipRect.height < viewportPadding) {
                    // Switch to bottom if not enough space on top
                    position = 'bottom';
                    top = targetRect.bottom + spacing;
                    transform = 'translateX(-50%)';
                }
                break;
                
            case 'bottom':
                left = targetRect.left + (targetRect.width / 2);
                top = targetRect.bottom + spacing;
                transform = 'translateX(-50%)';
                
                // Adjust if tooltip goes off-screen
                if (top + tooltipRect.height > window.innerHeight - viewportPadding) {
                    // Switch to top if not enough space on bottom
                    position = 'top';
                    top = targetRect.top - spacing;
                    transform = 'translate(-50%, -100%)';
                }
                break;
                
            case 'left':
                left = targetRect.left - spacing;
                top = targetRect.top + (targetRect.height / 2);
                transform = 'translate(-100%, -50%)';
                
                // Adjust if tooltip goes off-screen
                if (left - tooltipRect.width < viewportPadding) {
                    position = 'right';
                    left = targetRect.right + spacing;
                    transform = 'translateY(-50%)';
                }
                break;
                
            case 'right':
                left = targetRect.right + spacing;
                top = targetRect.top + (targetRect.height / 2);
                transform = 'translateY(-50%)';
                
                // Adjust if tooltip goes off-screen
                if (left + tooltipRect.width > window.innerWidth - viewportPadding) {
                    position = 'left';
                    left = targetRect.left - spacing;
                    transform = 'translate(-100%, -50%)';
                }
                break;
                
            default:
                left = window.innerWidth / 2;
                top = window.innerHeight / 2;
                transform = 'translate(-50%, -50%)';
        }
        
        // Apply positioning
        this.tooltip.style.left = left + 'px';
        this.tooltip.style.top = top + 'px';
        this.tooltip.style.transform = transform;
        
        // Update arrow position
        this.tooltip.setAttribute('data-position', position);
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
        document.body.classList.remove('tour-active');
        document.documentElement.classList.remove('tour-active');
        
        if (this.tooltip) {
            this.tooltip.classList.remove('tour-tooltip-visible');
            setTimeout(() => {
                this.tooltip.remove();
                this.tooltip = null;
            }, 300);
        }
        
        if (this.spotlight) {
            this.spotlight.remove();
            this.spotlight = null;
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
