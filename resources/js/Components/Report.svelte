<script>
    import { fade } from 'svelte/transition';
    import { inertia } from "@inertiajs/svelte";
    import clsx from 'clsx';
    import Heart from '@components/icons/Heart.svelte';
    import Copy from '@components/icons/Copy.svelte';

    export let report;
    function likeClicked() {
        console.log('I like that.');
    }
    function copyClicked() {
        console.log('I copied? that.');
    }
</script>

<article
    id={report.id}
    class={clsx(
        'border border-white rounded-lg p-2 md-shadow',
        $$props.class
    )}
    transition:fade>
    <section class="flex justify-between">
        <div>
            <a use:inertia href={route('report.show', report)}>#{report.id}</a>
        </div>
        <div class="flex gap-1 items-center text-sm">
            <button class="flex" on:click={copyClicked} >
                <Copy class="inline" />
                <span class="pl-1">Copy</span>
            </button>
            <button
                class="flex"
                on:click={likeClicked}
            >
                <Heart variant='outline' />
                <span class="pl-1 text-base">{report.likes}</span>
            </button>
        </div>
    </section>
    <section class="my-5">
        {#each report.dialogues as dialogue}
            <dl class="mb-2">
                <dt class="text-lg font-bold">{dialogue.speaker}</dt>
                <dd>{dialogue.line}</dd>
            </dl>
        {/each}
    </section>
    <section>
    </section>
</article>
