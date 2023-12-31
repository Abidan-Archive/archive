<script>
    import { inertia } from '@inertiajs/svelte';
    import route from '@/Utils/route';

    // Fallback redirect target
    let redirect = route('home');

    // Handles
    // /events/123/#e1234
    // /events/123-some-title/#e1234
    if (!!window.location.hash && window.location.hash.match(/\#e([0-9]+)/)) {
        redirect = route('handleRedirect', {
            type: 'report',
            context: encodeURI(
                stripSlug(window.location.pathname) + window.location.hash
            ),
        });
    }

    // Redirect them
    window.location.href = redirect;

    function stripSlug(pathname) {
        return pathname.slice(0, pathname.indexOf('-')) + '/';
    }
</script>

<p class="text-center">
    You are currently being automatically redirected. If this takes too long <a
        use:inertia
        href={redirect}>Click Here</a
    >.
</p>
