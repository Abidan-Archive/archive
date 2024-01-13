<script>
    import Paginator from '@/Components/Paginator.svelte';
    import Event from '@/Components/Event.svelte';

    export let events;
</script>

<svelte:head>
    <title>Events | Abidan Archive</title>
</svelte:head>

<div class="container mx-auto pt-5">
    <section>
        <h2 class="my-5 text-3xl">Events</h2>
    </section>
    <hr class="my-5" />
    <section class="mx-auto flex w-2/3 flex-col gap-3">
        {#if !events.data.length}
            <div class="text-center">
                <p>No Events found.</p>
            </div>
        {:else}
            <Paginator {...events} />
            {#each events.data as event}
                <Event {event} />
            {/each}
            <Paginator {...events} />
        {/if}
    </section>
</div>
