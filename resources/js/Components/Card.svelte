<script>
    let className;
    export { className as class };
</script>

<div
    class={`rounded-lg border border-enkai-500 bg-midnight-700 p-4 shadow-md ${className}`}
    {...$$restProps}>
    <slot />
</div>
