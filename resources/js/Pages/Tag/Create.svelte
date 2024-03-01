<script>
    import { getRandomColor, getContrastText } from '@/lib/color.js';
    import { useForm } from '@inertiajs/svelte';
    import Page from '@/components/Page.svelte';
    import cn from '@/lib/cn.js';
    import route from '@/lib/route.js';
    import { ErrorMessage, Label, Button, Input } from '@/components/forms';

    let form = useForm({
        name: null,
        color: getRandomColor(),
    });
    function submit() {
        $form.post(route('tag.store'));
    }
</script>

<Page header="Create Tag">
    <div class="flex gap-8">
        <section class="w-1/3">
            <h3>Live Preview</h3>
            <p class="mb-4">This is what your tag will look like...</p>
            <button
                style={`background-color: ${$form.color};`}
                class={cn(
                    'h-10 rounded-md px-4 py-2 font-bold capitalize ease-in-out',
                    getContrastText($form.color),
                    'mx-auto'
                )}>{$form.name}</button>
        </section>
        <section class="w-2/3">
            <form
                method="POST"
                on:submit|preventDefault={submit}
                class="flex flex-col gap-4">
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
                <div class="mt-4 flex items-center justify-end">
                    <Button class="ml-3">Create</Button>
                </div>
            </form>
        </section>
    </div>
</Page>
