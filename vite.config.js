import laravel from 'laravel-vite-plugin';
import preprocess from 'svelte-preprocess';
import { defineConfig } from 'vite';
import { resolve } from 'path';
import { svelte } from '@sveltejs/vite-plugin-svelte';

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
            '@components': resolve(projectRoot, 'resources/js/Components'),
            '@layouts': resolve(projectRoot, 'resources/js/Layouts'),
            ziggy: resolve(projectRoot, 'vendor/tightenco/ziggy/dist/index.es'),
        },
        extensions: ['.js', '.svelte', '.json'],
    },
    resolveFn: (name) => {
        const pages = import.meta.glob('./Pages/**/*.svelte', { eager: true });
        return pages[`./Pages/${name}.svelte`];
    },
});
