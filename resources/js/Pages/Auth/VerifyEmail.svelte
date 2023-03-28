<script>
    import route from '@/route';
    import {
        ErrorMessage,
        Label,
        PrimaryButton,
        TextInput,
    } from '@components/forms';
    import { page, router } from '@inertiajs/svelte';

    function resend() {
        router.post(route('verification.send'));
    }
    function logout() {
        router.post(route('logout'));
    }
</script>

<div class="mb-4 text-sm text-gray-400">
    Thanks for signing up! Before getting started, could you verify your email
    address by clicking on the link we just emailed to you? If you didn't
    receive the email, we will gladly send you another.
</div>

{#if $page.props.status == 'verification-link-sent'}
    <div class="mb-4 text-sm font-medium text-green-400">
        A new verification link as been sent to the email address you provided
        during registration.
    </div>
{/if}

<div class="mt-4 flex items-center justify-between">
    <form method="POST" on:submit|preventDefault={resend}>
        <div>
            <PrimaryButton>Resend Verification Email</PrimaryButton>
        </div>
    </form>

    <form method="POST" on:submit|preventDefault={logout}>
        <button
            type="submit"
            class="focus:ring=2 rounded-md text-sm text-gray-400 underline hover:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800">
            Log Out
        </button>
    </form>
</div>
