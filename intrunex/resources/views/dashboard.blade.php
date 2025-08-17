@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 flex flex-col">
    {{-- Top Navbar --}}
    <nav class="bg-intrunex-dark text-white px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">IntruNex Dashboard</h1>
        <div class="flex items-center gap-4">
            <span>Hello, {{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-intrunex-accent hover:bg-intrunex-accent2 text-black px-4 py-1 rounded">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    {{-- Content Area --}}
    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Quick Actions --}}
        <a href="{{ route('scan.new') }}" 
           class="bg-white shadow p-6 rounded-lg hover:border-intrunex-accent border-2 transition">
            <h2 class="text-lg font-semibold mb-2">🔍 Run New Scan</h2>
            <p class="text-gray-600 text-sm">Check a website for vulnerabilities</p>
        </a>

        <a href="{{ route('reports') }}" 
           class="bg-white shadow p-6 rounded-lg hover:border-intrunex-accent border-2 transition">
            <h2 class="text-lg font-semibold mb-2">📄 View Reports</h2>
            <p class="text-gray-600 text-sm">See your past scan results</p>
        </a>

        @if(auth()->user()->role === 'admin')
        <a href="{{ route('users') }}" 
           class="bg-white shadow p-6 rounded-lg hover:border-intrunex-accent border-2 transition">
            <h2 class="text-lg font-semibold mb-2">👥 Manage Users</h2>
            <p class="text-gray-600 text-sm">View & manage all registered accounts</p>
        </a>
        @endif
    </div>

    {{-- Recent Scans Table --}}
    <div class="p-6">
        <h2 class="text-lg font-semibold mb-4">Recent Scans</h2>
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-200 text-left">
                    <tr>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Target</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scans ?? [] as $scan)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $scan->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-2">{{ $scan->target_url }}</td>
                            <td class="px-4 py-2">
                                @if($scan->status === 'Clean')
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Clean</span>
                                @else
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Threats</span>
                                @endif
                            </td>
                            <td class="px-4 py-2"><a href="{{ route('scan.show', $scan->id) }}" class="text-intrunex-accent hover:underline">View</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-gray-500">No scans yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
