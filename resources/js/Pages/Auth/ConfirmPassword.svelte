<script>
    import route from '@/route';
    import {
        ErrorMessage,
        Label,
        PrimaryButton,
        TextInput,
    } from '@components/forms';
    import { useForm } from '@inertiajs/svelte';

    let form = useForm({
        password: null,
    });

    function submit() {
        $form.post(route('password.confirm'));
    }
</script>

<div class="mb-4 text-sm text-gray-400">
    This is a secure area of the application. Please confirm password before
    continuing.
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
            autocomplete="current-password" />
        <ErrorMessage message={$form.errors.password} class="mt-2" />
    </div>

    <div class="mt-4 flex justify-end">
        <PrimaryButton>Confirm</PrimaryButton>
    </div>
</form>
