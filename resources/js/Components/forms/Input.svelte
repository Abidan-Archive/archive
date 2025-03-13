<script>
    import cn from '@/lib/cn';

    /**
     * @typedef {Object} Props
     * @property {string} [class]
     * @property {string} [type]
     * @property {any} value
     * @property {boolean} [readonly]
     */

    /** @type {Props & { [key: string]: any }} */
    let {
        class: className = '',
        type = 'text',
        value = $bindable(),
        ...rest
    } = $props();

    const setType = (node) => (node.type = type);
</script>

<input
    bind:value
    use:setType
    class={cn(
        'input',
        // 'rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm',
        // 'focus:border-indigo-600 focus:ring-indigo-600',
        className
    )}
    {...rest} />
