<script>
    import { inertia } from '@inertiajs/svelte';
    import { Previous, Next } from '@components/icons';

    export let current_page;
    export let last_page;
    export let per_page = 20;
    export let from;
    export let to;
    export let total;
    export let next_page_url;
    export let prev_page_url;
    export let links;

    const prev_page_label = '&laquo; Previous';
    const next_page_label = 'Next &raquo;';
</script>

{#if total > per_page}
    <nav
        aria-label="Pagination Navigation"
        class="flex items-center justify-between">
        <div class="flex flex-1 justify-between sm:hidden">
            {#if current_page <= 1}
                <span
                    class="border-muenkai relative inline-flex cursor-default items-center rounded-md border bg-midnight-700 px-4 py-2 text-sm font-medium leading-5 text-gray-500">
                    {@html prev_page_label}
                </span>
            {:else}
                <a
                    use:inertia
                    href={prev_page_url}
                    class="border-muenkai relative inline-flex items-center rounded-md border bg-midnight-700 px-4 py-2 text-sm font-medium leading-5 text-gray-300 ring-gray-300 transition duration-150 ease-in-out hover:text-gray-500 focus:border-blue-300 focus:outline-none focus:ring active:bg-gray-100 active:text-gray-100"
                    >{@html prev_page_label}</a>
            {/if}

            {#if current_page < last_page}
                <a
                    use:inertia
                    href={next_page_url}
                    class="border-muenkai relative ml-3 inline-flex items-center rounded-md border bg-midnight-700 px-4 py-2 text-sm font-medium leading-5 text-gray-300 ring-gray-300 transition duration-150 ease-in-out hover:text-gray-500 focus:border-blue-300 focus:outline-none focus:ring active:bg-gray-100 active:text-gray-300"
                    >{@html next_page_label}</a>
            {:else}
                <span
                    class="border-muenkai relative ml-3 inline-flex cursor-default items-center rounded-md border bg-midnight-700 px-4 py-2 text-sm font-medium leading-5 text-gray-500"
                    >{@html next_page_label}</span>
            {/if}
        </div>

        <div
            class="hidden sm:flex sm:flex-1 sm:flex-col sm:items-center sm:justify-between sm:gap-y-2">
            <div>
                <p class="text-sm leading-5 text-gray-300">
                    Showing
                    {#if current_page > 1}
                        <span class="font-medium">{from}</span>
                        to
                        <span class="font-medium">{to}</span>
                    {:else}
                        {per_page}
                    {/if}
                    of
                    <span class="font-medium">{total}</span>
                    results
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-md shadow-sm">
                    {#if current_page <= 1}
                        <span aria-disabled="true" aria-label={prev_page_label}>
                            <span
                                class="border-muenkai relative inline-flex cursor-default items-center rounded-l-md border bg-midnight-700 px-2 py-2 text-sm font-medium leading-5 text-gray-500"
                                aria-hidden="true">
                                <Previous />
                            </span>
                        </span>
                    {:else}
                        <a
                            use:inertia
                            href={prev_page_url}
                            rel="prev"
                            aria-label={prev_page_label}
                            class="border-muenkai relative -ml-px inline-flex items-center rounded-l-md border bg-midnight-700 px-2 py-2 text-sm font-medium leading-5 text-gray-200 ring-gray-300 transition duration-150 ease-in-out hover:text-gray-400 focus:z-10 focus:border-blue-300 focus:outline-none focus:ring active:bg-gray-500 active:text-gray-100">
                            <Previous />
                        </a>
                    {/if}

                    {#each links.slice(1, links.length - 1) as link}
                        {#if link.label === '...'}
                            <span aria-disabled="true">
                                <span
                                    class="border-muenkai relative -ml-px inline-flex cursor-default items-center border bg-midnight-700 px-4 py-2 text-sm font-medium leading-5 text-gray-300"
                                    >...</span>
                            </span>
                        {:else if link.active}
                            <span aria-current="page">
                                <span
                                    class="border-muenkai relative -ml-px inline-flex cursor-default items-center border bg-midnight-700 px-4 py-2 text-sm font-medium leading-5 text-gray-500"
                                    >{@html link.label}</span>
                            </span>
                        {:else}
                            <a
                                use:inertia
                                href={link.url}
                                aria-label={`Go to page ${link.label}`}
                                class="border-muenkai relative -ml-px inline-flex items-center border bg-midnight-700 px-4 py-2 text-sm font-medium leading-5 text-gray-300 ring-gray-300 transition duration-150 ease-in-out hover:text-gray-500 focus:z-10 focus:border-blue-300 focus:outline-none focus:ring active:bg-gray-100 active:text-gray-300">
                                {@html link.label}
                            </a>
                        {/if}
                    {/each}

                    <!-- Next Page Link -->
                    {#if current_page < last_page}
                        <a
                            use:inertia
                            href={next_page_url}
                            rel="next"
                            aria-label={next_page_label}
                            class="border-muenkai relative -ml-px inline-flex items-center rounded-r-md border bg-midnight-700 px-2 py-2 text-sm font-medium leading-5 text-gray-200 ring-gray-300 transition duration-150 ease-in-out hover:text-gray-400 focus:z-10 focus:border-blue-300 focus:outline-none focus:ring active:bg-gray-500 active:text-gray-100">
                            <Next />
                        </a>
                    {:else}
                        <span aria-disabled="true" aria-label={next_page_label}>
                            <span
                                class="border-muenkai relative inline-flex cursor-default items-center rounded-r-md border bg-midnight-700 px-2 py-2 text-sm font-medium leading-5 text-gray-500"
                                aria-hidden="true">
                                <Next />
                            </span>
                        </span>
                    {/if}
                </span>
            </div>
        </div>
    </nav>
{/if}
