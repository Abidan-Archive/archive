<script>
    export let value = null;

    let forInput;
    let className = '';
    export { className as class };
    export { forInput as for };
</script>

<label
    for={forInput}
    class={`block text-sm font-medium text-gray-300 ${className}`}
    {...$$restProps}>
    {#if !!value}
        {value}
    {:else}
        <slot />
    {/if}
</label>
