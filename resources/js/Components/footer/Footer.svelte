<script>
    import FooterIcon from '@components/Footer/FooterIcon.svelte';
    import {
        Reddit,
        Facebook,
        Instagram,
        Twitter,
        Github,
    } from '@components/icons';
    const links = {
        facebook: 'https://www.facebook.com/willwightauthor',
        twitter: 'https://twitter.com/williamwight',
        reddit: 'https://www.reddit.com/r/Iteration110Cradle',
        github: 'https://github.com/Abidan-Archive/archive',
        instagram: 'https://www.instagram.com/willwight110',
    };
</script>

<footer
    {...$$restProps}
    class={`bg-white p-4 dark:bg-gray-800 sm:p-6 ${$$props.class}`}>
    <div class="sm:flex sm:items-center sm:justify-between">
        <span
            class="block text-sm text-gray-500 dark:text-gray-400 sm:text-center">
            Made and maintained by Telariel, the Spider
        </span>
        <div class="mt-4 flex space-x-6 sm:mt-0 sm:justify-center">
            <FooterIcon
                href={links['facebook']}
                target="_blank"
                rel="noreferrer"
                class="hover:text-abidan-30  text-gray-400">
                <Facebook />
            </FooterIcon>
            <FooterIcon
                href={links['instagram']}
                target="_blank"
                rel="noreferrer"
                class="hover:text-abidan-30  text-gray-400">
                <Instagram />
            </FooterIcon>
            <FooterIcon
                href={links['twitter']}
                target="_blank"
                rel="noreferrer"
                class="hover:text-abidan-30  text-gray-400">
                <Twitter />
            </FooterIcon>
            <FooterIcon
                href={links['reddit']}
                target="_blank"
                rel="noreferrer"
                class="hover:text-abidan-30  text-gray-400">
                <Reddit />
            </FooterIcon>
            <FooterIcon
                href={links['github']}
                target="_blank"
                rel="noreferrer"
                class="hover:text-abidan-30  text-gray-400">
                <Github />
            </FooterIcon>
        </div>
    </div>
</footer>
