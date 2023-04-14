<script>
    import Paginator from '@components/Paginator.svelte';
    import Event from '@components/Event.svelte';
    import PaginatorSimple from '@components/PaginatorSimple.svelte';
    export let events;
    export let paginate;
    console.log(events);
</script>

<svelte:head>
    <title>Events | Abidan Archive</title>
</svelte:head>

<div class="container mx-auto pt-5">
    <section>
        <h2 class="my-5 text-3xl">Events</h2>
    </section>
    <hr class="my-5" />
    <section class="flex flex-col gap-3">
        {#if !events.length}
            <div class="text-center">
                <p>No Events found.</p>
            </div>
        {:else}
            <Paginator {...paginate} />
            {#each events as event}
                <Event {event} />
            {/each}
            <Paginator {...paginate} />
        {/if}
    </section>
</div>
