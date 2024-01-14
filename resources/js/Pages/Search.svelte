<script>
    import Report from '@/Components/Report.svelte';
    import SearchForm from '@/Components/SearchForm.svelte';
    import Paginator from '@/Components/Paginator.svelte';
    //import { Sort } from '@/Components/icons';
    //import { fade } from 'svelte/transition';

    export let paginate;
    export let reports;
</script>

<svelte:head>
    <title>Search | Abidan Archive</title>
</svelte:head>

<section class="container mx-auto pt-5">
    <h2 class="my-5 text-2xl">Search Results</h2>
    <SearchForm class="my-5 w-full" />
    <hr />
    <div class="flex flex-col gap-5">
        {#if !reports.length}
            <div class="text-center">
                <p>No reports found on your supplied query.</p>
            </div>
        {:else}
            <Paginator {...paginate} />
            {#each reports as report}
                <Report {report} />
            {/each}
            <Paginator {...paginate} />
        {/if}
    </div>
</section>
