import { createInertiaApp } from '@inertiajs/svelte';

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
        new App({ target: el, props, hydrate: true });
    },
    progress: {
        color: '#326695',
    },
});
