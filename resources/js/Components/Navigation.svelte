<script>
    import { inertia } from "@inertiajs/svelte";
    import Logo from "@components/Logo.svelte";
</script>

<nav
    class="fixed z-50 top-0 left-0 h-16 w-full bg-midnight border-l-5 border-abidan-400 text-white drop-shadow-md"
>
    <div class="px-4 sm:px-6">
        <div class="flex justify-between h-16 w-full">
            <a use:inertia href={route("home")}>
                <div class="flex flex-row h-full justify-start items-center">
                    <Logo />
                    <h1 class="shrink-0 text-3xl px-2">Abidan Archive</h1>
                </div>
            </a>
        </div>
    </div>
</nav>
