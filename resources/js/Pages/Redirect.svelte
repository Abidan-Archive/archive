<script>
    import { inertia } from '@inertiajs/svelte';
    import route from '@/route';

    let redirect = route('home');
    const hash = window.location.hash;

    // abidanarchive.com/events/123-some-title/#e1234
    // abidanarchive.com/events/123/#e1234
    if (!!hash && hash.match(/\#e([0-9]+)/)) {
        redirect = route('report.show', hash.substring(2));
    }
    window.location.href = redirect;
</script>

<p class="text-center">
    You are currently being redirected. If this takes too long <a
        use:inertia
        href={redirect}>Click Here</a
    >.
</p>
