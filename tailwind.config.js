import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            keyframes: {
                popIn: {
                    '0%': { opacity: '0', transform: 'scale(0.7)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                popOut: {
                    '0%': { opacity: '1', transform: 'scale(1)' },
                    '100%': { opacity: '0', transform: 'scale(0.7)' },
                },
            },
            animation: {
                'pop-in': 'popIn 0.2s ease-out',
                'pop-out': 'popOut 0.5s ease-in forwards',
            },
        },
    },

    plugins: [forms],
};
