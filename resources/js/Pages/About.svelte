<script>
    import Page from '@/Components/Page.svelte';
    import { Discord, Github } from '@/Components/icons';
    import { inertia } from '@inertiajs/svelte';
    import route from '@/lib/route';

    const channel = '#wiki-and-archive-discussion';
    const github = 'https://github.com/Abidan-Archive/archive';
    const discord = 'https://discord.gg/tCg94qy';
</script>

<Page header="About Site" class="lg:w-2/3">
    <section>
        <p>
            Whenever Will has a live event, stream, or socials post, should it
            contain relevant information about his works, it is recorded here
            within the Archive. Many of these blurbs are canon or give some
            additional insight to Will's worlds, writing process, and humor.
            After being transcribed by community members the so-called <a
                use:inertia
                href={route('report.index')}>Reports</a> are fully text searchable
            for people to browse and link to for discussion.
        </p>
        <p>
            This site is maintained and built by the Will Wight fanbase and
            community.
        </p>
        <p>It's main contributors are:</p>
        <ul class="list-disc pl-5">
            <li>🍑 - pchpchpchpch</li>
            <li>Entropy - Decadakon</li>
            <li>Lil'Blue - Shadowsaber</li>
        </ul>
        <sub>Discord nicknames &amp; handles.</sub>
    </section>
    <section>
        <h3 class="text-xl">Experiencing an Issue?</h3>
        Don't hesitate to reach out on discord in
        <a href={discord} class="font-bold">{channel}</a> channel. Or, raise an
        issue on
        <a href={github}>Github</a> if you are familiar with that sort of thing.
        You may also contact one of the main contributors listed above, just be sure
        to explain why you're contacting them as we are wary of bots.
    </section>
    <section>
        <h3 class="text-xl">How to Contribute?</h3>
        <p>
            If you want to get involved with either the development of the
            website or help with more than transcribing new Reports. Simply
            reach out to any of the main contributors on discord or ask in the
            <a href={discord} class="font-bold">{channel}</a> channel.
        </p>
        <p>
            Additionally, the entire website is open source and available on <a
                href={github}>Github</a
            >. If you'd like to contribute code or notice something wrong raise
            a PR or issue!
        </p>
        <ul>
            <li><Github class="inline pr-1" /> <a href={github}>Github</a></li>
            <li>
                <Discord class="inline pr-1" /> <a href={discord}>Discord</a>
            </li>
        </ul>
    </section>
    <section>
        <h3 class="text-xl">Site Technologies</h3>
        <p>
            The website is built using <a href="https://laravel.com/">Laravel</a
            >, <a href="https://inertiajs.com/">Inertiajs</a>,
            <a href="https://svelte.dev/">Svelte</a>, and
            <a href="https://tailwindcss.com/">Tailwindcss</a>. With these
            technologies, the aim is that the website is easy to maintain and
            anyone can quickly become familiar with its workings and contribute.
        </p>
    </section>
</Page>

<style lang="scss">
    section {
        @apply mb-4;
    }
    p {
        @apply mb-2;
    }
    a {
        @apply underline;
    }
</style>
