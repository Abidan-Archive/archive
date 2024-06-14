<script>
    import { TabGroup, Tab } from '@skeletonlabs/skeleton';
    import Page from '@/components/Page.svelte';
    import { useForm, page } from '@inertiajs/svelte';
    import { route, recaptcha } from '@/lib';
    import { ErrorMessage, Label, Input } from '@/components/forms';
    import CircleInfo from '@/components/icons/CircleInfo.svelte';

    export let user;

    const isAdmin = $page.props.auth.user?.roles.includes('admin');

    // let settingsForm = useForm({});

    let accountForm = useForm({
        username: user.username,
        email: user.email,
        email_confirmation: null,
        new_passsword: null,
        new_password_confirmation: null,
        password: null,
        recaptcha: null,
    });

    function accountSubmit() {
        recaptcha('user/update', (token) => {
            $accountForm.recaptcha = token;
            $accountForm.patch(route('user.update', user));
        });
    }
    function settingsSubmit() {}
    let activeTab = 1;
</script>

<Page header="User Settings">
    <TabGroup>
        <Tab bind:group={activeTab} name="tab0" value={0}>General</Tab>
        <Tab bind:group={activeTab} name="tab1" value={1}>Account</Tab>
        <svelte:fragment slot="panel">
            {#if activeTab === 0}
                <form method="POST" on:submit|preventDefault={settingsSubmit}>
                    WIP Settings
                </form>
            {:else if activeTab === 1}
                <form
                    method="POST"
                    on:submit|preventDefault={accountSubmit}
                    class="flex flex-col gap-4">
                    <div>
                        <Label for="username" value="Username" />
                        <Input
                            id="username"
                            class="mt-1 block w-full"
                            name="username"
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
                            bind:value={$accountForm.email} />
                        <ErrorMessage
                            message={$accountForm.errors.email}
                            class="mt-2" />
                    </div>
                    <div>
                        <Label for="email_confirmation" value="Confirm Email" />
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
                            name="new_password"
                            type="password"
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
                            bind:value={$accountForm.new_password_confirmation} />
                        <ErrorMessage
                            message={$accountForm.errors
                                .new_password_confirmation}
                            class="mt-2" />
                    </div>
                    <hr />
                    <div>
                        {#if isAdmin}
                            <aside class="alert variant-ghost-warning mb-2">
                                <div class="alert-message">
                                    <h3 class="h3">
                                        <CircleInfo class="inline" /> Hey Admin
                                    </h3>
                                    <p>
                                        This field is still required, use your
                                        own account's password.
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
                    <div class="text-right">
                        <button class="variant-filled btn">Save</button>
                    </div>
                </form>
            {/if}
        </svelte:fragment>
    </TabGroup>
</Page>
