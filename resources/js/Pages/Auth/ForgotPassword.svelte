<script>
    import Page from '@/Components/Page.svelte';
    import route from '@/lib/route';
    import { ErrorMessage, Label, Button, Input } from '@/Components/forms';
    import { useForm, page } from '@inertiajs/svelte';
    import { onMount } from 'svelte';
    import recaptcha from '@/lib/recaptcha';
    import { getToastStore } from '@skeletonlabs/skeleton';

    const toastStore = getToastStore();

    onMount(() => {
        if ($page.props.status)
            toastStore.trigger({
                message: $page.props.status,
                background: 'variant-filled-success',
            });
    });

    let form = useForm({
        email: null,
        recaptcha: null,
    });

    function submit() {
        recaptcha('password/email', (token) => {
            $form.recaptcha = token;
            $form.post(route('password.email'));
        });
    }
</script>

<Page class="w-1/2" header="Forgot Password">
    <div class="card">
        <div class="mb-4 text-sm text-gray-400">
            Forgot your password? No problem. Just let us know your email
            address and we will email you a password reset link that will allow
            you to choose a new one.
        </div>

        <form method="POST" on:submit|preventDefault={submit}>
            <div>
                <Label for="email" value="Email" />
                <Input
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
    </div>
</Page>
