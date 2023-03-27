<script>
    export let message;
</script>

{#if !!message}
    <ul class={`space-y-1 text-sm text-red-400 ${$$props.class}`}>
        <li>{message}</li>
    </ul>
{/if}
