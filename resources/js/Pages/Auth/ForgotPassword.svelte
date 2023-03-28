<script>
    import Status from '@components/Status.svelte';
    import route from '@/route';
    import {
        ErrorMessage,
        Label,
        PrimaryButton,
        TextInput,
    } from '@components/forms';
    import { useForm } from '@inertiajs/svelte';

    export let status;

    let form = useForm('ForgotPassword', {
        email: null,
    });

    function submit() {
        $form.post(route('password.email'));
    }
</script>

<div class="mb-4 text-sm text-gray-400">
    Forgot your password? No problem. Just let us know your email address and we
    will email you a password reset link that will allow you to choose a new
    one.
</div>

<Status {status} />

<form method="POST" on:submit|preventDefault={submit}>
    <div>
        <Label for="email" value="email" />
        <TextInput
            id="email"
            class="mt-1 block w-full"
            type="email"
            name="email"
            required
            autofocus />
        <ErrorMessage message={$form.errors.email} class="mt-2" />
    </div>

    <div class="mt-4 flex items-center justify-end">
        <PrimaryButton>Email Password Reset Link</PrimaryButton>
    </div>
</form>
