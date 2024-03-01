<script>
    export let href = '';
    export let ariaLabel = '';
    export let target = '_blank';
    export let ref = 'noreferrer';
</script>

{#if href}
    <a
        {...$$restProps}
        {href}
        {target}
        {ref}
        aria-label={ariaLabel}
        class={`text-on-surface-token hover:text-surface-300 ${$$props.class}`}>
        <slot />
    </a>
{:else}
    <slot />
{/if}
