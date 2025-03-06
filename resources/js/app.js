import { createInertiaApp } from '@inertiajs/svelte';
import { hydrate, mount } from 'svelte';

import '../scss/app.scss';
import Layout from './Layouts/Layout.svelte';

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.svelte', {
            eager: true,
        });
        let page = pages[`./Pages/${name}.svelte`];
        return { default: page.default, layout: page.layout || Layout };
    },
    setup({ el, App, props }) {
        if (el.dataset.serverRendered === 'true') {
            hydrate(App, { target: el, props });
        } else {
            mount(App, { target: el, props });
        }
    },
    progress: {
        color: '#326695',
    },
});
