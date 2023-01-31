const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    darkMode: 'class',
    theme: {
        borderWidth: {
            ...defaultTheme.borderWidth,
            '3': '3px',
            '5': '5px',
        },
        extend: {
            colors: {
                'midnight': '#1B2735',
                'abidan': {
                    50:  '#ccd9e4',
                    100: '#99b3ca',
                    200: '#668daf',
                    300: '#4c7aa2',
                    400: '#326695',
                    500: '#00417b',
                    600: '#003a6e',
                    700: '#003462',
                    800: '#002d56',
                    900: '#00203d'

                }
            },
            fontFamily: {
                sans: ['Roboto', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
