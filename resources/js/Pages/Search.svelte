<script>
    import Report from '@components/Report.svelte';
    import SearchForm from '@components/SearchForm.svelte';
    //import { Sort } from '@components/icons';
    //import { fade } from 'svelte/transition';

    export let reports;
</script>

<svelte:head>
    <title>Search | Abidan Archive</title>
</svelte:head>

<div class="container mx-auto pt-5">
    <h2 class="text-2xl my-5">Search Results</h2>
    <SearchForm class="my-5 w-full"/>
    <hr class="my-5" />
    <section class="flex flex-col gap-5">
        {#if !reports.length}
            <div class="text-center">
                <p>No reports found on your supplied query.</p>
            </div>
        {:else}
            <section>
                <div class="text-right">
                    Reports Found: {reports.length}
                </div>
            </section>
            {#each reports as report}
                <Report {report} />
            {/each}
        {/if}
    </section>
</div>
