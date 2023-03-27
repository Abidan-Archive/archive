<script>
    export let value;

    let className;
    export { className as class };
</script>

<label
    class={`block text-sm font-medium text-gray-300 ${className}`}
    {...$$restProps}>
    {#if !!value}
        {value}
    {:else}
        <slot />
    {/if}
</label>
