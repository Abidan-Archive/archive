import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { svelte } from '@sveltejs/vite-plugin-svelte';

// let host = 'laravel-breeze-svelte.test';

export default defineConfig({
    plugins: [
        laravel.default({
            input: [ 'resources/scss/app.scss', 'resources/js/app.js', ],
            refresh: true
        }),
        svelte({})
    ],
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.svelte', { eager: true });
        return pages[`./Pages/${name}.svelte`];
    },
});
