<script>
    export let status;
</script>

{#if !!status}
    <div class={`text-sm font-medium text-green-400 ${$$props.class}`}>
        {status}
    </div>
{/if}
