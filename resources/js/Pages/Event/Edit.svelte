<script>
    import Card from '@/Components/Card.svelte';
    import CircleX from '@/Components/icons/CircleX.svelte';
    import Dropzone from 'svelte-file-dropzone/Dropzone.svelte';
    import Dialog from '@/Components/modals/Dialog.svelte';
    import { formatToInputDateString, route } from '@/Utils';
    import { ErrorMessage, Label, Button, TextInput } from '@/Components/forms';
    import { router, useForm, page } from '@inertiajs/svelte';
    import { onMount, getContext } from 'svelte';
    import { addToast, addFlash } from '@/Stores/toast';

    onMount(() => {
        if ($page.props.status)
            addToast({ message: status, type: 'success', timeout: false });
    });

    const { open } = getContext('simple-modal');

    export let event;

    let form = useForm({
        name: event.name,
        date: formatToInputDateString(event.date),
        location: event.location,
        sources: [],
    });
    let rejectedFiles = [];

    function handleFilesSelect(e) {
        const { acceptedFiles, fileRejections } = e.detail;
        $form.sources = [...$form.sources, ...acceptedFiles];
        rejectedFiles = [...rejectedFiles, ...fileRejections];
    }

    function handleRemoveFile(index) {
        $form.sources.splce(index, 1);
        $form.sources = [...$form.sources];
    }

    function handleRemoveAllFiles() {
        $form.sources = [];
    }

    function handleEventSubmit() {
        router.post(
            route('event.update', event.id),
            {
                _method: 'put',
                ...$form,
            },
            { forceFormData: true }
        );
        // $form.put(route('event.update', event.id), { forceFormData: true });
    }

    // Let's use the same form for each source
    let renameSource = useForm({
        name: null,
    });
    function handleRenameSourceSubmit(source) {
        // And set the name on submit
        $renameSource.name = source.name;
        // Then submit to the proper source
        $renameSource.put(
            route('event.source.update', [source.event_id, source.id]),
            {
                // Handle via toast
                onSuccess: () => addFlash($page.props.flash),
                onError: () =>
                    addToast({
                        message: $renameSource.errors.name,
                        type: 'error',
                    }),
            }
        );
    }

    function handleDeleteSourceSubmit(source) {
        router.delete(
            route('event.source.destroy', [source.event_id, source.id]),
            {
                onSuccess: () => addFlash($page.props.flash),
                onError: () =>
                    addToast({
                        message: 'Uh oh something went wrong.',
                        type: 'error',
                    }),
            }
        );
    }
</script>

<section class="contianer mx-auto mt-10">
    <h2 class="my-5 text-2xl">Edit Event</h2>
    <Card>
        <form method="POST" on:submit|preventDefault={handleEventSubmit}>
            <div class="block">
                <Label for="name">Name</Label>
                <TextInput
                    id="name"
                    name="name"
                    bind:value={$form.name}
                    class="mt-1 block w-full"
                    required />
                <ErrorMessage message={$form.errors.name} class="mt-2" />
            </div>
            <div class="mt-4 block">
                <Label for="date">Date</Label>
                <TextInput
                    id="date"
                    name="date"
                    type="date"
                    bind:value={$form.date}
                    class="mt-1 block w-full"
                    required />
                <ErrorMessage message={$form.errors.date} class="mt-2" />
            </div>
            <div class="mt-4 block">
                <Label for="location">Location (Place, Url, Etc.)</Label>
                <TextInput
                    id="location"
                    name="location"
                    bind:value={$form.location}
                    class="mt-1 block w-full"
                    required />
                <ErrorMessage message={$form.errors.location} class="mt-2" />
            </div>
            <div id="dropzone-container" class="mt-4 block">
                <Label for="dropzone">Add Additional Audio Sources</Label>
                <Dropzone accept="audio/*" on:drop={handleFilesSelect} multiple>
                    <button>Choose audio files to upload</button>
                    <p>or</p>
                    <p>Drag and drop them here</p>
                </Dropzone>
                {#each $form.sources as item, i}
                    <ErrorMessage
                        message={($form.errors['sources.' + i] || '').replace(
                            'sources.' + i,
                            item.name
                        )}
                        class="mt-2" />
                {/each}
                {#if $form.sources.length > 0}
                    <div class="mt-1">
                        <div class="flex justify-between">
                            <span>Files</span>
                            <button
                                class="text-red-400"
                                on:click={handleRemoveAllFiles}
                                >Remove All</button>
                        </div>
                        {#each $form.sources as item, i}
                            <div class="mt-2 flex gap-2">
                                <span>{item.name}</span>
                                <button
                                    class="text-red-400"
                                    aria-label="Remove File"
                                    on:click={() => handleRemoveFile(i)}
                                    ><CircleX /></button>
                            </div>
                        {/each}
                    </div>
                {/if}
            </div>

            <div class="mt-4 flex items-center justify-end p-3">
                <Button type="submit">Update</Button>
            </div>
        </form>
    </Card>
</section>

<section>
    <h3 class="my-4 text-xl">Manage Sources</h3>
    <div class="flex flex-col gap-5">
        {#each event.sources as source}
            <Card class="flex items-center justify-between">
                <form
                    method="PATCH"
                    on:submit|preventDefault={() =>
                        handleRenameSourceSubmit(source)}
                    class="flex items-center gap-1">
                    <Button href={route('home')}>Stub</Button>
                    <TextInput
                        id={'source-' + source.id}
                        bind:value={source.name} />
                    <Button type="submit">Rename</Button>
                </form>
                <audio src={source.url} controls />
                <Button
                    variant="danger"
                    on:click={() => {
                        open(Dialog, {
                            message:
                                'Are you sure you want to delete this source? All attached stubs will be deleted regardless of completion.',
                            onOkay: () => handleDeleteSourceSubmit(source),
                            closeButton: false,
                            closeOnOuterClick: false,
                        });
                    }}>
                    Delete
                </Button>
            </Card>
        {/each}
    </div>
</section>

<style lang="postcss">
    #dropzone-container :global(.dropzone) {
        @apply rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600;
    }
</style>
