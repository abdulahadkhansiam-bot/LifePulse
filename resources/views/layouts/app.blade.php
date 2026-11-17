<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'LifePulse - Blood Donation Coordination Platform' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col antialiased">
    <!-- Top Emergency Help Banner -->
    <div class="bg-gradient-to-r from-red-950 via-slate-900 to-red-950 border-b border-red-900/40 text-xs px-4 py-1.5">
        <div class="max-w-7xl mx-auto flex items-center justify-between text-slate-400">
            <span class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span class="text-slate-300 font-medium">LifePulse Emergency Blood Coordination Network &bull; 24/7 Active Hotline: <strong>10678</strong></span>
            </span>
            <span class="text-[11px] hidden sm:inline text-rose-300/90">Every Drop Saves Lives</span>
        </div>
    </div>

    <!-- Navigation Header -->
    <header class="bg-slate-900/90 border-b border-slate-800 backdrop-blur sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                    <div class="w-9 h-9 rounded-xl bg-red-600 flex items-center justify-center text-white shadow-md shadow-red-600/30 group-hover:scale-105 transition">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-white">Life<span class="text-red-500">Pulse</span></span>
                </a>

                @auth
                    <nav class="hidden md:flex items-center gap-1 ml-6 text-sm">
                        <a href="{{ route('dashboard') }}" class="px-3 py-1.5 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-red-600/10 text-red-400 font-semibold border border-red-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition">
                            Dashboard
                        </a>
                        <a href="{{ route('donor.eligibility') }}" class="px-3 py-1.5 rounded-lg {{ request()->routeIs('donor.eligibility*') ? 'bg-red-600/10 text-red-400 font-semibold border border-red-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition">
                            Donor Eligibility
                        </a>
                        <a href="{{ route('inventory.alerts') }}" class="px-3 py-1.5 rounded-lg {{ request()->routeIs('inventory.alerts*') ? 'bg-red-600/10 text-red-400 font-semibold border border-red-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition">
                            Stock & Expiry Alerts
                        </a>
                        <a href="{{ route('profile.edit') }}" class="px-3 py-1.5 rounded-lg {{ request()->routeIs('profile.*') ? 'bg-red-600/10 text-red-400 font-semibold border border-red-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition">
                            Profile
                        </a>
                    </nav>
                @endauth
            </div>

            <div class="flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-300 hover:text-white px-3.5 py-1.5 rounded-lg hover:bg-slate-800 transition">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="text-sm font-semibold text-white bg-red-600 hover:bg-red-500 px-4 py-1.5 rounded-lg shadow-sm shadow-red-600/30 transition">
                        Sign Up
                    </a>
                @else
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex flex-col text-right">
                            <span class="text-xs font-semibold text-white leading-tight">{{ auth()->user()->name }}</span>
                            <span class="text-[10px] uppercase font-bold tracking-wider text-red-400">{{ auth()->user()->role }}</span>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="w-8 h-8 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-xs font-bold text-red-400 hover:border-red-500 transition" title="Edit Profile">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs font-semibold text-slate-400 hover:text-red-400 px-2.5 py-1.5 rounded-lg hover:bg-slate-800 transition" title="Sign out securely">
                                Logout
                            </button>
                        </form>
                    </div>
                @endguest
            </div>
        </div>
    </header>

    <!-- Global Flash Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
        @if (session('success'))
            <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-300 text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session('info'))
            <div class="p-4 rounded-xl bg-blue-500/10 border border-blue-500/30 text-blue-300 text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-300 text-sm shadow-sm space-y-1">
                <div class="font-bold flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Please correct the following errors:</span>
                </div>
                <ul class="list-disc list-inside text-xs text-red-200/90 pl-2 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-1 py-6 sm:py-8">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Global Footer -->
    <footer class="border-t border-slate-800/80 bg-slate-900/60 py-6 text-center text-xs text-slate-500 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-1">
            <p><strong>LifePulse &copy; 2026</strong> &bull; Voluntary Blood Donation & Emergency Matching Platform</p>
            <p class="text-slate-600">Dedicated to eliminating blood shortages and connecting donors directly with clinical facilities.</p>
        </div>
    </footer>
</body>
</html>
