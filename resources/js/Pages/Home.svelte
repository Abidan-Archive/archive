<script>
    import { inertia } from '@inertiajs/svelte';
    import Star from '@components/Star.svelte';

    export let events;
    export let quote;
</script>

<section
    class="bg-radial h-[33vh] overflow-hidden shadow-md md:h-[60vh] lg:h-[66vh]">
    <Star>
        <div class="mx-auto flex h-full flex-col items-center justify-center">
            <section>{quote}</section>
            <!-- Search -->
        </div>
    </Star>
</section>

<div class="bg-midnight-500">
    <div class="flew-row container mx-auto flex py-8 px-4 md:px-0">
        <section class="px-8 pt-2 md:p-0">
            <h2 class="mb-8 text-center text-4xl md:m-0 md:text-left">
                Welcome to the Archive
            </h2>
            <p>
                This is a fan run site and is not meant to be official in any
                context.
            </p>
            <p>
                Everything on this site is <b>subject to change</b>. Will can
                and will change anything to suit the story better.
            </p>
        </section>
        <!-- <aside class="block text-right flex-1">
            <h3>Want to contribute?</h3>
            <p>We are always looking for chroniclers to transcribe audio into new Reports.</p>
            <a use:inertia href={route('register')}>Join us!</a>
        </aside> -->
    </div>
</div>
<div class="py-12">
    <div class="container mx-auto flex flex-row">
        <section>
            <h2 class="pb-4 text-3xl font-thin uppercase">
                Most Recent Events
            </h2>
            <ul>
                {#each events as event}
                    <li>
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
</div>

<style>
    .bg-radial {
        background-image: radial-gradient(
            ellipse at center,
            #1b2735 0%,
            #090a0f 90%
        );
    }
</style>
