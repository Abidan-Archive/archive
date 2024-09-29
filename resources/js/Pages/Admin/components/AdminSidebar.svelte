<script>
    import { cn, route } from '@/lib';
    import Input from '@/Components/forms/Input.svelte';
    import { router, inertia } from '@inertiajs/svelte';

    const links = {
        Overview: route('admin.index'),
        'Create Event': route('event.create'),
        'Create Tag': route('tag.create'),
        'Manage Users': route('admin.user'),
        // 'Review Reports': route('home'),
    };

    let className = '';
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

<nav class={cn('overflow-hidden pr-4', className)}>
    <h2 class="mb-4 text-2xl">Admin Nav</h2>
    <Input
        id="filter"
        class="mb-4 placeholder:text-center"
        name="filter"
        placeholder="Filter"
        bind:value={filter}
        on:keydown={onKeyDown} />
    <ul>
        {#each filtered as [label, href]}
            <li><a use:inertia {href}>{label}</a></li>
        {/each}
    </ul>
</nav>
