import './bootstrap';
import '../scss/app.scss';
import Layout from './Layouts/Layout.svelte';

// import './darkmode'; // Custom vanilla js darkmode switcher, let's decide if and when we want to use this..

import { createInertiaApp } from '@inertiajs/svelte';
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";

async function resolve(name) {
    let component;
    const pagesWithoutLayout = [

    ];
    const page = resolvePageComponent(`./Pages/${name}.svelte`, import.meta.glob('./Pages/**/*.svelte'));
    await page.then(module => {
        component = pagesWithoutLayout.includes(name)
            ? module
            : Object.assign({layout: Layout}, module);
    });

    return component;
}

createInertiaApp({
    resolve,
    setup({ el, App, props }) {
        new App({ target: el, props });
    },
});
