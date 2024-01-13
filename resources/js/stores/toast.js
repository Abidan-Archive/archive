import { writable } from 'svelte/store';

export const toasts = writable([]);

export const addToast = (toast) => {
    const id = Date.now(); // Good enough uuid
    const defaults = {
        id,
        type: 'info',
        dismissible: true,
        timeout: 3000,
    };

    // Let's get those defaults, no I don't care about side-effects
    toast = { ...defaults, ...toast };
    console.log(toast);
    toasts.update((all) => [toast, ...all]);

    // If toast has a timeout, dismiss it after the timeout
    if (toast.timeout) {
        setTimeout(() => dismissToast(id), toast.timeout);
    }
};

export const dismissToast = (id) => {
    console.log(id);
    toasts.update((all) => all.filter((t) => t.id !== id));
};
