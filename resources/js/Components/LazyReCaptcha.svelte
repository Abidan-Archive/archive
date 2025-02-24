<script>
    /*global grecaptcha, grecaptcha_sitekey */
    import { onMount } from 'svelte';
    import { state } from 'svelte/runes';

    let reCaptchaLoaded = state(false);
    let reCaptchaInstance = state(null);

    const recaptchaUrl = 'https://www.google.com/recaptcha/api.js?render=';

    function loadReCaptcha() {
        if (!$reCaptchaLoaded) {
            const script = document.createElement('script');
            script.src = recaptchaUrl + grecaptcha_sitekey;
            script.async = true;
            script.defer = true;
            script.onload = () => {
                reCaptchaLoaded.set(true);
                reCaptchaInstance.set(grecaptcha);
            };
            document.head.appendChild(script);
        }
    }

    function executeReCaptcha(action, callback) {
        if ($reCaptchaLoaded && $reCaptchaInstance) {
            return $reCaptchaInstance
                .execute(window.grecaptcha_sitekey, {
                    action,
                })
                .then(callback);
        }
        return Promise.reject('reCATCHA not loaded');
    }

    onMount(() => {
        loadReCaptcha();
        if ($reCaptchaLoaded) {
            const script = document.querySelector(
                `script[src^="${recaptchaUrl}"]`
            );
            if (script) {
                document.head.removeChild(script);
            }
        }
    });
</script>

<slot {executeReCaptcha} />
