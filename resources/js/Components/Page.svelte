<script>
    import { inertia } from '@inertiajs/svelte';
    import cn from '@/lib/cn';

    export let header = '';
    let className = '';
    export { className as class };

    export let edit = undefined;
</script>

<section class={cn('container mx-auto my-4 h-full px-4', className)}>
    {#if $$slots.header || !!header}
        <slot name="header">
            {#if edit}
                <div class="flex items-baseline justify-between">
                    <h2 class="text-3xl">{header}</h2>
                    <a use:inertia href={edit} class="text-xl">Edit</a>
                </div>
            {:else}
                <h2 class="text-3xl">{header}</h2>
            {/if}
        </slot>
        <hr />
    {/if}
    <slot />
</section>
