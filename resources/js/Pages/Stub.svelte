<script>
    import { inertia } from '@inertiajs/svelte';

    import Card from '@/components/Card.svelte';
    import Page from '@/components/Page.svelte';
    import Paginator from '@/Components/Paginator.svelte';
    import route from '@/lib/route';

    export let stubs;
</script>

<Page header="All Stubs">
    <div class="mx-auto flex w-1/2 flex-col gap-3">
        <p>Stubs are audio snippets that need transcription.</p>
        {#if !stubs.data.length}
            <p>Currently there is no work to be done! Woohoo!</p>
        {:else}
            <Paginator {...stubs} />
            {#each stubs.data as stub}
                <Card>
                    <a
                        use:inertia
                        href={route('event.source.stub.show', [
                            stub.event.id,
                            stub.source.id,
                            stub.id,
                        ])}>
                        {stub.prompt || 'No Prompt Given'} &dash; {stub.event
                            .name}
                    </a>
                </Card>
            {/each}
            <Paginator {...stubs} />
        {/if}
    </div>
</Page>
