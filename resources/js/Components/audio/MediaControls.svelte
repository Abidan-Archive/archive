<script>
    import { createEventDispatcher } from 'svelte';
    import {
        PlayPause,
        Previous,
        Next,
        Forward,
        Backward,
        Scissors,
        MagnifyingGlassPlus,
        MagnifyingGlassMinus,
    } from '@/Components/icons';
    import { IconButton } from '@/Components/forms';

    let dispatch = createEventDispatcher();

    const controls = [
        {
            title: 'seek backward 5 seconds',
            event: ['seek', -5],
            icon: Backward,
        },
        {
            title: 'seek backward 1 seconds',
            event: ['seek', -1],
            icon: Previous,
        },
        {
            title: 'toggle play pause',
            event: ['playpause'],
            icon: PlayPause,
            variant: { large: true },
        },
        {
            title: 'seek forward 1 seconds',
            event: ['seek', 1],
            icon: Next,
        },
        {
            title: 'seek forward 5 seconds',
            event: ['seek', 5],
            icon: Forward,
        },
    ];
</script>

<div class="flex w-full justify-between">
    <div class="w-1/6" />
    <div class="align-center flex justify-center gap-2">
        {#each controls as control}
            <IconButton
                title={control.title}
                on:click={() => dispatch(...control.event)}
                {...control.variant}>
                <svelte:component this={control.icon} />
            </IconButton>
        {/each}
    </div>
    <div class="w-1/6 self-center justify-self-end text-right">
        <button
            title="create a segment"
            class="hover:text-typo-600"
            on:click={() => dispatch('zoom', false)}>
            <MagnifyingGlassMinus />
        </button>
        <button
            title="create a segment"
            class="hover:text-typo-600"
            on:click={() => dispatch('zoom', true)}>
            <MagnifyingGlassPlus />
        </button>
    </div>
</div>
<div class="align-center flex justify-center gap-2">
    <IconButton
        title="create a segment"
        on:click={() => dispatch('clip')}
        large>
        <Scissors />
    </IconButton>
</div>
