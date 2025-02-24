<script>
    import { inertia, router } from '@inertiajs/svelte';
    import { getToastStore } from '@skeletonlabs/skeleton';
    import { Copy, Heart, Link } from '@/Components/icons';
    import Tag from '@/Components/Tag.svelte';
    import { cn, route, Oddment } from '@/lib';

    const toastStore = getToastStore();

    /**
     * @typedef {Object} Props
     * @property {string} [class]
     * @property {boolean} [withEvent]
     * @property {any} report
     */

    /** @type {Props} */
    let { class: className = '', withEvent = true, report } = $props();

    const formattedDate = $derived(
        new Date(report.date).toLocaleDateString('en-US')
    );
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
            router.post(
                route('like'),
                {
                    likeable_type: 'App\\Models\\Report',
                    id: report.id,
                    onSuccess: () =>
                        toastStore.trigger({
                            message: `${likedVerbs.pick()} Report #${report.id}`,
                        }),
                },
                { preserveScroll: true }
            );
        } else {
            // router delete doesn't allow payload, so we're faking it with _method
            router.post(
                route('unlike'),
                {
                    _method: 'DELETE',
                    likeable_type: 'App\\Models\\Report',
                    id: report.id,
                    onSuccess: () =>
                        toastStore.trigger({
                            message: `Report #${
                                report.id
                            } ${removeVerbs.pick()} from your likes`,
                        }),
                },
                { preserveScroll: true }
            );
        }
    }
</script>

{#snippet event()}
    <a
        class="hover:underline"
        use:inertia
        href={route('event.show', report.event.id)}>
        {report.event.name}
    </a>
{/snippet}
{#snippet date()}
    {formattedDate}
{/snippet}
<article id={report.id} class={cn('card flex flex-col gap-4', className)}>
    <section class="flex items-start justify-between gap-2">
        <div class="md:gap-none flex flex-col gap-2 md:flex-row">
            <h3>
                <a
                    class="font-bold hover:underline"
                    use:inertia
                    href={route('report.show', report)}>
                    #{report.id}
                </a>
            </h3>
            {#if withEvent}
                <span class="hidden md:block"> &middot; </span>
                {@render event()}
            {/if}
            <span class="hidden md:block">&middot;</span>
            {@render date()}
        </div>
        <div
            class="flex flex-col items-start gap-2 text-sm text-typo-600 sm:flex-row sm:items-center">
            <button
                class="flex hover:underline hover:underline-offset-4"
                onclick={linkClicked}>
                <Link class="inline" />
                <span class="pl-1">Link</span>
            </button>
            <button
                class="flex hover:underline hover:underline-offset-4"
                onclick={copyClicked}>
                <Copy class="inline" />
                <span class="pl-1">Copy</span>
            </button>
            <button class="flex" onclick={likeClicked}>
                <Heart
                    variant={report.is_liked ? 'filled' : 'outline'}
                    class={cn(report.is_liked && 'fill-error-500')} />
                <span class="pl-1 text-base">{report.likes_count}</span>
            </button>
        </div>
    </section>
    <section class="flex flex-col gap-4 md:pb-4">
        {#if !report.dialogues.length}
            <div class="text-center">
                <p>Nothing was said.</p>
            </div>
        {:else}
            {#each report.dialogues as dialogue}
                <dl>
                    <dt class="text-lg font-bold">{dialogue.speaker}</dt>
                    <dd class="whitespace-pre-line">
                        <!-- eslint-disable-next-line svelte/no-at-html-tags -->
                        {@html dialogue.line_html.trim()}
                    </dd>
                </dl>
            {/each}
        {/if}
    </section>
    <div class="flex flex-col justify-between gap-4 md:flex-row">
        <section>
            {#if !!report.footnote}
                <h5 class="pb-2 font-bold">Footnote:</h5>
                <p>{report.footnote}</p>
            {/if}
        </section>
        <section class="block items-end md:flex">
            {#if !!report.tags?.length}
                <h5 class="pb-2 text-left font-bold md:hidden md:text-right">
                    Tags:
                </h5>
            {/if}
            <div
                class="flex flex-wrap items-center justify-start gap-2 md:flex-nowrap md:justify-end">
                {#each report.tags || [] as tag}
                    <Tag {tag} />
                {/each}
            </div>
        </section>
    </div>
</article>
