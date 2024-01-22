<script>
    import route from '@/lib/route';
    import { fade } from 'svelte/transition';
    import Page from '@/Components/Page.svelte';
    import SortBy from '@/Components/SortBy.svelte';
    import TextInput from '@/Components/forms/TextInput.svelte';
    import cn from '@/lib/cn';
    import { getContrastText } from '@/lib/color';
    import { inertia, router } from '@inertiajs/svelte';

    export let tags;
    let filter = '';
    let sortState = null;
    let data;
    $: {
        // Svelte magic, assignment triggers updates, reactivity of data needs to know sortState is updating
        sortState;
        data = tags
            .filter(
                (tag) =>
                    !filter ||
                    tag.name.toLowerCase().indexOf(filter.toLowerCase()) > -1
            )
            .sort(sortFn);
    }
    const sortOptions = [
        { value: 'name', label: 'Alpha' },
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
</script>

<Page header="Tags">
    <div class="mb-4 flex justify-between">
        <TextInput
            id="filter"
            name="filter"
            placeholder="Filter"
            bind:value={filter}
            on:keydown={onKeyDown} />
        <div>
            <SortBy
                bind:state={sortState}
                options={sortOptions}
                placeholder="Sort" />
        </div>
    </div>
    <div class="flex flex-wrap gap-2 ">
        {#each data as tag}
            <a
                use:inertia
                href={route('tag.show', tag)}
                style={`background-color: ${tag.color};`}
                class={cn(
                    'h-10 rounded-md py-2 px-4 font-bold ease-in-out capitalize',
                    getContrastText(tag.color)
                )}
                transition:fade|local>
                {tag.name} ({tag.reports_count})</a>
        {:else}
            <div
                class="flex-shrink-0 self-center align-center w-full text-center"
                in:fade={{ delay: 401 }}>
                Ozriel cleaned too much here. Not a speck of tags to be found.
            </div>
        {/each}
    </div>
</Page>
