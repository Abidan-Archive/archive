<script>
    import Page from '@/Components/Page.svelte';
    import Card from '@/Components/Card.svelte';
    import route from '@/lib/route';
    import recaptcha from '@/lib/recaptcha';
    import { ErrorMessage, Label, Button, Input } from '@/Components/forms';
    import { useForm } from '@inertiajs/svelte';

    let form = useForm('ResetPassword', {
        token: route().params.token,
        email: route().params.email,
        password: null,
        password_confirmation: null,
        recaptcha: null,
    });

    function submit() {
        recaptcha('password/update', (token) => {
            $form.recaptcha = token;
            $form.post(route('password.update'));
        });
    }
</script>

<Page class="w-1/2" header="Reset Password">
    <Card>
        <form method="POST" on:submit|preventDefault={submit}>
            <div>
                <Label for="email" value="Email" />
                <Input
                    id="email"
                    class="mt-1 block w-full"
                    type="email"
                    name="email"
                    bind:value={$form.email}
                    required
                    autofocus />
                <ErrorMessage message={$form.errors.email} class="mt-2" />
            </div>
            <div class="mt-4">
                <Label for="password" value="Password" />
                <Input
                    id="password"
                    class="mt-1 block w-full"
                    type="password"
                    name="password"
                    bind:value={$form.password}
                    required />
                <ErrorMessage message={$form.errors.password} class="mt-2" />
            </div>
            <div class="mt-4">
                <Label for="password_confirmation" value="Confirm Password" />
                <Input
                    id="password_confirmation"
                    class="mt-1 block w-full"
                    type="password"
                    name="password_confirmation"
                    bind:value={$form.password_confirmation}
                    required />
                <ErrorMessage
                    message={$form.errors.password_confirmation}
                    class="mt-2" />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Button>Reset Password</Button>
            </div>
        </form>
    </Card>
</Page>
