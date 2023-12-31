<script>
    import { cn, route } from '@/Utils';
    import { inertia } from '@inertiajs/svelte';
    import Star from '@components/Star.svelte';
    import Heart from '@components/icons/Heart.svelte';
    import SearchForm from '@components/SearchForm.svelte';

    export let events;
    export let mostLiked;
    export let quote;
</script>

<div
    class={cn(
        'bg-radial overflow-hidden shadow-md',
        '-mx-2 sm:-mx-5',
        'h-[calc(100vh_-_4rem_-_14rem)] sm:h-[calc(100vh_-_4rem_-_10rem)]'
    )}>
    <Star>
        <div
            class="mx-auto flex h-full flex-col items-center justify-center px-8">
            <div
                class="my-8 w-1/2 text-center md:w-2/3 md:text-3xl lg:text-4xl">
                {quote}
            </div>
            <SearchForm class="z-10 sm:w-3/4 md:w-1/2 lg:w-1/3" />
        </div>
    </Star>
</div>

<div class="-mx-5 h-56 bg-midnight-500 sm:h-40">
    <div class="flex px-6 py-6">
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
                and may change anything to suit the story better.
            </p>
        </section>
        <!-- <aside class="block text-right"> -->
        <!--     <h3>Want to contribute?</h3> -->
        <!--     <p>We are always looking for chroniclers to transcribe audio into new Reports.</p> -->
        <!--     <a use:inertia href={route('register')}>Join us!</a> -->
        <!-- </aside>  -->
    </div>
</div>
<div class="container mt-12 flex justify-evenly">
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
    <section class="flex-1">
        <h2 class="text-3xl font-thin uppercase">Most Liked Reports</h2>
        <i>Coming soon</i>
        <ul>
            {#each mostLiked as report}
                <li>
                    <!-- svelte-ignore missing-declaration -->
                    <a
                        class="text-lg hover:underline"
                        use:inertia
                        href={route('report.show', report)}>
                        {report.likes_count}
                        <Heart
                            class="inline"
                            variant={report.is_liked ? 'filled' : 'outline'} /> &middot;
                        Report#{report.id}
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
