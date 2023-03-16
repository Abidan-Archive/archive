<script>
    import { inertia } from '@inertiajs/svelte';
    import Logo from '@components/Logo.svelte';
    import route from '@/route';
</script>

<nav
    class="fixed top-0 left-0 z-50 h-16 w-full border-l-5 border-abidan-400 bg-midnight-500 text-white drop-shadow-md">
    <div class="px-4 sm:px-6">
        <div class="flex h-16 w-full justify-between">
            <!-- svelte-ignore missing-declaration -->
            <a use:inertia href={route('home')}>
                <div class="flex h-full flex-row items-center justify-start">
                    <Logo />
                    <h1 class="shrink-0 px-2 text-3xl">Abidan Archive</h1>
                </div>
            </a>
        </div>
    </div>
</nav>
