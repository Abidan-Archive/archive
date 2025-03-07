<script>
    import { onMount } from 'svelte';
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

        return () => {
            // Cleanup: Remove widget on unmount
            if (widgetId) {
                window.turnstile.remove(widgetId);
            }
        };
    });
</script>

<div>
    <div id="turnstile-widget" data-size="flexible" data-theme="dark" {...rest}>
    </div>
    <ErrorMessage message={$form.errors[field]} />
</div>
