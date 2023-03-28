<script>
    import route from '@/route';
    import {
        ErrorMessage,
        Label,
        PrimaryButton,
        TextInput,
    } from '@components/forms';
    import { useForm } from '@inertiajs/svelte';

    export let token;

    let form = useForm('ResetPassword', {
        token,
        email: null,
        password: null,
        password_confirmation: null,
    });

    function submit() {
        $form.post(route('password.store'));
    }
</script>

<form method="POST" on:submit|preventDefault={submit}>
    <div>
        <Label for="email" value="Email" />
        <TextInput
            id="email"
            class="mt-1 block w-full"
            type="email"
            name="email"
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
            required />
        <ErrorMessage
            message={$form.errors.password_confirmation}
            class="mt-2" />
    </div>

    <div class="mt-4 flex items-center justify-end">
        <PrimaryButton>Reset Password</PrimaryButton>
    </div>
</form>
