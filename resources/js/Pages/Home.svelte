<script>
    import { inertia } from "@inertiajs/svelte";
    import Star from "@components/Star.svelte";
    export let events;
    export let quote;
</script>

<section
    class="overflow-hidden bg-radial lg:h-4/6 md:h-3/5 sm:h-1/2 h-auto shadow-md"
>
    <Star>
        <div class="flex w-full h-full justify-center items-center mx-auto">
            <!-- Search -->
        </div>
    </Star>
</section>

<div class="relative z-10 py-8 bg-midnight text-white">
    <div class="container mx-auto flex flew-row">
        <section>
            <h2 class="text-4xl">Welcome to the Archive</h2>
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
    <div class="relative py-12 mx-20">
        <div class="container flex flex-row">
            <section class="w-4/5">
                <h2 class="uppercase font-thin text-3xl pb-4">
                    Most Recent Events
                </h2>
                <ul>
                    {#each events as event}
                        <li>
                            <a use:inertia href={route("event.show", event)}
                                >{event.name}</a
                            >
                        </li>
                    {/each}
                </ul>
            </section>
        </div>
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
