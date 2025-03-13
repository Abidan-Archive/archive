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
    import Footer from '@/Components/footer/Footer.svelte';
    import Header from '@/Components/Header.svelte';
    import Navigation from '@/Components/Navigation.svelte';
    import BanModal from '@/Components/modals/BanModal.svelte';
    import ChangeUserRoleModal from '@/Components/modals/ChangeUserRoleModal.svelte';
    /**
     * @typedef {Object} Props
     * @property {import('svelte').Snippet} [children]
     */

    /** @type {Props} */
    let { children } = $props();

    storePopup.set({ computePosition, autoUpdate, offset, shift, flip, arrow });
    initializeStores();

    const flashType = {
        success: 'variant-filled-success',
        error: 'variant-filled-error',
        warn: 'variant-filled-warning',
    };

    const toastStore = getToastStore();
    $effect(() => {
        $page.props.flash &&
            toastStore.trigger({
                background: flashType[$page.props.flash?.type || 'success'],
                autohide: true,
                ...$page.props.flash,
            });
    });

    let validationTitlePrefix = $derived.by(() => {
        const count = Object.keys($page.props?.errors ?? {}).length;
        return count ? `(${count} error${count > 2 ? 's' : ''}) ` : '';
    });

    // Register window helpers
    $effect(() => {
        if (typeof window === 'undefined') return;
        window.page = $page;

        // Inform turnstile we're loaded, async defer loading the deps
        window.onTurnstileLoad = () => {
            window.turnstileLoaded = true;
            document.dispatchEvent(new Event('turnstileLoaded'));
        };
    });

    const modalRegistry = {
        banModal: { ref: BanModal },
        changeUserRoleModal: { ref: ChangeUserRoleModal },
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
    {#snippet header()}
        <Header />
    {/snippet}
    {@render children?.()}
    {#snippet footer()}
        <Footer />
    {/snippet}
</AppShell>
