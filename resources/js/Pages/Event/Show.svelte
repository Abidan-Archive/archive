<script>
    import Page from '@/Components/Page.svelte';
    import Paginator from '@/Components/Paginator.svelte';
    import Report from '@/Components/Report.svelte';

    export let event;
    export let reports;
</script>

<svelte:head>
    <title>{event.name} | Abidan Archive</title>
</svelte:head>

<Page header={event.name}>
    <div class="flex flex-col gap-5">
        {#if !reports.data.length}
            No reports within this Event.<br />
            How very very mysterious, yes.
        {:else}
            <Paginator {...reports} />
            {#each reports.data as report}
                <Report {report} withEvent={false} />
            {/each}
            <Paginator {...reports} />
        {/if}
    </div>
</Page>
