<script>
    let className = '';
    export { className as class };
</script>

<div
    class={`rounded-lg border border-typo-500 bg-base-700 p-4 shadow-md ${className}`}
    {...$$restProps}>
    <slot />
</div>
