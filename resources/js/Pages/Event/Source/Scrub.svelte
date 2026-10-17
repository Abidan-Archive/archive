<script>
    import { onMount, onDestroy } from 'svelte';
    import { inertia, router } from '@inertiajs/svelte';
    import ScrubControls from '@/Components/audio/ScrubControls.svelte';
    import Oddment from '@/lib/oddment.js';
    import Page from '@/Components/Page.svelte';
    import route from '@/lib/route.js';
    import { Button, IconButton } from '@/Components/forms';
    import { Play, Pause, Trash } from '@/Components/icons';
    import { getModalStore } from '@skeletonlabs/skeleton';
    import { getRandomColor } from '@/lib/color';
    import { toFixed } from '@/lib/time';

    const modalStore = getModalStore();

    /**
     * @typedef {Object} Props
     * @property {any} event - - Handle errors / form validation for stubs
     * @property {any} source
     */
    /** @type {Props} */
    let { event, source } = $props();

    let audioUrl = source.audio_url;
    let waveformDataUrl = source.dat_url;
    let audioContentType = 'audio/mpeg';
    let audioContext = null;

    let zoomviewWaveformRef = $state();
    let overviewWaveformRef = $state();
    let audioElementRef = $state();
    let peaks = $state(null);
    let destroyed = false;

    let currentTime = $state(0);
    let duration = $state();
    let playing = $state(false);

    let segments = $state([]);
    let segmentPlaying = $state(null);

    const promptOddment = new Oddment({
        Prompt: 10,
        Blurb: 5,
        'Yada yada': 5,
        Nonsene: 3,
        Whomps: 3,
        Poppycock: 1,
        Skadoosh: 1,
    });

    onMount(() => {
        initPeaks().catch(console.error);
    });
    onDestroy(() => {
        destroyed = true;
        if (peaks) peaks.destroy();
    });

    async function initPeaks() {
        // Canvas/audio dependencies must only load in the browser, never during SSR.
        const [{ default: Peaks }, { default: CustomSegmentMarker }] =
            await Promise.all([
                import('peaks.js'),
                import('@/lib/peaks-custom-marker.js'),
            ]);
        if (destroyed) return;
        const blueColor = 'rgba(50,102,149,1)';
        const greenColor = 'rgba(0, 225, 128, 1)';
        const whiteColor = '#eee';
        const options = {
            zoomview: {
                container: zoomviewWaveformRef,
                waveformColor: blueColor,
                playedWaveformColor: greenColor,
                wheelMode: 'scroll',
                enableSegments: true,
            },
            overview: {
                container: overviewWaveformRef,
                waveformColor: blueColor,
                playedWaveformColor: greenColor,
            },
            playheadTextColor: '#f00',
            playheadColor: whiteColor,
            mediaElement: audioElementRef,
            keyboard: false,
            logger: console.error.bind(console),
            createSegmentMarker: (options) => new CustomSegmentMarker(options),
        };

        if (waveformDataUrl) {
            options.dataUri = {
                arraybuffer: waveformDataUrl,
            };
        } else if (audioContext) {
            options.webAudio = {
                audioContext: audioContext,
            };
        }

        audioElementRef.src = audioUrl;

        if (peaks) {
            peaks.destroy();
            peaks = null;
        }

        console.log('init', options, audioElementRef.src, peaks);
        Peaks.init(options, (err, instance) => {
            if (err) {
                console.error(
                    'Failed to initialize Peaks instance: ' + err.message
                );
                return;
            }
            if (destroyed) {
                instance.destroy();
                return;
            }
            peaks = instance;
            onPeaksReady();
        });
    }
    function onPeaksReady() {
        console.log('Peaks is ready');

        peaks.on('player.playing', () => {
            playing = true;
        });
        peaks.on('player.pause', () => {
            playing = false;
            segmentPlaying = null;
        });

        const updateSegments = () => (segments = peaks.segments.getSegments());
        peaks.on('segments.add', updateSegments);
        peaks.on('segments.remove', updateSegments);
        peaks.on('segments.dragend', updateSegments);

        // Create all the previous created segments
        peaks.segments.add(
            source.stubs.map((stub) => ({
                startTime: stub.from,
                endTime: stub.to,
                editable: true,
                prompt: stub.prompt,
            }))
        );
    }

    function seek(t) {
        if (!peaks) return;
        peaks.player.seek((peaks.player.getCurrentTime() || 0) + t);
    }
    function playpause() {
        if (!peaks) return;
        !playing ? peaks.player.play() : peaks.player.pause();
    }
    function addSegment() {
        if (!peaks) return;
        const time = peaks.player.getCurrentTime();
        const color = getRandomColor();
        peaks.segments.add({
            color,
            startTime: toFixed(time, 2),
            endTime: toFixed(time + 10, 2),
            editable: true,
            endMarkerColor: '#f00',
            prompt: '',
        });
    }
    function zoom(isMagnify) {
        if (!peaks) return;
        if (isMagnify) {
            // console.log('zoom in');
            peaks.zoom.zoomIn();
            return;
        }
        // console.log('zoom out');
        peaks.zoom.zoomOut();
    }

    function playSegment(segment = null) {
        if (!peaks || !segment) return;
        // console.log('playing segment id: ', segment?.id);
        peaks.player.playSegment(segment);
        segmentPlaying = segment.id;
    }
    var debounceTimer;
    function debounce(callback, wait = 500) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(callback, wait);
    }
    function updateSegmentStart(e, segment) {
        return debounce(() => {
            if (!peaks || !e.target.value) return;
            let startTime = parseFloat(e.target.value),
                endTime = segment.endTime;
            if (startTime < 0) startTime = 0;
            if (startTime > duration) startTime = duration;
            if (startTime > segment.endTime) endTime = startTime;
            segment.update({ startTime, endTime });
            document.getElementById('end_' + segment.id).value = endTime;
            // console.log('segment start update', segment, e);
        });
    }
    function updateSegmentEnd(e, segment) {
        return debounce(() => {
            if (!peaks || !e.target.value) return;
            let endTime = parseFloat(e.target.value),
                startTime = segment.startTime;
            if (endTime < 0) {
                endTime = 0;
                startTime = 0;
            }
            if (endTime > duration) endTime = duration;
            if (endTime > 0 && endTime < startTime) startTime = endTime;
            segment.update({ startTime, endTime });
            document.getElementById('start_' + segment.id).value = startTime;
            // console.log('segment end update', segment, e);
        });
    }
    function updateSegmentPrompt(e, segment) {
        return debounce(() => {
            if (!peaks || !e.target.value) return;
            segment.update({ prompt: e.target.value });
            // console.log('segment prompt update', segment, e);
        });
    }

    let isShiftHeld = false;
    function onKeyDown(e) {
        if (e.repeat) return;
        if (e.key === 'Shift') return (isShiftHeld = true);
        switch (e.code) {
            case 'Space':
                if (e.target.tagName.toLowerCase() === 'input') return;
                e.preventDefault();
                return playpause();
            case 'Enter':
                return isShiftHeld && addSegment();
            case 'ArrowRight':
                if (e.target.tagName.toLowerCase() === 'input') return;
                e.preventDefault();
                return seek(isShiftHeld ? 5 : 1);
            case 'ArrowLeft':
                if (e.target.tagName.toLowerCase() === 'input') return;
                e.preventDefault();
                return seek(isShiftHeld ? -5 : -1);
        }
    }
    function onKeyUp(e) {
        if (e.key === 'Shift') isShiftHeld = false;
    }

    function submit(e) {
        e.preventDefault();
        const stubs = segments.map((s) => ({
            from: s.startTime,
            to: s.endTime,
            prompt: s.prompt,
        }));

        router.post(
            route('event.source.stub.store', [event.id, source.id]),
            { stubs },
            {}
        );
    }
