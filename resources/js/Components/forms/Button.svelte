<script>
    import cn from '@/Utils/cn';
    import { cva } from 'class-variance-authority';

    let className;
    export { className as class };
    export let href;
    export let type;
    export let variant = 'default';
    export let size = 'default';

    const buttonVariants = cva(
        'inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background ',
        {
            variants: {
                variant: {
                    default:
                        'bg-gray-200 text-gray-800 hover:bg-gray-200/90 border border-transparent hover:bg-white focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800 active:bg-gray-300',
                    success:
                        'bg-green-500 hover:bg-green-600 focus:bg-green-400',
                    destructive:
                        'bg-red-600 hover:bg-red-500 focus:bg-red-300 text-typo-500',
                    warn: 'bg-yellow-500 hover:bg-yellow-600 focus:bg-yellow-400',
                    link: 'underline-offset-4 hover:underline',
                },
                size: {
                    default:
                        'h-10 py-2 px-4 duration-150 ease-in-out',
                    sm: 'h-9 px-3 rounded-md',
                    lg: 'h-11 px-9 rounded-md',
                },
            },
            defaultVariants: {
                variant: 'default',
                size: 'default',
            },
        }
    );
</script>

<svelte:element
    this={href ? 'a' : 'button'}
    type={href ? 'undefined' : type}
    {href}
    class={cn(buttonVariants({ variant, size, className }))}
    {...$$restProps}
    on:click
    on:change
    on:keydown
    on:keyup
    on:mouseenter
    on:mouseleave>
    <slot />
</svelte:element>
