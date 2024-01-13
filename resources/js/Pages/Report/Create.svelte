<script>
    // import AudioScrubber from '@/Components/AudioScrubber.svelte';
    import Card from '@/Components/Card.svelte';
    import { Play, Trash } from '@/Components/icons';
    import { secondsToDuration, durationToSeconds } from '@/Utils';

    export let event;
    let scrubber;
    let regions = {};

    function handleRegionUpdate(id, field, value) {
        const secs = durationToSeconds(value);
        console.log(id);
        if (field === 'start') {
            id.onResize(secs - id.start, 'start');
        } else {
            id.onResize(secs - id.start);
        }

        // }
        // id.resize({
        //     [field]: secs
        //
        // })
    }
</script>

<div class="container mx-auto mt-10 flex flex-col justify-center gap-4">
    <!-- <AudioScrubber bind:this={scrubber} bind:regions src="/testaudio" title="Test Scrubber"/> -->
    {#each Object.entries(regions) as [id, region], idx}
        <Card {id} class="flex items-center justify-start gap-2">
            <button
                class="h-10 w-10 rounded-full border p-2"
                style={`background-color:${region.color}`}><Play /></button>
            <div class="flex flex-col">
                <div class="flex gap-2">
                    <label for={'start_' + idx} class="flex-1">Start</label>
                    <input
                        id={'start' + idx}
                        type="text"
                        class="h-5 w-20 rounded text-black"
                        value={secondsToDuration(region.start)}
                        on:input={(e) =>
                            handleRegionUpdate(
                                region,
                                'start',
                                e.target.value
                            )} />
                </div>
                <div class="flex gap-2">
                    <label for={'end_' + idx} class="flex-1">End</label>
                    <input
                        id={'end_' + idx}
                        type="text"
                        class="h-5 w-20 rounded text-black"
                        value={secondsToDuration(region.end)}
                        on:input={(e) =>
                            handleRegionUpdate(id, 'end', e.target.value)} />
                </div>
            </div>
            <div class="mx-4 flex-1">
                <label for={id + '_label'} class="sr-only">Blurb</label>
                <input
                    id={id + '_label'}
                    type="text"
                    class="w-full rounded text-black"
                    placeholder="Blurb" />
            </div>
            <button
                class="h-10 w-10 rounded-full border bg-enkai-500 p-2 text-black"
                ><Trash /></button>
        </Card>
    {/each}
</div>
