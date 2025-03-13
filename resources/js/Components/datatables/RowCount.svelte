<script>
    import { cn } from '@/lib/cn';
    let { total = 0, from, to, class: className = '' } = $props();
</script>

<aside class={cn('mr-6 text-sm leading-8', className)}>
    {#if total > 0}
        <b>{from}</b> - <b>{to}</b> / <b>{total}</b>
    {:else}
        No entries found
    {/if}
</aside>
