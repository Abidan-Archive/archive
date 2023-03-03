<script>
    import { useForm } from "@inertiajs/svelte";
    import Lens from "@components/icons";
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
            <Lens/>  
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
        <Lens/>  
        <span class="sr-only">Search</span>
    </button>
</form>
