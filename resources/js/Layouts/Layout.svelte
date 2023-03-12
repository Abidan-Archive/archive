<script>
    import Navigation from '@components/Navigation.svelte';
</script>

<Navigation />
<main class="mt-16 h-full">
    <slot />
</main>

<style @global lang="postcss">
    :global(body) {
        @apply h-full bg-midnight-900 font-sans text-enkai-500 antialiased;
    }
</style>
