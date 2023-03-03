<script>
    import { inertia } from "@inertiajs/svelte";
    import Logo from "@components/Logo.svelte";
    import {Globe, House, Calendar, Discord, Hamburger} from "@components/icons";
    const links = {
        discord: "https://discord.gg/tCg94qy",
        wiki: "https://wiki.abidanarchive.com",
    };
    let showMenu = false;

    function toggleNavbar() {
        showMenu = !showMenu;
    }
</script>

<div>
    <div class="fixed top-0 left-0 w-full h-auto z-50 bg-gray-800">
        <nav
            class="container px-6 py-6 mx-auto md:flex md:justify-between md:items-center"
        >
            <div class="flex items-center justify-between">
                <a use:inertia href={route("home")}>
                    <div
                        class="flex flex-row h-full justify-start items-center"
                    >
                        <Logo />
                        <h1 class="shrink-0 text-3xl px-2">Abidan Archive</h1>
                    </div>
                </a>
                <!-- Mobile menu button -->
                <!-- svelte-ignore a11y-click-events-have-key-events -->
                <div on:click={toggleNavbar} class="flex md:hidden">
                    <button
                        type="button"
                        class="text-white-100 hover:text-white-400 focus:outline-none focus:text-white-400"
                    >
                        <Hamburger/>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu open: "block", Menu closed: "hidden" -->
            <div
                class="flex-col mt-8 lg:text-xl space-y-4 md:flex md:space-y-0 md:flex-row md:items-center md:space-x-6 lg:space-x-10 md:mt-0" class:hidden={!showMenu}
                class:flex={showMenu}
            >
                <a
                    class="flex text-white-500 hover:text-abidan-300 "
                    use:inertia
                    href={route("home")}
                >
                    <div class="px-2">
                        <House/>
                    </div>
                    Home
                </a>
                <a
                    class="flex text-white-500 hover:text-abidan-300"
                    use:inertia
                    href="/"
                >
                    <div class="px-2">
                        <Calendar/>
                    </div>
                    Recent
                </a>
                <!-- 
        <a class="text-white-500 hover:text-abidan-300" use:inertia href={route("recent")}>Recent</a>
        <a class="text-white-500 hover:text-abidan-300" use:inertia href={route("tags")}>Tags</a> -->

                <a
                    class="flex text-white-400 hover:text-abidan-300"
                    href={links["wiki"]}
                >
                    <div class="px-2">
                        <Globe/>
                    </div>
                    Wiki
                </a>

                <a
                    class="flex text-white-400 hover:text-abidan-300"
                    href={links["discord"]}
                >
                    <div class="px-2">
                        <Discord/>
                    </div>
                    Discord
                </a>
            </div>
        </nav>
    </div>
</div>