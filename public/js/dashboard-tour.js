/**
 * Dashboard Product Tour - Pure Vanilla JavaScript with Spotlight
 * No external dependencies required
 */

class DashboardTour {
    constructor() {
        this.currentStep = 0;
        this.steps = [
            {
                title: 'Selamat Datang di Dashboard!',
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
                target: '.bg-white.rounded-xl.border.border-gray-100.shadow-sm[data-aos="fade-up"]',
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
        this.backdrop = this.overlay.querySelector('.tour-backdrop');
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
            this.backdrop.classList.remove('has-spotlight');
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
            this.backdrop.classList.remove('has-spotlight');
            return;
        }

        // Temporarily enable scroll for smooth scrolling
        const scrollY = window.scrollY;
        document.body.style.position = '';
        document.body.style.top = '';
        window.scrollTo(0, scrollY);
        
        // Scroll target into view smoothly
        target.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'nearest' });
        
        // Wait for scroll to complete, then re-disable scroll
        setTimeout(() => {
            // Re-apply fixed position to prevent manual scrolling
            const currentScrollY = window.scrollY;
            document.body.style.position = 'fixed';
            document.body.style.top = `-${currentScrollY}px`;
            document.body.style.width = '100%';
            
            const rect = target.getBoundingClientRect();
            const padding = 12;
            
            // Position spotlight
            this.spotlight.style.display = 'block';
            this.spotlight.style.left = (rect.left - padding) + 'px';
            this.spotlight.style.top = (rect.top - padding) + 'px';
            this.spotlight.style.width = (rect.width + padding * 2) + 'px';
            this.spotlight.style.height = (rect.height + padding * 2) + 'px';
            
            // Create cutout in backdrop using clip-path
            // This ensures the highlighted area is NOT blurred
            this.backdrop.classList.add('has-spotlight');
            this.backdrop.style.setProperty('--spotlight-x1', `${rect.left - padding}px`);
            this.backdrop.style.setProperty('--spotlight-y1', `${rect.top - padding}px`);
            this.backdrop.style.setProperty('--spotlight-x2', `${rect.right + padding}px`);
            this.backdrop.style.setProperty('--spotlight-y2', `${rect.bottom + padding}px`);
            
            // Position tooltip
            this.positionTooltip(rect, step.position);
        }, 600); // Increased timeout for smooth scroll completion
    }

    positionTooltip(targetRect, position) {
        this.tooltip.style.position = 'fixed';
        this.tooltip.style.maxWidth = '380px';
        this.tooltip.style.width = 'auto';
        
        // Force a reflow to get accurate dimensions
        const tooltipRect = this.tooltip.getBoundingClientRect();
        const spacing = 20; // Spacing between spotlight and tooltip
        const viewportPadding = 20; // Increased padding from viewport edges
        
        let left, top, transform;
        let finalPosition = position;
        
        // Calculate available space in each direction
        const spaceTop = targetRect.top;
        const spaceBottom = window.innerHeight - targetRect.bottom;
        const spaceLeft = targetRect.left;
        const spaceRight = window.innerWidth - targetRect.right;
        
        // Check if tooltip fits in preferred position
        const fitsTop = spaceTop >= tooltipRect.height + spacing + viewportPadding;
        const fitsBottom = spaceBottom >= tooltipRect.height + spacing + viewportPadding;
        const fitsLeft = spaceLeft >= tooltipRect.width + spacing + viewportPadding;
        const fitsRight = spaceRight >= tooltipRect.width + spacing + viewportPadding;
        
        // Determine best position - prioritize keeping tooltip close to target
        if (position === 'top' && !fitsTop) {
            if (fitsBottom) {
                finalPosition = 'bottom';
            } else if (fitsRight) {
                finalPosition = 'right';
            } else if (fitsLeft) {
                finalPosition = 'left';
            } else {
                // If nothing fits, use position with most space
                const maxSpace = Math.max(spaceTop, spaceBottom, spaceLeft, spaceRight);
                if (maxSpace === spaceBottom) finalPosition = 'bottom';
                else if (maxSpace === spaceTop) finalPosition = 'top';
                else if (maxSpace === spaceRight) finalPosition = 'right';
                else finalPosition = 'left';
            }
        } else if (position === 'bottom' && !fitsBottom) {
            if (fitsTop) {
                finalPosition = 'top';
            } else if (fitsRight) {
                finalPosition = 'right';
            } else if (fitsLeft) {
                finalPosition = 'left';
            } else {
                const maxSpace = Math.max(spaceTop, spaceBottom, spaceLeft, spaceRight);
                if (maxSpace === spaceTop) finalPosition = 'top';
                else if (maxSpace === spaceBottom) finalPosition = 'bottom';
                else if (maxSpace === spaceRight) finalPosition = 'right';
                else finalPosition = 'left';
            }
        } else if (position === 'left' && !fitsLeft) {
            if (fitsRight) {
                finalPosition = 'right';
            } else if (fitsBottom) {
                finalPosition = 'bottom';
            } else if (fitsTop) {
                finalPosition = 'top';
            }
        } else if (position === 'right' && !fitsRight) {
            if (fitsLeft) {
                finalPosition = 'left';
            } else if (fitsBottom) {
                finalPosition = 'bottom';
            } else if (fitsTop) {
                finalPosition = 'top';
            }
        }
        
        // Calculate position based on final position
        switch (finalPosition) {
            case 'top':
                left = targetRect.left + (targetRect.width / 2);
                top = targetRect.top - spacing;
                transform = 'translate(-50%, -100%)';
                
                // Ensure horizontally centered but within viewport
                left = Math.max(tooltipRect.width / 2 + viewportPadding, 
                               Math.min(window.innerWidth - tooltipRect.width / 2 - viewportPadding, left));
                break;
                
            case 'bottom':
                left = targetRect.left + (targetRect.width / 2);
                top = targetRect.bottom + spacing;
                transform = 'translateX(-50%)';
                
                // Ensure horizontally centered but within viewport
                left = Math.max(tooltipRect.width / 2 + viewportPadding, 
                               Math.min(window.innerWidth - tooltipRect.width / 2 - viewportPadding, left));
                break;
                
            case 'left':
                left = targetRect.left - spacing;
                top = targetRect.top + (targetRect.height / 2);
                transform = 'translate(-100%, -50%)';
                
                // Ensure vertically centered but within viewport
                top = Math.max(tooltipRect.height / 2 + viewportPadding, 
                              Math.min(window.innerHeight - tooltipRect.height / 2 - viewportPadding, top));
                break;
                
            case 'right':
                left = targetRect.right + spacing;
                top = targetRect.top + (targetRect.height / 2);
                transform = 'translateY(-50%)';
                
                // Ensure vertically centered but within viewport
                top = Math.max(tooltipRect.height / 2 + viewportPadding, 
                              Math.min(window.innerHeight - tooltipRect.height / 2 - viewportPadding, top));
                break;
                
            case 'center':
            default:
                left = window.innerWidth / 2;
                top = window.innerHeight / 2;
                transform = 'translate(-50%, -50%)';
                finalPosition = 'center';
        }
        
        // Apply positioning
        this.tooltip.style.left = left + 'px';
        this.tooltip.style.top = top + 'px';
        this.tooltip.style.transform = transform;
        
        // CRITICAL: Final boundary check to prevent any clipping
        // Get actual tooltip dimensions after positioning
        setTimeout(() => {
            const finalRect = this.tooltip.getBoundingClientRect();
            let adjustLeft = 0;
            let adjustTop = 0;
            
            // Check if tooltip is clipped on any edge
            if (finalRect.left < viewportPadding) {
                adjustLeft = viewportPadding - finalRect.left;
            } else if (finalRect.right > window.innerWidth - viewportPadding) {
                adjustLeft = (window.innerWidth - viewportPadding) - finalRect.right;
            }
            
            if (finalRect.top < viewportPadding) {
                adjustTop = viewportPadding - finalRect.top;
            } else if (finalRect.bottom > window.innerHeight - viewportPadding) {
                adjustTop = (window.innerHeight - viewportPadding) - finalRect.bottom;
            }
            
            // Apply adjustments if needed
            if (adjustLeft !== 0 || adjustTop !== 0) {
                const currentLeft = parseFloat(this.tooltip.style.left);
                const currentTop = parseFloat(this.tooltip.style.top);
                this.tooltip.style.left = (currentLeft + adjustLeft) + 'px';
                this.tooltip.style.top = (currentTop + adjustTop) + 'px';
            }
        }, 10);
        
        // Update arrow position
        this.tooltip.setAttribute('data-position', finalPosition);
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
        // Scroll to top after tour completes
        setTimeout(() => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }, 300);
    }

    end() {
        // Restore scroll position and scroll to top
        const scrollY = document.body.style.top;
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.width = '';
        
        // Always scroll to top when tour ends
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
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
