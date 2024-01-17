<script>
    import { inertia, page } from '@inertiajs/svelte';
    import Logo from '@/Components/Logo.svelte';
    import {
        Calendar,
        Discord,
        Globe,
        Hamburger,
        House,
        Tag,
        User,
        XMark,
    } from '@/Components/icons';
    import route from '@/lib/route';

    // External links through use:inertia feels bad, see if we can get a change
    // https://github.com/inertiajs/inertia/issues/1719#issuecomment-1890295472
    const links = [
        $page.props.auth.user?.roles.includes('admin')
            ? {
                  label: 'Admin',
                  href: route('admin.index'),
                  icon: House,
              }
            : null,
        {
            label: 'Events',
            href: route('event.index'),
            icon: Calendar,
        },
        {
            label: 'Tags',
            href: route('tag.index'),
            icon: Tag,
        },
        {
            label: 'Discord',
            href: 'https://discord.gg/tCg94qy',
            icon: Discord,
            external: true,
        },
        {
            label: 'Wiki',
            href: 'https://wiki.abidanarchive.com',
            icon: Globe,
            external: true,
        },
        !$page.props.auth.user
            ? {
                  label: 'Login',
                  href: route('login'),
                  icon: User,
              }
            : null,
    ].filter(Boolean);

    let showMenu = false;
    let showProfileMenu = false;
</script>

<nav
    class="fixed top-0 left-0 z-40 h-16 w-full border-l-5 border-primary-400 bg-gray-800">
    <div
        class="mx-6 px-4 py-2 md:flex md:items-center md:justify-between md:px-0 md:py-1">
        <div class="flex items-center justify-between">
            <a use:inertia href={route('home')}>
                <div class="flex h-full flex-row items-center justify-start">
                    <Logo />
                    <h1 class="shrink-0 px-2 text-2xl">Abidan Archive</h1>
                </div>
            </a>
            <!-- Mobile menu button -->
            <div class="flex md:hidden">
                <button
                    on:click={() => (showMenu = !showMenu)}
                    type="button"
                    class="rounded-lg bg-gray-800 p-2 text-white hover:bg-gray-700 focus:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-600 active:bg-gray-800 md:hidden">
                    <svelte:component this={!showMenu ? Hamburger : XMark} />
                </button>
            </div>
        </div>

        <!-- Mobile Menu open: "block", Menu closed: "hidden" -->
        <div
            class="mt-6 flex-col space-y-6 rounded bg-gray-800 py-4 px-8 sm:mt-4 sm:border sm:border-gray-600 md:mt-0 md:flex md:flex-row md:items-center md:space-y-0 md:space-x-6 md:border-0 lg:space-x-10"
            class:hidden={!showMenu}
            class:flex={showMenu}>
            {#each links as { label, href, icon, external = false }}
                {#if !external}
                    <a
                        class="text-white-500 flex hover:text-primary-300 "
                        use:inertia
                        on:click={() => showMenu = false}
                        {href}>
                        <svelte:component this={icon} class="mx-2 h-6 w-6" />
                        {label}
                    </a>
                {:else}
                    <a
                        class="text-white-500 flex hover:text-primary-300 "
                        on:click={() => showMenu = false}
                        {href}>
                        <svelte:component this={icon} class="mx-2 h-6 w-6" />
                        {label}
                    </a>
                {/if}
            {/each}
            {#if !!$page.props.auth.user}
                <button
                    class="text-white-500 flex hover:text-primary-300"
                    on:click={() => {
                        showMenu = false;
                        showProfileMenu = true;
                    }}>
                    <User class="mx-2 h-6 w-6" />
                    {$page.props.auth.user?.username}
                </button>
            {/if}
        </div>
    </div>
</nav>
