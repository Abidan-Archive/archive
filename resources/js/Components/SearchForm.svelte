<script>
    import cn from '@/lib/cn';
    import { useForm } from '@inertiajs/svelte';
    import Lens from '@/Components/icons/Lens';

    let className;
    export { className as class };

    const urlParams = new URLSearchParams(window.location.search);
    let form = useForm({
        query: urlParams.get('query') || '',
    });

    function submit() {
        $form.get('/search');
    }
</script>

<form class={cn('flex', className)} on:submit|preventDefault={submit}>
    <div class="flex h-14 w-full justify-center">
        <div
            class="relative m-0 h-full flex-1 border-2 border-primary-400 bg-black p-1.5 shadow-[0_0_0_0.10rem] shadow-transparent transition-all duration-500 focus-within:border-cyan-500 focus-within:shadow-base-500 hover:border-cyan-500 hover:shadow-base-500">
            <label for="simple-search" class="sr-only">Search</label>
            <input
                type="text"
                bind:value={$form.query}
                id="simple-search"
                placeholder="[Information Requested]"
                class="group m-0 h-full w-full rounded-sm border-none bg-base-900 ring-0 placeholder:text-center focus:border-none focus:ring-0" />
        </div>
        <button
            type="submit"
            disabled={$form.processing}
            class="flex h-full w-20 items-center justify-center bg-primary-400 px-3 py-2 transition-colors duration-500 hover:bg-cyan-400 z-10">
            <Lens class="block h-5 w-5 text-base-500" />
            <span class="sr-only">Search Button</span>
        </button>
    </div>
</form>
