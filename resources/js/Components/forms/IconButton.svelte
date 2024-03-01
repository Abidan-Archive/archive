<script>
    import cn from '@/lib/cn';

    let className;
    export let large = false;
    export let marked = false;
    export let light = false;
    export { className as class };
</script>

<button
    class={cn(
        'flex items-center justify-center self-center rounded-full border border-white p-2 text-center text-2xl',
        { 'h-10 w-10': !large, 'h-12 w-12': !!large },
        {
            'bg-typo-500 hover:bg-typo-600': !marked,
            'bg-red-600 hover:bg-red-700': !!marked,
        },
        { 'text-black': !light, 'text-typo-500': !!light },
        className
    )}
    on:click
    {...$$restProps}>
    <slot />
</button>
