<script>
    import { TabGroup, Tab } from '@skeletonlabs/skeleton';
    import Page from '@/Components/Page.svelte';
    import { useForm, page } from '@inertiajs/svelte';
    import { route } from '@/lib/route';
    import { ErrorMessage, Label, Input, Turnstile } from '@/Components/forms';
    import CircleInfo from '@/Components/icons/CircleInfo.svelte';

    let { user } = $props();

    const isAdmin = $page.props.auth.user?.roles.includes('admin');

    // let settingsForm = useForm({});

    let accountForm = useForm({
        username: user.username,
        email: user.email,
        email_confirmation: null,
        new_password: null,
        new_password_confirmation: null,
        password: null,
        turnstile: null,
    });

    function removeEmptyAndSame(obj) {
        return Object.fromEntries(
            Object.entries(obj).filter(([k, v]) => v && user[k] != v)
        );
    }

    function accountSubmit(e) {
        e.preventDefault();
        $accountForm
            .transform(removeEmptyAndSame)
            .patch(route('user.update', user));
        $accountForm.reset('email_confirmation', 'new_password_confirmation');
    }
    function settingsSubmit(e) {
        e.preventDefault();
    }
    let activeTab = $state(1);
</script>

<Page header="User Settings">
    <TabGroup>
        <!-- <Tab bind:group={activeTab} name="tab0" value={0}>General</Tab> -->
        <Tab bind:group={activeTab} name="tab1" value={1}>Account</Tab>
        {#snippet panel()}
            {#if activeTab === 0}
                <form method="POST" onsubmit={settingsSubmit}>
                    WIP Settings
                </form>
            {:else if activeTab === 1}
                <form
                    method="POST"
                    onsubmit={accountSubmit}
                    class="flex flex-col gap-4">
                    {#if user.is_sso}
                        <aside class="alert variant-ghost-primary mb-2">
                            <div class="alert-message">
                                <h3 class="h3">
                                    <CircleInfo class="inline" /> Hey Boss
                                </h3>
                                <p>
                                    This account is using SSO to sign in.<br />
                                    Your account details are managed by your third-party
                                    account.
                                </p>
                            </div>
                        </aside>
                    {/if}
                    <div>
                        <Label for="username" value="Username" />
                        <Input
                            id="username"
                            class="mt-1 block w-full"
                            name="username"
                            readonly={user.is_sso}
                            bind:value={$accountForm.username} />
                        <ErrorMessage
                            message={$accountForm.errors.username}
                            class="mt-2" />
                    </div>
                    <div>
                        <Label for="email" value="Email" />
                        <Input
                            id="email"
                            class="mt-1 block w-full"
                            name="email"
                            type="email"
                            readonly={user.is_sso}
                            bind:value={$accountForm.email} />
                        <ErrorMessage
                            message={$accountForm.errors.email}
                            class="mt-2" />
                    </div>
                    {#if !user.is_sso}
                        <div>
                            <Label
                                for="email_confirmation"
                                value="Confirm Email" />
                            <Input
                                id="email_confirmation"
                                class="mt-1 block w-full"
                                name="email_confirmation"
                                type="email"
                                bind:value={$accountForm.email_confirmation} />
                            <ErrorMessage
                                message={$accountForm.errors.email_confirmation}
                                class="mt-2" />
                        </div>
                        <div>
                            <Label for="new_password" value="New Password" />
                            <Input
                                id="new_password"
                                class="mt-1 block w-full"
                                type="password"
                                name="new_password"
                                bind:value={$accountForm.new_password} />
                            <ErrorMessage
                                message={$accountForm.errors.new_password}
                                class="mt-2" />
                        </div>
                        <div>
                            <Label
                                for="new_password_confirmation"
                                value="Confirm New Password" />
                            <Input
                                id="new_password_confirmation"
                                class="mt-1 block w-full"
                                type="password"
                                name="new_password_confirmation"
                                bind:value={
                                    $accountForm.new_password_confirmation
                                } />
                            <ErrorMessage
                                message={$accountForm.errors
                                    .new_password_confirmation}
                                class="mt-2" />
                        </div>
                        <hr />
                        <div>
                            {#if isAdmin && user.id !== $page.props.auth.user?.id}
                                <aside class="alert variant-ghost-primary mb-2">
                                    <div class="alert-message">
                                        <h3 class="h3">
                                            <CircleInfo class="inline" /> Hey Admin
                                        </h3>
                                        <p>
                                            This field is still required, use
                                            your own account password.
                                        </p>
                                    </div>
                                </aside>
                            {/if}
                            <Label for="password" value="Current Password" />
                            <Input
                                id="password"
                                class="mt-1 block w-full"
                                name="password"
                                type="password"
                                required
                                bind:value={$accountForm.password} />
                            <ErrorMessage
                                message={$accountForm.errors.password}
                                class="mt-2" />
                        </div>
                        <Turnstile form={accountForm} />
                        <div class="text-right">
                            <button class="variant-filled btn">Save</button>
                        </div>
                    {/if}
                </form>
            {/if}
        {/snippet}
    </TabGroup>
</Page>
