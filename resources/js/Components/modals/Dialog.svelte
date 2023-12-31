<script>
    import {getContext} from 'svelte';
    import Button from '@components/forms/Button.svelte';

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
    <h2 class="text-3xl mb-8 text-center">{message}</h2>
    <div class="flex justify-between">
        <Button variant="destructive" class="font-bold w-5/12"
            on:click={_onCancel}
        >{cancelText}</Button>
        <Button size='small' class="bg-green-600 hover:bg-green-600/90 text-enkai-500 font-bold w-5/12"
            on:click={_onOkay}>{okayText}</Button>
    </div>
</div>
