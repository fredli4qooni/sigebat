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
        extend: {
            colors: {
                aspal: {
                    DEFAULT: '#1E293B', // Slate 800
                    gelap: '#0F172A',   // Slate 900
                    terang: '#334155',  // Slate 700
                },
                beton: '#F8FAFC',       // Slate 50
                abu: '#64748B',         // Slate 500
                putih: '#FFFFFF',
                cokelat: {
                    DEFAULT: '#78350F', // Amber 900 / Warm Heritage Wood
                    gelap: '#451A03',
                    terang: '#92400E',
                },
                hijau: {
                    DEFAULT: '#047857', // Emerald 700
                    gelap: '#065F46',   // Emerald 800
                    terang: '#10B981',  // Emerald 500
                },
                kuning: {
                    DEFAULT: '#D97706', // Amber 600
                    gelap: '#B45309',   // Amber 700
                    terang: '#FBBF24',  // Amber 400
                },
                biru: {
                    DEFAULT: '#0284C7', // Sky 600
                    gelap: '#0369A1',   // Sky 700
                    terang: '#38BDF8',  // Sky 400
                },
                merah: {
                    DEFAULT: '#E11D48', // Rose 600
                    gelap: '#BE123C',   // Rose 700
                },
                heritage: {
                    50: '#F0FDF4',
                    100: '#DCFCE7',
                    200: '#BBF7D0',
                    300: '#86EFAC',
                    400: '#4ADE80',
                    500: '#22C55E',
                    600: '#16A34A',
                    700: '#15803D',
                    800: '#166534',
                    900: '#14532D',
                    950: '#052E16',
                },
            },
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'Inter', 'system-ui', '-apple-system', 'sans-serif'],
                papan: ['"Plus Jakarta Sans"', 'Inter', 'system-ui', '-apple-system', 'sans-serif'],
            },
            borderRadius: {
                tag: '6px',
                kontrol: '10px',
                kartu: '16px',
                papan: '20px',
            },
            boxShadow: {
                xs: '0 1px 2px 0 rgba(0, 0, 0, 0.04)',
                card: '0 4px 20px -2px rgba(15, 23, 42, 0.06), 0 2px 6px -1px rgba(15, 23, 42, 0.04)',
                'card-hover': '0 12px 28px -4px rgba(15, 23, 42, 0.12), 0 4px 10px -2px rgba(15, 23, 42, 0.06)',
            },
            maxWidth: {
                prose: '68ch',
            },
        },
    },

    plugins: [forms],
};
