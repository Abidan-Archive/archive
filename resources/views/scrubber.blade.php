<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Test Page</title>
    </head>
    <body class="antialiased">

        <span class="text-green">
            {{ date('Y-m-d H:i:s') }}
        </span>

        <div id="waveform"></div>
        <div id="time">0</div>
        <button id="skipbb">&lt;&lt;</button>
        <button id="skipb">&lt;</button>
        <button id="play">Play/Pause</button>
        <button id="skipf">&gt;</button>
        <button id="skipff">&gt;&gt;</button>

        {{-- https://wavesurfer-js.org/docs/methods.html#connecting-filters --}}
        <script src="https://unpkg.com/wavesurfer.js"></script>
        <script src="https://unpkg.com/wavesurfer.js/dist/plugin/wavesurfer.regions.min.js"></script>
        <script>
            var wavesurfer = WaveSurfer.create({
                container:'#waveform',
                waveColor: 'violet',
                progressColor: 'purple',
                plugins: [
                    WaveSurfer.regions.create({})
                ]
            });
            wavesurfer.load('../test_sound.mp3');

            let updateTimestamp = () => {
                let cur = Math.floor(wavesurfer.getCurrentTime());
                let len = Math.ceil(wavesurfer.getDuration());
                document.getElementById('time').innerHTML = `${cur} / ${len}`;
            };
            wavesurfer.on('ready', () => {
                console.log('wavesurfer loaded');
                wavesurfer.region.addRegion({
                    start: 10,
                    end: 20,
                    drag: false
                });
            });
            document.addEventListener('DOMContentLoaded', () => {
                console.log('Dom loaded');
                document.getElementById('play')
                    .addEventListener('click', () => {
                        wavesurfer.playPause();
                        updateTimestamp();
                    });
                let skipMap = {
                    'b': -1, 'bb': -5,
                    'f': 1,  'ff': 5
                };
                Object.keys(skipMap).forEach((k) => {
                    document.getElementById('skip'+k).addEventListener('click', () => wavesurfer.skip(skipMap[k]));
                });
            });
        </script>
    </body>
</html>
