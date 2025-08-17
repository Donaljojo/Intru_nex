<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IntruNex — Welcome</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;900&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])

    <style>
        :root{
            --nx-green:#00ff88;
            --nx-green-2:#31ffa7;
            --nx-green-dim:#86ffc8;
            --nx-bg:#000000;
        }
        body{
            font-family:'JetBrains Mono', monospace;
            background:var(--nx-bg);
            color:var(--nx-green-dim);
        }
        .bg-anim{
            position:fixed;inset:0;
            background:
              radial-gradient(60% 60% at 70% 30%, rgba(0,255,136,0.15) 0%, transparent 60%),
              radial-gradient(50% 50% at 20% 70%, rgba(0,255,136,0.10) 0%, transparent 60%),
              linear-gradient(180deg, #000 0%, #020202 100%);
            animation:bgPulse 12s ease-in-out infinite;
            pointer-events:none;
        }
        @keyframes bgPulse{
            0%,100%{filter:hue-rotate(0deg) brightness(1);}
            50%{filter:hue-rotate(10deg) brightness(1.1);}
        }
        .card{
            background:rgba(0,0,0,.65);
            border:1px solid rgba(0,255,136,.35);
            box-shadow:0 0 20px rgba(0,255,136,.15), inset 0 0 20px rgba(0,255,136,.05);
            backdrop-filter:blur(6px);
        }
        .neon-title{
            color:var(--nx-green);
            text-shadow:0 0 12px rgba(0,255,136,.8);
        }
        .nx-btn{
            display:inline-flex;align-items:center;justify-content:center;gap:.5rem;
            padding:.85rem 1.25rem;border-radius:.75rem;font-weight:700;
            transition:.2s ease; text-transform:uppercase;
        }
        .nx-btn.primary{
            background:var(--nx-green);
            color:#000;
            box-shadow:0 0 18px rgba(0,255,136,.4);
        }
        .nx-btn.primary:hover{
            transform:translateY(-1px) scale(1.02);
            box-shadow:0 0 24px rgba(0,255,136,.6);
        }
        .nx-btn.ghost{
            border:1px solid var(--nx-green);
            color:var(--nx-green);
        }
        .nx-btn.ghost:hover{
            background:rgba(0,255,136,.12);
            box-shadow:0 0 18px rgba(0,255,136,.3);
        }
        .tag{
            display:inline-block;
            padding:.2rem .6rem;
            border:1px solid rgba(0,255,136,.35);
            border-radius:.5rem;
            color:var(--nx-green-2);
        }
        .features{
            display:grid;
            gap:2rem;
            grid-template-columns:repeat(1,minmax(0,1fr));
        }
        @media(min-width:640px){
            .features{grid-template-columns:repeat(3,minmax(0,1fr));}
        }
        .feature{
            border:1px solid rgba(0,255,136,.25);
            background:rgba(0,0,0,.5);
            border-radius:.75rem;
            padding:1.5rem;
            transition:.25s;
        }
        .feature:hover{
            transform:translateY(-2px);
            box-shadow:0 0 16px rgba(0,255,136,.25);
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="bg-anim"></div>

    <div class="relative z-10 min-h-screen flex items-center justify-center p-6">
        <div class="card max-w-3xl w-full rounded-xl p-10 sm:p-12 text-center">

            {{-- Logo text only --}}
            <div class="mb-6">
                <span class="text-xl tag">IntruNex</span>
            </div>

            <h1 class="text-4xl sm:text-5xl font-extrabold neon-title mb-8">
                Welcome to IntruNex
            </h1>

            <p class="text-sm sm:text-base text-green-200/90 leading-relaxed max-w-2xl mx-auto mb-10">
                Your journey into proactive web defense starts here. Map your perimeter, scan with intent, and act with clarity.
                You’re not just a user — you’re the architect of your own security.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 mb-12">
                <a href="{{ route('login') }}" class="nx-btn primary w-full sm:w-auto">Login</a>
                <a href="{{ route('register') }}" class="nx-btn ghost w-full sm:w-auto">Register</a>
            </div>

            <div class="features">
                <div class="feature">
                    <div class="font-bold text-green-300 mb-2">Real‑Time Scans</div>
                    <div class="text-green-200/80 text-sm">Detect threats as they emerge, not after the breach.</div>
                </div>
                <div class="feature">
                    <div class="font-bold text-green-300 mb-2">Actionable Reports</div>
                    <div class="text-green-200/80 text-sm">Evidence, context, and steps — no fluff.</div>
                </div>
                <div class="feature">
                    <div class="font-bold text-green-300 mb-2">Role‑Based Access</div>
                    <div class="text-green-200/80 text-sm">Give the right people the right visibility.</div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
