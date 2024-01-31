<script>
    import Svelecte from 'svelecte';
    import Report from '@/Components/Report.svelte';
    import SearchForm from '@/Components/SearchForm.svelte';
    import Paginator from '@/Components/Paginator.svelte';

    export let paginate;
    export let reports;

    export let tags;
    let includeTags = [];
    let excludeTags = [];
    let availableTags = tags.filter(
        (tag) => includeTags.indexOf(tag) < 0 || excludeTags.indexOf(tag) < 0
    );
</script>

<svelte:head>
    <title>Search | Abidan Archive</title>
</svelte:head>

<section class="container mx-auto pt-5">
    <h2 class="my-5 text-2xl">Search Results</h2>
    <SearchForm class="my-5 mx-auto w-full" />
    <div>
        <label for="includeTags">Include Tags</label>
        <Svelecte
            bind:options={availableTags}
            inputId="includeTags"
            bind:value={includeTags}
            placeholder="Select tags"
            multiple
            clearable />
    </div>
    <div>
        <label for="excludeTags">Exclude Tags</label>
        <Svelecte
            bind:options={availableTags}
            inputId="excludeTags"
            bind:value={excludeTags}
            placeholder="Select tags"
            multiple
            clearable />
    </div>
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
