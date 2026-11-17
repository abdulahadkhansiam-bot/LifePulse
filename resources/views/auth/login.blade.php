@extends('layouts.app', ['title' => 'Sign In - LifePulse'])

@section('content')
<div class="max-w-md mx-auto px-4 py-8">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6 sm:p-8">
        <div class="text-center mb-6">
            <div class="w-12 h-12 rounded-xl bg-red-600/10 border border-red-500/20 text-red-500 flex items-center justify-center mx-auto mb-3 shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Welcome Back</h1>
            <p class="text-xs text-slate-400 mt-1">Sign in with your registered credentials</p>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    autocomplete="email"
                    placeholder="e.g. user@example.com"
                    class="w-full px-3.5 py-2.5 rounded-lg bg-slate-950 border @error('email') border-red-500 @else border-slate-700 @enderror text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                >
                @error('email')
                    <p class="text-xs text-red-400 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">Password</label>
                </div>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    required 
                    autocomplete="current-password"
                    placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                    class="w-full px-3.5 py-2.5 rounded-lg bg-slate-950 border @error('password') border-red-500 @else border-slate-700 @enderror text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                >
                @error('password')
                    <p class="text-xs text-red-400 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        value="1" 
                        {{ old('remember') ? 'checked' : '' }}
                        class="w-4 h-4 rounded bg-slate-950 border-slate-700 text-red-600 focus:ring-red-500 focus:ring-offset-slate-900"
                    >
                    <span class="text-xs text-slate-400">Remember this device</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full py-2.5 px-4 bg-red-600 hover:bg-red-500 text-white font-semibold text-sm rounded-lg shadow-md shadow-red-600/30 transition duration-150 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-slate-900"
            >
                Sign In
            </button>
        </form>

        <div class="mt-6 pt-5 border-t border-slate-800 text-center">
            <p class="text-xs text-slate-400">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-red-400 hover:text-red-300 font-semibold ml-1">Create one here &rarr;</a>
            </p>
        </div>

        <!-- Testing / Demo Helper for Viva Assessment -->
        <details class="mt-4 pt-3 border-t border-slate-800/60 text-xs">
            <summary class="cursor-pointer text-slate-500 hover:text-slate-300 font-medium select-none text-center">
                &bull; Quick Testing Credentials (Click to view)
            </summary>
            <div class="mt-2.5 p-3 rounded-xl bg-slate-950/80 border border-slate-800 text-[11px] text-slate-400 space-y-1.5 font-mono">
                <div><span class="text-red-400 font-bold">Donor:</span> rifaat@lifepulse.org / Password123!</div>
                <div><span class="text-red-400 font-bold">Requester:</span> siam@lifepulse.org / Password123!</div>
                <div><span class="text-red-400 font-bold">Hospital:</span> mashud@lifepulse.org / Password123!</div>
                <div><span class="text-red-400 font-bold">Admin:</span> reehay@lifepulse.org / Password123!</div>
            </div>
        </details>
    </div>
</div>
@endsection
