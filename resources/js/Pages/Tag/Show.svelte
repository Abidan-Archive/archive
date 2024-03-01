<script>
    import Page from '@/components/Page.svelte';
    import Paginator from '@/components/Paginator.svelte';
    import Report from '@/components/Report.svelte';

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
