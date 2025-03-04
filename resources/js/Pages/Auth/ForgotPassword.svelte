<script>
    import Page from '@/Components/Page.svelte';
    import route from '@/lib/route';
    import { Field, Button, Turnstile } from '@/Components/forms';
    import { useForm, page } from '@inertiajs/svelte';
    import { onMount } from 'svelte';
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
        turnstile: null,
    });

    function submit(e) {
        e.preventDefault();
        $form.post(route('password.email'));
    }
</script>

<Page class="w-1/2" header="Forgot Password">
    <div class="card">
        <form method="POST" onsubmit={submit} class="flex flex-col gap-4">
            <div class="text-sm text-gray-400">
                Forgot your password? No problem. Just let us know your email
                address and we will email you a password reset link that will
                allow you to choose a new one.
            </div>

            <Field {form} name="email" type="email" required autofocus />

            <Turnstile {form} />

            <div class="flex items-center justify-end">
                <Button>Email Password Reset Link</Button>
            </div>
        </form>
    </div>
</Page>
