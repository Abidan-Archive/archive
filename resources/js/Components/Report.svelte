<script>
    import { fade } from 'svelte/transition';
    import { inertia } from '@inertiajs/svelte';
    import clsx from 'clsx';
    import { Copy, Heart, Link } from '@components/icons';

    export let report;
    async function copyClicked() {
        let out = `#${report.id}\n`;
        out += report.dialogues.reduce(
            (acc, { speaker, line }) => acc + speaker + '\n' + line + '\n\n',
            ''
        );
        if (!!report.footnote) out += `Footnote: ${report.footnote}\n\n`;
        out += report.permalink;

        await navigator.clipboard.writeText(out);
    }
    async function linkClicked() {
        await navigator.clipboard.writeText(report.permalink);
    }
</script>

<article
    id={report.id}
    class={clsx(
        'border-muenkai md-shadow rounded-lg border bg-midnight-700 p-4',
        $$props.class
    )}
    transition:fade>
    <section class="flex justify-between">
        <div>
            <h3>
                <a
                    class="font-bold"
                    use:inertia
                    href={route('report.show', report)}>#{report.id}</a>
            </h3>
        </div>
        <div class="flex items-center gap-2 text-sm text-enkai-600">
            <button
                class="flex hover:underline hover:underline-offset-4"
                on:click={linkClicked}>
                <Link class="inline" />
                <span class="pl-1">Link</span>
            </button>
            <button
                class="flex hover:underline hover:underline-offset-4"
                on:click={copyClicked}>
                <Copy class="inline" />
                <span class="pl-1">Copy</span>
            </button>
            <!-- <button class="flex hidden" > -->
            <!--     <Heart variant='outline' /> -->
            <!--     <span class="pl-1 text-base">{report.likes}</span> -->
            <!-- </button> -->
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
    <section />
</article>
