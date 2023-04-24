<script>
    import clsx from 'clsx';
    import route from '@/route';
    import { Link } from '@components/icons';
    import { inertia } from '@inertiajs/svelte';
    export let event;

    const formattedDate = new Date(event.date).toLocaleDateString('en-US');
    async function linkClicked() {
        await navigator.clipboard.writeText(route('event.show', event));
    }
</script>

<article
    id={event.id}
    class={clsx('md-shadow bg-midnight-700 rounded-lg p-4', $$props.class)}>
    <section class="flex justify-between mb-4">
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
        <div class="flex items-center text-sm text-enkai-600">
            <button
                class="flex hover:underline hover:underline-offset-4"
                on:click={linkClicked}>
                <Link class="inline" />
                <span class="pl-1">Link</span>
            </button>
        </div>
    </section>
    <section class="flex justify-between">
        <span class="text-sm text-enkai-600 sm:flex hidden">
            Source: {event.location || "Dubious Forums"}
        </span>
        <span class="text-sm text-enkai-600">
            {event.reports.length || 0} reports
        </span> 
    </section>
    
</article>
