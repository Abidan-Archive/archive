<script>
    import Modal from 'svelte-simple-modal';
    import Navigation from '@components/Navigation.svelte';
    import Footer from '@components/footer/Footer.svelte';

</script>

<svelte:head>
    <title>Abidan Archive</title>
    <meta
        name="description"
        content="The Abidan Archive is website responsible for recording all of the Will Wight fanbase canon non-book external information on Will's various works." />
</svelte:head>

<Modal
    unstyled={true}
    classBg="fixed top-0 left-0 w-screen h-screen flex flex-col justify-center bg-midnight-900/[.9]"
    classWindowWrap="relative m-20 max-h-full"
    classWindow="relative max-w-full max-h-full my-2 mx-auto rounded shadow-md bg-midnight-700 text-enkai-500 border border-enkai-500 p-4"
    classContent="relative p-2 overflow-auto"
    closeButton={false}>
    <Navigation />
    <main class="mt-16 grow px-2 sm:px-5">
        <slot />
    </main>
    <Footer />
</Modal>

<style @global lang="postcss">
    :global(body) {
        @apply h-full bg-midnight-900 font-sans text-enkai-500 antialiased;
    }
    :global(#app) {
        @apply flex min-h-screen flex-col;
    }
    :global(hr) {
        @apply border-gray-500;
    }
    :global(main a) {
        @apply text-enkai-500 hover:text-enkai-600 hover:underline;
    }
</style>
