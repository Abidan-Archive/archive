<script>
    import {
        Autocomplete,
        getModalStore,
        getToastStore,
        popup,
    } from '@skeletonlabs/skeleton';
    import Card from '@/components/Card.svelte';
    import cn from '@/lib/cn';
    import route from '@/lib/route';
    import { Button, ErrorMessage, Input, Label } from '@/components/forms';
    import { useForm } from '@inertiajs/svelte';
    import axios from 'axios';

    export let parent;

    const modalStore = getModalStore();
    const toastStore = getToastStore();

    let debounceTimer;
    function debounce(callback, wait = 500) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(callback, wait);
    }

    let form = useForm({
        bannable_id: $modalStore[0].meta.bannable.id,
        type: $modalStore[0].meta.type,
        reason: '',
        expires: null,
    });

    let searchInput =
        $modalStore[0].meta.bannable?.username ||
        $modalStore[0].meta.bannable?.ip ||
        $modalStore[0].meta.bannable?.id ||
        '';
    let searchOptions = [];
    function onInput() {
        if (searchInput) {
            debounce(() => {
                axios
                    .post(route('admin.query'), {
                        q: searchInput,
                        type: $form.type,
                    })
                    .then((response) => {
                        searchOptions = response.data.results;
                    })
                    .catch((error) => {
                        console.error(error);
                        toastStore.trigger({
                            background: 'error',
                            message: 'Something went wrong',
                        });
                    });
            });
        } else {
            searchOptions = [];
        }
    }

    function select(event) {
        searchInput = event.detail.label;
        $form.bannable_id = event.detail.value;
    }

    function submit() {
        $form.post(route('admin.ban'), {
            preserveScroll: true,
            onSuccess: () => modalStore.close(),
        });
    }
</script>

<Card is="section" class={cn('', parent.width)}>
    <h3 class="pb-2 font-bold">Create Ban</h3>
    <form
        method="POST"
        on:submit|preventDefault={submit}
        class="flex flex-col gap-4">
        <div class="flex flex-col gap-4">
            <Label for="type">Type</Label>
            <select
                name="type"
                class="select"
                bind:value={$form.type}
                on:change={() => {
                    searchInput = '';
                    searchOptions = [];
                }}>
                <option value="user">User</option>
                <option value="ip">IP</option>
            </select>
            <ErrorMessage message={$form.errors.type} />
        </div>

        <div class="flex flex-col gap-4">
            <Label for="bannable"
                >{$form.type === 'ip' ? 'Which' : 'Who'}</Label>
            <input
                class="input"
                type="search"
                name="search"
                bind:value={searchInput}
                on:input={onInput}
                use:popup={{
                    event: 'focus-click',
                    target: 'popupAutocomplete',
                    placement: 'bottom',
                }} />
            <div data-popup="popupAutocomplete" class="z-10">
                <div
                    class="card mx-auto max-h-48 w-full max-w-sm overflow-y-auto p-4"
                    tabindex="-1">
                    <Autocomplete
                        bind:input={searchInput}
                        options={searchOptions}
                        on:selection={select} />
                </div>
            </div>
            <ErrorMessage message={$form.errors.bannable} />
        </div>

        <div class="flex flex-col gap-4">
            <Label for="expires">Expires</Label>
            <Input
                class="form-input"
                type="datetime-local"
                name="expires"
                bind:value={$form.expires} />
            <ErrorMessage message={$form.errors.expires} />
        </div>

        <div class="flex flex-col gap-4">
            <Label for="reason">Reason</Label>
            <textarea
                class="textarea"
                rows="4"
                value={$form.reason}
                required
                placeholder="Justify your ban reason, this is for internal use." />
            <ErrorMessage message={$form.errors.reason} />
        </div>
        <div class="flex items-center justify-between pt-4">
            <Button type="button" on:click={() => modalStore.close()}
                >Cancel</Button>
            <Button type="submit">Ban</Button>
        </div>
    </form>
</Card>
