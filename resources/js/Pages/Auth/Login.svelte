<script>
    import Status from '@components/Status.svelte';
    import clsx from 'clsx';
    import route from '@/route';
    import {
        ErrorMessage,
        Label,
        PrimaryButton,
        TextInput,
    } from '@components/forms';
    import { useForm, inertia } from '@inertiajs/svelte';

    export let status;

    let form = useForm({
        email: null,
        password: null,
        remember: false,
    });

    function submit() {
        $form.post(route('login'));
    }
</script>

<Status {status} />

<form method="POST" on:submit|preventDefault={submit}>
    <div>
        <Label for="email">Email</Label>
        <!-- svelte-ignore a11y-autofocus -->
        <TextInput
            id="email"
            name="email"
            bind:value={$form.email}
            class="mt-1 block w-full"
            required
            autofocus />
        <ErrorMessage message={$form.errors.email} class="mt-2" />
    </div>

    <div class="mt">
        <Label for="password">Password</Label>
        <TextInput
            id="password"
            type="password"
            name="password"
            bind:value={$form.password}
            class="mt-1 block w-full"
            required
            autocomplete="current-password" />
        <ErrorMessage message={$form.errors.password} class="mt-2" />
    </div>

    <div class="mt-4 block">
        <Label for="remember_me" class="inline-flex items-center">
            <input
                id="remember_me"
                type="checkbox"
                class={clsx('')}
                name="remember" />
            <span class="ml-2 text-sm text-gray-400">Remember Me</span>
        </Label>
    </div>

    <div class="mt-4 flex items-center justify-end">
        {#if route().has('password.request')}
            <a
                use:inertia
                href={route('password.request')}
                class={clsx(
                    'rounded-md text-sm text-gray-400 underline hover:text-gray-100',
                    'focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800'
                )}>
                Forgot your password?
            </a>
        {/if}

        <PrimaryButton class="ml-3">Log in</PrimaryButton>
    </div>
</form>
