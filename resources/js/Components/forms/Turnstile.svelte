<script>
    import { onMount } from 'svelte';
    import { router } from '@inertiajs/svelte';
    import ErrorMessage from '@/Components/forms/ErrorMessage.svelte';

    let {
        form,
        field = 'turnstile',
        siteKey = typeof window === 'undefined'
            ? null
            : window.turnstile_sitekey,
        ...rest
    } = $props();

    let widgetId = $state();
    let container;

    function renderTurnstile() {
        if (!window.turnstile || widgetId !== undefined) return;

        widgetId = window.turnstile.render(container, {
            sitekey: siteKey,
            size: 'flexible',
            theme: 'dark',
            callback: (tokenResponse) => ($form[field] = tokenResponse),
            'expired-callback': () => ($form[field] = null),
            'error-callback': () => ($form[field] = null),
        });
    }

    onMount(() => {
        $form[field] = null;
        if (window.turnstileLoaded) {
            renderTurnstile();
        } else {
            document.addEventListener('turnstileLoaded', renderTurnstile);
        }
        // Listen if we got an error, and reset the widget if we did
        let removeErrorListener = router.on('error', () => {
            $form[field] = null;
            if (widgetId !== undefined) window.turnstile.reset(widgetId);
        });

        return () => {
            // Cleanup: Remove widget on unmount and listener
            if (widgetId !== undefined) {
                window.turnstile.remove(widgetId);
            }
            document.removeEventListener('turnstileLoaded', renderTurnstile);
            removeErrorListener();
        };
    });
</script>

<div>
    <div bind:this={container} {...rest}></div>
    <ErrorMessage message={$form.errors[field]} />
</div>
