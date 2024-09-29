<script>
    import Report from '@/Components/Report.svelte';
    import SearchForm from '@/Components/SearchForm.svelte';
    import Paginator from '@/Components/Paginator.svelte';
    import Page from '@/Components/Page.svelte';

    export let reports;
    export let tags;
</script>

<Page header="Search Results">
    <SearchForm class="mx-auto my-5 w-full" {tags} advanced={true} />
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
</Page>
