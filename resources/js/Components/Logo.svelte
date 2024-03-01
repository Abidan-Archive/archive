<script>
    import cn from '@/lib/cn';
    let className = '';

    export { className as class };
</script>

<div
    class={cn('marble block h-12 w-12', className)}
    aria-label="Abidan Archive Logo">
    <div class="fire">
        <div class="fire-left">
            <div class="main-fire" />
            <div class="particle-fire" />
        </div>
        <div class="fire-center">
            <div class="main-fire" />
            <div class="particle-fire" />
        </div>
        <div class="fire-right">
            <div class="main-fire" />
            <div class="particle-fire" />
        </div>
        <div class="fire-bottom">
            <div class="main-fire" />
        </div>
    </div>
</div>

<style lang="scss">
    $marbleBg: #090b29;
    $marbleInner: #ad98e7;
    $fireDarkest: #131852;
    $fireDark: #131e94;
    $fireNormal: #5992df;
    $fireBright: #7bd3e4;

    * {
        box-sizing: border-box;
    }

    @keyframes scaleUpDown {
        0%,
        100% {
            transform: scaleY(1) scaleX(1);
        }
        50%,
        90% {
            transform: scaleY(1.1);
        }
        75% {
            transform: scaleY(0.95);
        }
        80% {
            transform: scaleX(0.95);
        }
    }

    @keyframes shake {
        0%,
        100% {
            transform: skewX(0) scale(1);
        }
        50% {
            transform: skewX(5deg) scale(0.9);
        }
    }

    @keyframes particleUp {
        0% {
            opacity: 0;
        }
        20% {
            opacity: 1;
        }
        80% {
            opacity: 1;
        }
        100% {
            opacity: 0;
            top: -100%;
            transform: scale(0.5);
        }
    }

    @keyframes glow {
        0%,
        100% {
            background-color: $fireNormal;
        }
        50% {
            background-color: $fireBright;
        }
    }

    .marble {
        border-radius: 100%;
        background-color: $marbleBg;
        box-shadow: inset 0 0 0.5rem 0.4rem $marbleInner;
    }

    .fire {
        position: relative;
        top: 1rem;
        width: 1.5rem;
        height: 1.5rem;
        background-color: transparent;
        margin: 0 auto;
    }

    .fire-center {
        position: absolute;
        height: 100%;
        width: 100%;
        animation: scaleUpDown 3s ease-out;
        animation-iteration-count: infinite;
        animation-fill-mode: both;

        .main-fire {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(
                farthest-corner at 0.1rem 0,
                $fireDark 0%,
                $fireNormal 95%
            );
            transform: scaleX(0.6) rotate(45deg);
            border-radius: 0 40% 60% 40%;
            filter: drop-shadow(0 0 0.1rem $fireDarkest);
        }

        .particle-fire {
            position: absolute;
            top: 60%;
            left: 45%;
            width: 0.2rem;
            height: 0.2rem;
            background-color: $fireNormal;
            border-radius: 50%;
            filter: drop-shadow(0 0 0.1rem $fireDarkest);
            animation: particleUp 2s ease-out 0;
            animation-iteration-count: infinite;
            animation-fill-mode: both;
        }
    }

    .fire-right {
        height: 100%;
        width: 100%;
        position: absolute;
        animation: shake 2s ease-out 0;
        animation-iteration-count: infinite;
        animation-fill-mode: both;

        .main-fire {
            position: absolute;
            top: 15%;
            right: -25%;
            width: 80%;
            height: 80%;
            background-color: $fireNormal;
            transform: scaleX(0.8) rotate(45deg);
            border-radius: 0 40% 60% 40%;
            filter: drop-shadow(0 0 10px $fireDarkest);
        }

        .particle-fire {
            position: absolute;
            top: 45%;
            left: 50%;
            width: 0.6rem;
            height: 0.6rem;
            background-color: $fireNormal;
            transform: scaleX(0.8) rotate(45deg);
            border-radius: 50%;
            filter: drop-shadow(0 0 0.1rem $fireDarkest);
            animation: particleUp 2s ease-out 0;
            animation-iteration-count: infinite;
            animation-fill-mode: both;
        }
    }

    .fire-left {
        position: absolute;
        height: 100%;
        width: 100%;
        animation: shake 3s ease-out 0;
        animation-iteration-count: infinite;
        animation-fill-mode: both;

        .main-fire {
            position: absolute;
            top: 15%;
            left: -20%;
            width: 80%;
            height: 80%;
            background-color: $fireNormal;
            transform: scaleX(0.8) rotate(45deg);
            border-radius: 0 40% 60% 40%;
            filter: drop-shadow(0 0 0.1rem $fireDarkest);
        }

        .particle-fire {
            position: absolute;
            top: 10%;
            left: 20%;
            width: 10%;
            height: 10%;
            background-color: $fireNormal;
            border-radius: 50%;
            filter: drop-shadow(0 0 0.1rem $fireDarkest);
            animation: particleUp 3s infinite ease-out 0;
            animation-fill-mode: both;
        }
    }

    .fire-bottom .main-fire {
        position: absolute;
        top: 30%;
        left: 20%;
        width: 75%;
        height: 75%;
        background-color: $fireBright;
        transform: scaleX(0.8) rotate(45deg);
        border-radius: 0 40% 100% 40%;
        filter: blur(0.3rem);
        animation: glow 2s ease-out 0;
        animation-iteration-count: infinite;
        animation-fill-mode: both;
    }
</style>
