import './bootstrap';
import '../scss/app.scss';
import Layout from './Layouts/Layout.svelte';

// import './darkmode'; // Custom vanilla js darkmode switcher, let's decide if and when we want to use this..

import { createInertiaApp } from '@inertiajs/svelte';

async function resolve(name) {
    const pages = import.meta.glob('./Pages/**/*.svelte', { eager: true });
    let page = pages[`./Pages/${name}.svelte`];
    return { default: page.default, layout: page.layout || Layout };
}

createInertiaApp({
    resolve,
    setup({ el, App, props }) {
        new App({ target: el, props });
    },
    progress: {
        color: '#326695',
    },
});
