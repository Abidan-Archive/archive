<script>
    import Navigation from '@components/Navigation.svelte';
    import Footer from '@components/footer/Footer.svelte';
</script>

<svelte:head>
    <title>Abidan Archive</title>
    <meta
        name="description"
        content="The Abidan Archive is website responsible for recording all of the Will Wight fanbase canon non-book external information on Will's various works." />
</svelte:head>

<Navigation />
<main class="mt-16 grow px-2 sm:px-5">
    <slot />
</main>
<Footer />

<style @global lang="postcss">
    :global(body) {
        @apply h-full bg-midnight-900 font-sans text-enkai-500 antialiased;
    }
    :global(#app) {
        @apply flex min-h-screen flex-col;
    }
</style>
