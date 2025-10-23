import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            // Penambahan Palet Warna Kustom
            colors: {
              'smaba-dark-blue': '#0B2447',      // Biru Tua Profesional
              'smaba-light-blue': '#576CBC',     // Biru Terang Ramah
              'smaba-mint': '#A5D7E8',           // Aksen/Highlight
              'smaba-text': '#19376D',           // Warna Teks Abu Gelap
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};