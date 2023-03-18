<script>
    import FooterIcon from '@components/Footer/FooterIcon.svelte';
    import {
        Reddit,
        Facebook,
        Instagram,
        Twitter,
        Github,
        Gnome,
    } from '@components/icons';
    const links = {
        facebook: 'https://www.facebook.com/willwightauthor',
        twitter: 'https://twitter.com/williamwight',
        reddit: 'https://www.reddit.com/r/Iteration110Cradle',
        github: 'https://github.com/Abidan-Archive/archive',
        instagram: 'https://www.instagram.com/willwight110',
        willBlog: 'https://www.willwight.com',
    };
</script>

<footer {...$$restProps} class={`bg-gray-800 p-4 sm:p-6 ${$$props.class}`}>
    <div class="sm:mx-auto sm:flex sm:items-center sm:justify-between">
        <span class="block text-sm text-gray-400 sm:text-center">
            Made and maintained by Telariel, the Spider
        </span>
        <div class="mt-4 flex space-x-6 sm:mt-0 sm:justify-center">
            <FooterIcon
                href={links['willBlog']}
                target="_blank"
                rel="noreferrer"
                class="text-gray-400  hover:text-abidan-400">
                <Gnome />
            </FooterIcon>
            <FooterIcon
                href={links['reddit']}
                target="_blank"
                rel="noreferrer"
                class="text-gray-400  hover:text-abidan-400">
                <Reddit />
            </FooterIcon>
            <FooterIcon
                href={links['facebook']}
                target="_blank"
                rel="noreferrer"
                class="text-gray-400  hover:text-abidan-400">
                <Facebook />
            </FooterIcon>
            <FooterIcon
                href={links['instagram']}
                target="_blank"
                rel="noreferrer"
                class="text-gray-400  hover:text-abidan-400">
                <Instagram />
            </FooterIcon>
            <FooterIcon
                href={links['twitter']}
                target="_blank"
                rel="noreferrer"
                class="text-gray-400  hover:text-abidan-400">
                <Twitter />
            </FooterIcon>
            <FooterIcon
                href={links['github']}
                target="_blank"
                rel="noreferrer"
                class="text-gray-400  hover:text-abidan-400">
                <Github />
            </FooterIcon>
        </div>
    </div>
</footer>
