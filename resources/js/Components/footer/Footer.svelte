<script>
    import { inertia } from '@inertiajs/svelte';
    import route from '@/lib/route';
    import FooterIcon from '@/components/footer/FooterIcon.svelte';
    import {
        Reddit,
        Facebook,
        Twitter,
        Github,
        Gnome,
    } from '@/components/icons';

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

<div {...$$restProps} class={`w-full bg-surface-500 p-5 ${$$props.class}`}>
    <div class="sm:mx-auto sm:flex sm:items-center sm:justify-between">
        <nav
            class="flex items-center justify-between gap-2 text-sm text-on-surface-token sm:text-center">
            <FooterIcon href="https://github.com/Abidan-Archive/archive">
                <Github class="inline" />
            </FooterIcon>
            <a use:inertia href={route('about')} class="hover:underline"
                >About Site</a>
        </nav>
        <nav class="mt-4 flex space-x-6 sm:mt-0 sm:justify-center">
            {#each links as link}
                <FooterIcon href={link.href}>
                    <svelte:component this={link.icon} />
                </FooterIcon>
            {/each}
        </nav>
    </div>
</div>
