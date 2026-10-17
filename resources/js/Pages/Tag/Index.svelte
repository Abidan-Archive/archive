<script>
    import { router } from '@inertiajs/svelte';
    import { fade } from 'svelte/transition';

    import Page from '@/Components/Page.svelte';
    import SortBy from '@/Components/SortBy.svelte';
    import Input from '@/Components/forms/Input.svelte';
    import Tag from '@/Components/Tag.svelte';

    let { tags } = $props();

    let filter = $state('');
    let sortState = $state(null);
    const sortOptions = [
        { value: 'name', label: 'Alphabetical' },
        { value: 'reports_count', label: 'Reports' },
    ];

    function sortFn(a, b) {
        if (!sortState) return 0;
        switch (sortState.key) {
            case 'name': // Alpha
                return (
                    (a[sortState.key] > b[sortState.key] ? 1 : -1) *
                    sortState.direction
                );
            case 'reports_count': // Numeric
                return (
                    (a[sortState.key] - b[sortState.key]) * sortState.direction
                );
            default:
                return 0;
        }
    }

    function onKeyDown(e) {
        if (e.repeat) return;
        if (e.code === 'Enter' && data.length === 1) router.get(data[0]);
    }
    let data = $derived(
        tags
            .filter(
                (tag) =>
                    !filter ||
                    tag.name.toLowerCase().indexOf(filter.toLowerCase()) > -1
            )
            .toSorted(sortFn)
    );
</script>

<Page header="Tags">
    <div class="mb-4 flex justify-between">
        <Input
            id="filter"
            name="filter"
            placeholder="Filter"
            bind:value={filter}
            on:keydown={onKeyDown} />
        <div>
            <SortBy
                bind:value={sortState}
                options={sortOptions}
                placeholder="Sort" />
        </div>
    </div>
    <div class="flex flex-wrap justify-center gap-2">
        {#each data as tag}
            <Tag {tag} showCount class="ease-in-out" />
        {:else}
            <div
                class="align-center w-full flex-shrink-0 self-center text-center"
                in:fade={{ delay: 400 }}>
                An Arelius cleaned too much here. Not a speck of tags to be
                found.
            </div>
        {/each}
    </div>
</Page>
