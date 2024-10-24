<script>
    import { inertia } from '@inertiajs/svelte';

    import Page from '@/Components/Page.svelte';
    import Paginator from '@/Components/Paginator.svelte';
    import route from '@/lib/route';

    export let stubs;
</script>

<Page header="All Stubs">
    <div class="mx-auto flex flex-col gap-3">
        <p class="text-center">
            Stubs are audio snippets that need transcription.
        </p>
        {#if !stubs.data.length}
            <p class="text-center">
                Currently there is no work to be done! Woohoo!
            </p>
        {:else}
            <Paginator {...stubs} />
            {#each stubs.data as stub}
                <div class="card">
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
                </div>
            {/each}
            <Paginator {...stubs} />
        {/if}
    </div>
</Page>
