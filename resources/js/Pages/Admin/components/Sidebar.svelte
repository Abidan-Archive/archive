<script>
    import { cn, route } from '@/Utils';
    import TextInput from '@components/forms/TextInput.svelte';
    import { router, inertia } from '@inertiajs/svelte';

    const links = {
        'Create Event': route('event.create'),
        'Review Reports': route('home'),
        'Tag Create': route('home'),
    };

    let className;
    export { className as class };
    let filter = '';
    $: filtered = Object.entries(links).filter(
        ([key]) =>
            !filter || key.toLowerCase().indexOf(filter.toLowerCase()) > -1
    );
    function onKeyDown(e) {
        if (e.repeat) return;
        if (e.code === 'Enter' && filtered.length === 1)
            router.get(filtered[0][1]);
    }
</script>

<nav class={cn('h-screen overflow-hidden border-r pr-4', className)}>
    <h2 class="text-2xl mb-4">Navigation</h2>
    <TextInput
        id="filter"
        class="mb-4 placeholder:text-center"
        name="filter"
        placeholder="filter"
        bind:value={filter}
        on:keydown={onKeyDown} />
    <ul>
        {#each filtered as [label, href]}
            <li><a use:inertia {href}>{label}</a></li>
        {/each}
    </ul>
</nav>
