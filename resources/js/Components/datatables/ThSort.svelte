<script>
    import { cn } from '@/lib/cn';
    let {
        filters,
        handler,
        orderBy,
        children,
        class: className = '',
    } = $props();
    let sort = $derived(filters.sort);
    let direction = $derived(filters.direction);

    function nextDirection() {
        if (sort !== orderBy) return 'asc';
        switch (direction) {
            case 'asc':
                return 'desc';
            case 'desc':
                return undefined;
            default:
                return 'asc';
        }
    }
</script>

<th
    onclick={() => handler(orderBy, nextDirection())}
    class={[
        'cursor-pointer select-none !p-2',
        { 'table-sort-asc': sort === orderBy && direction === 'asc' },
        { 'table-sort-dsc': sort === orderBy && direction === 'desc' },
    ]}>
    <div class={cn('flex h-full items-center justify-start', className)}>
        {@render children()}
    </div>
</th>
