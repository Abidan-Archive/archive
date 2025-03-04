<script>
    import Page from '@/Components/Page.svelte';
    import { cn, route } from '@/lib';
    import { Button, Field, Turnstile } from '@/Components/forms';
    import { useForm, inertia } from '@inertiajs/svelte';

    let form = useForm('Register', {
        username: null,
        email: null,
        email_confirmation: null,
        password: null,
        password_confirmation: null,
        turnstile: null,
    });

    function submit(e) {
        e.preventDefault();
        $form.post(route('register'));
    }
</script>

<Page class="w-full md:w-1/2" header="Register">
    <div class="card">
        <form method="POST" onsubmit={submit} class="flex flex-col gap-4">
            <Field {form} name="username" required autofocus />
            <Field {form} name="email" required />
            <Field
                {form}
                name="email_confirmation"
                label="Confirm Email"
                required />

            <Field
                {form}
                name="password"
                type="password"
                required
                autocomplete="new-password" />
            <Field
                {form}
                name="password_confirmation"
                type="password"
                label="Confirm Password" />

            <Turnstile {form} />

            <div class="flex items-center justify-end gap-2">
                <a
                    use:inertia
                    href={route('login')}
                    class={cn(
                        'rounded-md text-sm text-gray-400 underline hover:text-gray-100',
                        'focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800'
                    )}>
                    Already registered?
                </a>

                <Button>Register</Button>
            </div>
        </form>
    </div>
</Page>
