import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
// import lineClamp from '@tailwindcss/line-clamp';
import colors from 'tailwindcss/colors'
/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'selector',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.jsx',
    ],

    theme: {
        colors: {
            ...colors,

            // 🎨 Nueva paleta primary basada en tu color #f3e8d6
            primary: {
                50:  '#fdf9f4',
                100: '#fbf2e9',
                200: '#f7e4d3',
                300: '#f3d6bd',
                400: '#efc8a7',
                500: '#eab992',
                600: '#24170a', // color principal
                700: '#d0c0aa',
                800: '#a89a85',
                900: '#24170a',
                DEFAULT: '#24170a',
            },
        },

        container: {
            center: true,
            padding: {
                DEFAULT: "1.5rem",
                sm: "2rem",
                lg: "2rem",
                xl: "2rem",
                '2xl': '6rem',
            },
        },
        extend: {
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
            //accordion search filters
            transitionProperty: {
                'max-height': 'max-height',
            },
            //animation toast
            animation: {
                enter: 'enter 200ms ease-out',
                'slide-in': 'slide-in 1.2s cubic-bezier(.41,.73,.51,1.02)',
                leave: 'leave 150ms ease-in forwards',
            },
            keyframes: {
                enter: {
                    '0%': { transform: 'scale(0.9)', opacity: 0 },
                    '100%': { transform: 'scale(1)', opacity: 1 },
                },
                leave: {
                    '0%': { transform: 'scale(1)', opacity: 1 },
                    '100%': { transform: 'scale(0.9)', opacity: 0 },
                },
                'slide-in': {
                    '0%': { transform: 'translateY(-100%)' },
                    '100%': { transform: 'translateY(0)' },
                },
            }
        },
    },

    plugins: [forms],
};
