<script>
    import { inertia, router } from '@inertiajs/svelte';
    import { getModalStore, popup } from '@skeletonlabs/skeleton';
    import { TableHandler } from '@vincjo/datatables';

    import AdminSidebar from '@/Pages/Admin/components/AdminSidebar.svelte';
    import ChevronDown from '@/Components/icons/ChevronDown.svelte';
    import Page from '@/Components/Page.svelte';
    import route from '@/lib/route';
    import { XMark } from '@/Components/icons';

    let { users, auth } = $props();

    let table = $derived(new TableHandler(users.data, { rowsPerPage: 20 }));
    // let rows = $derived(table.getRows());
    let searchTerm = $state('');

    /** @type {import('@skeletonlabs/skeleton').PopupSettings} */
    const actionPopup = {
        event: 'click',
        target: 'actionPopup',
        placement: 'bottom',
    };

    const modalStore = getModalStore();
    const managementActions = [
        {
            label: 'Reset Password',
            criteria: (user, permissions) =>
                !user.is_sso && permissions.includes('admin_reset_password'),
            handler: (user) => router.post(route('admin.resetpassword', user)),
        },
        {
            label: 'Assume',
            criteria: (_, permissions) =>
                permissions.includes('admin_assume_user'),
            handler: (user) => router.post(route('admin.assume', user)),
        },
        {
            label: 'Ban',
            criteria: (_, permissions) => permissions.includes('admin_ban'),
            handler: (user) =>
                modalStore.trigger({
                    type: 'component',
                    component: 'banModal',
                    meta: { bannable: user, type: 'user' },
                }),
        },
        {
            label: 'Delete',
            criteria: (user, permissions) =>
                permissions.includes('admin_delete_user'),
            handler: (user) =>
                modalStore.trigger({
                    type: 'confirm',
                    title: 'Please Confirm',
                    body: 'Are you sure you want to delete this user?',
                    response: (r) =>
                        r && router.delete(route('user.destroy', user)),
                }),
        },
    ];
</script>

<div class="container mt-8 flex h-full flex-row gap-4 px-4">
    <AdminSidebar />
    <Page header="User Management" class="flex-1">
        <div class="table-container space-y-2 overflow-x-auto">
            <header class="flex justify-between gap-4">
                <input
                    bind:value={searchTerm}
                    type="search"
                    placeholder="Search..."
                    class="input" />
            </header>
            <table class="table table-hover table-compact w-full table-auto">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Login Method</th>
                        <th>Last Login <small>(UTC)</small></th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {#each table.rows as user}
                        <tr>
                            <td>{user.id}</td>
                            <td>
                                <a use:inertia href={route('user.show', user)}>
                                    {user.username}
                                </a>
                            </td>
                            <td>
                                <a href={'mailto:' + user.email}>
                                    {user.email}
                                </a>
                                {#if !user.email_verified_at}
                                    <span title="Email not verified">
                                        <XMark class="inline" />
                                    </span>
                                {/if}
                            </td>
                            <td>{user.is_sso ? 'discord' : 'password'}</td>
                            <td>{user.login_at}</td>
                            <td>
                                <button
                                    type="button"
                                    use:popup={actionPopup}
                                    class="flex w-full justify-center">
                                    <ChevronDown />
                                </button>
                                <div data-popup="actionPopup">
                                    <nav
                                        class="card list-nav z-50 w-48 text-left shadow-xl">
                                        <ul>
                                            {#each managementActions as action}
                                                <li>
                                                    <button
                                                        disabled={action.criteria(
                                                            user,
                                                            auth.user
                                                                .permissions
                                                        )}
                                                        type="button"
                                                        class="w-full"
                                                        onclick={() =>
                                                            action.handler(
                                                                user
                                                            )}>
                                                        {action.label}
                                                    </button>
                                                </li>
                                            {/each}
                                        </ul>
                                    </nav>
                                </div>
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        </div>
    </Page>
</div>
