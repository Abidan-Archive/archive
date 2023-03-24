<script>
    import FooterIcon from '@components/Footer/FooterIcon.svelte';
    import {
        Reddit,
        Facebook,
        Twitter,
        Github,
        Gnome,
    } from '@components/icons';
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

<footer {...$$restProps} class={`bg-gray-800 p-4 sm:p-6 ${$$props.class}`}>
    <div class="sm:mx-auto sm:flex sm:items-center sm:justify-between">
        <div class="text-sm text-gray-400 sm:text-center">
            This site is built and maintained by the Will Wight fanbase and community.
        </div>
        <div class="mt-4 flex space-x-6 sm:mt-0 sm:justify-center">
            {#each links as link}
                <FooterIcon href={link.href}>
                    <svelte:component this={link.icon} />
                </FooterIcon>
            {/each}
        </div>
    </div>
</footer>
