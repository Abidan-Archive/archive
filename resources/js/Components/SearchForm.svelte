<script>
    import clsx from 'clsx';
    import { useForm } from '@inertiajs/svelte';
    import Lens from '@components/icons/Lens';

    const urlParams = new URLSearchParams(window.location.search);
    let form = useForm({
        query: urlParams.get('query') || '',
    });

    function submit() {
        $form.get('/search');
    }
</script>

<form class={clsx('flex', $$props.class)} on:submit|preventDefault={submit}>
    <label for="simple-search" class="sr-only">Search</label>
    <div class="relative w-full">
        <div
            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <Lens />
        </div>
        <input
            type="text"
            bind:value={$form.query}
            id="simple-search"
            class="focus:ring-black-800 focus:ring-black-500 block w-full rounded-lg border border-gray-600 bg-gray-700 p-2.5  pl-10 text-center text-sm font-bold text-white placeholder-gray-400 focus:border-abidan-700"
            placeholder="[Information Requested]"
            required />
    </div>
    <button
        type="submit"
        disabled={$form.processing}
        class="ml-2 rounded-lg border border-abidan-700 bg-abidan-700 p-2.5 text-sm font-medium text-white hover:bg-abidan-800 focus:outline-none focus:ring-4 focus:ring-abidan-400 dark:bg-abidan-500 dark:hover:bg-abidan-700 dark:focus:ring-abidan-800">
        <Lens class="h-5 w-5 text-white" />
        <span class="sr-only">Search</span>
    </button>
</form>
