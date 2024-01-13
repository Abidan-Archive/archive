<script>
    import { inertia } from '@inertiajs/svelte';
    import route from '@/Utils/route';
    import FooterIcon from '@/Components/footer/FooterIcon.svelte';
    import {
        Reddit,
        Facebook,
        Twitter,
        Github,
        Gnome,
    } from '@/Components/icons';
    const links = [
        {
            icon: Gnome,
            href: 'https://www.willwight.com',
        },
        {
            icon: Reddit,
            href: 'https://www.reddit.com/r/Iteration110Cradle',
        },
        {
            icon: Facebook,
            href: 'https://www.facebook.com/willwightauthor',
        },
        {
            icon: Twitter,
            href: 'https://twitter.com/williamwight',
        },
    ];
</script>

<footer
    {...$$restProps}
    class={`mt-10 w-full bg-gray-800 p-5 ${$$props.class}`}>
    <div class="sm:mx-auto sm:flex sm:items-center sm:justify-between">
        <nav
            class="flex items-baseline justify-between gap-2 text-sm text-gray-400 sm:text-center">
            <a use:inertia href={route('about')} class="hover:underline"
                >About Site</a>
            <FooterIcon href="https://github.com/Abidan-Archive/archive">
                <Github class="inline" />
            </FooterIcon>
        </nav>
        <nav class="mt-4 flex space-x-6 sm:mt-0 sm:justify-center">
            {#each links as link}
                <FooterIcon href={link.href}>
                    <svelte:component this={link.icon} />
                </FooterIcon>
            {/each}
        </nav>
    </div>
</footer>
