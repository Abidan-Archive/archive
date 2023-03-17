<script>
    import { inertia } from '@inertiajs/svelte';
    import Star from '@components/Star.svelte';
    import Searchform from '../Components/Searchform.svelte';

    export let events;
    export let quote;
</script>

<section
    class="bg-radial h-[66vh] md:h-[73vh] overflow-hidden shadow-md lg:h-[80vh]">
    <Star>
        <div class="flex flex-col h-full justify-center items-center mx-auto px-8">
            <section class="lg:text-4xl md:text-3xl my-8 w-1/2 md:w-2/3 text-center">{quote}</section>
            <!-- Search -->
            <Searchform />
        </div>
    </Star>
</section>

<div class="bg-midnight-500">
    <div class="flew-row container mx-auto flex py-4 px-4 md:px-0 lg:py-8">
        <section class="px-6 pt-2 md:p-0">
            <h2 class="mb-8 md:mb-2 text-center md:text-4xl text-3xl md:m-0 md:text-left">
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
