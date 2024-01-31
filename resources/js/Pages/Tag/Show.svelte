<script>
    import Page from '@/Components/Page.svelte';
    import Paginator from '@/Components/Paginator.svelte';
    import Report from '@/Components/Report.svelte';

    export let tag;
    export let reports;
</script>

<Page header={`Tag - ${tag.name} (${reports.total})`}>
    <div class="flex flex-col gap-5">
        {#if !reports.data.length}
            Yikes, this tag has no reports.
        {:else}
            <Paginator {...reports} />
            {#each reports.data as report}
                <Report {report} />
            {/each}
            <Paginator {...reports} />
        {/if}
    </div>
</Page>
