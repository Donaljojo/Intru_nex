<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'IntruNex') }}</title>

    {{-- Google Font: Orbitron for cyber look --}}
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;800&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')

    <style>
        body {
            font-family: 'Orbitron', sans-serif;
        }
        /* Subtle animated gradient background */
        .bg-animated {
            background: radial-gradient(circle at center, #00ff00 0%, #000 70%);
            background-size: 200% 200%;
            animation: pulseBg 8s ease-in-out infinite;
        }
        @keyframes pulseBg {
            0% { background-position: 50% 50%; }
            50% { background-position: 55% 45%; }
            100% { background-position: 50% 50%; }
        }
    </style>
</head>
<body class="bg-black text-green-400 bg-animated min-h-screen flex flex-col">

    {{-- Main content wrapper --}}
    <main class="flex-1 flex items-center justify-center p-4">
        <div class="w-full max-w-2xl text-center p-8 bg-black/70 border border-green-500 rounded-xl shadow-[0_0_25px_rgba(0,255,0,0.6)]">
            @yield('content')
        </div>
    </main>

    {{-- Optional footer --}}
    <footer class="text-center text-green-500 text-xs py-4 opacity-70">
        &copy; {{ date('Y') }} IntruNex — Proactive Web Defense
    </footer>
</body>
</html>
