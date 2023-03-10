module.exports = {
    env: {
        browser: true,
        es6: true,
        node: true,
    },
    plugins: ['prettier', 'svelte3'],
    extends: ['plugin:prettier/recommended'],
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
};
