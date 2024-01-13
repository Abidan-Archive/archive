<script>
    import Card from '@/Components/Card.svelte';
    import route from '@/Utils/route';
    import { ErrorMessage, Label, Button, TextInput } from '@/Components/forms';
    import { useForm } from '@inertiajs/svelte';

    let form = useForm({
        password: null,
    });

    function submit() {
        $form.post(route('password.confirm'));
    }
</script>

<section class="contianer mx-auto mt-10 w-1/2">
    <h2 class="my-5 text-2xl">Confirm Password</h2>
    <Card>
        <div class="mb-4 text-sm text-gray-400">
            This is a secure area of the application. Please confirm password
            before continuing.
        </div>
        <form method="POST" on:submit|preventDefault={submit}>
            <div>
                <Label for="password" value="Password" />
                <TextInput
                    id="password"
                    class="mt-1 block w-full"
                    type="password"
                    name="password"
                    required
                    bind:value={$form.password}
                    autocomplete="current-password" />
                <ErrorMessage message={$form.errors.password} class="mt-2" />
            </div>

            <div class="mt-4 flex justify-end">
                <Button>Confirm</Button>
            </div>
        </form>
    </Card>
</section>
