import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class', // Enable class-based dark mode

    theme: {
        extend: {
            // Penambahan Palet Warna Kustom (Green Theme - SMABA Logo)
            colors: {
              'smaba-dark-green': '#15803d',     // Green 700
              'smaba-light-green': '#22c55e',    // Green 500
              'smaba-dark-blue': '#1d4ed8',      // Blue 700
              'smaba-light-blue': '#1e40af',     // Blue 800
              'smaba-gold': '#ffb700',           // Custom Gold Accent
              'smaba-mint': '#f0fdf4',           // Green 50
              'smaba-text': '#1e293b',           // Slate 800
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};