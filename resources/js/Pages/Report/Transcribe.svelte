<script>
    import { useForm } from '@inertiajs/svelte';
    import { route } from '@/lib';

    import Page from '@/Components/Page.svelte';
    import {
        ErrorBanner,
        ErrorMessage,
        Button,
        Input,
    } from '@/Components/forms';
    import ReportForm from '@/Components/ReportForm.svelte';
    import AudioPlayer from '@/Components/audio/AudioPlayer.svelte';

    let { stub, tags } = $props();
    let event = stub.source.event;
    let form = useForm({
        event_id: event.id,
        dialogues: [
            {
                speaker: 'Speaker',
                line: undefined,
            },
            {
                speaker: 'Will Wight',
                line: undefined,
            },
        ],
        date: event.date,
        source_label: undefined,
        source_href: undefined,
        footnote: undefined,
        tags: [],
    });

    function submit(e) {
        e.preventDefault();
        $form.stub_id = stub.id;
        $form.dialogues = $form.dialogues.filter((d) =>
            (d.speaker + d.line).trim()
        );
        $form.post(route('report.store'));
    }
</script>

<Page header="Transcribe Report">
    <form method="POST" onsubmit={submit} class="flex flex-col gap-4">
        <h3 class="text-center">{stub.prompt}</h3>
        <ErrorBanner {form} />
        <div class="flex justify-center">
            <AudioPlayer src={stub.audio_url} />
        </div>

        <ReportForm {form} {tags} {eventSection} />

        {#snippet eventSection()}
            <h3>
                Event &dash;
                <small>
                    This is gathered from the stub and cannot be changed.
                </small>
            </h3>
            <Input
                type="text"
                disabled
                class="input block w-full"
                value={`${event.name} - #${event.id}`} />
            <ErrorMessage message={$form.errors.event_id} />
            <ErrorMessage message={$form.errors.stub_id} />
        {/snippet}

        <div class="flex items-baseline justify-end">
            <Button>Create</Button>
        </div>
    </form>
</Page>
