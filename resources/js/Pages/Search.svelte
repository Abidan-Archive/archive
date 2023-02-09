<script>
    import Report from '@components/Report.svelte';
    import SortIcon from '@components/icons/Sort.svelte';

    export let reports;
</script>

<div class="container mx-auto">
    <section class="mb-5">
        <div class="p-5">
            <!-- Search -->
            <p class="text-center">Search box box</p>
        </div>
        <div class="flex items-center">
            <span title="hi"><SortIcon /></span>
            <span class="px-2">Sort By</span>
        </div>
    </section>
    <section class="flex flex-col gap-5">
        {#if !reports.length}
            <div class="text-center">
                <p>No reports found on your supplied query.</p>
            </div>
        {:else}
            {#each reports as report}
                <Report {report} />
            {/each}
        {/if}
    </section>
</div>
