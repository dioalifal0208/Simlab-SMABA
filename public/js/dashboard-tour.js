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
            if (this.maskCutout) {
                this.maskCutout.setAttribute('width', '0');
                this.maskCutout.setAttribute('height', '0');
            }
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
            if (this.maskCutout) {
                this.maskCutout.setAttribute('width', '0');
                this.maskCutout.setAttribute('height', '0');
            }
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
            
            // Remove blur from highlighted element and its parents
            this.currentTarget = target;
            target.style.filter = 'blur(0px) !important';
            target.style.position = 'relative';
            target.style.zIndex = '9999';
            
            // Position spotlight
            this.spotlight.style.display = 'block';
            this.spotlight.style.left = (rect.left - padding) + 'px';
            this.spotlight.style.top = (rect.top - padding) + 'px';
            this.spotlight.style.width = (rect.width + padding * 2) + 'px';
            this.spotlight.style.height = (rect.height + padding * 2) + 'px';
            
            // Update SVG mask cutout - this creates the transparent area
            if (this.maskCutout) {
                this.maskCutout.setAttribute('x', (rect.left - padding).toString());
                this.maskCutout.setAttribute('y', (rect.top - padding).toString());
                this.maskCutout.setAttribute('width', (rect.width + padding * 2).toString());
                this.maskCutout.setAttribute('height', (rect.height + padding * 2).toString());
            }
            
            // Position tooltip
            this.positionTooltip(rect, step.position);
        }, 600);
    }

    positionTooltip(targetRect, preferredPosition) {
        const viewportPadding = 10; // Reduced padding for more space
        const spacing = 12; // Reduced spacing to allow more flexibility
        
        // Reset tooltip positioning for accurate measurement
        this.tooltip.style.position = 'fixed';
        this.tooltip.style.maxWidth = '420px'; // Slightly larger max width
        this.tooltip.style.width = 'auto';
        this.tooltip.style.left = '0px';
        this.tooltip.style.top = '0px';
        this.tooltip.style.transform = 'none';
        this.tooltip.style.zIndex = '10001'; // Ensure tooltip is above everything
        
        // Force reflow and get actual tooltip dimensions
        this.tooltip.offsetHeight; // Force reflow
        const tooltipRect = this.tooltip.getBoundingClientRect();
        const tooltipWidth = tooltipRect.width;
        const tooltipHeight = tooltipRect.height;
        
        // Calculate available space in all directions
        const spaces = {
            top: targetRect.top - viewportPadding,
            bottom: window.innerHeight - targetRect.bottom - viewportPadding,
            left: targetRect.left - viewportPadding,
            right: window.innerWidth - targetRect.right - viewportPadding
        };
        
        // Determine which positions can fit the tooltip
        const canFit = {
            top: spaces.top >= tooltipHeight + spacing,
            bottom: spaces.bottom >= tooltipHeight + spacing,
            left: spaces.left >= tooltipWidth + spacing,
            right: spaces.right >= tooltipWidth + spacing
        };
        
        // Choose best position
        let finalPosition = preferredPosition;
        
        // If preferred position doesn't fit, find the best alternative
        if (!canFit[preferredPosition]) {
            // Try positions in order of preference based on available space
            const positionsBySpace = Object.keys(spaces).sort((a, b) => spaces[b] - spaces[a]);
            
            for (const pos of positionsBySpace) {
                if (canFit[pos]) {
                    finalPosition = pos;
                    break;
                }
            }
            
            // If nothing fits perfectly, use position with most space
            // and allow tooltip to overlap with highlight if needed
            if (!canFit[finalPosition]) {
                finalPosition = positionsBySpace[0];
            }
        }
        
        // Calculate tooltip position
        let left, top, transform;
        
        switch (finalPosition) {
            case 'top':
                left = targetRect.left + (targetRect.width / 2);
                top = targetRect.top - spacing;
                transform = 'translate(-50%, -100%)';
                
                // Constrain horizontally to viewport
                const maxLeftForTop = window.innerWidth - viewportPadding - (tooltipWidth / 2);
                const minLeftForTop = viewportPadding + (tooltipWidth / 2);
                left = Math.max(minLeftForTop, Math.min(maxLeftForTop, left));
                
                // If doesn't fit, allow overlap
                if (spaces.top < tooltipHeight + spacing) {
                    top = viewportPadding;
                    transform = 'translateX(-50%)';
                }
                break;
                
            case 'bottom':
                left = targetRect.left + (targetRect.width / 2);
                top = targetRect.bottom + spacing;
                transform = 'translateX(-50%)';
                
                // Constrain horizontally to viewport
                const maxLeftForBottom = window.innerWidth - viewportPadding - (tooltipWidth / 2);
                const minLeftForBottom = viewportPadding + (tooltipWidth / 2);
                left = Math.max(minLeftForBottom, Math.min(maxLeftForBottom, left));
                
                // If doesn't fit, allow overlap
                if (spaces.bottom < tooltipHeight + spacing) {
                    top = Math.max(viewportPadding, window.innerHeight - tooltipHeight - viewportPadding);
                }
                break;
                
            case 'left':
                left = targetRect.left - spacing;
                top = targetRect.top + (targetRect.height / 2);
                transform = 'translate(-100%, -50%)';
                
                // Constrain vertically to viewport
                const maxTopForLeft = window.innerHeight - viewportPadding - (tooltipHeight / 2);
                const minTopForLeft = viewportPadding + (tooltipHeight / 2);
                top = Math.max(minTopForLeft, Math.min(maxTopForLeft, top));
                
                // If doesn't fit, allow overlap
                if (spaces.left < tooltipWidth + spacing) {
                    left = viewportPadding;
                    transform = 'translateY(-50%)';
                }
                break;
                
            case 'right':
                left = targetRect.right + spacing;
                top = targetRect.top + (targetRect.height / 2);
                transform = 'translateY(-50%)';
                
                // Constrain vertically to viewport
                const maxTopForRight = window.innerHeight - viewportPadding - (tooltipHeight / 2);
                const minTopForRight = viewportPadding + (tooltipHeight / 2);
                top = Math.max(minTopForRight, Math.min(maxTopForRight, top));
                
                // If doesn't fit, allow overlap
                if (spaces.right < tooltipWidth + spacing) {
                    left = Math.max(viewportPadding, window.innerWidth - tooltipWidth - viewportPadding);
                }
                break;
                
            default:
                // Center fallback
                left = window.innerWidth / 2;
                top = window.innerHeight / 2;
                transform = 'translate(-50%, -50%)';
                finalPosition = 'center';
        }
        
        // Apply calculated position
        this.tooltip.style.left = left + 'px';
        this.tooltip.style.top = top + 'px';
        this.tooltip.style.transform = transform;
        
        // Final safety check after transform is applied
        requestAnimationFrame(() => {
            const finalRect = this.tooltip.getBoundingClientRect();
            let adjustX = 0;
            let adjustY = 0;
            
            // Check and fix any remaining clipping
            if (finalRect.left < viewportPadding) {
                adjustX = viewportPadding - finalRect.left;
            } else if (finalRect.right > window.innerWidth - viewportPadding) {
                adjustX = (window.innerWidth - viewportPadding) - finalRect.right;
            }
            
            if (finalRect.top < viewportPadding) {
                adjustY = viewportPadding - finalRect.top;
            } else if (finalRect.bottom > window.innerHeight - viewportPadding) {
                adjustY = (window.innerHeight - viewportPadding) - finalRect.bottom;
            }
            
            // Apply micro-adjustments if needed
            if (adjustX !== 0 || adjustY !== 0) {
                const currentLeft = parseFloat(this.tooltip.style.left);
                const currentTop = parseFloat(this.tooltip.style.top);
                this.tooltip.style.left = (currentLeft + adjustX) + 'px';
                this.tooltip.style.top = (currentTop + adjustY) + 'px';
            }
        });
        
        // Update arrow direction
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
