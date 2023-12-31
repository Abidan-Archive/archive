<script>
    import Card from '@components/Card.svelte';
    import Toast from '@components/Toast.svelte';
    import route from '@/Utils/route';
    import { ErrorMessage, Label, Button, TextInput } from '@components/forms';
    import { useForm, page } from '@inertiajs/svelte';

    let form = useForm('ForgotPassword', {
        email: null,
    });

    function submit() {
        $form.post(route('password.email'));
    }
</script>

<section class="contianer mx-auto mt-10 w-1/2">
    <h2 class="my-5 text-2xl">Forgot Password</h2>
    <Card>
        <div class="mb-4 text-sm text-gray-400">
            Forgot your password? No problem. Just let us know your email
            address and we will email you a password reset link that will allow
            you to choose a new one.
        </div>

        <Toast message={$page.props.status} />

        <form method="POST" on:submit|preventDefault={submit}>
            <div>
                <Label for="email" value="Email" />
                <TextInput
                    id="email"
                    class="mt-1 block w-full"
                    type="email"
                    name="email"
                    required
                    bind:value={$form.email}
                    autofocus />
                <ErrorMessage message={$form.errors.email} class="mt-2" />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Button>Email Password Reset Link</Button>
            </div>
        </form>
    </Card>
</section>
