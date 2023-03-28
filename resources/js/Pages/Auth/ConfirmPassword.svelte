<script>
    import clsx from 'clsx';
    import route from '@/route';
    import {
        ErrorMessage,
        Label,
        PrimaryButton,
        TextInput,
    } from '@components/forms';
    import { useForm, inertia } from '@inertiajs/svelte';

    let form = useForm({
        password: null,
    });

    function submit() {
        $form.post(route('password.confirm'));
    }
</script>

<div class="mb-4 text-sm text-gray-400">
    This is a secure area of the application. Please confirm password before continuing.
</div>

<form method="POST" on:submit|preventDefault={submit}>
    <div>
        <Label for="password" value="Password" />
        <TextInput
            id="password"
            class="block mt-1 w-full"
            type="password"
            name="password"
            required
            autocomplete="current-password"
            />
        <ErrorMessage message={$form.errors.password} class="mt-2" />
    </div>

    <div class="flex justify-end mt-4">
        <PrimaryButton>
            Confirm
        </PrimaryButton>
    </div>
</form>
