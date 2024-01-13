<script>
    import { getContext } from 'svelte';
    import Button from '@/Components/forms/Button.svelte';

    export let message = '';
    export let onCancel = () => {};
    export let onOkay = () => {};

    export let cancelText = 'Cancel';
    export let okayText = 'Okay';

    const { close } = getContext('simple-modal');

    function _onCancel() {
        onCancel();
        close();
    }
    function _onOkay() {
        onOkay();
        close();
    }
</script>

<div>
    <h2 class="mb-8 text-center text-3xl">{message}</h2>
    <div class="flex justify-between">
        <Button
            variant="destructive"
            class="w-5/12 font-bold"
            on:click={_onCancel}>{cancelText}</Button>
        <Button
            size="small"
            class="w-5/12 bg-green-600 font-bold text-enkai-500 hover:bg-green-600/90"
            on:click={_onOkay}>{okayText}</Button>
    </div>
</div>
