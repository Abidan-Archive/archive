<script>
    import { cn } from '@/lib/cn';
    let { filters, handler, filterBy, class: className = '' } = $props();
    let value = $state(filters?.column?.[filterBy]);
    $inspect(value, filters);
    let timeout;
    const debounce = 400;

    const filter = () => {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            handler(value, filterBy);
        }, debounce);
    };
</script>

<th class="!p-2">
    <input
        class={cn('input w-full text-sm', className)}
        type="text"
        placeholder="Filter"
        bind:value
        oninput={filter} />
</th>
