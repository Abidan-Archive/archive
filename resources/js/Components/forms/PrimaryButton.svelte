<script>
    import clsx from 'clsx';
</script>

<button
    type={$$props.type ?? 'submit'}
    class={clsx(
        'inline-flex items-center rounded-md border border-transparent bg-gray-200 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-800',
        'hover:bg-white focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800 active:bg-gray-300',
        'transition duration-150 ease-in-out',
        $$props.class
    )}>
    <slot />
</button>
