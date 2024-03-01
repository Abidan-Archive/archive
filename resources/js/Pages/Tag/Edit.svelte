<script>
    import { getContrastText } from '@/lib/color.js';
    import { router, page, useForm } from '@inertiajs/svelte';
    // import { getContext } from 'svelte';
    import Page from '@/components/Page.svelte';
    import cn from '@/lib/cn.js';
    import route from '@/lib/route.js';
    import { ErrorMessage, Label, Button, Input } from '@/components/forms';
    import Dialog from '@/components/modals/Dialog.svelte';
    import { addFlash } from '@/stores/toast.js';

    addFlash($page.props.flash);

    // const { open } = getContext('simple-modal');

    function open(...what) {
        console.log(what);
    }

    export let tag;

    let form = useForm({
        name: tag.name,
        color: tag.color,
    });
    function submit() {
        $form.put(route('tag.update', tag));
    }
    function handleDeleteClick() {
        open(Dialog, {
            message: 'Are you sure you want to delete this tag?',
            onOkay: deleteSubmit,
            closeButton: false,
            closeOnOuterClick: false,
        });
    }
    function deleteSubmit() {
        router.delete(route('tag.destroy', tag));
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
                    'h-10 rounded-md px-4 py-2 font-bold capitalize ease-in-out',
                    getContrastText($form.color)
                )}>{$form.name}</button>
        </section>
        <section class="w-2/3">
            <form
                id="update-form"
                method="POST"
                on:submit|preventDefault={submit}
                class="mb-4 flex flex-col gap-4">
                <div class="flex flex-col gap-2">
                    <Label for="name">Name</Label>
                    <Input
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
        </section>
    </div>
    <section>
        <div class="flex items-center justify-between">
            <Button on:click={handleDeleteClick} variant="destructive"
                >Delete</Button>
            <Button form="update-form" on:click={submit}>Update</Button>
        </div>
    </section>
</Page>
