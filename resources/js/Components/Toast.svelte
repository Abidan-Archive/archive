<script>
    import { createEventDispatcher } from 'svelte';
    import { fade } from 'svelte/transition';
    import { cva } from 'class-variance-authority';
    import {
        CircleCheck,
        CircleExclamation,
        CircleInfo,
    } from '@/components/icons';
    import cn from '@/utils/cn.js';
    import XMark from '@/components/icons/XMark.Svelte';

    const dispatch = createEventDispatcher();

    export let type = 'info';
    export let dismissible = true;

    let className;
    export { className as class };

    const variants = cva(
        'color-typo-500 flex items-center mx-auto mb-2 text-sm font-medium',
        {
            variants: {
                type: {
                    error: 'bg-red-500',
                    success: 'bg-green-500',
                    info: 'bg-blue-500',
                },
            },
        }
    );
    const iconMap = {
        success: CircleCheck,
        error: CircleExclamation,
        info: CircleInfo,
    };
</script>

<article class={cn(variants({ type }), className)} role="alert" transition:fade>
    <svelte:component this={iconMap[type] || CircleInfo} class="w-4" />
    <div class="ml-4">
        <slot />
    </div>
    {#if dismissible}
        <button
            class="m-0 ml-auto border-none bg-transparent p-0"
            on:click={() => dispatch('dismiss')}>
            <XMark />
        </button>
    {/if}
</article>
