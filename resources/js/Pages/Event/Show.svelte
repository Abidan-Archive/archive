<script>
    import Report from '@components/Report.svelte';
    import Status from '@components/Status.svelte';

    export let event;
    export let status;
</script>

<svelte:head>
    <title>{event.name} | Abidan Archive</title>
</svelte:head>

<div class="container mx-auto pt-5">
    <Status {status} />
    <section class="mb-5">
        <h2 class="text-2xl">{event.name}</h2>
    </section>
    <hr class="my-5" />
    <section class="flex flex-col gap-5">
        {#each event.reports as report}
            <Report {report} withEvent={false} />
        {/each}
    </section>
</div>
