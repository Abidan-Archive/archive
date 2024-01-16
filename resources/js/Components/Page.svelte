<script>
    import cn from '@/Utils/cn';

    export let header;
    let className;
    export { className as class };
</script>

<section class={cn('container mx-auto my-4 px-4', className)}>
    {#if $$slots.header || !!header}
        <slot name="header">
            <h2 class="text-3xl">{header}</h2>
        </slot>
        <hr />
    {/if}
    <slot />
</section>
