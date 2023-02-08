<script>
    const num = 100;
    let width = 0;
    let height = 0;

    const randomRadius = () => {
        return Math.random() * 0.8 + 0.6;
    };
    const getRandom = (x) => {
        return Math.floor(Math.random() * Math.floor(parseFloat(x)));
    };
</script>

<svelte:window bind:innerWidth={width} bind:innerHeight={height} />

<div class="lg:h-4/6 md:h-3/5 sm:h-1/2 h-auto">
    <div class="sky-container overflow-hidden w-full h-full top-0 left-0">
        {#each Array(5) as _}
            <div class="shootingstar" />
        {/each}
    </div>
    <svg {width} {height}>
        {#each Array(num) as _}
            <circle
                cx={getRandom(width)}
                cy={getRandom(height)}
                r={randomRadius()}
                class="star"
            />
        {/each}
    </svg>
</div>

<style lang="scss">
    .star {
        fill: white;
        stroke: none;
    }
    @function randomNum($min, $max) {
        $rand: random();
        $randomNum: $min + floor($rand * (($max - $min) + 1));

        @return $randomNum;
    }
    .shootingstar {
        position: absolute;
        top: 50%;
        left: 50%;
        height: 1.5px;
        background: linear-gradient(-45deg, #fbfcfe, rgba(0, 0, 255, 0));
        filter: drop-shadow(0 0 6px #699bff);
        animation: tail 3000ms ease-in-out infinite,
            shooting 3000ms ease-in-out infinite,
            delay-animation 12000ms ease-in-out infinite;
    }
    .sky-container {
        transform: rotateZ(45deg);
        position: absolute;
        overflow: hidden;
    }

    .shootingstar::before,
    .shootingstar::after {
        position: absolute;
        content: "";
        top: calc(50% - 1px);
        right: 0;
        height: 1.5px;
        background: linear-gradient(
            -45deg,
            rgba(0, 0, 255, 0),
            #5f91ff,
            rgba(0, 0, 255, 0)
        );
        border-radius: 100%;
        transform: translateX(50%) rotateZ(45deg);
        animation: ease-in-out infinite;
    }

    .shootingstar::after {
        transform: translateX(50%) rotateZ(-45deg);
    }

    /* Animations */
    @keyframes tail {
        0% {
            width: 0;
        }

        30% {
            width: 90px;
        }

        100% {
            width: 0;
        }
    }

    @keyframes shooting {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(320px);
        }
    }

    @keyframes delay-animation {
        0% {
            opacity: 1;
        }
        50% {
            opacity: 1;
        }
        50.01% {
            opacity: 0;
        }
        100% {
            opacity: 0;
        }
    }
    .shootingstar:nth-child(1) {
        top: calc(50% - 45%);
        left: calc(50% - 10%);
        animation-delay: 1600ms;
    }
    .shootingstar:nth-child(1)::before, .shootingstar:nth-child(1)::after {
        animation-delay: 1600ms;
    }

    .shootingstar:nth-child(2) {
        top: calc(50% - -15%);
        left: calc(50% - 15%);
        animation-delay: 1250ms;
    }
    .shootingstar:nth-child(2)::before, .shootingstar:nth-child(2)::after {
        animation-delay: 1250ms;
    }

    .shootingstar:nth-child(3) {
        top: calc(50% - -25%);
        left: calc(50% - -30%);
        animation-delay: 2600ms;
    }
    .shootingstar:nth-child(3)::before, .shootingstar:nth-child(3)::after {
        animation-delay: 2600ms;
    }

    .shootingstar:nth-child(4) {
        top: calc(50% - 20%);
        left: calc(50% - 10%);
        animation-delay: 9700ms;
    }
    .shootingstar:nth-child(4)::before, .shootingstar:nth-child(4)::after {
        animation-delay: 9700ms;
    }

    .shootingstar:nth-child(5) {
        top: calc(50% - -45%);
        left: calc(50% - 45%);
        animation-delay: 5100ms;
    }
    .shootingstar:nth-child(5)::before, .shootingstar:nth-child(5)::after {
        animation-delay: 5100ms;
    }
    
</style>
