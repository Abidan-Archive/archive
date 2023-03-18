<script>
    export let href = '';
    export let ariaLabel = '';
    export let target = undefined;
    export let ref = '';
</script>

{#if href}
    <a
        {...$$restProps}
        {href}
        {target}
        {ref}
        aria-label={ariaLabel}
        class={`text-gray-500 ${$$props.class}`}>
        <slot />
    </a>
{:else}
    <slot />
{/if}
