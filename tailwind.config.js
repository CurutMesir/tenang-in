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
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                hand: ['Caveat', 'cursive'],
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-14px)' },
                },
                'fade-up': {
                    '0%': { opacity: '0', transform: 'translateY(16px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
            animation: {
                float: 'float 7s ease-in-out infinite',
                'float-slow': 'float 11s ease-in-out infinite',
                'fade-up': 'fade-up .5s ease-out both',
            },
        },
    },

    safelist: [
        'bg-indigo-200', 'text-indigo-700', 'bg-indigo-200/60',
        'bg-blue-200', 'text-blue-700', 'bg-blue-200/60',
        'bg-amber-200', 'text-amber-700', 'bg-amber-200/60',
        'bg-green-200', 'text-green-700', 'bg-green-200/60',
        'bg-emerald-300', 'text-emerald-700', 'bg-emerald-300/60',
        { pattern: /peer-checked:bg-(indigo|blue|amber|green|emerald)-(200|300)/ },
    ],

    plugins: [forms],
};
