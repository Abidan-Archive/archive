<script>
    import { inertia } from '@inertiajs/svelte';
    import route from '@/lib/route';
    export let stubs;
</script>

<h2>All Stubs</h2>
<ul>
    {#each stubs as stub}
        <li>
            <a
                use:inertia
                href={route(
                    'event.source.stub.show',
                    stub.event,
                    stub.source,
                    stub
                )}>{stub.prompt} &dash; {stub.event.name}</a>
        </li>
    {/each}
</ul>
