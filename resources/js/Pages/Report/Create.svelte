<script>
    import { useForm } from '@inertiajs/svelte';
    import { route } from '@/lib';

    import Page from '@/Components/Page.svelte';
    import {
        ErrorBanner,
        ErrorMessage,
        Label,
        Button,
        Autocomplete,
    } from '@/Components/forms';
    import ReportForm from '@/Components/ReportForm.svelte';

    let { events, tags } = $props();

    let form = useForm({
        event_id: undefined,
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
        date: undefined,
        source_label: undefined,
        source_href: undefined,
        footnote: undefined,
        tags: [],
    });

    function submit(e) {
        e.preventDefault();
        $form.dialogues = $form.dialogues.filter((d) =>
            (d.speaker + d.line).trim()
        );
        $form.post(route('report.store'));
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
        if (!$form.date) {
            $form.date = value.date;
        }
        $form.event_id = value.id;
    }
</script>

<Page header="Create Report">
    <form method="POST" onsubmit={submit} class="flex flex-col gap-4">
        <ErrorBanner {form} />

        <ReportForm {form} {tags} {eventSection} />

        {#snippet eventSection()}
            <h3>Event</h3>
            <Label for="searchEvent">Search Event</Label>
            <Autocomplete
                id="eventSearch"
                onsearch={onSearch}
                onselect={onSelect}
                bind:searchOptions />
            <ErrorMessage message={$form.errors.event_id} />
        {/snippet}

        <div class="flex items-baseline justify-end">
            <Button>Create</Button>
        </div>
    </form>
</Page>
