<script>
    import cn from '@/lib/cn';

    let className = '';
    export { className as class };
    export let is = 'div'; // Change the container html tag type
</script>

<svelte:element this={is} class={cn('card', className)} {...$$restProps}>
    <slot />
</svelte:element>
