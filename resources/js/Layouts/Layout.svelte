<script>
    import { page } from '@inertiajs/svelte';
    import {
        storePopup,
        initializeStores,
        Drawer,
        Toast,
        getToastStore,
        Modal,
    } from '@skeletonlabs/skeleton';
    import {
        computePosition,
        autoUpdate,
        offset,
        shift,
        flip,
        arrow,
    } from '@floating-ui/dom';

    import AppShell from '@/Layouts/AppShell.svelte';
    import Footer from '@/components/footer/Footer.svelte';
    import Header from '@/components/Header.svelte';
    import Navigation from '@/components/Navigation.svelte';
    import BanModal from '@/components/modals/BanModal.svelte';

    storePopup.set({ computePosition, autoUpdate, offset, shift, flip, arrow });
    initializeStores();

    const flashType = {
        success: 'variant-filled-success',
        error: 'variant-filled-error',
        warn: 'variant-filled-warn',
    };

    const toastStore = getToastStore();
    $page.props.flash &&
        toastStore.trigger({
            ...{ background: flashType[$page.props.flash?.type || 'success'] },
            ...$page.props.flash,
        });

    let validationCount = Object.keys($page.props.errors).length;
    $: validationTitlePrefix = validationCount
        ? `(${validationCount} errors) `
        : '';
    console.log({ validationCount, validationTitlePrefix });

    const modalRegistry = {
        banModal: { ref: BanModal },
    };
</script>

<svelte:head>
    <title>{validationTitlePrefix}Abidan Archive</title>
    <meta
        name="description"
        content="The Abidan Archive is website responsible for recording all of the Will Wight fanbase canon non-book external information on Will's various works." />
</svelte:head>

<Drawer bgDrawer="bg-surface-600" regionDrawer="p-4">
    <h2 class="sm:text-xl">Navigation</h2>
    <hr />
    <Navigation column={true} />
</Drawer>
<Toast />
<Modal components={modalRegistry} />
<AppShell>
    <Header slot="header" />
    <slot />
    <Footer slot="footer" />
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
    :global(.grecaptcha-badge) {
        visibility: hidden;
    }
</style>
