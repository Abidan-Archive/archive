import { createInertiaApp } from '@inertiajs/svelte';
import createServer from '@inertiajs/svelte/server';
import { render } from 'svelte/server';

import '../scss/app.scss';
import Layout from './Layouts/Layout.svelte';

createServer((page) =>
    createInertiaApp({
        page,
        resolve: (name) => {
            const pages = import.meta.glob('./Pages/**/*.svelte', {
                eager: true,
            });
            let page = pages[`./Pages/${name}.svelte`];
            return { default: page.default, layout: page.layout || Layout };
        },
        setup({ App, props }) {
            return render(App, { props });
        },
        progress: {
            color: '#326695',
        },
    })
);
