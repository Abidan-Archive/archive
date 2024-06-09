<script>
    import route from '@/lib/route';
    import { inertia } from '@inertiajs/svelte';
    import cn from '@/lib/cn';
    import { getContrastText } from '@/lib/color';

    export let tag;

    let classname = '';
    export { classname as class };
    export let showCount = false;
</script>

<a
    use:inertia
    href={route('tag.show', tag)}
    style={`background-color: ${tag.color};`}
    class={cn(
        'chip font-bold capitalize',
        getContrastText(tag.color),
        classname
    )}>
    {tag.name}
    {#if showCount}
        ({tag.reports_count})
    {/if}
</a>
