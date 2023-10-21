<script>
    import cn from '@/utils/cn.js';

    export let message;

    let className;
    export { className as class };
</script>

{#if !!message}
    <div
        class={cn('text-sm font-medium text-green-400', className)}
        {...$$restProps}>
        {message}
    </div>
{/if}
