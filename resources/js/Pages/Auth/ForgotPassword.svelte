<script>
    import Page from '@/Components/Page.svelte';
    import Card from '@/Components/Card.svelte';
    import route from '@/lib/route';
    import { ErrorMessage, Label, Button, Input } from '@/Components/forms';
    import { useForm, page } from '@inertiajs/svelte';
    import { onMount } from 'svelte';
    import { addToast } from '@/Stores/toast';

    onMount(() => {
        if ($page.props.status)
            addToast({ message: status, type: 'success', timeout: false });
    });

    let form = useForm('ForgotPassword', {
        email: null,
    });

    function submit() {
        $form.post(route('password.email'));
    }
</script>

<Page class="w-1/2" header="Forgot Password">
    <Card>
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
    </Card>
</Page>
