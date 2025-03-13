<script>
    import { page, useForm } from '@inertiajs/svelte';
    import { getModalStore } from '@skeletonlabs/skeleton';
    import cn from '@/lib/cn';
    import route from '@/lib/route';
    import { Button, ErrorMessage, Input, Label } from '@/Components/forms';

    let { parent } = $props();

    const modalStore = getModalStore();

    let form = useForm({
        user_id: $modalStore[0].meta.user.id,
        roles: $modalStore[0].meta.user.roles?.map((role) => role.name) ?? [],
    });
    let permissions = $derived(
        [].concat(
            ...$page.props.roles
                .filter((r) => $form.roles.includes(r.name))
                .map((r) => r.permissions.map((p) => `${p.label} - ${r.label}`))
        )
    );

    function submit(e) {
        e.preventDefault();
        $form.post(route('admin.assign-role'), {
            preserveScroll: true,
            onSuccess: () => modalStore.close(),
        });
    }
</script>

<section class={cn('card', parent.width)}>
    <h3 class="pb-2 font-bold">Manage Roles</h3>
    <form method="POST" onsubmit={submit} class="flex flex-col gap-4">
        <div class="flex flex-col gap-2">
            <Label for="user_id" value="User (readonly)" />
            <Input
                id="user_id"
                name="user_id"
                type="text"
                readonly={true}
                tabindex="-1"
                value={$modalStore[0].meta.user.username} />
            <ErrorMessage message={$form.errors['user_id']} />
        </div>

        <div class="flex flex-col gap-2">
            <Label for="roles" value="Roles" />
            <select class="select" multiple bind:value={$form.roles}>
                {#each $page.props.roles as role}
                    <option value={role.name}>{role.label}</option>
                {/each}
            </select>
            <ErrorMessage message={$form.errors['roles']} />

            <h4 class="pb-1 font-bold">Permissions</h4>
            <ul class=" max-h-40 space-y-1 overflow-auto text-sm">
                {#each permissions as permission}
                    <li>{permission}</li>
                {:else}
                    <li>None</li>
                {/each}
            </ul>
        </div>

        <div class="flex items-center justify-between pt-4">
            <Button type="button" onclick={() => modalStore.close()}>
                Cancel
            </Button>
            <Button type="submit">Update</Button>
        </div>
    </form>
</section>
