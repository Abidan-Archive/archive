import { svelte } from '@sveltejs/vite-plugin-svelte';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'path';
import preprocess from 'svelte-preprocess';
import { defineConfig } from 'vite';

const projectRoot = resolve(__dirname);

export default defineConfig({
    plugins: [
        laravel.default({
            input: ['resources/scss/app.scss', 'resources/js/app.js'],
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        svelte({
            preprocess: preprocess({ postcss: true }),
            compilerOptions: { hydratable: true },
        }),
    ],
    resolve: {
        alias: {
            '@': resolve(projectRoot, 'resources/js'),
            ziggy: resolve(projectRoot, 'vendor/tightenco/ziggy/dist/index.es'),
        },
        dedupe: ['svelte', 'svelte/transition', 'svelte/internal'],
        extensions: ['.js', '.svelte', '.json'],
    },
});
