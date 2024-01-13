<script>
    import Page from '@/Components/Page.svelte';
    import Report from '@/Components/Report.svelte';

    export let event;
</script>

<svelte:head>
    <title>{event.name} | Abidan Archive</title>
</svelte:head>

<Page header={event.name}>
    <div class="flex flex-col gap-5">
        {#each event.reports as report}
            <Report {report} withEvent={false} />
        {/each}
    </div>
</Page>
