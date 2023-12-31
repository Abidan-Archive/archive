<script>
    import Card from '@components/Card.svelte';
    import route from '@/Utils/route';
    import { ErrorMessage, Label, Button, TextInput } from '@components/forms';
    import { page, useForm } from '@inertiajs/svelte';

    let form = useForm('ResetPassword', {
        token: route().params.token,
        email: route().params.email,
        password: null,
        password_confirmation: null,
    });

    function submit() {
        $form.post(route('password.update'));
    }
</script>

<section class="contianer mx-auto mt-10 w-1/2">
    <h2 class="my-5 text-2xl">Reset Password</h2>
    <Card>
        <form method="POST" on:submit|preventDefault={submit}>
            <div>
                <Label for="email" value="Email" />
                <TextInput
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
                <TextInput
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
                <TextInput
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
</section>
