<script>
    import { onMount, onDestroy, getContext } from 'svelte';
    import Peaks from 'peaks.js';
    import Card from '@components/Card.svelte';
    import { Play, Trash } from '@components/icons';
    import { Button, IconButton } from '@components/forms';
    import MediaControls from '@components/audio/MediaControls.svelte';
    import Dialog from '@components/Modals/Dialog.svelte';

    // Props
    let audioUrl = '/testaudio';
    let audioContentType = 'audio/mpeg';
    let waveformDataUrl = '/storage/sources/Bloodline_Release_Part_1.dat';
    let audioContext = null;

    let zoomviewWaveformRef;
    let overviewWaveformRef;
    let audioElementRef;
    let peaks = null;

    let currentTime = 0;
    let duration;
    let playing = false;
    let segments = [];

    const { open } = getContext('simple-modal');

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

    function saveWork() {
        console.log({ segments });
    }

    // Todo:
    // - Blub and data correlation for submit, data bundling
    // - Adjust segment creation for start stop
    // - Keybindings, space enter left right shift left shift right
    // - Validation on blurbs to be filled
    // - Save to local on timer or significant changes
    let isShiftHeld = false;
    function onKeyDown(e) {
        if (e.repeat) return;
        console.log(e);
        if (e.key === 'Shift') return (isShiftHeld = true);
        switch (e.code) {
            case 'Space':
                return playpause();
            case 'Enter':
                return isShiftHeld && addSegment();
            case 'ArrowRight':
                return seek(isShiftHeld ? 5 : 1);
            case 'ArrowLeft':
                return seek(isShiftHeld ? -5 : -1);
        }
    }
    function onKeyUp(e) {
        if (e.key === 'Shift') isShiftHeld = false;
    }
</script>

<svelte:window
    on:keydown|preventDefault={onKeyDown}
    on:keyup|preventDefault={onKeyUp} />
<div class="container mx-auto flex w-full flex-col gap-5">
    <figure class="flex flex-col items-center gap-2">
        <figcaption class="my-2">{audioUrl}</figcaption>
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
        <div class="flex flex-col justify-center gap-2">
            {#each segments as segment}
                <Card
                    id={'segment-' + segment.id}
                    class="flex items-center justify-start gap-2">
                    <IconButton
                        style={`background-color:${segment.color}`}
                        light
                        on:click={playSegment(segment)}>
                        <Play /></IconButton>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2">
                            <label for={'start_' + segment.id} class="flex-1"
                                >Start</label>
                            <input
                                id={'start_' + segment.id}
                                type="text"
                                class="h-5 w-20 rounded text-black"
                                value={segment.startTime}
                                on:input={(e) =>
                                    updateSegmentStart(e, segment)} />
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
                                    updateSegmentEnd(e, segment)} />
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
                            required />
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
            <Button on:click={saveWork}>Save Work</Button>
            <Button>Submit</Button>
        </div>
    {/if}
</div>

<style lang="postcss">
    #zoomview-container,
    #overview-container {
        @apply w-full;
        height: 100px;
    }
</style>
