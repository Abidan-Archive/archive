<script>
    import { cn, route } from '@/Utils';
    import { Copy, Heart, Link } from '@/Components/icons';
    // import { fade } from 'svelte/transition';
    import { inertia, router } from '@inertiajs/svelte';
    import { addToast } from '@/Stores/toast';
    import Oddment from '@/oddment.js';

    let className;
    export { className as class };
    export let withEvent = true;
    export let report;

    const formattedDate = new Date(report.date).toLocaleDateString('en-US');
    const verbs = new Oddment({
        Murdered: 1,
        Removed: 20,
        Dismissed: 5,
        Tidied: 5,
        Vanished: 5,
    });

    async function linkClicked() {
        await navigator.clipboard.writeText(report.permalink);
    }
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
        addToast({ message: 'Report Copied into your clipboard' });
    }
    function likeClicked() {
        if (!report.is_liked) {
            router.post(route('like'), {
                likeable_type: 'App\\Models\\Report',
                id: report.id,
            });
            addToast({
                message: `Liked Report #${report.id}`,
                type: 'success',
            });
        } else {
            // router delete doesn't allow payload, so we're faking it with _method
            router.post(route('unlike'), {
                _method: 'DELETE',
                likeable_type: 'App\\Models\\Report',
                id: report.id,
            });

            addToast({
                message: `${verbs.pick()} Report #${report.id} from your likes`,
                type: 'success',
            });
        }
    }
</script>

<article
    id={report.id}
    class={cn(
        'rounded-lg border border-typo-500 bg-base-700 p-4 shadow-md',
        className
    )}>
    <div class="flex justify-between">
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
        <div class="flex items-center gap-2 text-sm text-typo-600">
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
            <button class="flex" on:click={likeClicked}>
                <Heart variant={report.is_liked ? 'filled' : 'outline'} />
                <span class="pl-1 text-base">{report.likes_count}</span>
            </button>
        </div>
    </div>
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
