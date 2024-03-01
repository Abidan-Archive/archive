<script>
    import { onMount } from 'svelte';
    export let src;
    export let title = 'untitled';
    // export let muted = false;
    // export let crossorigin;
    export let loop = true;
    // export let preload = true;

    const barWidth = 3;
    const gap = 2;

    let audio; // MediaElement
    let audioCtx = new AudioContext();
    let track;
    let gainNode;
    let analyserNode;
    let canvas;
    let canvasCtx;

    let isPlaying;
    let currentTime;
    let duration;
    let volume = 0.4;
    let bufferLength;
    let dataArray;

    // If src ever changes, initailize
    // $: src && initializeAudio();
    onMount(async () => {
        initializeAudio();
    });

    function initializeAudio() {
        track = audioCtx.createMediaElementSource(audio);
        gainNode = audioCtx.createGain();
        analyserNode = audioCtx.createAnalyser();
        analyserNode.fftSize = 2048;
        bufferLength = analyserNode.frequencyBinCount;
        dataArray = new Uint8Array(bufferLength);
        track
            .connect(gainNode)
            .connect(analyserNode)
            .connect(audioCtx.destination);
        canvasCtx = canvas.getContext('2d');
    }

    // This is called per frame of monitor refresh rate
    // Be careful
    function updateFrequency() {
        if (!isPlaying) return;
        analyserNode.getByteFrequencyData(dataArray);

        canvasCtx.clearRect(0, 0, canvas.width, canvas.height);
        canvasCtx.fillStyle = 'rgba(0,0,0,0)';
        canvasCtx.fillRect(0, 0, canvas.width, canvas.height);

        const barCount = bufferLength / (barWidth + gap - 1);
        for (let i = 0, x = 0; i < barCount; ++i, x += barWidth + gap) {
            const perc = (dataArray[i] * 100) / 255; // Percantage scaled to 0-255
            const h = (perc * canvas.height) / 100;
            canvasCtx.fillStyle = `rgba(${perc}, ${perc}, 255, 1)`;
            canvasCtx.fillRect(x, canvas.height - h, barWidth, canvas.height);
        }

        requestAnimationFrame(updateFrequency.bind(this));
    }

    async function togglePlay() {
        if (audioCtx.state === 'suspended') {
            await audioCtx.resume();
        }

        if (isPlaying) {
            await audio.pause();
            isPlaying = false;
        } else {
            await audio.play();
            isPlaying = true;
            updateFrequency();
        }
    }
    function formatTime(time) {
        if (!time) return '0:00';
        const secs = `${parseInt(`${time % 60}`, 10)}`.padStart(2, '0');
        const mins = parseInt(`${(time / 60) % 60}`, 10);
        const hours = parseInt(`${(time / 3600) % 60}`, 10);

        return (
            hours ? [hours, `${mins}`.padStart(2, '0'), secs] : [mins, secs]
        ).join(':');
    }
    function changeVolume(value) {
        volume = value;
        gainNode.gain.value = volume;
    }
    function seekTo(value) {
        audio.currentTime = value;
    }
    async function ended() {
        if (loop) {
            audio.currentTime = 0;
            await audio.play();
        } else {
            isPlaying = false;
        }
    }
</script>

<figure
    class="align-center relative mb-5 flex w-full max-w-md rounded bg-black p-2 font-sans text-white">
    <figcaption
        class="absolute left-0 top-[calc(100%+2px)] m-0 w-full rounded-sm bg-inherit px-2 py-1 font-normal uppercase">
        {title}
    </figcaption>
    <audio
        {src}
        bind:this={audio}
        bind:duration
        bind:currentTime
        on:ended={ended} />
    <button
        type="button"
        class="h-8 w-8 min-w-8 appearance-none overflow-hidden border-none bg-white text-black"
        on:click={togglePlay}>
        {#if !isPlaying}
            play
        {:else}
            pause
        {/if}
    </button>
    <div class="flex-1">
        <span>{formatTime(currentTime)}</span>
        <input
            type="range"
            max={duration}
            value={currentTime}
            on:input={(e) => seekTo(e.target.value)} />
        <span>{formatTime(duration)}</span>
        <canvas bind:this={canvas} class="h-3 w-full" />
    </div>
    <button
        type="button"
        class="h-8 w-8 min-w-8 appearance-none overflow-hidden border-none bg-cyan-500 text-black"
        on:click={() => undefined} />
    <div class="hidden">
        <input
            type="range"
            min="0"
            max="2"
            step="0.01"
            value={volume}
            on:input={(e) => changeVolume(e.target.value)} />
    </div>
</figure>
