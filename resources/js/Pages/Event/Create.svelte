<script>
    import Page from '@/Components/Page.svelte';
    import CircleX from '@/Components/icons/CircleX.svelte';
    import route from '@/lib/route';
    import { ErrorMessage, Label, Button, Input } from '@/Components/forms';
    import { useForm } from '@inertiajs/svelte';
    import { FileDropzone } from '@skeletonlabs/skeleton';

    let form = useForm({
        name: null,
        date: null,
        location: null,
        sources: [],
    });
    let rejectedFiles = [];

    $: console.log($form.sources);

    // Todo Add Skeleton UI dropzone
    // eslint-disable-next-line no-unused-vars
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

    function submit() {
        $form.post(route('event.store'), { forceFormData: true });
    }
</script>

<Page header="Create Event">
    <div class="card">
        <form
            method="POST"
            on:submit|preventDefault={submit}
            class="flex flex-col gap-4">
            <div class="block">
                <Label for="name">Name</Label>
                <Input
                    id="name"
                    name="name"
                    bind:value={$form.name}
                    class="mt-1 block w-full"
                    required />
                <ErrorMessage message={$form.errors.name} class="mt-2" />
            </div>
            <div class="block">
                <Label for="date">Date</Label>
                <Input
                    id="date"
                    name="date"
                    type="date"
                    bind:value={$form.date}
                    class="mt-1 block w-full"
                    required />
                <ErrorMessage message={$form.errors.date} class="mt-2" />
            </div>
            <div class="block">
                <Label for="location">Location (Place, Url, Etc.)</Label>
                <Input
                    id="location"
                    name="location"
                    bind:value={$form.location}
                    class="mt-1 block w-full"
                    required />
                <ErrorMessage message={$form.errors.location} class="mt-2" />
            </div>
            <div class="block">
                <Label for="dropzone">Audio Source</Label>
                <FileDropzone
                    name="sources"
                    accept="audio/*"
                    bind:files={$form.sources}>
                    <svelte:fragment slot="meta"
                        >Audio files accepted</svelte:fragment>
                </FileDropzone>
                <!-- <Dropzone accept="audio/*" on:drop={handleFilesSelect} multiple> -->
                <!--     <button>Choose audio files to upload</button> -->
                <!--     <p>or</p> -->
                <!--     <p>Drag and drop them here</p> -->
                <!-- </Dropzone> -->
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
                            <Button
                                type="button"
                                variant="destructive"
                                on:click={handleRemoveAllFiles}
                                aria-label="Remove all files"
                                >Remove All</Button>
                        </div>
                        {#each $form.sources as item, i}
                            <div class="mt-2 flex gap-2">
                                <span>{item.name}</span>
                                <button
                                    type="button"
                                    class="text-red-400"
                                    aria-label="Remove File"
                                    on:click={() => handleRemoveFile(i)}
                                    ><CircleX /></button>
                            </div>
                        {/each}
                    </div>
                {/if}
            </div>

            <div class="flex items-center justify-end">
                <Button type="submit" class="ml-3">Create</Button>
            </div>
        </form>
    </div>
</Page>
