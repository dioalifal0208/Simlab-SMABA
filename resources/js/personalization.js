// Dark Mode & Personalization System
// Manages user preferences with localStorage

document.addEventListener('DOMContentLoaded', () => {
    const preferences = {
        theme: 'light', // light or dark
        
        // Load from localStorage
        load() {
            const saved = localStorage.getItem('lab-smaba-preferences');
            if (saved) {
                try {
                    const data = JSON.parse(saved);
                    this.theme = data.theme || 'light';
                    
                    // FORCE LIGHT MODE for Admin and Guru
                    // Check body attribute set in app.blade.php
                    const userRole = document.body.dataset.userRole;
                    if (userRole === 'admin' || userRole === 'guru') {
                        this.theme = 'light';
                        // Update storage to prevent future flashes
                        this.save();
                    }
                } catch (e) {
                    console.error('Failed to load preferences:', e);
                }
            }
            this.apply();
        },
        
        // Save to localStorage
        save() {
            localStorage.setItem('lab-smaba-preferences', JSON.stringify({
                theme: this.theme
            }));
        },
        
        // Apply theme to document
        apply() {
            document.documentElement.setAttribute('data-theme', this.theme);
            
            // Sync with Tailwind class-based dark mode
            if (this.theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            this.updateToggleIcon();
        },
        
        // Toggle between light and dark
        toggleTheme() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            this.save();
            this.apply();
            
            // Show toast notification
            window.dispatchEvent(new CustomEvent('show-toast', {
                detail: {
                    message: `Mode ${this.theme === 'dark' ? 'Gelap' : 'Terang'} diaktifkan`,
                    type: 'info'
                }
            }));
        },
        
        // Update toggle button icon
        updateToggleIcon() {
            const icon = document.querySelector('.dark-mode-toggle i');
            if (icon) {
                icon.className = this.theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            }
        }
    };
    
    // Load preferences on page load
    preferences.load();

    // Double check enforcement for current session
    const userRole = document.body.dataset.userRole;
    if ((userRole === 'admin' || userRole === 'guru') && preferences.theme === 'dark') {
        preferences.theme = 'light';
        preferences.save();
        preferences.apply();
    }
    
    // Create theme toggle button if doesn't exist
    // Handle theme toggle button
    const existingToggle = document.querySelector('.dark-mode-toggle');
    
    if (existingToggle) {
        existingToggle.addEventListener('click', (e) => {
            e.preventDefault();
            preferences.toggleTheme();
        });
    } else {
        // Create floating button if not found in DOM
        const toggle = document.createElement('button');
        toggle.className = 'dark-mode-toggle fixed bottom-4 right-4 z-50 p-3 rounded-full bg-gray-800 text-white shadow-lg hover:bg-gray-700 transition-all duration-300';
        toggle.setAttribute('aria-label', 'Toggle dark mode');
        toggle.innerHTML = '<i class="fas fa-moon"></i>';
        toggle.addEventListener('click', () => preferences.toggleTheme());
        document.body.appendChild(toggle);
    }
    
    // Expose to window for programmatic access
    window.labSmabaPreferences = preferences;
    
    // Listen for system theme changes
    if (window.matchMedia) {
        const darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');
        
        // Auto-apply on first visit
        // Auto-apply on first visit
        if (!localStorage.getItem('lab-smaba-preferences')) {
            const userRole = document.body.dataset.userRole;
            // Only auto-detect dark mode if NOT admin/guru
            if (userRole !== 'admin' && userRole !== 'guru') {
                 preferences.theme = darkModeQuery.matches ? 'dark' : 'light';
            } else {
                 preferences.theme = 'light';
            }
            preferences.save();
            preferences.apply();
        }
        
        // Listen for changes
        darkModeQuery.addEventListener('change', (e) => {
            if (e.matches) {
                console.log('System switched to dark mode');
            }
        });
    }
});
