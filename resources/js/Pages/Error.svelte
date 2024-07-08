<script>
    export let status;

    $: title = {
        503: 'Service Unavailable',
        500: 'Server Error',
        404: 'Page Not Found',
        403: 'Forbidden',
    }[status];

    $: description = {
        503: [
            'The application is currently undergoing maintenance.',
            'Please check back soon.',
        ],
        500: [
            'The server has encountered an internal error.',
            'Should this persist, contact an admin with details on how to reproduce.',
        ],
        404: ['Sorry, the page you are looking for could not be found.'],
        403: ['Sorry, you do not have permissions to access this page.'],
    }[status];
</script>

<section
    class="container mx-auto my-4 flex h-full flex-col gap-8 px-4 py-8 text-center lg:w-2/3">
    <h1 class="flex items-center justify-center gap-4 text-4xl">
        <span class="text-9xl">{status}</span>
        {title}
    </h1>
    <p class="text-2xl">
        {#each description as desc}
            {desc}<br />
        {/each}
    </p>
</section>
