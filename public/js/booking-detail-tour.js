/**
 * Booking Detail Page Product Tour — 4 Steps
 * Reuses shared dashboard-tour.css styles.
 * Accounts for #main-wrapper being the scroll container.
 */
document.addEventListener('DOMContentLoaded', () => {
    const STORAGE_KEY = 'lab-smaba-booking-detail-tour-v1';
    const SCROLL_CONTAINER = document.getElementById('main-wrapper');

    const tour = {
        currentStep: 0,
        isActive: false,
        overlay: null,
        spotlight: null,
        tooltip: null,
        maskCutout: null,

        steps: [
            {
                title: 'Detail Booking',
                content: 'Halaman ini menampilkan informasi lengkap terkait penggunaan laboratorium.',
                target: '#tour-info',
                position: 'bottom'
            },
            {
                title: 'Status',
                content: 'Bagian ini menunjukkan apakah booking sudah disetujui, ditolak, atau masih menunggu.',
                target: '#tour-status',
                position: 'left'
            },
            {
                title: 'Jadwal',
                content: 'Menampilkan waktu penggunaan laboratorium yang telah diajukan, lengkap dengan visualisasi timeline.',
                target: '#tour-schedule',
                position: 'top'
            },
            {
                title: 'Tindakan',
                content: 'Gunakan tombol ini untuk melakukan aksi seperti menyetujui, menolak, atau menandai booking selesai.',
                target: '#tour-actions',
                position: 'left'
            }
        ],

        init() {
            if (!localStorage.getItem(STORAGE_KEY)) {
                setTimeout(() => this.start(), 800);
            }

            const navBtn = document.getElementById('navbar-tour-button');
            const navBtnMobile = document.getElementById('navbar-tour-button-mobile');
            if (navBtn) navBtn.addEventListener('click', () => this.start());
            if (navBtnMobile) navBtnMobile.addEventListener('click', () => this.start());
        },

        start() {
            if (this.isActive) return;
            this.isActive = true;
            this.currentStep = 0;

            this.createOverlay();
            this.createTooltip();
            this.showStep(0);

            document.body.classList.add('tour-active');
            document.documentElement.classList.add('tour-active');
        },

        createOverlay() {
            this.overlay = document.createElement('div');
            this.overlay.className = 'tour-overlay';

            const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.innerHTML = `
                <defs>
                    <filter id="bd-tour-blur"><feGaussianBlur in="SourceGraphic" stdDeviation="3"/></filter>
                    <mask id="bd-tour-mask">
                        <rect x="0" y="0" width="100%" height="100%" fill="white"/>
                        <rect id="bd-tour-cutout" x="0" y="0" width="0" height="0" rx="14" fill="black"/>
                    </mask>
                </defs>
                <rect x="0" y="0" width="100%" height="100%" fill="rgba(0,0,0,0.72)" mask="url(#bd-tour-mask)" filter="url(#bd-tour-blur)"/>
            `;
            this.overlay.appendChild(svg);
            document.body.appendChild(this.overlay);
            this.maskCutout = svg.querySelector('#bd-tour-cutout');

            this.spotlight = document.createElement('div');
            this.spotlight.className = 'tour-spotlight';
            this.spotlight.style.display = 'none';
            document.body.appendChild(this.spotlight);
        },

        createTooltip() {
            this.tooltip = document.createElement('div');
            this.tooltip.className = 'tour-tooltip';
            this.tooltip.innerHTML = `
                <div class="tour-tooltip-header">
                    <h3 class="tour-tooltip-title"></h3>
                    <button class="tour-tooltip-close" aria-label="Close tour">&times;</button>
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
        },

        showStep(index) {
            // Skip steps whose target doesn't exist (e.g. action panel hidden for non-admin)
            const step = this.steps[index];
            if (!step) return;

            if (step.target && !document.querySelector(step.target)) {
                // Target missing — skip forward or backward
                if (index < this.steps.length - 1) {
                    this.showStep(index + 1);
                } else {
                    this.complete();
                }
                return;
            }

            this.currentStep = index;

            this.tooltip.querySelector('.tour-tooltip-title').textContent = step.title;
            this.tooltip.querySelector('.tour-tooltip-content').textContent = step.content;

            // Calculate visible step number (accounting for skipped steps)
            const visibleSteps = this.steps.filter(s => !s.target || document.querySelector(s.target));
            const visibleIndex = visibleSteps.indexOf(step);
            this.tooltip.querySelector('.tour-tooltip-progress').textContent = `Langkah ${visibleIndex + 1} dari ${visibleSteps.length}`;

            const backBtn = this.tooltip.querySelector('.tour-btn-back');
            const nextBtn = this.tooltip.querySelector('.tour-btn-next');

            if (index === 0) {
                backBtn.textContent = 'Lewati';
                nextBtn.textContent = 'Selanjutnya';
            } else if (index === this.steps.length - 1 || visibleIndex === visibleSteps.length - 1) {
                backBtn.textContent = 'Sebelumnya';
                nextBtn.textContent = 'Selesai';
            } else {
                backBtn.textContent = 'Sebelumnya';
                nextBtn.textContent = 'Selanjutnya';
            }

            this.tooltip.classList.remove('tour-tooltip-visible');
            this.positionElements(step);

            setTimeout(() => {
                this.tooltip.classList.add('tour-tooltip-visible');
            }, 150);
        },

        positionElements(step) {
            const target = step.target ? document.querySelector(step.target) : null;

            if (!target) {
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

            if (SCROLL_CONTAINER) {
                target.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'nearest' });
            }

            setTimeout(() => {
                const rect = target.getBoundingClientRect();
                const pad = 12;

                this.spotlight.style.display = 'block';
                this.spotlight.style.left = (rect.left - pad) + 'px';
                this.spotlight.style.top = (rect.top - pad) + 'px';
                this.spotlight.style.width = (rect.width + pad * 2) + 'px';
                this.spotlight.style.height = (rect.height + pad * 2) + 'px';

                if (this.maskCutout) {
                    this.maskCutout.setAttribute('x', rect.left - pad);
                    this.maskCutout.setAttribute('y', rect.top - pad);
                    this.maskCutout.setAttribute('width', rect.width + pad * 2);
                    this.maskCutout.setAttribute('height', rect.height + pad * 2);
                }

                this.positionTooltip(rect, step.position);
            }, 500);
        },

        positionTooltip(targetRect, preferred) {
            const vp = 12, gap = 14;

            this.tooltip.style.position = 'fixed';
            this.tooltip.style.maxWidth = '420px';
            this.tooltip.style.width = 'auto';
            this.tooltip.style.left = '0';
            this.tooltip.style.top = '0';
            this.tooltip.style.transform = 'none';
            this.tooltip.style.zIndex = '10001';

            this.tooltip.offsetHeight;
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
        },

        _handleBack() {
            if (this.currentStep === 0) {
                this.end();
            } else {
                this.prevStep();
            }
        },

        _handleNext() {
            // Check if this is the last visible step
            const visibleSteps = this.steps.filter(s => !s.target || document.querySelector(s.target));
            const currentVisible = visibleSteps.indexOf(this.steps[this.currentStep]);
            if (currentVisible === visibleSteps.length - 1) {
                this.complete();
            } else {
                this.nextStep();
            }
        },

        nextStep() {
            if (this.currentStep < this.steps.length - 1) {
                this.tooltip.classList.remove('tour-tooltip-visible');
                setTimeout(() => this.showStep(this.currentStep + 1), 250);
            }
        },

        prevStep() {
            if (this.currentStep > 0) {
                this.tooltip.classList.remove('tour-tooltip-visible');
                // Find previous visible step
                let prev = this.currentStep - 1;
                while (prev >= 0) {
                    const s = this.steps[prev];
                    if (!s.target || document.querySelector(s.target)) break;
                    prev--;
                }
                if (prev >= 0) {
                    setTimeout(() => this.showStep(prev), 250);
                }
            }
        },

        complete() {
            localStorage.setItem(STORAGE_KEY, 'true');
            this.end();
        },

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
            if (SCROLL_CONTAINER) SCROLL_CONTAINER.scrollTo({ top: 0, behavior: 'smooth' });
        }
    };

    tour.init();
});
