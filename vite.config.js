import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { svelte } from '@sveltejs/vite-plugin-svelte';
import { resolve } from 'path';

// let host = 'laravel-breeze-svelte.test';
const projectRoot = resolve(__dirname);

export default defineConfig({
    plugins: [
        laravel.default({
            input: [ 'resources/scss/app.scss', 'resources/js/app.js', ],
            refresh: true
        }),
        svelte({})
    ],
    resolve: {
        alias: {
            '@': resolve(projectRoot, 'resources/js'),
            '@components': resolve(projectRoot, 'resources/js/Components'),
            '@layouts': resolve(projectRoot, 'resources/js/Layouts'),
        }
    },
    resolveFn: name => {
        const pages = import.meta.glob('./Pages/**/*.svelte', { eager: true });
        return pages[`./Pages/${name}.svelte`];
    },
});
