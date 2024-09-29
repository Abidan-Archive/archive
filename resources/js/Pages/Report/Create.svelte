<script>
    import Page from '@/Components/Page.svelte';
    import Card from '@/Components/Card.svelte';
    import { ErrorMessage, Label, Button, Input } from '@/Components/forms';

    export let stub = undefined;
    let dialogues = [
        {
            speaker: 'Speaker',
            line: '',
        },
        {
            speaker: 'Will Wight',
            line: '',
        },
    ];
    function submit() {
        console.log({ dialogues });
    }
</script>

<Page header="Create Report">
    {#if stub}
        <h3>{stub.prompt}</h3>
        <audio src={stub.audio_url} controls />
    {/if}
    <form method="POST" on:submit|preventDefault={submit}>
        {#each dialogues as dialogue, idx}
            <Card>
                <div>
                    <Label for={'speaker-' + idx}>Speaker</Label>
                    <Input
                        id={'speaker-' + idx}
                        type="text"
                        name="speaker[]"
                        bind:value={dialogue.speaker} />
                    <ErrorMessage message={'idk'} class="mt-2" />
                </div>
                <div>
                    <Label for={'line-' + idx}>Line</Label>
                    <textarea
                        id={'line-' + idx}
                        name="line[]"
                        bind:value={dialogue.line} />
                    <ErrorMessage message={'idk'} class="mt-2" />
                </div>
            </Card>
        {/each}
        <div class="flex items-baseline justify-end">
            <Button>Create</Button>
        </div>
    </form>
</Page>
