<script>
    import cn from '@/lib/cn';

    let className = '';
    export let type = 'text';
    export let value;
    export { className as class };
    export let readonly = false;

    const setType = (node) => (node.type = type);
</script>

<input
    bind:value
    use:setType
    on:keydown
    {readonly}
    class={cn(
        'rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm',
        'focus:border-indigo-600 focus:ring-indigo-600',
        className
    )}
    {...$$restProps} />
