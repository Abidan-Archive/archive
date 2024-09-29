<script>
    import { page } from '@inertiajs/svelte';
    import route from '@/lib/route';
    import Page from '@/Components/Page.svelte';
    import Paginator from '@/Components/Paginator.svelte';
    import Report from '@/Components/Report.svelte';
    import isValidUrl from '@/lib/url';

    export let event;
    export let reports;

    const edit =
        ['admin', 'moderator'].some((role) =>
            $page.props.auth.user?.roles.includes(role)
        ) && route('event.edit', event);
</script>

<Page header={event.name} {edit}>
    <div class="mb-8 grid w-full grid-cols-3">
        <dl>
            <dt class="font-semibold">Date:</dt>
            <dd>{new Date(event.date).toDateString()}</dd>
        </dl>
        <dl>
            <dt class="font-semibold">Reports:</dt>
            <dd>{reports.total}</dd>
        </dl>
        <dl>
            <dt class="font-semibold">Source:</dt>
            <dd>
                {#if event.location && isValidUrl(event.location)}
                    <a href={event.location} target="_blank">
                        {event.location}
                    </a>
                {:else}
                    {event.location || 'n/a'}
                {/if}
            </dd>
        </dl>
    </div>
    <div class="flex flex-col items-center gap-5">
        {#if !reports.data.length}
            No reports within this Event.<br />
            How very very mysterious, yes.
        {:else}
            <Paginator {...reports} />
            {#each reports.data as report}
                <Report {report} withEvent={false} />
            {/each}
            <Paginator {...reports} />
        {/if}
    </div>
</Page>
