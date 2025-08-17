@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-intrunex-dark via-black to-gray-900 p-6">
    <div class="bg-white/90 backdrop-blur-md p-8 rounded-xl shadow-lg w-full max-w-md">

        {{-- Logo --}}
        <div class="flex flex-col items-center mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-intrunex-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4" />
            </svg>
            <h1 class="text-2xl font-bold text-intrunex-accent mt-2">Create Your IntruNex Account</h1>
            <p class="text-gray-600 text-sm">Secure your web presence</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required
                    class="w-full border-gray-300 rounded-lg focus:ring-intrunex-accent focus:border-intrunex-accent px-4 py-2">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="w-full border-gray-300 rounded-lg focus:ring-intrunex-accent focus:border-intrunex-accent px-4 py-2">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input id="password" type="password" name="password" required
                    class="w-full border-gray-300 rounded-lg focus:ring-intrunex-accent focus:border-intrunex-accent px-4 py-2">
                <small class="text-xs text-gray-500">Use at least 8 characters with numbers & symbols</small>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="w-full border-gray-300 rounded-lg focus:ring-intrunex-accent focus:border-intrunex-accent px-4 py-2">
            </div>

            <button type="submit"
                class="w-full bg-intrunex-accent hover:bg-intrunex-accent2 text-black font-semibold py-2 rounded-lg transition">
                Create Account
            </button>

            <p class="text-center text-sm text-gray-500">
                Already have an account?
                <a href="{{ route('login') }}" class="text-intrunex-accent hover:underline">Sign in here</a>
            </p>
        </form>
    </div>
</div>
@endsection
