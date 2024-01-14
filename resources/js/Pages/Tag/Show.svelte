<script>
    import Page from '@/Components/Page.svelte';
    import Report from '@/Components/Report.svelte';

    export let tag;
</script>

<Page header={`Tag - ${tag.name} (${tag.reports_count})`}>
    <div class="flex flex-col gap-5">
        {#each tag.reports as report}
            <Report {report} />
        {:else}
            Yikes, this tag has no reports.
        {/each}
    </div>
</Page>
