<script>
    import RowCount from './RowCount.svelte';
    import Search from './Search.svelte';
    import ThFilter from './ThFilter.svelte';
    import ThSort from './ThSort.svelte';
    import Paginator from '@/Components/Paginator.svelte';

    const efn = () => undefined;
    let {
        data = { data: [] },
        columns = [],
        onSearchChange = efn,
        onFilterChange = efn,
        onSortChange = efn,
        ...rest
    } = $props();
</script>

<div class="space-y-4 overflow-y-auto">
    <header class="flex justify-between">
        <Search handler={onSearchChange} />
    </header>
    <table class="table table-hover table-compact w-full table-auto">
        <thead>
            <tr>
                {#each columns as col}
                    {#if col.field}
                        <ThSort orderBy={col.field} handler={onSortChange}>
                            {col.label}
                        </ThSort>
                    {:else}
                        <th class="select-none p-2 px-5">
                            {col.label}
                        </th>
                    {/if}
                {/each}
            </tr>
            <tr>
                {#each columns as col}
                    {#if col.field}
                        <ThFilter
                            filterBy={col.field}
                            handler={onFilterChange} />
                    {:else}
                        <th class="select-none p-2 px-5"> </th>
                    {/if}
                {/each}
            </tr>
        </thead>
        <tbody>
            {#each data as row}
                <tr>
                    {#each columns as col}
                        <td>
                            {#if col.fied}
                                {row[col.field]}
                            {:else if col.render}
                                {@render rest[col.render]?.()}
                            {/if}
                        </td>
                    {/each}
                </tr>
            {/each}
        </tbody>
    </table>
    <footer class="flex justify-between">
        <RowCount {...data} />
        <Paginator {...data} />
    </footer>
</div>
