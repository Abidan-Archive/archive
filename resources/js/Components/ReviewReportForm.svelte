<script>
    import { inertia, useForm } from '@inertiajs/svelte';
    import { getModalStore } from '@skeletonlabs/skeleton';

    import route from '@/lib/route';
    import Report from '@/Components/Report.svelte';
    import {
        ErrorBanner,
        ErrorMessage,
        Label,
        Button,
        Autocomplete,
    } from '@/Components/forms';
    import ReportForm from '@/Components/ReportForm.svelte';
    import AudioPlayer from '@/Components/audio/AudioPlayer.svelte';

    const modalStore = getModalStore();
    let { report, events, tags } = $props();

    let isEditVisible = $state(false);
    function toggleEditForm() {
        isEditVisible = !isEditVisible;
    }

    let approveForm = useForm('review-' + report.id, {});

    function onApprove(e) {
        e.preventDefault();

        const submit = () => {
            $approveForm.post(route('admin.approve', e.target.dataset.report));
        };

        // Do a quick validation for my tag loving sanity
        if (report.tags.length === 0) {
            modalStore.trigger({
                type: 'confirm',
                title: 'No Tags',
                body: 'There are no tags attached to this report. Are you sure you want to approve this?',
                response: (r) => r && submit(),
            });
            return;
        }
        submit();
    }

    let patchForm = useForm('patch-' + report.id, {
        event_id: report.event_id,
        dialogues: report.dialogues,
        date: report.date,
        source_label: report.source_label ?? undefined,
        source_href: report.source_href ?? undefined,
        footnote: report.footnote ?? undefined,
        tags: report.tags.map((t) => t.name),
    });

    function onPatch(e) {
        e.preventDefault();
        $patchForm.dialogues = $patchForm.dialogues.filter((d) =>
            (d.speaker + d.line).trim()
        );

        $patchForm.patch(route('admin.report.update', report));
    }

    function getCompareDate(value) {
        const date = new Date(value);

        if (isNaN(date.getTime())) return null;

        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    let searchOptions = $state([]);
    function onSearch(value) {
        searchOptions = events
            .filter(
                (event) =>
                    `${event.id}`.includes(value) ||
                    event.name.toLowerCase().includes(value.toLowerCase()) ||
                    event.date === getCompareDate(value) ||
                    event.date.includes(value)
            )
            .map((event) => ({ label: event.name, value: event }));
    }
    function onSelect(value) {
        if (!$patchForm.date) {
            $patchForm.date = value.date;
        }
        $patchForm.event_id = value.id;
    }
</script>

<div id={`report-${report.id}`} class="flex flex-col gap-4">
    <div class="flex items-center justify-between">
        <h3 class="h3">
            Report #{report.id}
        </h3>
        <p>
            Created:
            {new Date(report.created_at).toLocaleDateString('en-US')}
        </p>
    </div>
    {#if isEditVisible}
        <form method="POST" onsubmit={onPatch} class="flex flex-col gap-4">
            <ErrorBanner form={patchForm} />

            {#if report.stub}
                <div class="flex justify-center">
                    <AudioPlayer src={report.stub.audio_url} />
                </div>
            {/if}

            {#snippet eventSection()}
                <h3>Event</h3>
                <Label for="searchEvent">Search Event</Label>
                <Autocomplete
                    id="eventSearch"
                    onsearch={onSearch}
                    onselect={onSelect}
                    bind:searchOptions />
                <ErrorMessage message={$patchForm.errors.event_id} />
            {/snippet}

            <ReportForm form={patchForm} {report} {tags} {eventSection} />
            <div class="flex justify-between">
                <Button type="button" onclick={toggleEditForm}>Cancel</Button>
                <Button>Update</Button>
            </div>
        </form>
    {:else}
        <Report {report} />
        <div class="text-center">
            {#if report.stub}
                <div class="card">
                    <a
                        use:inertia
                        href={route('stub.transcribe', [
                            report.stub.source.event_id,
                            report.stub.source_id,
                            report.stub.id,
                        ])}>
                        <h4 class="h4">
                            {report.stub.prompt || 'No Prompt Given'}
                        </h4>
                    </a>
                    <audio controls>
                        <source src={report.stub.audio_url} type="audio/mpeg" />
                        Your browser does not support the audio element.
                    </audio>
                </div>
            {:else}
                <p>No stub available.</p>
            {/if}
        </div>
        <form
            class="flex items-center justify-between"
            onsubmit={onApprove}
            data-report={report.id}>
            <Button type="button" onclick={toggleEditForm}>Change</Button>
            <small>
                Approving will make it visible to public and delete the stub.
            </small>
            <Button>Approve</Button>
        </form>
    {/if}
</div>
