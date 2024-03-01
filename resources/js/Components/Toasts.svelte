<script>
    import Toast from '@/Components/Toast.svelte';
    import { dismissToast, toasts } from '@/Stores/toast';
</script>

{#if $toasts}
    <section
        class="pointer-events-none fixed left-0 right-0 top-0 z-50 mt-20 flex w-full flex-col justify-center">
        {#each $toasts as toast (toast.id)}
            <Toast
                type={toast.type}
                dismissible={toast.dismissible}
                on:dismiss={() => dismissToast(toast.id)}>
                {toast.message}
            </Toast>
        {/each}
    </section>
{/if}
