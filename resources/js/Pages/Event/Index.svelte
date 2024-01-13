<script>
    import Page from '@/Components/Page.svelte';
    import Paginator from '@/Components/Paginator.svelte';
    import Event from '@/Components/Event.svelte';

    export let events;
</script>

<svelte:head>
    <title>Events | Abidan Archive</title>
</svelte:head>

<Page header="Events">
    <div class="mx-auto flex w-3/4 flex-col gap-3">
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
    </div>
</Page>
