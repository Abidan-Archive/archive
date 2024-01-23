<script>
    import { page } from '@inertiajs/svelte';

    import Modal from 'svelte-simple-modal';
    import Navigation from '@/Components/Navigation.svelte';
    import Footer from '@/Components/footer/Footer.svelte';
    import Toasts from '@/Components/Toasts.svelte';
    import { addFlash } from '@/Stores/toast.js';

    $: addFlash($page.props.flash);
</script>

<svelte:head>
    <title>Abidan Archive</title>
    <meta
        name="description"
        content="The Abidan Archive is website responsible for recording all of the Will Wight fanbase canon non-book external information on Will's various works." />
</svelte:head>

<Modal
    unstyled={true}
    classBg="fixed top-0 left-0 w-screen h-screen flex flex-col justify-center bg-base-900/[.9]"
    classWindowWrap="relative m-20 max-h-full"
    classWindow="relative max-w-full max-h-full my-2 mx-auto rounded shadow-md bg-base-700 text-typo-500 border border-typo-500 p-4"
    classContent="relative p-2 overflow-auto"
    closeButton={false}>
    <Navigation />
    <Toasts class="top-16" />
    <main class="mt-16 h-full grow px-2 sm:px-5" aria-label="content">
        <slot />
    </main>
    <Footer />
</Modal>

<style @global lang="postcss">
    :global(body) {
        @apply h-full bg-base-900 font-sans text-typo-500 antialiased;
    }
    :global(#app) {
        @apply flex min-h-screen flex-col;
    }
    :global(hr) {
        @apply mb-4 mt-2 border-gray-500;
    }
    :global(main a) {
        @apply text-typo-500 hover:text-typo-600 hover:underline;
    }
</style>
