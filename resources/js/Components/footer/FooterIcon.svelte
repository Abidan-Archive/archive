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
        class={`text-gray-400  hover:text-primary-400 ${$$props.class}`}>
        <slot />
    </a>
{:else}
    <slot />
{/if}
