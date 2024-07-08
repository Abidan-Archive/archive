<script>
    import AdminSidebar from '@/Pages/Admin/components/AdminSidebar.svelte';
    import ChevronDown from '@/components/icons/ChevronDown.svelte';
    import Page from '@/components/Page.svelte';
    import route from '@/lib/route';
    import { XMark } from '@/components/icons';
    import { inertia, router } from '@inertiajs/svelte';
    import { popup } from '@skeletonlabs/skeleton';

    export let users;

    /** @type {import('@skeletonlabs/skeleton').PopupSettings} */
    const actionPopup = {
        event: 'click',
        target: 'actionPopup',
        placement: 'bottom',
    };

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
            handler: (user) => console.log('Ban', user),
        },
        {
            label: 'Delete',
            handler: (user) => console.log('Delete', user),
        },
    ];
</script>

<div class="container mt-8 flex h-full flex-row gap-4 px-4">
    <AdminSidebar />
    <Page header="User Management" class="flex-1">
        <div class="table-container">
            <table class="table table-hover table-compact">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Username</th>
                        <th>Email</th>
                        <td>Login Method</td>
                        <th>Last Login <small>(UTC)</small></th>
                        <th>Actions</th>
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
                                    <user title="Email not verified">
                                        <XMark />
                                    </user>
                                {/if}
                            </td>
                            <td>{user.is_sso ? 'discord' : 'password'}</td>
                            <td>{user.login_at}</td>
                            <td class="text-center">
                                <button type="button" use:popup={actionPopup}>
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
