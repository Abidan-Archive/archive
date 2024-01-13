<script>
    import AnchorButton from '@/Components/AnchorButton.svelte';
    import Card from '@/Components/Card.svelte';
    import CircleX from '@/Components/icons/CircleX.svelte';
    import Dropzone from 'svelte-file-dropzone/Dropzone.svelte';
    import Modal from '@/Components/Modal.svelte';
    import {formatToInputDateString, route} from '@/Utils';
    import { ErrorMessage, Label, Button, TextInput } from '@/Components/forms';
    import { router, useForm, page } from '@inertiajs/svelte';
    import { onMount } from 'svelte';
    import { addToast } from '@/Stores/toasts';

    onMount(() => {
        if ($page.props.flash)
            addToast({ message: status, type: 'success', timeout: false });
    });

    export let event;

    let showDeleteConfirmModal = false;
    let sourceToDelete = null;
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
        $form.patch(route('event.update', event.id));
    }
    function handleRenameSourceSubmit(id) {
        // router.update(route(''))
    }

    function handleDeleteSourceClicked(id) {
        showDeleteConfirmModal = true;
        sourceToDelete = id;
    }
    function handleDeleteSourceConfirmed() {
        showDeleteConfirmModal = false;
        // router.destroy(route(''), sourceToDelete);
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
        {#each event.sources as { id, name, path }}
            <Card class="flex items-center justify-between">
                <form
                    method="PATCH"
                    on:submit|preventDefault={() =>
                        handleRenameSourceSubmit(id)}
                    class="flex items-center gap-1">
                    <AnchorButton href={route('home')}>Stub</AnchorButton>
                    <TextInput id={'source-' + id} bind:value={name} />
                    <Button type="submit">Rename</Button>
                </form>
                <audio src={path} controls />
                <Button
                    variant="danger"
                    on:click={() => handleDeleteSourceClicked(id)}>
                    Delete
                </Button>
            </Card>
        {/each}
    </div>
</section>

<Modal bind:visible={showDeleteConfirmModal}>
    <h2 slot="header" class="mb-5 text-xl">Are you sure you want to delete?</h2>

    <div class="py-5">
        <p>
            By deleting a source you also delete all of the attached stubs
            regardless of completion.
        </p>
        <p>Are you sure you want to do this?</p>
    </div>

    <div slot="footer" class="mt-5 flex justify-between">
        <Button variant="danger" on:click={handleDeleteSourceConfirmed}
            >Yes, Delete</Button>
        <Button on:click={() => (showDeleteConfirmModal = false)}>
            Cancel
        </Button>
    </div>
</Modal>

<style lang="postcss">
    #dropzone-container :global(.dropzone) {
        @apply rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600;
    }
</style>
