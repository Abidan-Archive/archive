<script>
    import cn from '@/lib/cn';
    import { inertia, page } from '@inertiajs/svelte';
    import route from '@/lib/route';
    import {
        Calendar,
        Discord,
        Globe,
        House,
        Tag,
        Scale,
    } from '@/components/icons';
    import { getDrawerStore } from '@skeletonlabs/skeleton';

    // External links through use:inertia feels bad, see if we can get a change
    // https://github.com/inertiajs/inertia/issues/1719#issuecomment-1890295472

    let className = '';
    export { className as class };
    export let column = false;

    const drawerStore = getDrawerStore();

    $: links = [
        $page.props.auth?.user?.roles.includes('admin')
            ? {
                  label: 'Admin',
                  href: route('admin.index'),
                  icon: Scale,
              }
            : null,
        location.pathname !== '/'
            ? {
                  label: 'Home',
                  href: route('home'),
                  icon: House,
              }
            : null,
        {
            label: 'Events',
            href: route('event.index'),
            icon: Calendar,
        },
        {
            label: 'Tags',
            href: route('tag.index'),
            icon: Tag,
        },
        {
            label: 'Discord',
            href: 'https://discord.gg/tCg94qy',
            icon: Discord,
            external: true,
        },
        {
            label: 'Wiki',
            href: 'https://wiki.abidanarchive.com',
            icon: Globe,
            external: true,
        },
    ].filter(Boolean);

    function drawerClose() {
        drawerStore.close();
    }
</script>

<nav class={cn('list-nav m-0 p-0', className)}>
    <ul class={cn('flex items-baseline', column && 'flex-col items-start')}>
        {#each links as { label, href, icon, external = false }}
            <li>
                {#if !external}
                    <a
                        use:inertia
                        {href}
                        class="flex gap-2"
                        on:click={drawerClose}>
                        <svelte:component this={icon} class="h-6 w-6" />
                        {label}
                    </a>
                {:else}
                    <a {href} class="flex gap-2" target="_blank">
                        <svelte:component this={icon} class="h-6 w-6" />
                        {label}
                    </a>
                {/if}
            </li>
        {/each}
    </ul>
</nav>
