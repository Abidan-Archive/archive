<script>
    import cn from '@/lib/cn';
    import { ErrorMessage, Label, Input } from '@/Components/forms';
    /**
     * @typedef {Object} Props
     * @property {string} name
     * @property {any} form
     * @property {string} [type]
     * @property {string} [label]
     * @property {string} [class]
     * @property {import('svelte').Snippet} [children]
     */

    /** @type {Props & { [key: string]: any }} */
    let {
        name,
        form,
        label = undefined,
        type = undefined,
        class: className = '',
        children,
        ...rest
    } = $props();
</script>

<div class="flex flex-col gap-2">
    <Label for={name} value={label ?? name} class="capitalize" />
    <Input
        id={name}
        {name}
        {type}
        bind:value={$form[name]}
        class={cn('input block w-full', className)}
        {...rest} />
    <ErrorMessage message={$form.errors[name]} />

    {@render children?.()}
</div>
