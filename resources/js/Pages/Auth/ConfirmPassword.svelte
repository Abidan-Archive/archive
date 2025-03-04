<script>
    import Page from '@/Components/Page.svelte';
    import route from '@/lib/route';
    import { Field, Button, Turnstile } from '@/Components/forms';
    import { useForm } from '@inertiajs/svelte';

    let form = useForm({
        password: null,
        turnstile: null,
    });

    function submit(e) {
        e.preventDefault();
        $form.post(route('password.confirm'));
    }
</script>

<Page class="w-full md:w-1/2" header="Confirm Password">
    <div class="card">
        <form method="POST" onsubmit={submit} class="flex flex-col gap-4">
            <div class="text-sm text-gray-400">
                This is a secure area of the application. Please confirm
                password before continuing.
            </div>
            <Field
                {form}
                name="password"
                type="password"
                autocomplete="current-password" />

            <Turnstile {form} />

            <div class="flex justify-end">
                <Button>Confirm</Button>
            </div>
        </form>
    </div>
</Page>
