<script>
    import { page } from '@inertiajs/svelte';
    import { AppShell, initializeStores, Drawer } from '@skeletonlabs/skeleton';

    import Header from '@/components/Header.svelte';
    import Footer from '@/components/footer/Footer.svelte';
    import Navigation from '@/components/Navigation.svelte';

    initializeStores();

    // import { addFlash } from '@/stores/toast.js';

    $: $page.props.flash && alert($page.props.flash);
</script>

<svelte:head>
    <title>Abidan Archive</title>
    <meta
        name="description"
        content="The Abidan Archive is website responsible for recording all of the Will Wight fanbase canon non-book external information on Will's various works." />
</svelte:head>

<Drawer bgDrawer="bg-surface-600" regionDrawer="p-4">
    <h2 class="sm:text-xl">Navigation</h2>
    <hr />
    <Navigation column={true} />
</Drawer>
<AppShell>
    <svelte:fragment slot="header"><Header /></svelte:fragment>
    <slot />
    <svelte:fragment slot="pageFooter"><Footer /></svelte:fragment>
</AppShell>

<style @global lang="postcss">
    :global(html, body, #app) {
        @apply h-full overflow-hidden;
    }
    :global(body) {
        @apply text-token bg-surface-900 antialiased;
    }
    :global(hr) {
        @apply mb-4 mt-2 !border-surface-400;
    }
    :global(main a) {
        @apply text-surface-100 underline hover:text-surface-300;
    }
</style>
