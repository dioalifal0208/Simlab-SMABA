/**
 * Dashboard Product Tour — 4 Steps (+ optional sidebar)
 * Reuses shared dashboard-tour.css styles.
 * Accounts for #main-wrapper being the scroll container (position:fixed layout).
 *
 * Fixes:
 *  - Waits for window.load + Alpine ready before init (ensures DOM is fully rendered)
 *  - Validates each target exists before showing the step; skips missing targets
 *  - Debug logging in console for every step
 *  - Never falls back to centered modal for targeted steps
 */

class DashboardTour {
    constructor() {
        this.currentStep = 0;
        this.isActive = false;
        this.overlay = null;
        this.spotlight = null;
        this.tooltip = null;
        this.maskCutout = null;
        this.scrollContainer = null;

        this.STORAGE_KEY = 'lab-smaba-dashboard-tour-v2';
        this.DEBUG = true; // flip to false in production

        this.steps = [
            {
                title: 'Dashboard Utama',
                content: 'Halaman ini menampilkan ringkasan aktivitas dan data penting sistem laboratorium.',
                target: null,           // welcome — intentionally centered
                position: 'center'
            },
            {
                title: 'Ringkasan Data',
                content: 'Bagian ini menunjukkan informasi penting secara cepat seperti jumlah inventaris dan aktivitas terbaru.',
                target: '#tour-stats',
                position: 'bottom'
            },
            {
                title: 'Aktivitas Terbaru',
                content: 'Di sini Anda dapat melihat aktivitas terakhir seperti peminjaman atau booking lab.',
                target: '#tour-activity',
                position: 'top'
            },
            {
                title: 'Tindakan Cepat',
                content: 'Gunakan menu ini untuk melakukan aksi utama dengan cepat seperti menambah data atau membuat booking.',
                target: '#tour-quick-actions',
                position: 'left'
            }
        ];
    }

    _log(msg, ...args) {
        if (this.DEBUG) console.log(`%c[DashboardTour] ${msg}`, 'color:#22c55e;font-weight:bold', ...args);
    }

    _warn(msg, ...args) {
        if (this.DEBUG) console.warn(`[DashboardTour] ${msg}`, ...args);
    }

    /**
     * Build a filtered list of steps whose targets actually exist in the DOM.
     * Steps with target === null (welcome) are always included.
     */
    _getVisibleSteps() {
        return this.steps.filter(step => {
            if (!step.target) return true; // welcome step
            const el = document.querySelector(step.target);
            if (!el) {
                this._warn(`Target "${step.target}" NOT found — step "${step.title}" will be skipped.`);
                return false;
            }
            this._log(`Target "${step.target}" found ✓`, el);
            return true;
        });
    }

    init() {
        this.scrollContainer = document.getElementById('main-wrapper');

        // Log all targets at init time
        this._log('Initializing — checking targets…');
        this.steps.forEach((step, i) => {
            if (step.target) {
                const el = document.querySelector(step.target);
                this._log(`Step ${i} "${step.title}" → target="${step.target}" → ${el ? 'EXISTS ✓' : 'MISSING ✗'}`);
            } else {
                this._log(`Step ${i} "${step.title}" → no target (centered welcome)`);
            }
        });

        // Auto-start on first visit
        if (!localStorage.getItem(this.STORAGE_KEY)) {
            setTimeout(() => this.start(), 800);
        }

        // Listen for navbar "Bantuan" button
        const navBtn = document.getElementById('navbar-tour-button');
        const navBtnMobile = document.getElementById('navbar-tour-button-mobile');
        if (navBtn) navBtn.addEventListener('click', () => this.start());
        if (navBtnMobile) navBtnMobile.addEventListener('click', () => this.start());
    }

    start() {
        if (this.isActive) return;

        // Rebuild visible steps at start time (DOM may have changed)
        this.visibleSteps = this._getVisibleSteps();
        if (this.visibleSteps.length === 0) {
            this._warn('No visible steps — aborting tour.');
            return;
        }

        this.isActive = true;
        this.currentStep = 0;

        this._log(`Starting tour with ${this.visibleSteps.length} visible steps (out of ${this.steps.length} total).`);

        this.createOverlay();
        this.createTooltip();
        this.showStep(0);

        document.body.classList.add('tour-active');
        document.documentElement.classList.add('tour-active');
    }

