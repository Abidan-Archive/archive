<script>
    import Page from '@/components/Page.svelte';
    import Card from '@/components/Card.svelte';
    import route from '@/lib/route';
    import { ErrorMessage, Label, Button, Input } from '@/components/forms';
    import { useForm } from '@inertiajs/svelte';

    let form = useForm({
        password: null,
    });

    function submit() {
        $form.post(route('password.confirm'));
    }
</script>

<Page class="w-1/2" header="Confirm Password">
    <Card>
        <div class="mb-4 text-sm text-gray-400">
            This is a secure area of the application. Please confirm password
            before continuing.
        </div>
        <form method="POST" on:submit|preventDefault={submit}>
            <div>
                <Label for="password" value="Password" />
                <Input
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
</Page>
