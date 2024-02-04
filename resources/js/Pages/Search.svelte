<script>
    import Report from '@/Components/Report.svelte';
    import SearchForm from '@/Components/SearchForm.svelte';
    import Paginator from '@/Components/Paginator.svelte';

    export let reports;
    export let tags;
</script>

<section class="container mx-auto pt-5">
    <h2 class="my-5 text-2xl">Search Results</h2>
    <SearchForm class="my-5 mx-auto w-full" {tags} advanced={true} />
    <hr />
    <div class="flex flex-col gap-5">
        {#if !reports.data.length}
            <div class="text-center">
                <p>No reports found on your supplied query.</p>
            </div>
        {:else}
            <Paginator {...reports} maxResultDisclaimer />
            {#each reports.data as report}
                <Report {report} />
            {/each}
            <Paginator {...reports} maxResultDisclaimer />
        {/if}
    </div>
</section>
