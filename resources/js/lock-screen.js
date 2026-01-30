document.addEventListener('alpine:init', () => {
    Alpine.data('lockScreen', () => ({
        isOpen: false,
        password: '',
        isLoading: false,
        error: '',
        idleTime: 0,
        idleLimit: 10,
        // idleLimit: 5 * 60, // 5 minutes in seconds
        timer: null,

        init() {
            // Start the idle timer
            this.startTimer();

            // Track user activity to reset timer
            ['mousemove', 'keydown', 'click', 'scroll'].forEach(event => {
                window.addEventListener(event, () => this.resetTimer());
            });

            // Prevent closing the modal via Escape key if locked
            window.addEventListener('keydown', (e) => {
                if (this.isOpen && e.key === 'Escape') {
                    e.preventDefault();
                }
            });
        },

        startTimer() {
            this.timer = setInterval(() => {
                if (!this.isOpen) {
                    this.idleTime++;
                    if (this.idleTime >= this.idleLimit) {
                        this.lock();
                    }
                }
            }, 1000);
        },

        resetTimer() {
            if (!this.isOpen) {
                this.idleTime = 0;
            }
        },

        lock() {
            this.isOpen = true;
            this.error = '';
            this.password = '';
        },

        async unlock() {
            if (!this.password) {
                this.error = 'Masukkan password Anda.';
                return;
            }

            this.isLoading = true;
            this.error = '';

            try {
                const response = await axios.post('/lock-screen/unlock', {
                    password: this.password
                });

                if (response.data.success) {
                    this.isOpen = false;
                    this.resetTimer();
                    this.password = '';
                }
            } catch (err) {
                if (err.response && err.response.data.message) {
                    this.error = err.response.data.message;
                } else {
                    this.error = 'Terjadi kesalahan. Silakan coba lagi.';
                }
            } finally {
                this.isLoading = false;
            }
        }
    }));
});
