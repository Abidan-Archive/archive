<script>
    import cn from '@/lib/cn';
    import { ChevronUp, ChevronDown, ChevronUpDown } from '@/Components/icons';

    export let state;
    let className;
    export { className as class };

    let sortKey;
    export let options = []; // {value, label}
    export let placeholder;

    const directions = [
        { icon: ChevronUpDown, value: 0, label: 'No Sort Direction' },
        { icon: ChevronUp, value: 1, label: 'Ascending Sort' },
        { icon: ChevronDown, value: -1, label: 'Descending Sort' },
    ];
    let d = 0; // Direction index, internal use only
    function cycleDirections() {
        d = (d + 1) % directions.length;
    }
    $: {
        if (d !== 0 && !!sortKey) {
            state = { key: sortKey, direction: directions[d].value };
        } else {
            state = null;
        }
    }
</script>

<div class="flex">
    <label for="sortby-select" class="sr-only">Sort by select</label>
    <select
        id="sortby-select"
        bind:value={sortKey}
        class={cn(
            'apperance-none peer block w-full border-0 bg-transparent bg-none px-0 py-2.5 text-gray-300 focus:outline-none focus:ring-0',
            className
        )}>
        {#if placeholder}
            <option disabled selected>{placeholder}</option>
        {/if}
        {#each options as option}
            <option value={option.value}>{option.label}</option>
        {/each}
    </select>
    <button class="px-2" on:click={cycleDirections}>
        <span class="sr-only">{directions[d].label}</span>
        <svelte:component this={directions[d].icon} />
    </button>
</div>
