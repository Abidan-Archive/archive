<script>
    import Card from '@/components/Card.svelte';
    export let visible = false;

    let dialog;

    $: if (dialog && visible) {
        dialog.showModal();
    } else if (dialog && !visible) {
        dialog.close();
    }
</script>

<!-- svelte-ignore a11y-click-events-have-key-events a11y-no-noninteractive-element-interactions -->
<dialog
    bind:this={dialog}
    on:click|self={() => dialog.close()}
    class="w-1/2 bg-transparent p-0 text-typo-500">
    <!-- svelte-ignore a11y-no-static-element-interactions -->
    <div on:click|stopPropagation>
        <Card>
            <slot name="header" />
            <hr />
            <slot />
            <hr />

            <slot name="footer" />
        </Card>
    </div>
</dialog>

<style lang="postcss">
    dialog::backdrop {
        background: rgba(0, 0, 0, 0.3);
    }
    dialog[open] {
        animation: zoom 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    @keyframes zoom {
        from {
            transform: scale(0.95);
        }
        to {
            transform: scale(1);
        }
    }
    dialog[open]::backdrop {
        animation: fade 0.2s ease-out;
    }
    @keyframes fade {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
</style>
