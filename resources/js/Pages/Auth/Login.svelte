<script>
    import Toast from '@/Components/Toast.svelte';
    import Card from '@/Components/Card.svelte';
    import { cn, route } from '@/Utils';
    import { ErrorMessage, Label, Button, TextInput } from '@/Components/forms';
    import { useForm, inertia, page } from '@inertiajs/svelte';

    let form = useForm({
        email: null,
        password: null,
        remember: false,
    });

    function submit() {
        $form.post(route('login'));
    }
</script>

<section class="contianer mx-auto mt-10 w-1/2">
    <h2 class="my-5 text-2xl">Login</h2>
    <Card>
        <Toast message={$page.props.status} />

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

            <div class="mt-4">
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

            <div class="mt-4">
                <Label for="remember_me" class="inline-flex items-center">
                    <input
                        id="remember_me"
                        type="checkbox"
                        class={cn(
                            'rounded border-gray-700 bg-gray-900 text-indigo-600 shadow-sm',
                            'focus:ring-indigo-600 focus:ring-offset-gray-800'
                        )}
                        bind:checked={$form.remember}
                        name="remember" />
                    <span class="ml-2 text-sm text-gray-400">Remember Me</span>
                </Label>
            </div>

            <div class="mt-4 flex items-baseline justify-between">
                <a
                    use:inertia
                    href={route('register')}
                    class={cn(
                        'rounded-md text-sm text-gray-400 underline hover:text-gray-100',
                        'focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800'
                    )}>
                    Sign up
                </a>
                <div class="flex items-baseline justify-end">
                    {#if route().has('password.request')}
                        <a
                            use:inertia
                            href={route('password.request')}
                            class={cn(
                                'rounded-md text-sm text-gray-400 underline hover:text-gray-100',
                                'focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800'
                            )}>
                            Forgot your password?
                        </a>
                    {/if}

                    <Button class="ml-3">Log in</Button>
                </div>
            </div>
        </form>
    </Card>
</section>
