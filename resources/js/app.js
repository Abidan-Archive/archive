import './bootstrap';
import '../scss/app.scss';

// import './darkmode'; // Custom vanilla js darkmode switcher, let's decide if and when we want to use this..

import { createInertiaApp } from '@inertiajs/svelte';

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.svelte', { eager: true })
        return pages[`./Pages/${name}.svelte`]
    },
    setup({ el, App, props }) {
        new App({ target: el, props });
    },
});
