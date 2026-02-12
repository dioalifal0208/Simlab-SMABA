// Items Page Product Tour
document.addEventListener('DOMContentLoaded', () => {
    const STORAGE_KEY = 'lab-smaba-items-tour-completed';
    
    const tour = {
        currentStep: 0,
        isActive: false,
        
        steps: [
            {
                title: 'Selamat Datang di Inventaris',
                content: 'Mari kita kenali fitur-fitur pengelolaan inventaris laboratorium. Tour ini akan memandu Anda melalui semua fitur penting.',
                target: null,
                position: 'center'
            },
            {
                title: 'Tambah Item Baru',
                content: 'Klik tombol ini untuk menambah item baru ke inventaris. Admin dapat langsung menambah, sementara staf/guru dapat mengajukan permintaan penambahan.',
                target: 'a[href*="items.create"], a[href*="item-requests.create"]',
                position: 'bottom'
            },
            {
                title: 'Pencarian Real-time',
                content: 'Gunakan kolom pencarian ini untuk mencari item berdasarkan nama atau kode. Hasil akan muncul secara otomatis saat Anda mengetik.',
                target: '#search',
                position: 'bottom'
            },
            {
                title: 'Filter Tipe Item',
                content: 'Filter item berdasarkan tipe: Alat atau Bahan Habis Pakai. Filter akan diterapkan secara otomatis.',
                target: '#tipe',
                position: 'bottom'
            },
            {
                title: 'Filter Kondisi',
                content: 'Filter item berdasarkan kondisi: Baik, Kurang Baik, atau Rusak. Berguna untuk mengidentifikasi item yang perlu perbaikan atau penggantian.',
                target: '#kondisi',
                position: 'bottom'
            },
            {
                title: 'Tabel Inventaris',
                content: 'Tabel ini menampilkan semua item dengan informasi lengkap: nama, kode, jumlah tersedia, kondisi, dan lokasi laboratorium. Anda dapat mengurutkan dan melakukan aksi pada setiap item.',
                target: '#table-container',
                position: 'top'
            },
            {
                title: 'Tour Selesai!',
                content: 'Anda sekarang siap mengelola inventaris laboratorium. Klik tombol Tour di header kapan saja untuk mengulangi panduan ini.',
                target: null,
                position: 'center'
            }
        ],
        
        init() {
            // Auto-start tour on first visit
            if (!localStorage.getItem(STORAGE_KEY)) {
                setTimeout(() => this.start(), 1000);
            }
            
            // Listen for navbar tour button clicks (desktop and mobile)
            const navButton = document.getElementById('navbar-tour-button');
            const navButtonMobile = document.getElementById('navbar-tour-button-mobile');
            
            if (navButton) {
                navButton.addEventListener('click', () => this.start());
            }
            if (navButtonMobile) {
                navButtonMobile.addEventListener('click', () => this.start());
            }
        },
        
        start() {
            this.isActive = true;
            this.currentStep = 0;
            this.createOverlay();
            this.createTooltip();
            this.showStep(0);
            
            // Prevent body scroll
            const scrollY = window.scrollY;
            document.body.style.position = 'fixed';
            document.body.style.top = `-${scrollY}px`;
            document.body.style.width = '100%';
            document.body.classList.add('tour-active');
            document.documentElement.classList.add('tour-active');
        },
        
        createOverlay() {
            this.overlay = document.createElement('div');
            this.overlay.className = 'tour-overlay';
            
            const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.innerHTML = `
                <defs>
                    <filter id="items-tour-blur">
                        <feGaussianBlur in="SourceGraphic" stdDeviation="4"/>
                    </filter>
                    <mask id="items-tour-mask">
                        <rect x="0" y="0" width="100%" height="100%" fill="white"/>
                        <rect id="items-tour-cutout" x="0" y="0" width="0" height="0" rx="12" fill="black"/>
                    </mask>
                </defs>
                <rect x="0" y="0" width="100%" height="100%" fill="rgba(0, 0, 0, 0.75)" mask="url(#items-tour-mask)" filter="url(#items-tour-blur)"/>
            `;
            
            this.overlay.appendChild(svg);
            document.body.appendChild(this.overlay);
            this.maskCutout = svg.querySelector('#items-tour-cutout');
            
            this.spotlight = document.createElement('div');
            this.spotlight.className = 'tour-spotlight';
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
                        <button class="tour-btn tour-btn-secondary tour-btn-prev">Sebelumnya</button>
                        <button class="tour-btn tour-btn-primary tour-btn-next">Selanjutnya</button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(this.tooltip);
            
            // Event listeners
            this.tooltip.querySelector('.tour-tooltip-close').addEventListener('click', () => this.end());
            this.tooltip.querySelector('.tour-btn-prev').addEventListener('click', () => this.prevStep());
            this.tooltip.querySelector('.tour-btn-next').addEventListener('click', () => this.nextStep());
        },
        
        showStep(index) {
            const step = this.steps[index];
            if (!step) return;
            
            this.currentStep = index;
            
            // Update tooltip content
            this.tooltip.querySelector('.tour-tooltip-title').textContent = step.title;
            this.tooltip.querySelector('.tour-tooltip-content').textContent = step.content;
            this.tooltip.querySelector('.tour-tooltip-progress').textContent = `Langkah ${index + 1} dari ${this.steps.length}`;
            
            // Update button states
            const prevBtn = this.tooltip.querySelector('.tour-btn-prev');
            const nextBtn = this.tooltip.querySelector('.tour-btn-next');
            
            prevBtn.style.display = index === 0 ? 'none' : 'block';
            nextBtn.textContent = index === this.steps.length - 1 ? 'Selesai' : 'Selanjutnya';
            
            // Position elements
            this.positionElements(step);
            
            // Show tooltip with animation
            setTimeout(() => {
                this.tooltip.classList.add('tour-tooltip-visible');
            }, 100);
        },
        
        positionElements(step) {
            if (!step.target) {
                // Center position
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
            
            // Scroll target into view
            target.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'nearest' });
            
            setTimeout(() => {
                const rect = target.getBoundingClientRect();
                const padding = 12;
                
                // Position spotlight
                this.spotlight.style.display = 'block';
                this.spotlight.style.left = (rect.left - padding) + 'px';
                this.spotlight.style.top = (rect.top - padding) + 'px';
                this.spotlight.style.width = (rect.width + padding * 2) + 'px';
                this.spotlight.style.height = (rect.height + padding * 2) + 'px';
                
                // Update SVG mask cutout
                if (this.maskCutout) {
                    this.maskCutout.setAttribute('x', (rect.left - padding).toString());
                    this.maskCutout.setAttribute('y', (rect.top - padding).toString());
                    this.maskCutout.setAttribute('width', (rect.width + padding * 2).toString());
                    this.maskCutout.setAttribute('height', (rect.height + padding * 2).toString());
                }
                
                // Position tooltip
                this.positionTooltip(rect, step.position);
            }, 600);
        },
        
        positionTooltip(targetRect, preferredPosition) {
            const viewportPadding = 10;
            const spacing = 12;
            
            this.tooltip.style.position = 'fixed';
            this.tooltip.style.maxWidth = '420px';
            this.tooltip.style.width = 'auto';
            this.tooltip.style.left = '0px';
            this.tooltip.style.top = '0px';
            this.tooltip.style.transform = 'none';
            this.tooltip.style.zIndex = '10001';
            
            this.tooltip.offsetHeight;
            const tooltipRect = this.tooltip.getBoundingClientRect();
            const tooltipWidth = tooltipRect.width;
            const tooltipHeight = tooltipRect.height;
            
            const spaces = {
                top: targetRect.top - viewportPadding,
                bottom: window.innerHeight - targetRect.bottom - viewportPadding,
                left: targetRect.left - viewportPadding,
                right: window.innerWidth - targetRect.right - viewportPadding
            };
            
            const canFit = {
                top: spaces.top >= tooltipHeight + spacing,
                bottom: spaces.bottom >= tooltipHeight + spacing,
                left: spaces.left >= tooltipWidth + spacing,
                right: spaces.right >= tooltipWidth + spacing
            };
            
            let finalPosition = preferredPosition;
            
            if (!canFit[preferredPosition]) {
                const positionsBySpace = Object.keys(spaces).sort((a, b) => spaces[b] - spaces[a]);
                for (const pos of positionsBySpace) {
                    if (canFit[pos]) {
                        finalPosition = pos;
                        break;
                    }
                }
                if (!canFit[finalPosition]) {
                    finalPosition = positionsBySpace[0];
                }
            }
            
            let left, top, transform;
            
            switch (finalPosition) {
                case 'top':
                    left = targetRect.left + (targetRect.width / 2);
                    top = targetRect.top - spacing;
                    transform = 'translate(-50%, -100%)';
                    
                    const maxLeftForTop = window.innerWidth - viewportPadding - (tooltipWidth / 2);
                    const minLeftForTop = viewportPadding + (tooltipWidth / 2);
                    left = Math.max(minLeftForTop, Math.min(maxLeftForTop, left));
                    
                    if (spaces.top < tooltipHeight + spacing) {
                        top = viewportPadding;
                        transform = 'translateX(-50%)';
                    }
                    break;
                    
                case 'bottom':
                    left = targetRect.left + (targetRect.width / 2);
                    top = targetRect.bottom + spacing;
                    transform = 'translateX(-50%)';
                    
                    const maxLeftForBottom = window.innerWidth - viewportPadding - (tooltipWidth / 2);
                    const minLeftForBottom = viewportPadding + (tooltipWidth / 2);
                    left = Math.max(minLeftForBottom, Math.min(maxLeftForBottom, left));
                    
                    if (spaces.bottom < tooltipHeight + spacing) {
                        top = Math.max(viewportPadding, window.innerHeight - tooltipHeight - viewportPadding);
                    }
                    break;
                    
                case 'left':
                    left = targetRect.left - spacing;
                    top = targetRect.top + (targetRect.height / 2);
                    transform = 'translate(-100%, -50%)';
                    
                    const maxTopForLeft = window.innerHeight - viewportPadding - (tooltipHeight / 2);
                    const minTopForLeft = viewportPadding + (tooltipHeight / 2);
                    top = Math.max(minTopForLeft, Math.min(maxTopForLeft, top));
                    
                    if (spaces.left < tooltipWidth + spacing) {
                        left = viewportPadding;
                        transform = 'translateY(-50%)';
                    }
                    break;
                    
                case 'right':
                    left = targetRect.right + spacing;
                    top = targetRect.top + (targetRect.height / 2);
                    transform = 'translateY(-50%)';
                    
                    const maxTopForRight = window.innerHeight - viewportPadding - (tooltipHeight / 2);
                    const minTopForRight = viewportPadding + (tooltipHeight / 2);
                    top = Math.max(minTopForRight, Math.min(maxTopForRight, top));
                    
                    if (spaces.right < tooltipWidth + spacing) {
                        left = Math.max(viewportPadding, window.innerWidth - tooltipWidth - viewportPadding);
                    }
                    break;
                    
                default:
                    left = window.innerWidth / 2;
                    top = window.innerHeight / 2;
                    transform = 'translate(-50%, -50%)';
                    finalPosition = 'center';
            }
            
            this.tooltip.style.left = left + 'px';
            this.tooltip.style.top = top + 'px';
            this.tooltip.style.transform = transform;
            
            requestAnimationFrame(() => {
                const finalRect = this.tooltip.getBoundingClientRect();
                let adjustX = 0;
                let adjustY = 0;
                
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
                
                if (adjustX !== 0 || adjustY !== 0) {
                    const currentLeft = parseFloat(this.tooltip.style.left);
                    const currentTop = parseFloat(this.tooltip.style.top);
                    this.tooltip.style.left = (currentLeft + adjustX) + 'px';
                    this.tooltip.style.top = (currentTop + adjustY) + 'px';
                }
            });
            
            this.tooltip.setAttribute('data-position', finalPosition);
        },
        
        nextStep() {
            if (this.currentStep < this.steps.length - 1) {
                this.tooltip.classList.remove('tour-tooltip-visible');
                setTimeout(() => {
                    this.showStep(this.currentStep + 1);
                }, 300);
            } else {
                this.complete();
            }
        },
        
        prevStep() {
            if (this.currentStep > 0) {
                this.tooltip.classList.remove('tour-tooltip-visible');
                setTimeout(() => {
                    this.showStep(this.currentStep - 1);
                }, 300);
            }
        },
        
        complete() {
            localStorage.setItem(STORAGE_KEY, 'true');
            this.end();
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        
        end() {
            this.isActive = false;
            
            // Remove elements
            if (this.overlay) this.overlay.remove();
            if (this.spotlight) this.spotlight.remove();
            if (this.tooltip) this.tooltip.remove();
            
            // Restore scroll
            const scrollY = document.body.style.top;
            document.body.style.position = '';
            document.body.style.top = '';
            document.body.style.width = '';
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            document.body.classList.remove('tour-active');
            document.documentElement.classList.remove('tour-active');
        }
    };
    
    // Initialize tour
    tour.init();
});
