import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            white: '#FFFFFF',
            black: '#22282E',
            putih: '#FFFFFF',
            aspal: '#22282E',
            abu: '#59626B',
            beton: '#E4E7E9',
            cokelat: {
                DEFAULT: '#6B3A22',
                gelap: '#4F2A17',
            },
            biru: {
                DEFAULT: '#0B5EA8',
                gelap: '#084A86',
            },
            hijau: {
                DEFAULT: '#0F7A3C',
                gelap: '#0B5F2E',
            },
            kuning: {
                DEFAULT: '#FFC20E',
                gelap: '#E5AA00',
            },
            merah: {
                DEFAULT: '#C42B1C',
                gelap: '#9E2116',
            },
        },
        fontFamily: {
            sans: ['Barlow', 'system-ui', 'Segoe UI', 'Roboto', 'Arial', 'sans-serif'],
            papan: ['Barlow Semi Condensed', 'Barlow', 'system-ui', 'sans-serif'],
        },
        borderRadius: {
            none: '0px',
            sm: '4px',
            tag: '6px',
            kontrol: '8px',
            kartu: '12px',
            papan: '16px',
            full: '9999px',
        },
        boxShadow: {
            none: 'none',
        },
        extend: {
            maxWidth: {
                prose: '68ch',
            },
        },
    },

    plugins: [forms],
};
