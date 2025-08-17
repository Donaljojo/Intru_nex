<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IntruNex — Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])

    <style>
        :root{--nx-green:#00ff88;--nx-green-2:#24ff9a;--nx-bg:#000;}
        body{font-family:'JetBrains Mono', monospace;background:var(--nx-bg);color:#caffea;}
        .bg-anim{position:fixed;inset:0;background:
          radial-gradient(60% 60% at 70% 30%, rgba(0,255,136,.12), transparent 60%),
          radial-gradient(60% 60% at 30% 70%, rgba(0,255,136,.08), transparent 60%),
          #000;animation:slowPulse 14s ease-in-out infinite;}
        @keyframes slowPulse{0%,100%{filter:brightness(1)}50%{filter:brightness(1.08)}}
        .nav{position:sticky;top:0;background:rgba(0,0,0,.6);backdrop-filter:blur(8px);border-bottom:1px solid rgba(0,255,136,.25);}
        .brand{display:flex;align-items:center;gap:.6rem;color:var(--nx-green);}
        .brand svg{filter:drop-shadow(0 0 8px rgba(0,255,136,.6))}
        .nx-btn{padding:.6rem 1rem;border:1px solid var(--nx-green);color:var(--nx-green);border-radius:.6rem;transition:.2s;}
        .nx-btn:hover{background:rgba(0,255,136,.15);box-shadow:0 0 14px rgba(0,255,136,.35)}
        .section{background:rgba(0,0,0,.55);border:1px solid rgba(0,255,136,.25);border-radius:.8rem;padding:1rem;}
        .cards{display:grid;gap:1rem;grid-template-columns:repeat(1,minmax(0,1fr));}
        @media(min-width:768px){.cards{grid-template-columns:repeat(3,minmax(0,1fr));}}
        .card{background:rgba(0,0,0,.5);border:1px solid rgba(0,255,136,.25);border-radius:.8rem;padding:1rem;transition:.2s;}
        .card:hover{transform:translateY(-2px);box-shadow:0 0 18px rgba(0,255,136,.22);}
        table{width:100%;border-collapse:separate;border-spacing:0 10px;}
        th{color:#b9ffdf;text-align:left;font-weight:700;padding:.25rem .6rem;}
        td{padding:.65rem .6rem;background:rgba(0,0,0,.55);border:1px solid rgba(0,255,136,.18);}
        .badge{display:inline-block;padding:.2rem .5rem;border-radius:.5rem;font-size:.75rem;}
        .ok{background:rgba(0,255,136,.15);color:var(--nx-green);border:1px solid rgba(0,255,136,.5);}
        .warn{background:rgba(255,120,0,.12);color:#ffaa66;border:1px solid rgba(255,180,120,.5);}
        .danger{background:rgba(255,0,72,.12);color:#ff6b9a;border:1px solid rgba(255,0,72,.45);}
    </style>
</head>
<body class="min-h-screen">
    <div class="bg-anim"></div>

    <header class="nav relative z-10">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="brand">
                <svg class="w-7 h-7 text-[var(--nx-green)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="font-extrabold uppercase tracking-wider">IntruNex</span>
            </div>
            <div class="flex items-center gap-3 text-sm">
                <span>Hi, {{ Auth::user()->name ?? 'Analyst' }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="nx-btn" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="relative z-10 max-w-6xl mx-auto px-4 py-8 space-y-6">
        <section class="section">
            <h1 class="text-2xl font-extrabold text-[var(--nx-green)] mb-2" style="text-shadow:0 0 10px rgba(0,255,136,.6)">Your Digital Watchtower</h1>
            <p class="text-sm text-green-100/90">Real‑time insights, role‑based access, and actionable intelligence — all in one clean, fast interface.</p>
        </section>

        <section class="cards">
            <a href="#" class="card">
                <div class="font-bold text-green-200 mb-1">🔍 Run New Scan</div>
                <div class="text-green-100/85 text-sm">Launch a targeted scan and get a report in minutes.</div>
            </a>
            <a href="#" class="card">
                <div class="font-bold text-green-200 mb-1">📄 View Reports</div>
                <div class="text-green-100/85 text-sm">Browse historical results with filters and export.</div>
            </a>
            <a href="#" class="card">
                <div class="font-bold text-green-200 mb-1">👥 Manage Users</div>
                <div class="text-green-100/85 text-sm">Invite teammates and assign roles securely.</div>
            </a>
        </section>

        <section class="section">
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-bold text-green-200">Recent Scans</h2>
                <a href="#" class="nx-btn">All scans</a>
            </div>
            <div class="overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Target</th>
                            <th>Status</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($scans ?? []) as $scan)
                            <tr>
                                <td>{{ $scan->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ $scan->target_url }}</td>
                                <td>
                                    @if($scan->status === 'clean')
                                        <span class="badge ok">Clean</span>
                                    @elseif($scan->status === 'warn')
                                        <span class="badge warn">Warnings</span>
                                    @else
                                        <span class="badge danger">Threats</span>
                                    @endif
                                </td>
                                <td><a href="{{ route('scan.show',$scan->id) }}" style="color:var(--nx-green);text-decoration:underline dotted">Open</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-green-100/70">No scans yet — start your first one.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
