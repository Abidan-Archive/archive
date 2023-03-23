<script>
    import clsx from 'clsx';
    import route from '@/route';
    import { Copy, Heart, Link } from '@components/icons';
    // import { fade } from 'svelte/transition';
    import { inertia } from '@inertiajs/svelte';

    export let withEvent = true;
    export let report;

    const formattedDate = (new Date(report.date)).toLocaleDateString('en-US');

    async function copyClicked() {
        let out = `#${report.id}`;
        if (!!report.event) out += `- ${report.event.name}\n`;
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
    >
    <section class="flex justify-between">
        <div>
            <h3>
                <a
                    class="font-bold hover:underline"
                    use:inertia
                    href={route('report.show', report)}>#{report.id}</a>

                {#if withEvent}
                    &middot;
                    <a
                        class="hover:underline"
                        use:inertia
                        href={route('event.show', report.event.id)}>
                        {report.event.name}
                    </a>
                {/if}
                &middot; <span>{formattedDate}</span>
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
                <dd>{@html dialogue.line_html}</dd>
            </dl>
        {/each}
    </section>
    {#if !!report.footnote}
        <section>
            <h5>Footnote:</h5>
            <p>{report.footnote}</p>
        </section>
    {/if}
</article>
