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
        class={`dark:hover:text-white' text-gray-500 hover:text-gray-900 ${$$props.class}`}>
        <slot />
    </a>
{:else}
    <slot />
{/if}
