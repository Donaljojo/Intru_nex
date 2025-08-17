{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-intrunex-dark via-black to-gray-900 p-6">
    <div class="bg-white/90 backdrop-blur-md p-8 rounded-xl shadow-lg w-full max-w-md">
        
        {{-- Logo / Brand --}}
        <div class="flex flex-col items-center mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-intrunex-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4" />
            </svg>
            <h1 class="text-2xl font-bold text-intrunex-accent mt-2">IntruNex</h1>
            <p class="text-gray-600 text-sm">Secure Your Web</p>
        </div>

        {{-- Error message --}}
        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        {{-- Login Form --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-gray-700 text-sm font-medium mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border-gray-300 rounded-lg focus:ring-intrunex-accent focus:border-intrunex-accent px-4 py-2">
            </div>

            <div>
                <label for="password" class="block text-gray-700 text-sm font-medium mb-1">Password</label>
                <input id="password" type="password" name="password" required
                       class="w-full border-gray-300 rounded-lg focus:ring-intrunex-accent focus:border-intrunex-accent px-4 py-2">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="mr-2 rounded border-gray-300 text-intrunex-accent focus:ring-intrunex-accent">
                    Remember me
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-intrunex-accent hover:underline">
                    Forgot Password?
                </a>
            </div>

            <button type="submit"
                class="w-full bg-intrunex-accent hover:bg-intrunex-accent2 text-black font-semibold py-2 rounded-lg transition-colors duration-300">
                Sign In
            </button>

            <p class="text-center text-sm text-gray-500">
                Don’t have an account?
                <a href="{{ route('register') }}" class="text-intrunex-accent hover:underline">Create one</a>
            </p>
        </form>
    </div>
</div>
@endsection
