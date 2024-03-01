<script>
    import { inertia, page } from '@inertiajs/svelte';
    import { AppBar } from '@skeletonlabs/skeleton';
    import Logo from '@/components/Logo.svelte';
    import { Hamburger, User } from '@/components/icons';
    import route from '@/lib/route';
    import Navigation from '@/components/Navigation.svelte';
    import { getDrawerStore } from '@skeletonlabs/skeleton';

    const drawerStore = getDrawerStore();

    function openLeftNavSidebar() {
        drawerStore.open({
            width: 'w-64',
            rounded: 'rounded-none',
        });
    }
    function openRightNavSidebar() {
        drawerStore.open({
            position: 'right',
            width: 'w-64',
            rounded: 'rounded-none',
        });
    }
</script>

<AppBar
    border="lg:border-l-5 border-primary-500"
    background="bg-surface-500"
    padding="py-2 px-4"
    gridColumns="lg:grid-cols-2 grid-cols-3"
    slotLead="lg:hidden"
    slotDefault="place-self-center lg:place-self-start"
    slotTrail="place-self-end h-full flex items-center">
    <svelte:fragment slot="lead">
        <button
            class="btn-icon btn-sm !bg-transparent"
            on:click={openLeftNavSidebar}>
            <Hamburger />
        </button>
    </svelte:fragment>

    <a use:inertia href={route('home')}>
        <div class="flex h-full flex-row items-center justify-start gap-2">
            <Logo class="hidden sm:block" />
            <h1
                class="shrink-0 border-b-2 border-primary-500 text-xl sm:text-2xl md:border-b-0">
                Abidan Archive
            </h1>
        </div>
    </a>

    <svelte:fragment slot="trail">
        <Navigation class="hidden lg:block" />
        <div>
            {#if !!$page.props.auth.user}
                <button class="flex gap-2" on:click={openRightNavSidebar}>
                    <User class="h-6 w-6" />
                    {$page.props.auth.user?.username}
                </button>
            {:else}
                <a use:inertia href={route('login')} class="flex gap-2">
                    <User class="hidden h-6 w-6 sm:block" />
                    Login
                </a>
            {/if}
        </div>
    </svelte:fragment>
</AppBar>
