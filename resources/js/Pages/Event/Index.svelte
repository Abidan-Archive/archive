<script>
    import { inertia } from '@inertiajs/svelte';
    import route from '@/route';
    export let events;
</script>

<svelte:head>
    <title>Events | Abidan Archive</title>
</svelte:head>

<section class="container mx-auto pt-5">
    <h2 class="my-5 text-2xl">Events</h2>
    <hr class="my-5" />
    <div>
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
    </div>
</section>
