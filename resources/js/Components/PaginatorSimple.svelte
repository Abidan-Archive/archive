<script>
    import clsx from 'clsx';
    import { inertia } from '@inertiajs/svelte';
    import { Previous, Next } from '@components/icons';

    export let current_page;
    export let last_page;
    export let per_page = 20;
    export let total;
    export let next_page_url;
    export let prev_page_url;

    const prev_page_label = '&laquo; Previous';
    const next_page_label = 'Next &raquo;';
</script>

{#if total > per_page}
    <nav aria-label="Pagination Navigation" class="flex items-center justify-between">
        {#if current_page <= 1}
            <span
                class="relative inline-flex cursor-default items-center rounded-md border border-gray-300 bg-midnight-700 px-4 py-2 text-sm font-medium leading-5 text-gray-500">
                {@html prev_page_label}
            </span>
        {:else}
            <a
                use:inertia
                href={prev_page_url}
                rel="prev"
                class="relative inline-flex items-center rounded-md border border-gray-300 bg-midnight-700 px-4 py-2 text-sm font-medium leading-5 ring-gray-300 transition duration-150 ease-in-out hover:text-gray-500 focus:border-blue-700 focus:outline-none focus:ring active:bg-gray-900 active:text-gray-300">
                {@html prev_page_label}
            </a>
        {/if}

        {#if current_page < last_page}
            <a
                use:inertia
                href={next_page_url}
                rel="next"
                class="relative inline-flex items-center rounded-md border border-gray-300 bg-midnight-700 px-4 py-2 text-sm font-medium leading-5 ring-gray-300 transition duration-150 ease-in-out hover:text-gray-500 focus:border-blue-700 focus:outline-none focus:ring active:bg-gray-900 active:text-gray-300">
                {@html next_page_label}
            </a>
        {:else}
            <span
                class="relative inline-flex cursor-default items-center rounded-md border border-gray-300 bg-midnight-700 px-4 py-2 text-sm font-medium leading-5 text-gray-500">
                {@html next_page_label}
            </span>
        {/if}
    </nav>
{/if}
