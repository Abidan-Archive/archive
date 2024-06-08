<script>
    import Oddment from '@/lib/oddment.js';
    import cn from '@/lib/cn';
    import route from '@/lib/route';
    import { Link } from '@/components/icons';
    import { addToast } from '@/stores/toast';
    import { inertia } from '@inertiajs/svelte';

    const copyVerbs = new Oddment({
        copied: 20,
        'violently thrown': 5,
        'gently placed': 5,
    });

    export let event;

    const formattedDate = new Date(event.date).toLocaleDateString('en-US');
    async function copyLinkClicked() {
        await navigator.clipboard.writeText(route('event.show', event));
        addToast({
            message: `Event link ${copyVerbs.pick()} into your clipboard.`,
        });
    }
    let className = '';
    export { className as class };
</script>

<article
    id={event.id}
    class={cn('md-shadow rounded-lg bg-base-700 p-4', className)}>
    <section class="mb-4 flex justify-between">
        <div>
            <h3>
                <a
                    class="font-bold hover:underline"
                    use:inertia
                    href={route('event.show', event)}
                    >#{event.id}
                    &middot; <span>{event.name}</span>
                    &middot; <span>{formattedDate}</span>
                </a>
            </h3>
        </div>
        <div class="flex items-center text-sm text-typo-600">
            <button
                class="flex hover:underline hover:underline-offset-4"
                on:click|stopPropagation={copyLinkClicked}>
                <Link class="inline" />
                <span class="pl-1">Link</span>
            </button>
        </div>
    </section>
    <section class="flex justify-between">
        <span class="hidden text-sm text-typo-600 sm:flex">
            Source: {event.location || 'Dubious Forums'}
        </span>
        <span class="text-sm text-typo-600">
            {event.reports || 0} reports
        </span>
    </section>
</article>
