<script>
    import { inertia } from '@inertiajs/svelte';
    import Logo from '@components/Logo.svelte';
    import {
        Globe,
        House,
        Calendar,
        Discord,
        Hamburger,
    } from '@components/icons';
    const links = {
        discord: 'https://discord.gg/tCg94qy',
        wiki: 'https://wiki.abidanarchive.com',
    };
    let showMenu = false;

    function toggleNavbar() {
        showMenu = !showMenu;
    }
</script>

<nav class="fixed top-0 left-0 z-50 h-16 w-full bg-gray-800">
    <div
        class="mx-6 px-4 md:px-0 md:flex md:items-center md:justify-between py-2 md:py-1">
        <div class="flex items-center justify-between">
            <a use:inertia href={route('home')}>
                <div class="flex h-full flex-row items-center justify-start">
                    <Logo/>
                    <h1 class="shrink-0 px-2 text-2xl">Abidan Archive</h1>
                </div>
            </a>
            <!-- Mobile menu button -->
            <!-- svelte-ignore a11y-click-events-have-key-events -->
            <div on:click={toggleNavbar} class="flex md:hidden">
                <button
                    type="button"
                    class="bg-gray-800 text-white rounded-lg md:hidden p-2 focus:outline-none focus:ring-2 hover:bg-gray-700 focus:ring-gray-600 focus:bg-gray-800 active:bg-gray-800">
                    <Hamburger />
                </button>
            </div>
        </div>

        <!-- Mobile Menu open: "block", Menu closed: "hidden" -->
        <div
            class="rounded sm:border-gray-600 sm:border md:border-0 bg-gray-800 py-4 px-8 sm:mt-4 flex-col mt-6 space-y-6 md:mt-0 md:flex md:flex-row md:items-center md:space-y-0 md:space-x-6 lg:space-x-10"
            class:hidden={!showMenu}
            class:flex={showMenu}>
            <a
                class="text-white-500 flex hover:text-abidan-300 "
                use:inertia
                href={route('home')}>
                <div class="px-2">
                    <House class="w-6 h-6"/>
                </div>
                Home
            </a>
            <a
                class="text-white-500 flex hover:text-abidan-300"
                use:inertia
                href="/">
                <div class="px-2">
                    <Calendar class="w-6 h-6"/>
                </div>
                Recent
            </a>
            <a
                class="text-white-400 flex hover:text-abidan-300"
                href={links['wiki']}
                target="_blank" 
                rel="noreferrer">
                <div class="px-2">
                    <Globe class="w-6 h-6"/>
                </div>
                Wiki
            </a>
            <a
                class="text-white-400 flex hover:text-abidan-300"
                href={links['discord']}
                target="_blank" 
                rel="noreferrer">
                <div class="px-2">
                    <Discord class="w-6 h-6"/>
                </div>
                Discord
            </a>
        </div>
    </div>
</nav>
