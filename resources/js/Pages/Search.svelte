<script>
    import { fade } from 'svelte/transition';
    import Report from '@components/Report.svelte';
    //import { Sort } from '@components/icons';

    export let reports;
</script>

<div class="container mx-auto" transition:fade>
    <section class="mb-5">
        <div class="p-5">
            Search box box
        </div>
        <!-- <div class="flex items-center"> -->
        <!--     <span title="Sort By"><Sort /></span> -->
        <!--     <span class="px-2">Relevance</span> -->
        <!-- </div> -->
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