    /* ── Overlay + Spotlight ── */
    createOverlay() {
        this.overlay = document.createElement('div');
        this.overlay.className = 'tour-overlay';

        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.innerHTML = `
            <defs>
                <mask id="dash-tour-mask">
                    <rect x="0" y="0" width="100%" height="100%" fill="white"/>
                    <rect id="dash-tour-cutout" x="0" y="0" width="0" height="0" rx="12" fill="black"/>
                </mask>
            </defs>
            <rect x="0" y="0" width="100%" height="100%" fill="rgba(0,0,0,0.4)" mask="url(#dash-tour-mask)"/>
        `;
        this.overlay.appendChild(svg);
        document.body.appendChild(this.overlay);
        this.maskCutout = svg.querySelector('#dash-tour-cutout');

        this.spotlight = document.createElement('div');
        this.spotlight.className = 'tour-spotlight';
        this.spotlight.style.display = 'none';
        document.body.appendChild(this.spotlight);
    }

    /* ── Tooltip ── */
    createTooltip() {
        this.tooltip = document.createElement('div');
        this.tooltip.className = 'tour-tooltip';
        this.tooltip.innerHTML = `
            <div class="tour-tooltip-arrow"></div>
            <div class="tour-tooltip-header">
                <h3 class="tour-tooltip-title"></h3>
                <button class="tour-tooltip-close" aria-label="Close tour"></button>
            </div>
            <div class="tour-tooltip-content"></div>
            <div class="tour-tooltip-footer">
                <div class="tour-tooltip-progress"></div>
                <div class="tour-tooltip-buttons">
                    <button class="tour-btn tour-btn-secondary tour-btn-back">Lewati</button>
                    <button class="tour-btn tour-btn-primary tour-btn-next">Selanjutnya</button>
                </div>
            </div>
        `;
        document.body.appendChild(this.tooltip);

        this.tooltip.querySelector('.tour-tooltip-close').addEventListener('click', () => this.end());
        this.tooltip.querySelector('.tour-btn-back').addEventListener('click', () => this._handleBack());
        this.tooltip.querySelector('.tour-btn-next').addEventListener('click', () => this._handleNext());
    }

    /* ── Build dots indicator ── */
    _renderDots() {
        const container = this.tooltip.querySelector('.tour-tooltip-progress');
        container.innerHTML = '';
        for (let i = 0; i < this.visibleSteps.length; i++) {
            const dot = document.createElement('span');
            dot.className = 'tour-dot' + (i === this.currentStep ? ' active' : '');
            container.appendChild(dot);
        }
    }

    /* ── Show Step ── */
    showStep(index) {
        const step = this.visibleSteps[index];
        if (!step) {
            this._warn(`No step at visible index ${index} — ending tour.`);
            this.complete();
            return;
        }

        // Double-check targeted steps still exist (DOM can mutate)
        if (step.target) {
            const el = document.querySelector(step.target);
            if (!el) {
                this._warn(`Step "${step.title}" target "${step.target}" disappeared — skipping.`);
                // Try next step
                if (index < this.visibleSteps.length - 1) {
                    this.showStep(index + 1);
                } else {
                    this.complete();
                }
                return;
            }
        }

        this.currentStep = index;
        this._log(`Showing step ${index}: "${step.title}" → target=${step.target || '(centered)'}`);

        // Update content
        this.tooltip.querySelector('.tour-tooltip-title').textContent = step.title;
        this.tooltip.querySelector('.tour-tooltip-content').textContent = step.content;

        // Dots indicator
        this._renderDots();

        // Update buttons
        const backBtn = this.tooltip.querySelector('.tour-btn-back');
        const nextBtn = this.tooltip.querySelector('.tour-btn-next');

        if (index === 0) {
            backBtn.textContent = 'Lewati';
            nextBtn.textContent = 'Mulai Tour';
        } else if (index === this.visibleSteps.length - 1) {
            backBtn.textContent = 'Sebelumnya';
            nextBtn.textContent = 'Selesai';
        } else {
            backBtn.textContent = 'Sebelumnya';
            nextBtn.textContent = 'Selanjutnya';
        }

        // Reset tooltip visibility for animation
        this.tooltip.classList.remove('tour-tooltip-visible');

        // Position elements
        this.positionElements(step);

        setTimeout(() => {
            this.tooltip.classList.add('tour-tooltip-visible');
        }, 150);
    }

