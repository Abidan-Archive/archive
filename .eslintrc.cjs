module.exports = {
    env: {
        browser: true,
        es6: true,
        node: true,
    },
    plugins: ['prettier', 'svelte3', 'tailwindcss'],
    extends: ['plugin:prettier/recommended', 'plugin:tailwindcss/recommended'],
    parserOptions: {
        ecmaVersion: 'latest',
        sourceType: 'module',
    },
    overrides: [
        {
            files: ['**/*.svelte'],
            processor: 'svelte3/svelte3',
        },
    ],
    rules: {
        'tailwindcss/classnames-order': 'warn',
        'tailwindcss/no-custom-classname': 'warn',
        'tailwindcss/no-contradicting-classname': 'error',
    },
    settings: {
        tailwindcss: {
            callees: ['class', 'clsx'],
            config: 'tailwind.config.js',
            cssFiles: ['!**/*.js'],
        },
    },
};
