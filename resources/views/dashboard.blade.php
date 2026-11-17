@extends('layouts.app', ['title' => 'Dashboard - LifePulse Blood Network'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <!-- User Welcome Banner -->
    <div class="bg-gradient-to-r from-red-950/70 via-slate-900 to-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-xl">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-red-600 to-rose-400 text-white font-extrabold text-2xl flex items-center justify-center shadow-lg shadow-red-600/30 shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold text-white tracking-tight">Welcome back, {{ $user->name }}</h1>
                    <span class="px-2.5 py-0.5 rounded-full bg-red-600/20 border border-red-500/30 text-red-400 font-bold uppercase text-[10px]">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-1">
                    {{ $user->email }} &bull; 
                    Blood Group: <span class="text-red-400 font-bold">{{ $user->blood_group ?? 'Not set' }}</span> &bull;
                    Location: <span class="text-slate-300 font-medium">{{ $user->city ?? 'Not set' }}</span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('profile.edit') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold border border-slate-700 transition">
                Edit Profile
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl bg-red-600/20 hover:bg-red-600/30 text-red-400 text-xs font-semibold border border-red-500/30 transition">
                    Sign Out
                </button>
            </form>
        </div>
    </div>

    <!-- Overview Metric Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5">
            <span class="text-xs text-slate-400 font-medium block">Eligibility Status</span>
            <div class="mt-1 flex items-center gap-2">
                @if ($user->donorProfile && $user->donorProfile->is_eligible)
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                    <span class="text-base font-bold text-emerald-400">Eligible to Donate</span>
                @elseif ($user->donorProfile && $user->donorProfile->eligibility_status === 'temporarily_deferred')
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                    <span class="text-base font-bold text-amber-400">Deferred ({{ $user->donorProfile->daysRemaining() }}d)</span>
                @elseif ($user->donorProfile && $user->donorProfile->eligibility_status === 'permanently_deferred')
                    <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                    <span class="text-base font-bold text-red-400">Ineligible</span>
                @else
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-400"></span>
                    <span class="text-base font-bold text-slate-300">Pending Evaluation</span>
                @endif
            </div>
            <a href="{{ route('donor.eligibility') }}" class="text-[11px] text-red-400 hover:text-red-300 mt-1 block font-semibold">
                {{ $user->donorProfile ? 'Update Evaluation &rarr;' : 'Check Eligibility &rarr;' }}
            </a>
        </div>

        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5">
            <span class="text-xs text-slate-400 font-medium block">Your Blood Group</span>
            <div class="mt-1 flex items-center gap-2">
                <span class="text-xl font-extrabold text-red-500">{{ $user->blood_group ?? 'N/A' }}</span>
            </div>
            <span class="text-[11px] text-slate-400 mt-1 block">Verified on profile</span>
        </div>

        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5">
            <span class="text-xs text-slate-400 font-medium block">Total Donations</span>
            <div class="mt-1 flex items-center gap-2">
                <span class="text-xl font-extrabold text-white">0</span>
                <span class="text-xs text-slate-400">Pledges</span>
            </div>
            <span class="text-[11px] text-slate-400 mt-1 block">Pledge to start saving lives</span>
        </div>

        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5">
            <span class="text-xs text-slate-400 font-medium block">Stock & Expiry Alerts</span>
            <div class="mt-1 flex items-center gap-2">
                <span class="text-xl font-extrabold text-amber-400">R8</span>
                <span class="text-xs text-slate-400">Active Engine</span>
            </div>
            <a href="{{ route('inventory.alerts') }}" class="text-[11px] text-amber-400 hover:text-amber-300 mt-1 block font-semibold">
                Open Stock Monitor &rarr;
            </a>
        </div>
    </div>

    <!-- Quick Action Hub -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-red-600/10 border border-red-500/20 text-red-500 flex items-center justify-center font-bold mb-4">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </div>
                <h3 class="text-base font-bold text-white mb-1">Donor Health Evaluation (R1)</h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Evaluate age, weight, donation intervals, and medical questionnaire to compute your official clinical eligibility.
                </p>
            </div>
            <div class="mt-5">
                <a href="{{ route('donor.eligibility') }}" class="inline-block w-full py-2.5 px-4 bg-red-600 hover:bg-red-500 text-white font-bold text-xs rounded-xl text-center shadow-md shadow-red-600/20 transition">
                    Run Evaluation Engine
                </a>
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-amber-600/10 border border-amber-500/20 text-amber-500 flex items-center justify-center font-bold mb-4">
                    <svg class="w-5 h-5 fill-none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-base font-bold text-white mb-1">Stock & Expiry Alert System (R8)</h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Automated daily scan for units nearing expiry (&le; 48h platelets, &le; 7d whole blood) and low-stock thresholds.
                </p>
            </div>
            <div class="mt-5">
                <a href="{{ route('inventory.alerts') }}" class="inline-block w-full py-2.5 px-4 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-xl text-center border border-slate-700 transition">
                    View Stock Alerts
                </a>
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-blue-600/10 border border-blue-500/20 text-blue-500 flex items-center justify-center font-bold mb-4">
                    <svg class="w-5 h-5 fill-none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-base font-bold text-white mb-1">Upcoming Donation Drives</h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    View and register for local community blood donation camps organized in partnership with regional hospitals.
                </p>
            </div>
            <div class="mt-5">
                <a href="{{ route('home') }}#camps" class="inline-block w-full py-2.5 px-4 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-xl text-center border border-slate-700 transition">
                    View Donation Camps
                </a>
            </div>
        </div>
    </div>

    <!-- Active Emergency Blood Requests Feed -->
    <div id="active-requests" class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-base font-bold text-white">Live Regional Blood Requests</h2>
                <p class="text-xs text-slate-400">Patients and hospitals in your region requiring voluntary donors right now.</p>
            </div>
            <span class="px-2.5 py-1 rounded-full bg-red-500/10 text-red-400 text-xs font-bold border border-red-500/20">
                Live Feed
            </span>
        </div>

        <div class="space-y-3">
            <div class="p-4 rounded-xl bg-slate-950/80 border border-red-500/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-xl bg-red-600/20 text-red-500 font-extrabold text-xl flex items-center justify-center shrink-0 border border-red-500/30">
                        O-
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-white">Emergency Surgery Patient</h3>
                            <span class="px-2 py-0.5 rounded bg-red-600 text-white text-[10px] font-bold uppercase">SOS Critical</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">Dhaka Medical College Hospital &bull; 2 Bags Whole Blood &bull; Needed within 3 hours</p>
                    </div>
                </div>
                <button type="button" class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white text-xs font-bold rounded-lg shadow-sm transition shrink-0">
                    Pledge Blood
                </button>
            </div>

            <div class="p-4 rounded-xl bg-slate-950/80 border border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-xl bg-slate-800 text-white font-extrabold text-xl flex items-center justify-center shrink-0 border border-slate-700">
                        B+
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-white">Thalassemia Care Patient</h3>
                            <span class="px-2 py-0.5 rounded bg-amber-500/20 text-amber-400 text-[10px] font-bold uppercase">Urgent</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">National Institute of Diseases &bull; 1 Bag PRBC &bull; Needed within 12 hours</p>
                    </div>
                </div>
                <button type="button" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold rounded-lg border border-slate-700 transition shrink-0">
                    Pledge Blood
                </button>
            </div>

            <div class="p-4 rounded-xl bg-slate-950/80 border border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-xl bg-slate-800 text-white font-extrabold text-xl flex items-center justify-center shrink-0 border border-slate-700">
                        A+
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-white">Dengue Critical Patient</h3>
                            <span class="px-2 py-0.5 rounded bg-blue-500/20 text-blue-400 text-[10px] font-bold uppercase">Platelets</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">Kurmitola General Hospital &bull; 4 Units Platelets &bull; Needed today</p>
                    </div>
                </div>
                <button type="button" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold rounded-lg border border-slate-700 transition shrink-0">
                    Pledge Blood
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
