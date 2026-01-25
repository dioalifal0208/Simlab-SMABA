// Toast Notification Component untuk Lab-SMABA
// Alpine.js based, auto-dismiss, dengan icons

document.addEventListener('alpine:init', () => {
    Alpine.data('toast', () => ({
        visible: false,
        message: '',
        type: 'success', // success, error, warning, info
        duration: 3000,
        timeoutId: null,

        show(message, type = 'success', duration = 3000) {
            // Clear existing timeout if any
            if (this.timeoutId) {
                clearTimeout(this.timeoutId);
            }

            this.message = message;
            this.type = type;
            this.duration = duration;
            this.visible = true;

            // Auto-hide after duration
            this.timeoutId = setTimeout(() => {
                this.hide();
            }, duration);
        },

        hide() {
            this.visible = false;
            this.timeoutId = null;
        },

        getIcon() {
            const icons = {
                success: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
                error: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
                warning: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
                info: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
            };
            return icons[this.type] || icons.info;
        },

        getColorClasses() {
            const colors = {
                success: 'bg-green-50 border-green-200 text-green-800',
                error: 'bg-red-50 border-red-200 text-red-800',
                warning: 'bg-yellow-50 border-yellow-200 text-yellow-800',
                info: 'bg-blue-50 border-blue-200 text-blue-800'
            };
            return colors[this.type] || colors.info;
        }
    }));
});

// Global toast function untuk dipanggil dari anywhere
window.showToast = function(message, type = 'success', duration = 3000) {
    const event = new CustomEvent('show-toast', {
        detail: { message, type, duration }
    });
    window.dispatchEvent(event);
};
