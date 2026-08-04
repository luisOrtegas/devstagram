<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a DevStagram</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/devstagram-icon.svg') }}">
    <style>
        * { box-sizing: border-box; }
        html, body { width: 100%; min-height: 100%; margin: 0; }
        body {
            display: grid;
            min-height: 100vh;
            place-items: center;
            overflow: hidden;
            padding: 24px;
            color: #fff;
            font-family: Arial, Helvetica, sans-serif;
            background: radial-gradient(circle at center, #172554 0%, #071230 48%, #030712 100%);
        }
        .welcome {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            animation: scene 3.1s ease-in-out both;
        }
        .glow {
            position: absolute;
            top: -12px;
            width: min(48vw, 220px);
            aspect-ratio: 1;
            border-radius: 999px;
            background: linear-gradient(135deg, #22d3ee, #3b82f6 52%, #c026d3);
            filter: blur(48px);
            opacity: .58;
            animation: glow 1.25s ease-in-out infinite alternate;
        }
        .icon {
            position: relative;
            display: block;
            width: min(42vw, 180px);
            height: auto;
            padding: 10px;
            border-radius: 34px;
            object-fit: contain;
            background: #071230;
            box-shadow: 0 0 58px rgba(34, 211, 238, .3);
            animation: icon 1.15s cubic-bezier(.2, .85, .2, 1) both;
        }
        h1 {
            max-width: 92vw;
            margin: 28px 0 0;
            font-size: clamp(26px, 6vw, 42px);
            overflow-wrap: anywhere;
            animation: text .7s .55s ease-out both;
        }
        p {
            margin: 10px 0 0;
            color: #cbd5e1;
            font-size: clamp(15px, 3.5vw, 19px);
            animation: text .7s .75s ease-out both;
        }
        a {
            margin-top: 26px;
            color: #bae6fd;
            font-size: 14px;
            text-decoration: none;
            opacity: 0;
            animation: text .5s 1.1s ease-out forwards;
        }
        @keyframes icon {
            0% { opacity: 0; transform: scale(.35) rotate(-12deg); }
            65% { opacity: 1; transform: scale(1.1) rotate(3deg); }
            100% { transform: scale(1) rotate(0); }
        }
        @keyframes glow {
            from { transform: scale(.8); opacity: .32; }
            to { transform: scale(1.2); opacity: .7; }
        }
        @keyframes text {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes scene {
            0%, 82% { opacity: 1; }
            100% { opacity: 0; transform: scale(1.025); }
        }
        @media (prefers-reduced-motion: reduce) {
            .welcome { animation-duration: 1.8s; }
            .icon { animation-duration: .7s; }
            .glow { animation-duration: .8s; }
        }
    </style>
</head>
<body>
    <main class="welcome" role="status" aria-live="polite">
        <div class="glow" aria-hidden="true"></div>
        <picture>
            <source srcset="{{ asset('img/devstagram-icon-256.webp') }}" type="image/webp">
            <img
                src="{{ asset('img/devstagram-icon-256.png') }}"
                alt="DevStagram"
                width="256"
                height="256"
                class="icon"
            >
        </picture>
        <h1>Bienvenido, {{ $user->name }}</h1>
        <p>Tu comunidad está lista.</p>
        <a href="{{ $redirectUrl }}">Continuar ahora</a>
    </main>

    <script>
        window.setTimeout(function () {
            window.location.replace(@json($redirectUrl));
        }, 3200);
    </script>
</body>
</html>
