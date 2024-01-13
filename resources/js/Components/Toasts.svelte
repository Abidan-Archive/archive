<script>
    import Toast from '@/Components/Toast.svelte';
    import { dismissToast, toasts } from './stores/toasts';
</script>

{#if $toasts}
    <section
        class="fixed top-0 left-0 right-0 z-50 mt-4 flex w-full flex-col justify-center">
        {#each $toasts as toast (toast.id)}
            <Toast
                type={toast.type}
                dismissible={toast.dismissible}
                on:dimiss={() => dismissToast(toast.id)}>
                {toast.message}
            </Toast>
        {/each}
    </section>
{/if}
