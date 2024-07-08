<script>
    import { page } from '@inertiajs/svelte';
    import Page from '@/components/Page.svelte';
    import Paginator from '@/components/Paginator.svelte';
    import Report from '@/components/Report.svelte';
    import route from '@/lib/route';

    export let tag;
    export let reports;

    const edit =
        ['admin', 'moderator'].some((role) =>
            $page.props.auth.user?.roles.includes(role)
        ) && route('tag.edit', tag);
</script>

<Page header={`Tag - ${tag.name} (${reports.total})`} {edit}>
    <div class="flex flex-col gap-5">
        {#if !reports.data.length}
            Yikes, this tag has no reports.
        {:else}
            <Paginator {...reports} />
            {#each reports.data as report}
                <Report {report} />
            {/each}
            <Paginator {...reports} />
        {/if}
    </div>
</Page>
