<script>
    let { filters, handler } = $props();

    let value = $state(filters.search);
    let timeout;
    const debounce = 400;

    const search = () => {
        clearTimeout(timeout);
        timeout = setTimeout(() => handler(value), debounce);
    };
</script>

<input
    type="search"
    class="input-variant-secondary input w-36 sm:w-64"
    placeholder="Search..."
    bind:value
    oninput={search} />
