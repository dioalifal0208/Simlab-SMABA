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
            // Penambahan Palet Warna Kustom
            colors: {
              'smaba-dark-blue': '#1d4ed8',      // Blue 700 (Clean SaaS Primary)
              'smaba-light-blue': '#3b82f6',     // Blue 500 (Clean SaaS Secondary)
              'smaba-mint': '#eff6ff',           // Blue 50 (Very Light Background)
              'smaba-text': '#1e293b',           // Slate 800 (Modern Text)
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};