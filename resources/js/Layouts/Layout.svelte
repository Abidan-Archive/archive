<script>
    import Navigation from "@components/Navigation.svelte";
</script>

<Navigation />
<main class="mt-16 h-full">
    <slot />
</main>

<style @global lang="postcss">
    :global(body) {
        @apply bg-midnight-900 h-full font-sans antialiased text-enkai-500;
    }
</style>
