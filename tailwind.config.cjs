const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.{js,svelte}',
    ],
    theme: {
        borderWidth: {
            ...defaultTheme.borderWidth,
            3: '3px',
            5: '5px',
        },
        extend: {
            backgroundImage: {
                'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
            },
            colors: {
                typo: {
                    500: '#e8e6e3',
                    600: '#c3beb6',
                },
                base: {
                    500: '#1b2735',
                    700: '#25282a',
                    900: '#181a1b',
                },
                primary: {
                    50: '#ccd9e4',
                    100: '#99b3ca',
                    200: '#668daf',
                    300: '#4c7aa2',
                    400: '#326695',
                    500: '#00417b',
                    600: '#003a6e',
                    700: '#003462',
                    800: '#002d56',
                    900: '#00203d',
                },
            },
            fontFamily: {
                sans: ['Roboto', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
