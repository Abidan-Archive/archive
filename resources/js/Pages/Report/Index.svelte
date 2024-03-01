<script>
    import Page from '@/components/Page.svelte';
    import Paginator from '@/components/Paginator.svelte';
    import Report from '@/components/Report.svelte';

    export let reports;
</script>

<Page header="Reports">
    <div class="mx-auto flex w-3/4 flex-col gap-3">
        {#if !reports.data.length}
            <div class="text-center">
                <p>No Reports found.</p>
            </div>
        {:else}
            <Paginator {...reports} />
            {#each reports.data as report}
                <Report {report} />
            {/each}
            <Paginator {...reports} />
        {/if}
    </div>
</Page>
