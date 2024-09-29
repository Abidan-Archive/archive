<script>
    import { page } from '@inertiajs/svelte';
    import { cn, route } from '@/lib';
    import { inertia } from '@inertiajs/svelte';
    import Star from '@/Components/Star.svelte';
    import Heart from '@/Components/icons/Heart.svelte';
    import SearchForm from '@/Components/SearchForm.svelte';

    export let events;
    export let mostLiked;
    export let quote;
    export let contributors;
</script>

<div
    class={cn(
        'bg-radial overflow-hidden shadow-md',
        // 4rem (16 tw) for app bar 10rem (40 tw) for welcome bar
        'h-[calc(100vh_-_4rem_-_10rem)]'
    )}>
    <Star>
        <div class="mx-auto grid h-full grid-rows-2 px-8">
            <div
                class="mx-auto my-8 self-end text-center text-xl font-extrabold sm:w-1/2 md:w-2/3 md:text-3xl md:font-normal lg:text-4xl">
                {quote}
            </div>
            <SearchForm
                class="z-10 mx-auto w-full md:w-3/4 lg:w-2/3 xl:w-1/2" />
        </div>
    </Star>
</div>

<div class="min-h-40 bg-surface-500">
    <div class="container mx-auto flex flex-col p-6 md:flex-row">
        <section class="grow">
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
        <aside class="text-right">
            {#if !$page.props.auth.user}
                <h3 class="text-2xl">Want to contribute?</h3>
                <p class="mb-2 ml-auto md:w-2/3 lg:w-3/4">
                    We are always looking for chroniclers to transcribe audio
                    into new Reports.
                </p>
                <p>
                    <a
                        use:inertia
                        href={route('register')}
                        class="font-semi variant-filled-primary btn btn-md no-underline"
                        >Join Today!</a>
                </p>
            {/if}
        </aside>
    </div>
</div>
<div class="container mx-auto flex justify-evenly p-6 2xl:w-2/3">
    <section>
        <h2 class="text-3xl font-thin uppercase">Most Recent Events</h2>
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
            {:else}
                <li>Touch grass.</li>
            {/each}
        </ul>
    </section>
    <section>
        <h2 class="text-3xl font-thin uppercase">Most Liked Reports</h2>
        <ul>
            {#each mostLiked as report}
                <li>
                    <a
                        class="text-lg no-underline"
                        use:inertia
                        href={route('report.show', report)}>
                        {report.likes_count}
                        <Heart
                            class="inline"
                            variant={report.is_liked ? 'filled' : 'outline'} /> &middot;
                        <span class="underline">Report #{report.id}</span>
                    </a>
                </li>
            {:else}
                <li>Haters.</li>
            {/each}
        </ul>
    </section>
    {#if false}
        <section>
            <h2 class="text-3xl font-thin uppercase">Points Leaderboard</h2>
            <ol>
                {#each contributors as them}
                    <li>{them}</li>
                {:else}
                    <li>Slackers.</li>
                {/each}
                <ol />
            </ol>
        </section>
    {/if}
</div>

<style lang="postcss">
    .bg-radial {
        background-image: radial-gradient(
            ellipse at center,
            #1b2735 0%,
            #090a0f 90%
        );
    }
</style>
