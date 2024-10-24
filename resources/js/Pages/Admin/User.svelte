<script>
    import AdminSidebar from '@/Pages/Admin/components/AdminSidebar.svelte';
    import ChevronDown from '@/Components/icons/ChevronDown.svelte';
    import Page from '@/Components/Page.svelte';
    import route from '@/lib/route';
    import { XMark } from '@/Components/icons';
    import { inertia, router } from '@inertiajs/svelte';
    import { popup } from '@skeletonlabs/skeleton';
    import { getModalStore } from '@skeletonlabs/skeleton';

    export let users;

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
            handler: (user) => console.log('Reset Password', user),
        },
        {
            label: 'Assume',
            handler: (user) => router.post(route('admin.assume', user)),
        },
        {
            label: 'Ban',
            handler: (user) =>
                modalStore.trigger({
                    type: 'component',
                    component: 'banModal',
                    meta: { bannable: user, type: 'user' },
                }),
        },
        {
            label: 'Delete',
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
        <div class="table-container">
            <table class="table table-hover table-compact w-full">
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
                    {#each users as user}
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
                                                        type="button"
                                                        class="w-full"
                                                        on:click={() =>
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
