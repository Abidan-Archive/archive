<script>
    import { useForm } from "@inertiajs/svelte";

    let form = useForm({
        q: "",
    });

    function submit() {
        $form.get("/search");
    }
</script>

<form
    class="z-10 lg:w-1/3 md:w-1/2 sm:w-3/4 flex"
    on:submit|preventDefault={submit}
>
    <label for="simple-search" class="sr-only">Search</label>
    <div class="relative w-full">
        <div
            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
        >
            <svg
                aria-hidden="true"
                class="w-5 h-5 text-gray-500 dark:text-gray-400"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                ><path
                    fill-rule="evenodd"
                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                    clip-rule="evenodd"
                /></svg
            >
        </div>
        <input
            type="text"
            bind:value={$form.q}
            id="simple-search"
            class="border text-sm rounded-lg focus:ring-black-800 focus:border-abidan-700 block w-full pl-10 p-2.5  bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-black-500 font-bold text-center"
            placeholder="[Information Requested]"
            required
        />
    </div>
    <button
        type="submit"
        disabled={$form.processing}
        class="p-2.5 ml-2 text-sm font-medium text-white bg-abidan-700 rounded-lg border border-abidan-700 hover:bg-abidan-800 focus:ring-4 focus:outline-none focus:ring-abidan-400 dark:bg-abidan-500 dark:hover:bg-abidan-700 dark:focus:ring-abidan-800"
    >
        <svg
            class="w-5 h-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
            ><path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
            /></svg
        >
        <span class="sr-only">Search</span>
    </button>
</form>

<style lang="scss">
    $marbleBg: #090b29;
    $marbleInner: #ad98e7;
    $fireDarkest: #131852;
    $fireDark: #131e94;
</style>
