<script>
    import Navigation from '@components/Navigation.svelte';
    import Footer from '@components/footer/Footer.svelte';
</script>

<Navigation />
<main class="mt-[60px] h-full">
    <slot />
</main>
<Footer/>

<style @global lang="postcss">
    :global(body) {
        @apply h-full bg-midnight-900 font-sans text-enkai-500 antialiased;
    }
</style>
