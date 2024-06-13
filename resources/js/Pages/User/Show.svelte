<script>
    import Page from '@/components/Page.svelte';
    // import Report from '@/components/Report.svelte';

    export let user;

    // console.log({user, likes});

    // {#if user.likes.length}
    //     <h3 class="text-2xl">Likes</h3>
    //     <hr />
    //     <div class="flex flex-col gap-5">
    //         {#each user.likes as report}
    //             <Report {report} />
    //         {/each}
    //     </div>
    // {/if}
</script>

<Page header={`User ${user.username}`}>
    <div class="mb-8 grid w-full grid-cols-3">
        <dl>
            <dt class="font-semibold">Joined:</dt>
            <dd>{new Date(user.created_at).toDateString()}</dd>
        </dl>
        <dl>
            <dt class="font-semibold">Likes:</dt>
            <dd>0</dd>
        </dl>
        <dl>
            <dt class="font-semibold">Points:</dt>
            <dd>0</dd>
        </dl>
    </div>
</Page>
