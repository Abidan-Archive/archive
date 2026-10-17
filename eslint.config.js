import js from '@eslint/js';
import { fixupConfigRules } from '@eslint/compat';
import prettier from 'eslint-plugin-prettier/recommended';
import svelte from 'eslint-plugin-svelte';
import tailwindcss from 'eslint-plugin-tailwindcss';
import globals from 'globals';

export default [
    { ignores: ['**/*.bak', '**/*.bk', 'resources/js/ziggy.js'] },
    js.configs.recommended,
    ...svelte.configs['flat/recommended'],
    ...fixupConfigRules(tailwindcss.configs['flat/recommended']),
    prettier,
    {
        files: ['resources/**/*.{js,svelte}'],
        languageOptions: {
            globals: { ...globals.node, ...globals.browser },
        },
        settings: {
            tailwindcss: {
                callees: ['class', 'clsx', 'cn'],
                cssFiles: ['!**/*.{js,ts}'],
            },
        },
        rules: {
            // Existing unkeyed lists remain valid; review keys during component work.
            'svelte/require-each-key': 'warn',
            'tailwindcss/classnames-order': 'warn',
            'tailwindcss/no-custom-classname': 'warn',
            'tailwindcss/no-contradicting-classname': 'error',
            'no-unused-vars': ['error', { argsIgnorePattern: '^_' }],
        },
    },
    {
        files: ['resources/**/*.svelte'],
        rules: {
            // $bindable defaults are observable by parents, even if only assigned locally.
            'no-useless-assignment': 'off',
        },
    },
];