    /* ── Position Spotlight + Tooltip ── */
    positionElements(step) {
        const target = step.target ? document.querySelector(step.target) : null;

        if (!target) {
            // No target — center tooltip (welcome step only)
            this.spotlight.style.display = 'none';
            if (this.maskCutout) {
                this.maskCutout.setAttribute('width', '0');
                this.maskCutout.setAttribute('height', '0');
            }
            this.tooltip.style.position = 'fixed';
            this.tooltip.style.top = '50%';
            this.tooltip.style.left = '50%';
            this.tooltip.style.transform = 'translate(-50%, -50%)';
            this.tooltip.style.maxWidth = '480px';
            this.tooltip.setAttribute('data-position', 'center');
            return;
        }

        // Scroll target into view inside #main-wrapper
        if (this.scrollContainer) {
            target.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'nearest' });
        }

        setTimeout(() => {
            const rect = target.getBoundingClientRect();
            const pad = 12;

            this._log(`Target "${step.target}" rect:`, { top: rect.top, left: rect.left, w: rect.width, h: rect.height });

            // Spotlight
            this.spotlight.style.display = 'block';
            this.spotlight.style.left = (rect.left - pad) + 'px';
            this.spotlight.style.top = (rect.top - pad) + 'px';
            this.spotlight.style.width = (rect.width + pad * 2) + 'px';
            this.spotlight.style.height = (rect.height + pad * 2) + 'px';

            // SVG mask cutout
            if (this.maskCutout) {
                this.maskCutout.setAttribute('x', rect.left - pad);
                this.maskCutout.setAttribute('y', rect.top - pad);
                this.maskCutout.setAttribute('width', rect.width + pad * 2);
                this.maskCutout.setAttribute('height', rect.height + pad * 2);
            }

            // Tooltip — anchored to target
            this.positionTooltip(rect, step.position);
        }, 500);
    }

    positionTooltip(targetRect, preferred) {
        const vp = 12;
        const gap = 16;

        this.tooltip.style.position = 'fixed';
        this.tooltip.style.maxWidth = '400px';
        this.tooltip.style.width = 'auto';
        this.tooltip.style.left = '0';
        this.tooltip.style.top = '0';
        this.tooltip.style.transform = 'none';
        this.tooltip.style.zIndex = '10001';

        this.tooltip.offsetHeight; // force reflow
        const tt = this.tooltip.getBoundingClientRect();

        const space = {
            top: targetRect.top - vp,
            bottom: window.innerHeight - targetRect.bottom - vp,
            left: targetRect.left - vp,
            right: window.innerWidth - targetRect.right - vp
        };

        const fits = {
            top: space.top >= tt.height + gap,
            bottom: space.bottom >= tt.height + gap,
            left: space.left >= tt.width + gap,
            right: space.right >= tt.width + gap
        };

        let pos = preferred;
        if (!fits[pos]) {
            const ranked = Object.keys(space).sort((a, b) => space[b] - space[a]);
            pos = ranked.find(p => fits[p]) || ranked[0];
            this._log(`Preferred "${preferred}" doesn't fit → using "${pos}"`);
        }

        let left, top, transform;

        switch (pos) {
            case 'top':
                left = targetRect.left + targetRect.width / 2;
                top = targetRect.top - gap;
                transform = 'translate(-50%, -100%)';
                left = Math.max(vp + tt.width / 2, Math.min(window.innerWidth - vp - tt.width / 2, left));
                break;
            case 'bottom':
                left = targetRect.left + targetRect.width / 2;
                top = targetRect.bottom + gap;
                transform = 'translateX(-50%)';
                left = Math.max(vp + tt.width / 2, Math.min(window.innerWidth - vp - tt.width / 2, left));
                break;
            case 'left':
                left = targetRect.left - gap;
                top = targetRect.top + targetRect.height / 2;
                transform = 'translate(-100%, -50%)';
                top = Math.max(vp + tt.height / 2, Math.min(window.innerHeight - vp - tt.height / 2, top));
                break;
            case 'right':
                left = targetRect.right + gap;
                top = targetRect.top + targetRect.height / 2;
                transform = 'translateY(-50%)';
                top = Math.max(vp + tt.height / 2, Math.min(window.innerHeight - vp - tt.height / 2, top));
                break;
            default:
                left = window.innerWidth / 2;
                top = window.innerHeight / 2;
                transform = 'translate(-50%, -50%)';
                pos = 'center';
        }

        this.tooltip.style.left = left + 'px';
        this.tooltip.style.top = top + 'px';
        this.tooltip.style.transform = transform;
        this.tooltip.setAttribute('data-position', pos);

        // Micro-adjust if clipped
        requestAnimationFrame(() => {
            const fr = this.tooltip.getBoundingClientRect();
            let dx = 0, dy = 0;
            if (fr.left < vp) dx = vp - fr.left;
            else if (fr.right > window.innerWidth - vp) dx = (window.innerWidth - vp) - fr.right;
            if (fr.top < vp) dy = vp - fr.top;
            else if (fr.bottom > window.innerHeight - vp) dy = (window.innerHeight - vp) - fr.bottom;
            if (dx || dy) {
                this.tooltip.style.left = (parseFloat(this.tooltip.style.left) + dx) + 'px';
                this.tooltip.style.top = (parseFloat(this.tooltip.style.top) + dy) + 'px';
            }
        });
    }

    /* ── Navigation ── */
    _handleBack() {
        if (this.currentStep === 0) {
            this.end(); // "Lewati"
        } else {
            this.prevStep();
        }
    }

    _handleNext() {
        if (this.currentStep === this.visibleSteps.length - 1) {
            this.complete();
        } else {
            this.nextStep();
        }
    }

    nextStep() {
        if (this.currentStep < this.visibleSteps.length - 1) {
            this.tooltip.classList.remove('tour-tooltip-visible');
            setTimeout(() => this.showStep(this.currentStep + 1), 250);
        }
    }

    prevStep() {
        if (this.currentStep > 0) {
            this.tooltip.classList.remove('tour-tooltip-visible');
            setTimeout(() => this.showStep(this.currentStep - 1), 250);
        }
    }

    complete() {
        localStorage.setItem(this.STORAGE_KEY, 'true');
        this._log('Tour completed — saved to localStorage.');
        this.end();
    }

    skip() {
        this.complete();
    }

    end() {
        this.isActive = false;

        if (this.overlay) { this.overlay.remove(); this.overlay = null; }
        if (this.spotlight) { this.spotlight.remove(); this.spotlight = null; }
        if (this.tooltip) {
            this.tooltip.classList.remove('tour-tooltip-visible');
            setTimeout(() => { if (this.tooltip) { this.tooltip.remove(); this.tooltip = null; } }, 300);
        }

        document.body.classList.remove('tour-active');
        document.documentElement.classList.remove('tour-active');

        // Scroll back to top
        const sc = document.getElementById('main-wrapper');
        if (sc) sc.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

// ─── Initialize after full page load (images, Alpine, etc.) ───
// Using window.load instead of DOMContentLoaded ensures Alpine/AOS
// have finished rendering and all target elements exist in the DOM.

let dashboardTour;

function _initDashboardTour() {
    // Extra 400ms delay to let Alpine finish x-init / x-data rendering
    setTimeout(() => {
        dashboardTour = new DashboardTour();
        dashboardTour.init();
        window.startDashboardTour = () => dashboardTour.start();
    }, 400);
}

if (document.readyState === 'complete') {
    _initDashboardTour();
} else {
    window.addEventListener('load', _initDashboardTour);
}
