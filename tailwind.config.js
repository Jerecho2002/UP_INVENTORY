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

            // Extend animations to include fade-in
            animation: {
                'fade-in': 'fadeIn 0.5s ease-in-out',
            },

            // Define keyframes for the fade-in animation
            keyframes: {
                fadeIn: {
                    '0%': {
                        opacity: '0',
                        transform: 'translateY(20px)', // Slightly move up
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'translateY(0)', // End position
                    },
                },
            },
        },
    },

    plugins: [forms],
};
