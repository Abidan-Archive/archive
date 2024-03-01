<script>
    import Page from '@/components/Page.svelte';
    import Paginator from '@/components/Paginator.svelte';
    import Report from '@/components/Report.svelte';

    export let event;
    export let reports;
</script>

<Page header={event.name}>
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
            <dt class="font-semibold">Location:</dt>
            <dd>{event.location || 'n/a'}</dd>
        </dl>
    </div>
    <div class="flex flex-col gap-5">
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
