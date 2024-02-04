<script>
    import Page from '@/Components/Page.svelte';
    import Paginator from '@/Components/Paginator.svelte';
    import Report from '@/Components/Report.svelte';

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
