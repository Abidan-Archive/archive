<script>
    import cn from '@/Utils/cn';

    let className;
    export let type = 'text';
    export let value;
    export { className as class };

    const setType = (node) => (node.type = type);
</script>

<input
    bind:value
    use:setType
    class={cn(
        'rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm',
        'focus:border-indigo-600 focus:ring-indigo-600',
        className
    )}
    {...$$restProps} />
