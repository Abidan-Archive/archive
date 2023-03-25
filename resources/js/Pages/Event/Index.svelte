<script>
    import { inertia } from '@inertiajs/svelte';
    import route from '@/route';
    export let events;
</script>

<svelte:head>
    <title>Events | Abidan Archive</title>
</svelte:head>

<div class="container mx-auto pt-5">
    <section>
        <h2 class="my-5 text-2xl">Events</h2>
    </section>
    <hr class="my-5" />
    <section>
        {#each events as event}
            <a
                class="hover:underline"
                use:inertia
                href={route('event.show', event)}>
                <article>
                    {event.name}
                </article>
            </a>
        {/each}
    </section>
</div>
