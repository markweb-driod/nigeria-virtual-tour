/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                forest: {
                    DEFAULT: '#0B3D2E',
                    50: '#E8F4F0',
                    100: '#C5E3D8',
                    200: '#8FCAB5',
                    300: '#5AB192',
                    400: '#2E8A6B',
                    500: '#0B3D2E',
                    600: '#093326',
                    700: '#07291E',
                    800: '#051E16',
                    900: '#03140E',
                },
                gold: {
                    DEFAULT: '#D4A017',
                    50: '#FDF8E7',
                    100: '#F9EDBC',
                    200: '#F1D870',
                    300: '#E8C32A',
                    400: '#D4A017',
                    500: '#A87C12',
                    600: '#7C5B0D',
                    700: '#503A08',
                    800: '#241A04',
                    900: '#120D02',
                },
                dark: '#101914',
                surface: '#FFFFFF',
                bglight: '#F8FAF8',
            },
            fontFamily: {
                heading: ['Manrope', 'Sora', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                body: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.5rem',
                '4xl': '2rem',
            },
            backdropBlur: {
                xs: '2px',
            },
            animation: {
                'fade-in': 'fadeIn 0.5s ease-in-out',
                'slide-up': 'slideUp 0.4s ease-out',
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
