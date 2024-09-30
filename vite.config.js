import { svelte } from '@sveltejs/vite-plugin-svelte';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'path';
import { defineConfig } from 'vite';
import { purgeCss } from 'vite-plugin-tailwind-purgecss';

const projectRoot = resolve(__dirname);

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/scss/app.scss', 'resources/js/app.js'],
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        svelte({ compilerOptions: { hydratable: true } }),
        purgeCss(),
    ],
    resolve: {
        alias: {
            ziggy: resolve(projectRoot, 'vendor/tightenco/ziggy/dist/index.es'),
            '@': resolve('./resources/js/'),
        },
        dedupe: ['svelte', 'svelte/transition', 'svelte/internal'],
        extensions: ['.js', '.svelte', '.json'],
    },
});
