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
              'smaba-dark-green': '#15803d',     // Green 700 (Primary Dark)
              'smaba-light-green': '#22c55e',    // Green 500 (Primary Light)
              'smaba-mint': '#f0fdf4',           // Green 50 (Very Light Background)
              'smaba-text': '#1e293b',           // Slate 800 (Modern Text)
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};