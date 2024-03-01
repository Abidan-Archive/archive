<script>
    import cn from '@/lib/cn';

    let className = '';
    export { className as class };
</script>

<div
    class={cn('rounded-lg bg-base-700 p-4 shadow-md border-token', className)}
    {...$$restProps}>
    <slot />
</div>
