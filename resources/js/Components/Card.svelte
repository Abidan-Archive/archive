<script>
    import cn from '@/lib/cn';

    let className = '';
    export { className as class };
    export let is = 'div'; // Change the container html tag type
</script>

<svelte:element
    this={is}
    class={cn(
        'w-full rounded-lg border border-surface-400 bg-base-700 p-4 shadow-md',
        className
    )}
    {...$$restProps}>
    <slot />
</svelte:element>
