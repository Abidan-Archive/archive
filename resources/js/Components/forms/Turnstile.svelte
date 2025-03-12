<script>
    import { onMount } from 'svelte';
    import { router } from '@inertiajs/svelte';
    import ErrorMessage from '@/Components/forms/ErrorMessage.svelte';

    let {
        form,
        field = 'turnstile',
        siteKey = window.turnstile_sitekey,
        ...rest
    } = $props();

    let widgetId = $state();

    function renderTurnstile() {
        if (!window.turnstile) {
            console.error('Turnstile script not loaded.');
            return;
        }
        widgetId = window.turnstile.render('#turnstile-widget', {
            sitekey: siteKey,
            callback: (tokenResponse) => ($form[field] = tokenResponse),
        });
    }

    onMount(() => {
        if (window.turnstileLoaded) {
            renderTurnstile();
        } else {
            document.addEventListener('turnstileLoaded', renderTurnstile);
        }
        // Listen if we got an error, and reset the widget if we did
        let removeErrorListener = router.on('error', () =>
            window.turnstile.reset(widgetId)
        );

        return () => {
            // Cleanup: Remove widget on unmount and listener
            if (widgetId) {
                window.turnstile.remove(widgetId);
            }
            document.removeEventListener('turnstileLoaded', renderTurnstile);
            removeErrorListener();
        };
    });
</script>

<div>
    <div id="turnstile-widget" data-size="flexible" data-theme="dark" {...rest}>
    </div>
    <ErrorMessage message={$form.errors[field]} />
</div>
