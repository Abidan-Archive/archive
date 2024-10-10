<script>
    import cn from '@/lib/cn';

    let className = '';
    export { className as class };
    export let href = undefined;
    export let type = 'submit';

    export let themeDeg = '0deg';
</script>

<svelte:element
    this={href ? 'a' : 'button'}
    style={`--theme-deg: ${themeDeg}`}
    role={href ? 'link' : 'button'}
    type={href ? undefined : type}
    {href}
    class={cn('button-3d', className)}
    {...$$restProps}
    on:click
    on:change
    on:keydown
    on:keyup
    on:mouseenter
    on:mouseleave>
    <span class="shadow" />
    <span class="edge" />
    <span class="front">
        <slot />
    </span>
</svelte:element>

<style lang="postcss">
    .button-3d {
        @apply relative cursor-pointer rounded-sm bg-transparent;
        transition: filter 250ms;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
    }
    .button-3d:hover {
        filter: brightness(110%);
    }
    .button-3d:focus:not(:focus-visible) {
        outline: none;
    }
    .button-3d > .front {
        @apply relative block rounded-sm px-4 py-2;
        background-color: hsl(var(--theme-deg) 85% 65%);
        will-change: transform;
        transform: translateY(-4px);
        transition: transform 600ms cubic-bezier(0.3, 0.7, 0.4, 1);
    }
    .button-3d:hover > .front {
        transform: translateY(-6px);
        transition: transform 250ms cubic-bezier(0.3, 0.7, 0.4, 1.5);
    }
    .button-3d:active > .front {
        transform: translateY(-2px);
        transition: transform 34ms;
    }
    .button-3d > .edge {
        @apply absolute left-0 top-0 h-full w-full rounded-sm;
        background: linear-gradient(
            to left,
            hsl(var(--theme-deg) 85% 52%) 0%,
            hsl(var(--theme-deg) 85% 60%) 8%,
            hsl(var(--theme-deg) 85% 60%) 92%,
            hsl(var(--theme-deg) 85% 52%) 100%
        );
    }
    .button-3d > .shadow {
        @apply absolute left-0 top-0 h-full w-full rounded-sm;
        will-change: transform;
        background: hsl(0deg 0% 0% / 0.25);
        transform: translateY(2px);
        transition: transform 600ms cubic-bezier(0.3, 0.7, 0.4, 1);
        filter: blur(4px);
    }
    .button-3d:hover > .shadow {
        transform: translateY(4px);
        transition: transform 250ms cubic-bezier(0.3, 0.7, 0.4, 1.5);
    }
    .button-3d:active > .shadow {
        transform: translateY(1px);
        transition: transform 34ms;
    }
</style>
