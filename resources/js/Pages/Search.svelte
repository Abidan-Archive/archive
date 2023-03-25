<script>
    import Report from '@components/Report.svelte';
    import SearchForm from '@components/SearchForm.svelte';
    import Paginator from '@components/Paginator.svelte';
    //import { Sort } from '@components/icons';
    //import { fade } from 'svelte/transition';

    // const urlParams = new URLSearchParams(window.location.search);
    // console.log('what', urlParams.get('query'));
    export let paginate;
    export let reports;
    $: console.log(paginate);
</script>

<svelte:head>
    <title>Search | Abidan Archive</title>
</svelte:head>

<div class="container mx-auto pt-5">
    <h2 class="my-5 text-2xl">Search Results</h2>
    <SearchForm class="my-5 w-full" />
    <hr class="my-5" />
    <section class="flex flex-col gap-5">
        {#if !reports.length}
            <div class="text-center">
                <p>No reports found on your supplied query.</p>
            </div>
        {:else}
            <section>
                <div class="text-right">
                    Reports Found: {paginate.total}
                </div>
            </section>
            <section class="text-center">
                <Paginator {...paginate} />
            </section>
            {#each reports as report}
                <Report {report} />
            {/each}
            <!-- <section class="text-center"> -->
            <!--     <Paginator {...paginate} /> -->
            <!-- </section> -->
        {/if}
    </section>
</div>
