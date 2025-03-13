<script>
    import { inertia, router } from '@inertiajs/svelte';
    import { getModalStore, popup } from '@skeletonlabs/skeleton';

    import AdminSidebar from '@/Pages/Admin/components/AdminSidebar.svelte';
    import ChevronDown from '@/Components/icons/ChevronDown.svelte';
    import Page from '@/Components/Page.svelte';
    import route from '@/lib/route';
    import { XMark } from '@/Components/icons';
    import Search from '@/Components/datatables/Search.svelte';
    import ThSort from '@/Components/datatables/ThSort.svelte';
    import ThFilter from '@/Components/datatables/ThFilter.svelte';
    import RowCount from '@/Components/datatables/RowCount.svelte';
    import Paginator from '@/Components/Paginator.svelte';
    import { parseNestedParams } from '@/lib/url';

    let { users, auth } = $props();

    const modalStore = getModalStore();
    const managementActions = [
        {
            label: 'Reset Password',
            criteria: (user, permissions) =>
                !user.is_sso && permissions.includes('admin_reset_password'),
            handler: (user) => router.post(route('admin.reset-password', user)),
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
            label: 'Assign Role',
            criteria: (_, permissions) =>
                permissions.includes('admin_manage_permissions'),
            handler: (user) =>
                modalStore.trigger({
                    type: 'component',
                    component: 'changeUserRoleModal',
                    meta: { user },
                }),
        },
        {
            label: 'Assume',
            criteria: (_, permissions) =>
                permissions.includes('admin_assume_user'),
            handler: (user) => router.post(route('admin.assume', user)),
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
    let filters = $state(
        parseNestedParams(
            Object.fromEntries(new URLSearchParams(window.location.search))
        )
    );
    function onSearch(search) {
        filters = { ...filters, search };
    }
    function onSortChange(sort, direction) {
        filters = { ...filters, sort, direction };
    }
    function onFilterChange(value, field) {
        filters = {
            ...filters,
            column: {
                ...filters.column,
                [field]: value,
            },
        };
    }
    let initialized = false;
    $effect(() => {
        if (!initialized) {
            filters; // If not present, will not run the effect again past first load due to no state change analaysis
            initialized = true;
            return;
        }
        // Filters changed, get our users data refer she
        router.get(route('admin.user'), filters, {
            preserveState: true,
            only: ['users'],
        });
    });
</script>

{#snippet action(user)}
    <button
        type="button"
        use:popup={{
            event: 'click',
            placement: 'bottom',
            target: `actionPopup-${user.id}`,
            closeQuery: '.listbox-item',
        }}
        class="flex w-full justify-center">
        <ChevronDown />
    </button>
    <div data-popup="actionPopup-{user.id}">
        <nav class="card list-nav w-48 text-left shadow-xl">
            <ul>
                {#each managementActions as action}
                    {#if action.criteria(user, auth.user.permissions)}
                        <li>
                            <button
                                type="button"
                                class="listbox-item w-full"
                                onclick={() => action.handler(user)}>
                                {action.label}
                            </button>
                        </li>
                    {/if}
                {:else}
                    <li>No actions available</li>
                {/each}
            </ul>
        </nav>
    </div>
{/snippet}

<div class="mt-8 flex h-full flex-row gap-4 px-4">
    <AdminSidebar />
    <Page header="User Management" class="flex-1">
        <div class="space-y-2 overflow-y-auto">
            <header class="flex justify-between gap-4">
                <Search handler={onSearch} {filters} />
            </header>
            <table class="table table-compact w-full table-auto">
                <thead>
                    <tr>
                        <ThSort orderBy="id" handler={onSortChange} {filters}>
                            ID
                        </ThSort>
                        <ThSort
                            orderBy="username"
                            handler={onSortChange}
                            {filters}>
                            Username
                        </ThSort>
                        <ThSort
                            orderBy="email"
                            handler={onSortChange}
                            {filters}>
                            Email
                        </ThSort>
                        <th>Login Method</th>
                        <ThSort
                            orderBy="login_at"
                            handler={onSortChange}
                            {filters}>
                            Last Login <small>(UTC)</small>
                        </ThSort>
                        <th class="text-center">Actions</th>
                    </tr>
                    <tr>
                        <ThFilter
                            filterBy="id"
                            handler={onFilterChange}
                            {filters} />
                        <ThFilter
                            filterBy="username"
                            handler={onFilterChange} />
                        <ThFilter
                            filterBy="email"
                            handler={onFilterChange}
                            {filters} />
                        <th></th>
                        <ThFilter
                            filterBy="login_at"
                            handler={onFilterChange}
                            {filters} />
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {#each users.data as user}
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
                                {@render action(user)}
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
            <footer class="flex justify-between">
                <RowCount {...users} />
                <Paginator {...users} />
            </footer>
        </div>
    </Page>
</div>
