import { svelte } from '@sveltejs/vite-plugin-svelte';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'path';
import { fileURLToPath } from 'url';
import { defineConfig } from 'vite';

const projectRoot = fileURLToPath(new URL('.', import.meta.url));

export default defineConfig({
    // Skeleton 2's legacy pseudo-element selectors need the tolerant minifier.
    build: { cssMinify: 'esbuild' },
    plugins: [
        laravel({
            input: ['resources/scss/app.scss', 'resources/js/app.js'],
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        svelte(),
    ],
    resolve: {
        alias: {
            ziggy: resolve(projectRoot, 'vendor/tightenco/ziggy/dist/index.esm.js'),
            '@': resolve('./resources/js/'),
        },
        dedupe: ['svelte', 'svelte/transition', 'svelte/internal'],
        extensions: ['.js', '.svelte', '.json'],
    },
    server: { host: '127.0.0.1' },
});
