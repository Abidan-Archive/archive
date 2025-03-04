<script>
    /*global turnstile, turnstile_sitekey */
    import { onMount } from 'svelte';
    import ErrorMessage from '@/Components/forms/ErrorMessage.svelte';

    let {
        form,
        field = 'turnstile',
        siteKey = turnstile_sitekey,
        ...rest
    } = $props();

    let widgetId = $state();
    let isLoaded = $state(false);

    // Function to render Turnstile widget
    function renderTurnstile() {
        if (!window.turnstile) {
            console.error('Turnstile script not loaded.');
            return;
        }
        widgetId = turnstile.render('#turnstile-widget', {
            sitekey: siteKey,
            callback: (tokenResponse) => ($form[field] = tokenResponse),
        });
        isLoaded = true;
    }

    function waitForTurnstile(callback) {
        if (window.turnstile) {
            turnstile.ready(callback);
        } else {
            // Poll for Turnstile readiness as a fallback
            const interval = setInterval(() => {
                if (window.turnstile) {
                    clearInterval(interval);
                    turnstile.ready(callback);
                }
            }, 100);
        }
    }

    onMount(() => {
        waitForTurnstile(renderTurnstile);

        return () => {
            // Cleanup: Remove widget on unmount
            if (widgetId) {
                turnstile.remove(widgetId);
            }
        };
    });
</script>

<div>
    <div id="turnstile-widget" data-size="flexible" data-theme="dark" {...rest}>
    </div>
    {#if !isLoaded}
        <p>Loading CAPTCHA...</p>
    {/if}
    <ErrorMessage message={$form.errors[field]} />
</div>
