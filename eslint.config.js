import { FlatCompat } from '@eslint/eslintrc';
import js from '@eslint/js';
import prettier from 'eslint-plugin-prettier/recommended';
import globals from 'globals';
// import tailwindcss from 'eslint-plugin-tailwindcss';
// import svelte from 'eslint-plugin-svelte';
import path from 'path';
import { fileURLToPath } from 'url';

// mimic CommonJS variables -- not needed if using CommonJS
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const compat = new FlatCompat({
    baseDirectory: __dirname,
});

/** @type {import('eslint').Linter.FlatConfig[]} */
export default [
    js.configs.recommended,
    prettier,
    ...compat.extends(
        'plugin:svelte/recommended',
        'plugin:tailwindcss/recommended'
    ),
    // ...compat.plugins('svelte', 'tailwindcss'),
    {
        files: ['resources/**/*.{js,svelte}'],
        languageOptions: {
            globals: {
                ...globals.node,
                ...globals.browser,
            },
        },
        settings: {
            tailwindcss: {
                callees: ['class', 'clsx', 'cn'],
                config: 'tailwind.config.js',
                cssFiles: ['!**/*.js'],
            },
        },
        rules: {
            'tailwindcss/classnames-order': 'warn',
            'tailwindcss/no-custom-classname': 'warn',
            'tailwindcss/no-contradicting-classname': 'error',
            'no-unused-vars': [
                'error',
                {
                    argsIgnorePattern: '^_',
                },
            ],
        },
    },
];
