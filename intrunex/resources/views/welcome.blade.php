<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IntruNex – Secure Your Web</title>
    @vite('resources/css/app.css')
</head>
<body class="antialiased bg-gradient-to-br from-intrunex-dark via-black to-gray-900 text-white">

    {{-- Navigation --}}
    <header class="flex justify-between items-center px-6 py-4">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-intrunex-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4" />
            </svg>
            <span class="font-bold text-xl">IntruNex</span>
        </div>
        <nav class="flex gap-4">
            <a href="{{ route('login') }}" class="px-4 py-2 rounded bg-intrunex-accent text-black font-semibold hover:bg-intrunex-accent2 transition">Login</a>
            <a href="{{ route('register') }}" class="px-4 py-2 rounded border-2 border-intrunex-accent hover:bg-intrunex-accent hover:text-black transition">Register</a>
        </nav>
    </header>

    {{-- Hero Section --}}
    <section class="flex flex-col items-center justify-center text-center px-6 py-20">
        <h1 class="text-4xl md:text-6xl font-bold mb-4">
            Protect Your Web, Detect Exploits ⚡
        </h1>
        <p class="text-gray-300 max-w-2xl mb-8">
            IntruNex is your all‑in‑one solution for detecting and reporting web vulnerabilities before they become a problem.
        </p>
        <div class="flex gap-4">
            <a href="{{ route('register') }}" class="px-6 py-3 bg-intrunex-accent hover:bg-intrunex-accent2 text-black font-semibold rounded-lg transition">
                Get Started
            </a>
            <a href="{{ route('login') }}" class="px-6 py-3 border-2 border-intrunex-accent text-intrunex-accent hover:bg-intrunex-accent hover:text-black rounded-lg transition">
                Sign In
            </a>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="px-6 py-12 grid gap-8 md:grid-cols-3 max-w-6xl mx-auto">
        <div class="bg-white/10 backdrop-blur-lg p-6 rounded-lg shadow hover:scale-105 transition">
            <h3 class="text-xl font-semibold text-intrunex-accent mb-2">Real‑Time Scans</h3>
            <p class="text-gray-300 text-sm">Quickly detect suspicious activity and vulnerabilities in seconds.</p>
        </div>
        <div class="bg-white/10 backdrop-blur-lg p-6 rounded-lg shadow hover:scale-105 transition">
            <h3 class="text-xl font-semibold text-intrunex-accent mb-2">Detailed Reports</h3>
            <p class="text-gray-300 text-sm">Get actionable, easy‑to‑read security reports for every scan.</p>
        </div>
        <div class="bg-white/10 backdrop-blur-lg p-6 rounded-lg shadow hover:scale-105 transition">
            <h3 class="text-xl font-semibold text-intrunex-accent mb-2">Role‑Based Access</h3>
            <p class="text-gray-300 text-sm">Admins, analysts, and clients each see exactly what they need.</p>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="text-center text-gray-500 py-6 text-sm">
        &copy; {{ date('Y') }} IntruNex. Secure your web with confidence.
    </footer>

</body>
</html>
