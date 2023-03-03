<script>
    import { inertia } from "@inertiajs/svelte";
    import StarrySky from "@components/StarrySky.svelte";
    import SearchForm from "@components/SearchForm.svelte";
    export let events;
    export let quote;
</script>

<section
    class="relative overflow-hidden bg-radial lg:h-3/4 md:h-4/6 sm:h-1/2 h-1/2 shadow-md"
>
    <StarrySky>
        <SearchForm />
    </StarrySky>
</section>

<div class="relative z-10 py-8 bg-midnight text-white px-8">
    <div class="container mx-auto flex flew-row">
        <section>
            <h2 class="md:text-4xl text-3xl">Welcome to the Archive</h2>
            <p class="md:py-2 py-4">
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
    <div class="relative md:mx-12 py-12">
        <div class="container flex flex-row">
            <section class="md:w-4/5">
                <h2 class="uppercase font-thin md:text-3xl text-2xl pb-4">
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
