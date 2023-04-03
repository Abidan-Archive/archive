<script>
    import clsx from 'clsx';

    let type = 'submit';
    let className = '';
    export let variant = 'default';
    export { className as class };

    const setType = (node) => (node.type = type);
</script>

<button
    on:click
    use:setType
    class={clsx(
        {
            default: 'bg-gray-200 hover:bg-white focus:bg-white',
            success: 'bg-green-500 hover:bg-green-600 focus:bg-green-400',
            danger: 'bg-red-400 hover:bg-red-500 focus:bg-red-300',
            warn: 'bg-yellow-500 hover:bg-yellow-600 focus:bg-yellow-400',
        }[variant],
        'inline-flex items-center rounded-md border border-transparent px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-800',
        'focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800 active:bg-gray-300',
        'transition duration-150 ease-in-out',
        className
    )}
    {...$$restProps}>
    <slot />
</button>
