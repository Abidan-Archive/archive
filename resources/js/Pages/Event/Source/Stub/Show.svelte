<script>
    import { useForm, inertia } from '@inertiajs/svelte';
    import Page from '@/components/Page.svelte';
    import route from '@/lib/route.js';
    import { ErrorMessage, Label, Button, Input } from '@/components/forms';

    export let event;
    export let source;
    export let stub;
    export let tags;

    const audioUrl = source.url;
    const audioContentType = 'audio/mpeg';
    const today = new Date().toLocaleDateString('fr-ca'); // yyyy-mm-dd

    let form = useForm({
        dialogues: [
            { speaker: 'Questioner', line: null },
            { speaker: 'Will Wight', line: null },
        ],
        footnote: null,
        date: event.date,
        tags: [],
    });

    function addDialogue() {
        $form.dialogues = [...$form.dialogues, { speaker: null, line: null }];
    }

    function removeDialogue(idx) {
        $form.dialogues.splice(idx, 1);
    }

    function submit(e) {
        e.preventDefault();
        console.log($form);
        // form.post(route(''))
    }
</script>

<Page header={`Stub - ${stub.id}`}>
    <figure class="mb-4 flex w-full flex-col items-center justify-center">
        <figcaption class="mb-2">{stub.prompt || 'No Prompt Given'}</figcaption>
        <audio controls src={audioUrl} preload="auto">
            <source src={audioUrl} type={audioContentType} />
            Your browser does not support the audio element.
        </audio>
    </figure>
    <h3>Create Report</h3>
    <hr />
    <form on:submit={submit} class="flex flex-col gap-4">
        {#each $form.dialogues as dialogue, idx}
            <div class="flex flex-col gap-2">
                <div>
                    <Label for={'speaker-' + idx}>Speaker</Label>
                    <Input
                        id={'speaker-' + idx}
                        class="mt-1 block w-full"
                        bind:value={dialogue.speaker}
                        required />
                </div>
                <div>
                    <Label for={'line-' + idx}>Line</Label>
                    <textarea
                        class="w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600"
                        id={'line-' + idx}
                        bind:value={dialogue.line} />
                </div>
                <div>
                    <button
                        on:click={() => removeDialogue(idx)}
                        class="text-red-600 underline hover:text-red-700">
                        Remove Section
                    </button>
                </div>
            </div>
        {/each}
        <div class="flex justify-end">
            <Button on:click={addDialogue}>Add More</Button>
        </div>
        <div>
            <Label for="footnote">Footnote</Label>
            <Input
                id="footnote"
                class="mt-1 block w-full"
                bind:value={$form.footnote} />
            <ErrorMessage message={$form.errors.footnote} class="mt-2" />
        </div>
        <div>
            <Label for="date">Date</Label>
            <Input
                type="date"
                id="date"
                class="mt-1 block w-full"
                bind:value={$form.date}
                required
                max={today} />
            <ErrorMessage message={$form.errors.date} class="mt-2" />
        </div>
        <div>
            <Label for="tags">Tags</Label>
            <p>
                Tags are curated by the moderators. If you'd like to suggest a
                new tag <a
                    use:inertia
                    href={route('about')}
                    class="underline hover:text-typo-600"
                    >get in touch with one</a
                >.
            </p>
            <select
                id="tags"
                bind:value={$form.tags}
                multiple
                class="mt-1 block w-full">
                {#each tags as tag}
                    <option value={tag.id}>{tag.name}</option>
                {/each}
            </select>
            <ErrorMessage message={$form.errors.tags} class="mt-2" />
        </div>
        <div class="text-right">
            <p>All reports are reviewed by a moderator.</p>
        </div>
        <div class="flex justify-end">
            <Button type="submit" disabled={$form.processing}
                >Create Report</Button>
        </div>
    </form>
</Page>
