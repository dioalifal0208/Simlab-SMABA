// Keyboard Shortcuts for Lab-SMABA
// Power user features for faster navigation

document.addEventListener('DOMContentLoaded', () => {
    const shortcuts = {
        // Global shortcuts (work everywhere)
        global: {
            '/': () => focusSearch(), // Focus search bar
            '?': () => showShortcutsModal(), // Show shortcuts help
            'g d': () => navigateTo('/dashboard'), // Go to dashboard
            'g i': () => navigateTo('/items'), // Go to items
            'g l': () => navigateTo('/loans'), // Go to loans
            'g b': () => navigateTo('/bookings'), // Go to bookings
            'Escape': () => closeModals(), // Close any open modals
        },
        
        // Admin-only shortcuts
        admin: {
            'n i': () => navigateTo('/items/create'), // New item
            'n l': () => navigateTo('/loans/create'), // New loan
            'n b': () => navigateTo('/bookings/create'), // New booking
        }
    };

    let keySequence = [];
    let sequenceTimeout = null;
    const userRole = document.body.dataset.userRole;

    document.addEventListener('keydown', (e) => {
        // Ignore if typing in input/textarea
        if (e.target.matches('input, textarea, select')) {
            return;
        }

        // Ignore if Ctrl/Alt/Meta pressed (browser shortcuts)
        if (e.ctrlKey || e.altKey || e.metaKey) {
            return;
        }

        // Add key to sequence
        keySequence.push(e.key);

        // Clear old sequence after 1 second
        clearTimeout(sequenceTimeout);
        sequenceTimeout = setTimeout(() => {
            keySequence = [];
        }, 1000);

        // Check single key shortcuts
        const singleKey = e.key;
        if (shortcuts.global[singleKey]) {
            e.preventDefault();
            shortcuts.global[singleKey]();
            keySequence = [];
            return;
        }

        // Check two-key sequences
        if (keySequence.length === 2) {
            const seq = keySequence.join(' ');
            
            // Global shortcuts
            if (shortcuts.global[seq]) {
                e.preventDefault();
                shortcuts.global[seq]();
                keySequence = [];
                return;
            }
            
            // Admin shortcuts
            if (userRole === 'admin' && shortcuts.admin[seq]) {
                e.preventDefault();
                shortcuts.admin[seq]();
                keySequence = [];
                return;
            }
            
            // Reset if no match
            keySequence = [];
        }
    });

    // Shortcut functions
    function focusSearch() {
        const searchInput = document.querySelector('input[type="search"], input[placeholder*="Cari"], input[placeholder*="Search"]');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        }
    }

    function showShortcutsModal() {
        // Create modal if doesn't exist
        if (!document.getElementById('shortcutsModal')) {
            createShortcutsModal();
        }
        
        const modal = document.getElementById('shortcutsModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function navigateTo(path) {
        window.location.href = path;
    }

    function closeModals() {
        // Close shortcuts modal
        const shortcutsModal = document.getElementById('shortcutsModal');
        if (shortcutsModal && !shortcutsModal.classList.contains('hidden')) {
            shortcutsModal.classList.add('hidden');
            shortcutsModal.classList.remove('flex');
            return;
        }

        // Close other modals with Alpine.js
        window.dispatchEvent(new CustomEvent('close-modals'));
    }

    function createShortcutsModal() {
        const modal = document.createElement('div');
        modal.id = 'shortcutsModal';
        modal.className = 'hidden fixed inset-0 z-50 items-center justify-center bg-black/60 p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-gray-200 p-6 flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-900">Keyboard Shortcuts</h2>
                    <button onclick="document.getElementById('shortcutsModal').classList.add('hidden'); document.getElementById('shortcutsModal').classList.remove('flex')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Global Shortcuts</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-700">Focus search bar</span>
                                <kbd class="px-3 py-1 bg-white border border-gray-300 rounded shadow-sm font-mono text-sm">/</kbd>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-700">Show this help</span>
                                <kbd class="px-3 py-1 bg-white border border-gray-300 rounded shadow-sm font-mono text-sm">?</kbd>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-700">Close modals</span>
                                <kbd class="px-3 py-1 bg-white border border-gray-300 rounded shadow-sm font-mono text-sm">Esc</kbd>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Navigation</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-700">Go to Dashboard</span>
                                <div class="flex gap-1">
                                    <kbd class="px-3 py-1 bg-white border border-gray-300 rounded shadow-sm font-mono text-sm">g</kbd>
                                    <kbd class="px-3 py-1 bg-white border border-gray-300 rounded shadow-sm font-mono text-sm">d</kbd>
                                </div>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-700">Go to Items</span>
                                <div class="flex gap-1">
                                    <kbd class="px-3 py-1 bg-white border border-gray-300 rounded shadow-sm font-mono text-sm">g</kbd>
                                    <kbd class="px-3 py-1 bg-white border border-gray-300 rounded shadow-sm font-mono text-sm">i</kbd>
                                </div>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-700">Go to Loans</span>
                                <div class="flex gap-1">
                                    <kbd class="px-3 py-1 bg-white border border-gray-300 rounded shadow-sm font-mono text-sm">g</kbd>
                                    <kbd class="px-3 py-1 bg-white border border-gray-300 rounded shadow-sm font-mono text-sm">l</kbd>
                                </div>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-700">Go to Bookings</span>
                                <div class="flex gap-1">
                                    <kbd class="px-3 py-1 bg-white border border-gray-300 rounded shadow-sm font-mono text-sm">g</kbd>
                                    <kbd class="px-3 py-1 bg-white border border-gray-300 rounded shadow-sm font-mono text-sm">b</kbd>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    ${userRole === 'admin' ? `
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Admin Actions</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                <span class="text-gray-700">New Item</span>
                                <div class="flex gap-1">
                                    <kbd class="px-3 py-1 bg-white border border-gray-300 rounded shadow-sm font-mono text-sm">n</kbd>
                                    <kbd class="px-3 py-1 bg-white border border-gray-300 rounded shadow-sm font-mono text-sm">i</kbd>
                                </div>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                <span class="text-gray-700">New Loan</span>
                                <div class="flex gap-1">
                                    <kbd class="px-3 py-1 bg-white border border-gray-300 rounded shadow-sm font-mono text-sm">n</kbd>
                                    <kbd class="px-3 py-1 bg-white border border-gray-300 rounded shadow-sm font-mono text-sm">l</kbd>
                                </div>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                <span class="text-gray-700">New Booking</span>
                                <div class="flex gap-1">
                                    <kbd class="px-3 py-1 bg-white border border-gray-300 rounded shadow-sm font-mono text-sm">n</kbd>
                                    <kbd class="px-3 py-1 bg-white border border-gray-300 rounded shadow-sm font-mono text-sm">b</kbd>
                                </div>
                            </div>
                        </div>
                    </div>
                    ` : ''}
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-800">
                            <strong>Tip:</strong> Press <kbd class="px-2 py-0.5 bg-white border border-blue-300 rounded font-mono text-xs">?</kbd> anytime to see this help.
                        </p>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Close on outside click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    }
});
