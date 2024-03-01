import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';
import { resolve } from 'path';
import { svelte } from '@sveltejs/vite-plugin-svelte';

const projectRoot = resolve(__dirname);

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/scss/app.scss', 'resources/js/app.js'],
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        svelte({ compilerOptions: {hydratable: true }}),
    ],
    resolve: {
        alias: {
            ziggy: resolve(projectRoot, 'vendor/tightenco/ziggy/dist/index.es'),
        },
        dedupe: ['svelte', 'svelte/transition', 'svelte/internal'],
        extensions: ['.js', '.svelte', '.json'],
    },
});
