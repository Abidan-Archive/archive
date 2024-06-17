<script>
    import { Copy, Heart, Link } from '@/components/icons';
    import { cn, route, Oddment } from '@/lib';
    import { inertia, router } from '@inertiajs/svelte';
    import Tag from '@/components/Tag.svelte';
    import { getToastStore } from '@skeletonlabs/skeleton';

    const toastStore = getToastStore();

    let className = '';
    export { className as class };
    export let withEvent = true;
    export let report;

    const formattedDate = new Date(report.date).toLocaleDateString('en-US');
    const removeVerbs = new Oddment({
        murdered: 1,
        removed: 30,
        dismissed: 5,
        tidied: 5,
        vanished: 5,
        'mysteriously disappeared': 1,
    });
    const copyVerbs = new Oddment({
        absorbed: 5,
        burgled: 5,
        copied: 30,
        plagiarized: 5,
        'lured with a small piece of cheese': 1,
        yoinked: 1,
    });
    const likedVerbs = new Oddment({
        Liked: 20,
        Licked: 1,
    });

    async function linkClicked() {
        await navigator.clipboard.writeText(report.permalink);
        toastStore.trigger({
            message: `Report permalink ${copyVerbs.pick()} into your clipboard`,
        });
    }
    async function copyClicked() {
        let out = `#${report.id}`;
        if (report.event) out += `- ${report.event.name}\n`;
        out += report.dialogues.reduce(
            (acc, { speaker, line }) => `${acc}${speaker}\n\n${line}'\n\n`,
            ''
        );
        if (report.footnote) out += `Footnote: ${report.footnote}\n\n`;
        out += report.permalink;

        await navigator.clipboard.writeText(out);
        toastStore.trigger({
            message: `Report ${copyVerbs.pick()} into your clipboard`,
        });
    }
    function likeClicked() {
        if (!report.is_liked) {
            router.post(route('like'), {
                likeable_type: 'App\\Models\\Report',
                id: report.id,
            });
            toastStore.trigger({
                message: `${likedVerbs.pick()} Report #${report.id}`,
            });
        } else {
            // router delete doesn't allow payload, so we're faking it with _method
            router.post(route('unlike'), {
                _method: 'DELETE',
                likeable_type: 'App\\Models\\Report',
                id: report.id,
            });

            toastStore.trigger({
                message: `Report #${
                    report.id
                } ${removeVerbs.pick()} from your likes`,
            });
        }
    }
</script>

<article
    id={report.id}
    class={cn(
        'rounded-lg border border-surface-400 bg-base-700 p-4 shadow-md',
        className
    )}>
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
                <Heart
                    variant={report.is_liked ? 'filled' : 'outline'}
                    class={cn(report.is_liked && 'fill-error-500')} />
                <span class="pl-1 text-base">{report.likes_count}</span>
            </button>
        </div>
    </section>
    <section class="my-5">
        {#each report.dialogues as dialogue}
            <dl class="mb-2">
                <dt class="text-lg font-bold">{dialogue.speaker}</dt>
                <!-- eslint-disable-next-line svelte/no-at-html-tags -->
                <dd class="whitespace-pre-line">{@html dialogue.line_html}</dd>
            </dl>
        {/each}
    </section>
    <div class="flex justify-between">
        <section>
            {#if !!report.footnote}
                <h5>Footnote:</h5>
                <p>{report.footnote}</p>
            {/if}
        </section>
        <section class="flex items-end gap-2">
            {#each report.tags || [] as tag}
                <Tag {tag} />
            {/each}
        </section>
    </div>
</article>
