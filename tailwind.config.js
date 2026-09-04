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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                bps: {
                    navy: '#002b6a',
                    blue: '#003c80',
                    corporate: '#005b9f',
                    sky: '#0093dd',
                    orange: '#f7941d',
                    green: '#00a651',
                    dark: '#02183b',
                },
            },
        },
    },

    plugins: [forms],
};
