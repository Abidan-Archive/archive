<script>
    import { onMount, onDestroy, getContext } from 'svelte';
    import { page, router } from '@inertiajs/svelte';
    import Peaks from 'peaks.js';

    import { Button, IconButton } from '@/Components/forms';
    import { Play, Pause, Trash } from '@/Components/icons';
    import { addFlash } from '@/Stores/toast.js';
    import Card from '@/Components/Card.svelte';
    import Dialog from '@/Components/Modals/Dialog.svelte';
    import MediaControls from '@/Components/audio/MediaControls.svelte';
    import Page from '@/Components/Page.svelte';
    import route from '@/lib/route.js';

    // Todo:
    // - Handle errors / form validation for stubs
    // - Delete dialog z-indexing issues

    export let event;
    export let source;

    let audioUrl = source.url;
    let waveformDataUrl = source.dat_url;
    let audioContentType = 'audio/mpeg';
    let audioContext = null;

    let zoomviewWaveformRef;
    let overviewWaveformRef;
    let audioElementRef;
    let peaks = null;

    let currentTime = 0;
    let duration;
    let playing = false;

    let segments = [];
    let segmentPlaying = null;

    const { open } = getContext('simple-modal');

    addFlash($page.props?.flash);

    onMount(() => {
        initPeaks();
    });
    onDestroy(() => {
        if (peaks) peaks.destroy();
    });

    function initPeaks() {
        const color = 'rgba(50,102,149,1)';
        const options = {
            zoomview: {
                container: zoomviewWaveformRef,
                waveformColor: color,
                playedWaveformColor: 'rgba(0, 225, 128, 1)',
            },
            overview: {
                container: overviewWaveformRef,
                waveformColor: color,
                playedWaveformColor: 'rgba(0, 225, 128, 1)',
            },
            mediaElement: audioElementRef,
            keyboard: false,
            logger: console.error.bind(console),
            formatPlayheadTime() {
                return 'hi';
            },
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
        peaks.on(
            'segments.add',
            () => (segments = peaks.segments.getSegments())
        );
        peaks.on(
            'segments.remove',
            () => (segments = peaks.segments.getSegments())
        );
        peaks.on('segments.dragend', ({ segment }) => {
            console.log('segment dragged ', segment);
            document.getElementById('start_' + segment.id).value = parseFloat(
                segment.startTime
            );
            document.getElementById('end_' + segment.id).value = parseFloat(
                segment.endTime
            );
        });
        // Create all the previous created segments
        peaks.segments.add(source.stubs.map((stub) => ({
            startTime: stub.from,
            endTime: stub.to,
            editable: true,
            blurb: stub.prompt,
        })));
    }

    function seek(t) {
        if (!peaks) return;
        peaks.player.seek((peaks.player.getCurrentTime() || 0) + t);
    }
    function playpause() {
        console.log('test?');
        if (!peaks) return;
        !playing ? peaks.player.play() : peaks.player.pause();
    }
    function addSegment() {
        if (!peaks) return;
        const time = peaks.player.getCurrentTime();
        peaks.segments.add({
            startTime: time,
            endTime: time + 10,
            editable: true,
            blurb: '',
        });
    }
    function zoom(e) {
        if (!peaks) return;
        if (e.detail) {
            console.log('zoom in');
            peaks.zoom.zoomIn();
            return;
        }
        console.log('zoom out');
        peaks.zoom.zoomOut();
    }

    function playSegment(segment = null) {
        if (!peaks || !segment) return;
        console.log('playing segment id: ', segment?.id);
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
            console.log('segment start update', segment, e);
        });
    }
    function updateSegmentEnd(e, segment) {
        return debounce(() => {
            if (!peaks || !e.target.value) return;
            let endTime = parseFloat(e.target.value),
                startTime = segment.startTime;
            if (endTime < 0) (endTime = 0), (startTime = 0);
            if (endTime > duration) endTime = duration;
            if (endTime > 0 && endTime < startTime) startTime = endTime;
            segment.update({ startTime, endTime });
            document.getElementById('start_' + segment.id).value = startTime;
            console.log('segment end update', segment, e);
        });
    }
    function updateSegmentBlurb(e, segment) {
        return debounce(() => {
            if (!peaks || !e.target.value) return;
            segment.update({ blurb: e.target.value });
            console.log('segment blurb update', segment, e);
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

    function submit() {
        const stubs = segments.map((s) => ({from: s.startTime, to: s.endTime, prompt: s.blurb}));
        router.post(route('event.source.stub.store', [event.id, source.id]), {stubs}, {
            onSuccess: () => addFlash($page.props?.flash),
        });
    }
</script>

<svelte:window on:keydown={onKeyDown} on:keyup={onKeyUp} />
<Page class="flex w-full flex-col gap-5">
    <figure class="flex flex-col items-center gap-2">
        <figcaption class="my-2">{event.name} - {source.name}</figcaption>
        <div id="overview-container" bind:this={overviewWaveformRef} />
        <div
            id="zoomview-container"
            class="mb-2"
            bind:this={zoomviewWaveformRef} />

        <audio bind:this={audioElementRef} bind:currentTime bind:duration>
            <source src={audioUrl} type={audioContentType} />
            Your browser does not support the audio element.
        </audio>

        <MediaControls
            on:playpause={playpause}
            on:seek={(e) => seek(e.detail)}
            on:zoom={zoom}
            on:clip={addSegment} />
    </figure>
    {#if !!segments && !!segments?.length}
        <hr />
        <form method="POST" on:submit|preventDefault={submit}>
            <div class="flex flex-col justify-center gap-2 mb-8">
                {#each segments as segment}
                    <Card
                        id={'segment-' + segment.id}
                        class="flex items-center justify-start gap-2">
                        <IconButton
                            style={`background-color:${segment.color}`}
                            light
                            on:click={segmentPlaying === segment.id
                                ? playpause()
                                : playSegment(segment)}>
                            <svelte:component
                                this={segmentPlaying === segment.id
                                    ? Pause
                                    : Play} />
                        </IconButton>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-2">
                                <label
                                    for={'start_' + segment.id}
                                    class="flex-1">Start</label>
                                <input
                                    id={'start_' + segment.id}
                                    name=""
                                    type="text"
                                    class="h-5 w-20 rounded text-black"
                                    value={segment.startTime}
                                    on:input={(e) =>
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
                                    value={segment.endTime}
                                    on:input={(e) =>
                                        updateSegmentEnd(e, segment)}
                                    required />
                            </div>
                        </div>
                        <div class="mx-4 flex-1">
                            <label for={segment.id + '_label'} class="sr-only"
                                >Blurb</label>
                            <input
                                id={segment.id + '_label'}
                                type="text"
                                class="w-full rounded text-black"
                                placeholder="Blurb"
                                on:input={(e) => updateSegmentBlurb(e, segment)} />
                        </div>
                        <IconButton
                            on:click={() => {
                                open(Dialog, {
                                    message:
                                        'Are you sure you want to delete this clip?',
                                    onOkay: () => {
                                        if (!peaks) return;
                                        console.log('segment deleted', segment);
                                        peaks.segments.removeById(segment.id);
                                    },
                                    closeButton: false,
                                    closeOnOuterClick: false,
                                });
                            }}>
                            <Trash />
                        </IconButton>
                    </Card>
                {/each}
            </div>
            <hr />
            <div class="flex justify-end gap-2">
                <Button type="submit">Submit</Button>
            </div>
        </form>
    {/if}
</Page>

