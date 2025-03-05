<script>
    import Page from '@/Components/Page.svelte';
    import Paginator from '@/Components/Paginator.svelte';
    import Report from '@/Components/Report.svelte';

    let { user, likes } = $props();
</script>

<Page header={`User - ${user.username}`}>
    <div class="mb-8 grid w-full grid-cols-3">
        <dl>
            <dt class="font-semibold">Joined:</dt>
            <dd>{new Date(user.created_at).toDateString()}</dd>
        </dl>
        <dl>
            <dt class="font-semibold">Likes:</dt>
            <dd>{likes.data.length}</dd>
        </dl>
        <dl>
            <!-- <dt class="font-semibold">Points:</dt> -->
            <!-- <dd>0</dd> -->
        </dl>
    </div>
    {#if likes.data.length > 0}
        <h3 class="text-2xl">Likes</h3>
        <hr />
        <div class="flex flex-col gap-5">
            {#each likes.data as report}
                <Report {report} />
            {/each}
            <Paginator {...likes} />
        </div>
    {/if}
</Page>
