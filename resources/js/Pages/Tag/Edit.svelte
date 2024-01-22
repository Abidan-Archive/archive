<script>
    import { fade } from 'svelte/transition';
    import { getContrastText } from '@/lib/color.js';
    import { router, page, useForm } from '@inertiajs/svelte';
    import Page from '@/Components/Page.svelte';
    import cn from '@/lib/cn.js';
    import { ErrorMessage, Label, Button, TextInput } from '@/Components/forms';
    import Dialog from '@/Components/modals/Dialog.svelte';

    addFlash($page.props.flash);

    const { open } = getContext('simple-modal');

    export let tag;

    let form = useForm({
        name: tag.name,
        color: tag.color,
    });
    function submit() {
        $form.put(route('tag.update'));
    }
    function handleDelete() {
        router.delete(route('tag.destroy'), tag);
    }
</script>

<Page header="Update Tag">
    <div class="flex">
        <section class="w-1/3">
            <h3>Live Preview</h3>
            <p>This is what your tag will look like...</p>
            <button
                style={`background-color: ${$form.color};`}
                class={cn(
                    'h-10 rounded-md py-2 px-4 font-bold ease-in-out capitalize',
                    getContrastText($form.color)
                )}
                transition:fade|local>{$form.name}</button>
        </section>
        <section class="w-2/3">
            <form
                id="update-form"
                method="POST"
                on:submit|preventDefault={submit}
                class="flex flex-col gap-4 mb-4">
                <div class="flex flex-col gap-2">
                    <Label for="name">Name</Label>
                    <TextInput
                        id="name"
                        name="name"
                        bind:value={$form.name}
                        required
                        class="" />
                    <ErrorMessage message={$form.errors.name} />
                </div>
                <div class="flex flex-col gap-2">
                    <Label for="color">Color</Label>
                    <input
                        id="color"
                        name="color"
                        type="color"
                        bind:value={$form.color}
                        required
                        class="" />
                    <ErrorMessage message={$form.errors.color} />
                </div>
            </form>
            <div class="flex items-center space-between">
                <Button on:click={open(Dialog, {
                    message: 'Are you sure you want to delete this tag?',
                    onOkay: () => handleDelete(),
                    closeButton: false,
                    closeOnOuterClick: false,
                })}>Delete</Button>
                <Button>Update</Button>
            </div>
        </section>
    </div>
</Page>
