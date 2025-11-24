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
            },
            colors: {
                primary: {
                    DEFAULT: '#4461F2',
                    hover: '#3651E2',
                    light: '#E8EEFF',
                    lighter: '#F5F7FF',
                },
                secondary: {
                    DEFAULT: '#1e3a8a',
                },
                accent: {
                    DEFAULT: '#60a5fa',
                },
            },
            boxShadow: {
                'xs': '0 1px 2px rgba(0, 0, 0, 0.05)',
                'sm': '0 2px 4px rgba(0, 0, 0, 0.05)',
                'md': '0 4px 8px rgba(0, 0, 0, 0.08)',
                'lg': '0 8px 16px rgba(0, 0, 0, 0.1)',
                'xl': '0 12px 24px rgba(0, 0, 0, 0.12)',
                '2xl': '0 16px 32px rgba(0, 0, 0, 0.15)',
            },
            spacing: {
                'header': '64px',
                'sidebar': '280px',
            },
            maxWidth: {
                'container': '1280px',
            },
            zIndex: {
                'dropdown': '1000',
                'sticky': '1020',
                'fixed': '1030',
                'modal-backdrop': '1040',
                'modal': '1050',
                'popover': '1060',
                'tooltip': '1070',
            },
            transitionDuration: {
                'fast': '150ms',
                'base': '200ms',
                'slow': '300ms',
            },
        },
    },

    plugins: [forms],
};
