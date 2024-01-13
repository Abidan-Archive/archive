<script>
    import Page from '@/Components/Page.svelte';
    import Card from '@/Components/Card.svelte';
    import { cn, route } from '@/Utils';
    import { ErrorMessage, Label, Button, TextInput } from '@/Components/forms';
    import { useForm, inertia } from '@inertiajs/svelte';

    let form = useForm('Register', {
        username: null,
        email: null,
        email_confirmation: null,
        password: null,
        password_confirmation: null,
    });

    function submit() {
        $form.post(route('register'));
    }
</script>

<Page class="w-1/2" header="Register">
    <Card>
        <form method="POST" on:submit|preventDefault={submit}>
            <div>
                <Label for="username" value="Username" />
                <TextInput
                    id="username"
                    class="mt-1 block w-full"
                    name="username"
                    bind:value={$form.username}
                    required
                    autofocus />
                <ErrorMessage message={$form.errors.username} class="mt-2" />
            </div>
            <div class="mt-4">
                <Label for="email" value="Email" />
                <TextInput
                    id="email"
                    class="mt-1 block w-full"
                    type="email"
                    name="email"
                    bind:value={$form.email}
                    required />
                <ErrorMessage message={$form.errors.email} class="mt-2" />
            </div>
            <div class="mt-4">
                <Label for="email_confirmation" value="Confirm Email" />
                <TextInput
                    id="email_confirmation"
                    class="mt-1 block w-full"
                    type="email"
                    name="email_confirmation"
                    bind:value={$form.email_confirmation}
                    required />
                <ErrorMessage
                    message={$form.errors.email_confirmation}
                    class="mt-2" />
            </div>
            <div class="mt-4">
                <Label for="password" value="Password" />
                <TextInput
                    id="password"
                    class="mt-1 block w-full"
                    type="password"
                    name="password"
                    bind:value={$form.password}
                    required
                    autocomplete="new-password" />
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
                <a
                    use:inertia
                    href={route('login')}
                    class={cn(
                        'rounded-md text-sm text-gray-400 underline hover:text-gray-100',
                        'focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800'
                    )}>
                    Already registered?
                </a>

                <Button class="ml-4">Register</Button>
            </div>
        </form>
    </Card>
</Page>
