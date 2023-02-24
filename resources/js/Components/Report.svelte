<script> import { fade } from 'svelte/transition';
    import { inertia } from "@inertiajs/svelte";
    import clsx from 'clsx';
    import Heart from '@components/icons/Heart.svelte';
    import Copy from '@components/icons/Copy.svelte';

    export let report;
    function likeClicked() {
        console.log('I like that.');
    }

    async function copyClicked() {
        let out = report.dialogues.reduce((acc, {speaker, line}) => acc+speaker+'\n'+line+'\n\n', '');
        if (!!report.footnote) out += `Footnote: ${report.footnote}\n\n`;
        out += report.permalink;

        await navigator.clipboard.writeText(out);
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
        <div class="flex gap-1 items-center text-sm hover:underline-offset-4 hover:underline">
            <button class="flex" on:click={copyClicked} >
                <Copy class="inline" />
                <span class="pl-1">Copy</span>
            </button>
            <button
                class="flex hidden"
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
