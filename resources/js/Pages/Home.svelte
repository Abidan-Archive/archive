<script>
    import clsx from 'clsx';
    import { inertia } from '@inertiajs/svelte';
    import Star from '@components/Star.svelte';
    import SearchForm from '@components/SearchForm.svelte';
    import route from '@/route';

    export let events;
    export let quote;
</script>

<section
    class={clsx(
        'bg-radial overflow-hidden shadow-md',
        '-mx-2 sm:-mx-5',
        'h-[66vh] md:h-[73vh] lg:h-[80vh]'
    )}>
    <Star>
        <div
            class="mx-auto flex h-full flex-col items-center justify-center px-8">
            <section
                class="my-8 w-1/2 text-center md:w-2/3 md:text-3xl lg:text-4xl">
                {quote}
            </section>
            <SearchForm class="z-10 sm:w-3/4 md:w-1/2 lg:w-1/3" />
        </div>
    </Star>
</section>

<div class="-mx-5 bg-midnight-500">
    <div class="flex px-6 py-8">
        <section>
            <h2
                class="mb-8 text-center text-3xl md:m-0 md:mb-2 md:text-left md:text-4xl">
                Welcome to the Archive
            </h2>
            <p class="mb-2">
                This is a fan run site and is not meant to be official in any
                context.
            </p>
            <p>
                Everything on this site is <b>subject to change</b>. Will can
                and will change anything to suit the story better.
            </p>
        </section>
        <!-- <aside class="block text-right"> -->
        <!--     <h3>Want to contribute?</h3> -->
        <!--     <p>We are always looking for chroniclers to transcribe audio into new Reports.</p> -->
        <!--     <a use:inertia href={route('register')}>Join us!</a> -->
        <!-- </aside>  -->
    </div>
</div>
<div class="container mt-12 flex items-center justify-evenly">
    <section class="flex-1">
        <h2 class="text-3xl font-thin uppercase">Most Recent Events</h2>
        <ul>
            {#each events as event}
                <li>
                    <!-- svelte-ignore missing-declaration -->
                    <a
                        class="text-lg hover:underline"
                        use:inertia
                        href={route('event.show', event)}>
                        {event.name}
                    </a>
                </li>
            {/each}
        </ul>
    </section>
</div>

<style lang="scss">
    .bg-radial {
        background-image: radial-gradient(
            ellipse at center,
            #1b2735 0%,
            #090a0f 90%
        );
    }
</style>
