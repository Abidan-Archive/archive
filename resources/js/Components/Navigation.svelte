<script>
    import { inertia, page } from '@inertiajs/svelte';
    import Logo from '@components/Logo.svelte';
    import {
        Globe,
        House,
        Calendar,
        Discord,
        Hamburger,
    } from '@components/icons';
    import route from '@/Utils/route';

    const links = [
        $page.props.auth.user.roles.includes('admin')
            ? {
                  label: 'Admin',
                  href: route('admin.index'),
                  icon: House,
              }
            : null,
        {
            label: 'Recent Events',
            href: route('event.index'),
            icon: Calendar,
        },
        {
            label: 'Discord',
            href: 'https://discord.gg/tCg94qy',
            icon: Discord,
        },
        {
            label: 'Wiki',
            href: 'https://wiki.abidanarchive.com',
            icon: Globe,
        },
    ].filter(Boolean);

    let showMenu = false;

    function toggleNavbar() {
        showMenu = !showMenu;
    }
</script>

<nav
    class="fixed top-0 left-0 z-50 h-16 w-full border-l-5 border-abidan-400 bg-gray-800">
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
            <!-- svelte-ignore a11y-click-events-have-key-events -->
            <div on:click={toggleNavbar} class="flex md:hidden">
                <button
                    type="button"
                    class="rounded-lg bg-gray-800 p-2 text-white hover:bg-gray-700 focus:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-600 active:bg-gray-800 md:hidden">
                    <Hamburger />
                </button>
            </div>
        </div>

        <!-- Mobile Menu open: "block", Menu closed: "hidden" -->
        <div
            class="mt-6 flex-col space-y-6 rounded bg-gray-800 py-4 px-8 sm:mt-4 sm:border sm:border-gray-600 md:mt-0 md:flex md:flex-row md:items-center md:space-y-0 md:space-x-6 md:border-0 lg:space-x-10"
            class:hidden={!showMenu}
            class:flex={showMenu}>
            {#each links as { label, href, icon }}
                <a
                    class="text-white-500 flex hover:text-abidan-300 "
                    use:inertia
                    {href}>
                    <svelte:component this={icon} class="mx-2 h-6 w-6" />
                    {label}
                </a>
            {/each}
        </div>
    </div>
</nav>
