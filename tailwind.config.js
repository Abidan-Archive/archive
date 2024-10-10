import { skeleton } from '@skeletonlabs/tw-plugin';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import { join } from 'path';
import defaultTheme from 'tailwindcss/defaultTheme';

import { abidanTheme } from './resources/js/abidan-theme.js';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.{html,ts,js,svelte}',
        join(
            require.resolve('@skeletonlabs/skeleton'),
            '../**/*.{html,js,svelte,ts}'
        ),
    ],
    theme: {
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

                // Ideas for possible new colors
                // typo: '#D7E7F0',
                // background: '#000810',
                // primary: '#7531D7',
                // secondary: '#054076',
                // accent: '#16BAF6',
            },
            fontFamily: {
                sans: ['Roboto', ...defaultTheme.fontFamily.sans],
            },
            borderWidth: {
                3: '3px',
                5: '5px',
            },
            addComponents: {
                card: {
                    width: '100%',
                    borderRadius: defaultTheme.borderRadius.lg,
                    border: defaultTheme.borderWidth[1],
                    borderColor: abidanTheme.colors.surface[400],
                    backgroundColor: '#25282a', // theme.colors.base[700], defined above
                    padding: defaultTheme.spacing[4],
                    boxShadow: defaultTheme.boxShadow.md,
                },
            },
        },
    },

    plugins: [
        typography,
        forms,
        skeleton({ themes: { custom: [abidanTheme] } }),
    ],
};
