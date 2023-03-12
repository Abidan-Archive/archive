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

<div class="fixed top-0 left-0 z-50 h-auto w-full bg-gray-800">
    <nav
        class="container mx-auto px-6 py-6 md:flex md:items-center md:justify-between">
        <div class="flex items-center justify-between">
            <a use:inertia href={route('home')}>
                <div class="flex h-full flex-row items-center justify-start">
                    <Logo />
                    <h1 class="shrink-0 px-2 text-3xl">Abidan Archive</h1>
                </div>
            </a>
            <!-- Mobile menu button -->
            <!-- svelte-ignore a11y-click-events-have-key-events -->
            <div on:click={toggleNavbar} class="flex md:hidden">
                <button
                    type="button"
                    class="text-white-100 hover:text-white-400 focus:text-white-400 focus:outline-none">
                    <Hamburger />
                </button>
            </div>
        </div>

        <!-- Mobile Menu open: "block", Menu closed: "hidden" -->
        <div
            class="mt-8 flex-col space-y-4 md:mt-0 md:flex md:flex-row md:items-center md:space-y-0 md:space-x-6 lg:space-x-10 lg:text-xl"
            class:hidden={!showMenu}
            class:flex={showMenu}>
            <a
                class="text-white-500 flex hover:text-abidan-300 "
                use:inertia
                href={route('home')}>
                <div class="px-2">
                    <House />
                </div>
                Home
            </a>
            <a
                class="text-white-500 flex hover:text-abidan-300"
                use:inertia
                href="/">
                <div class="px-2">
                    <Calendar />
                </div>
                Recent
            </a>
            <!-- 
        <a class="text-white-500 hover:text-abidan-300" use:inertia href={route("recent")}>Recent</a>
        <a class="text-white-500 hover:text-abidan-300" use:inertia href={route("tags")}>Tags</a> -->

            <a
                class="text-white-400 flex hover:text-abidan-300"
                href={links['wiki']}>
                <div class="px-2">
                    <Globe />
                </div>
                Wiki
            </a>

            <a
                class="text-white-400 flex hover:text-abidan-300"
                href={links['discord']}>
                <div class="px-2">
                    <Discord />
                </div>
                Discord
            </a>
        </div>
    </nav>
</div>
