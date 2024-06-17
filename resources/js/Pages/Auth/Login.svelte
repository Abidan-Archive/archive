<script>
    import DiscordLogo from '@/components/DiscordLogo.svelte';
    import Page from '@/components/Page.svelte';
    import Card from '@/components/Card.svelte';
    import { cn, recaptcha, route } from '@/lib';
    import { ErrorMessage, Label, Button, Input } from '@/components/forms';
    import { useForm, inertia } from '@inertiajs/svelte';

    let form = useForm({
        email: null,
        password: null,
        remember: false,
        recaptcha: null,
    });

    function submit() {
        recaptcha('login', (token) => {
            $form.recaptcha = token;
            $form.post(route('login'));
        });
    }
</script>

<Page class="w-full md:w-1/2" header="Login">
    <Card>
        <form method="POST" on:submit|preventDefault={submit}>
            <div>
                <Label for="email">Email</Label>
                <Input
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
                <Input
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
        <hr />
        <div class="flex flex-col items-center gap-2">
            <p>Sign in with a third-party provider</p>
            <a href={route('oauth.discord')} class="discord-sso">
                <span class="shadow" />
                <span class="edge" />
                <span class="front"><DiscordLogo /></span>
            </a>
        </div>
    </Card>
</Page>

<style lang="postcss">
    .discord-sso {
        @apply relative cursor-pointer rounded-sm bg-transparent;
        transition: filter 250ms;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
    }
    .discord-sso:hover {
        filter: brightness(110%);
    }
    .discord-sso:focus:not(:focus-visible) {
        outline: none;
    }
    .discord-sso > .front {
        @apply relative block rounded-sm bg-[#5865F2] px-4 py-2;
        will-change: transform;
        transform: translateY(-4px);
        transition: transform 600ms cubic-bezier(0.3, 0.7, 0.4, 1);
    }
    .discord-sso:hover > .front {
        transform: translateY(-6px);
        transition: transform 250ms cubic-bezier(0.3, 0.7, 0.4, 1.5);
    }
    .discord-sso:active > .front {
        transform: translateY(-2px);
        transition: transform 34ms;
    }
    .discord-sso > .edge {
        @apply absolute left-0 top-0 h-full w-full rounded-sm;
        background: linear-gradient(
            to left,
            hsl(235deg, 85%, 52%) 0%,
            hsl(235deg, 85%, 60%) 8%,
            hsl(235deg, 85%, 60%) 92%,
            hsl(235deg, 85%, 52%) 100%
        );
    }
    .discord-sso > .shadow {
        @apply absolute left-0 top-0 h-full w-full rounded-sm;
        will-change: transform;
        background: hsl(0deg 0% 0% / 0.25);
        transform: translateY(2px);
        transition: transform 600ms cubic-bezier(0.3, 0.7, 0.4, 1);
        filter: blur(4px);
    }
    .discord-sso:hover > .shadow {
        transform: translateY(4px);
        transition: transform 250ms cubic-bezier(0.3, 0.7, 0.4, 1.5);
    }
    .discord-sso:active > .shadow {
        transform: translateY(1px);
        transition: transform 34ms;
    }
</style>