</script>

{#snippet header()}
    <div class="flex items-baseline justify-between">
        <h2 class="text-3xl">Create Stubs</h2>
        <a use:inertia href={route('event.edit', event)} class="text-xl">
            Back to Event
        </a>
    </div>
{/snippet}
<svelte:window onkeydown={onKeyDown} onkeyup={onKeyUp} />
<Page class="flex w-full flex-col gap-5" {header}>
    <figure class="flex flex-col items-center gap-2 text-white">
        <figcaption class="my-2">{event.name} - {source.name}</figcaption>
        <div id="overview-container" bind:this={overviewWaveformRef}></div>
        <div
            id="zoomview-container"
            class="mb-2"
            bind:this={zoomviewWaveformRef}>
        </div>

        <audio bind:this={audioElementRef} bind:currentTime bind:duration>
            <source src={audioUrl} type={audioContentType} />
            Your browser does not support the audio element.
        </audio>

        <ScrubControls {playpause} clip={addSegment} {seek} {zoom} />
    </figure>
    {#if !!segments && !!segments?.length}
        <hr />
        <form method="POST" onsubmit={submit}>
            <div class="mb-8 flex flex-col justify-center gap-2">
                {#each segments as segment, idx}
                    <div
                        id={'segment-' + segment.id}
                        class="card flex items-center justify-start gap-2">
                        <IconButton
                            style={`background-color:${segment.color}`}
                            light
                            onclick={() => {
                                segmentPlaying === segment.id
                                    ? playpause()
                                    : playSegment(segment);
                            }}>
                            {@const SvelteComponent =
                                segmentPlaying === segment.id ? Pause : Play}
                            <SvelteComponent />
                        </IconButton>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-2">
                                <label
                                    for={'start_' + segment.id}
                                    class="flex-1">Start</label>
                                <input
                                    id={'start_' + segment.id}
                                    type="text"
                                    class="h-5 w-20 rounded text-black"
                                    value={toFixed(segments[idx].startTime, 2)}
                                    oninput={(e) =>
                                        updateSegmentStart(e, segment)}
                                    required />
                            </div>
                            <div class="flex items-center gap-2">
                                <label for={'end_' + segment.id} class="flex-1"
                                    >End</label>
                                <input
                                    id={'end_' + segment.id}
                                    type="text"
                                    class="h-5 w-20 rounded text-black"
                                    value={toFixed(segments[idx].endTime, 2)}
                                    oninput={(e) =>
                                        updateSegmentEnd(e, segment)}
                                    required />
                            </div>
                        </div>
                        <div class="mx-4 flex-1">
                            <label for={segment.id + '_label'} class="sr-only"
                                >Prompt</label>
                            <input
                                id={segment.id + '_label'}
                                type="text"
                                class="w-full rounded text-black"
                                placeholder={promptOddment.pick()}
                                value={segment.prompt}
                                oninput={(e) => updateSegmentPrompt(e, segment)}
                                required />
                        </div>
                        <IconButton
                            onclick={() => {
                                modalStore.trigger({
                                    type: 'confirm',
                                    title: 'Please Confirm',
                                    body: 'Are you sure you want to delete this clip?',
                                    response: (r) => {
                                        if (!peaks && !r) return;
                                        console.log('segment deleted', segment);
                                        peaks.segments.removeById(segment.id);
                                    },
                                });
                            }}>
                            <Trash />
                        </IconButton>
                    </div>
                {:else}
                    <div class="text-center">
                        <p>
                            Click on the scissors to create a segment. Fill in
                            the prompt. When you're all done, hit submit.
                        </p>
                        <p>
                            Refreshing the page will clear all unsaved segments.
                        </p>
                    </div>
                {/each}
            </div>
            <hr />
            <div class="flex justify-end gap-2">
                <Button type="submit">Submit</Button>
            </div>
        </form>
    {/if}
</Page>

<style lang="postcss">
    #zoomview-container,
    #overview-container {
        @apply w-full;
        height: 100px;
    }
</style>
