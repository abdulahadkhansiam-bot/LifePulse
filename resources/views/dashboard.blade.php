@extends('layouts.app', ['title' => 'Dashboard - LifePulse'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <!-- Welcome Header Banner -->
    <div class="bg-gradient-to-r from-red-950/60 via-slate-900 to-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-xl">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-red-600 to-rose-400 text-white font-extrabold text-2xl flex items-center justify-center shadow-lg shadow-red-600/30 shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold text-white tracking-tight">Welcome, {{ $user->name }}!</h1>
                    <span class="px-2.5 py-0.5 rounded-full bg-red-600/20 border border-red-500/30 text-red-400 font-bold uppercase text-[10px]">
                        {{ $user->role }}
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-1">
                    Logged in as <span class="text-slate-300 font-medium">{{ $user->email }}</span> &bull; 
                    Blood Group: <span class="text-red-400 font-bold">{{ $user->blood_group ?? 'Not set' }}</span> &bull;
                    City: <span class="text-slate-300">{{ $user->city ?? 'Not set' }}</span>
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

    <!-- System Architecture Status -->
    <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
            <div>
                <h2 class="text-base font-bold text-white">Base Foundation & Skeleton Status</h2>
                <p class="text-xs text-slate-400">Core authentication, session security, and folder boundaries are ready.</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                Skeleton Operational
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
            <div class="bg-slate-950/80 p-3.5 rounded-xl border border-slate-800">
                <span class="text-slate-400 block font-medium">Authentication</span>
                <span class="text-sm font-bold text-emerald-400 mt-0.5 block">Active</span>
                <span class="text-[11px] text-slate-500">Bcrypt + Throttling</span>
            </div>
            <div class="bg-slate-950/80 p-3.5 rounded-xl border border-slate-800">
                <span class="text-slate-400 block font-medium">Session Handling</span>
                <span class="text-sm font-bold text-emerald-400 mt-0.5 block">Protected</span>
                <span class="text-[11px] text-slate-500">CSRF & Fixation Safe</span>
            </div>
            <div class="bg-slate-950/80 p-3.5 rounded-xl border border-slate-800">
                <span class="text-slate-400 block font-medium">Database</span>
                <span class="text-sm font-bold text-emerald-400 mt-0.5 block">MySQL</span>
                <span class="text-[11px] text-slate-500">lifepulse (Migrated)</span>
            </div>
            <div class="bg-slate-950/80 p-3.5 rounded-xl border border-slate-800">
                <span class="text-slate-400 block font-medium">Feature Scope</span>
                <span class="text-sm font-bold text-blue-400 mt-0.5 block">Ready to Build</span>
                <span class="text-[11px] text-slate-500">Folders partitioned</span>
            </div>
        </div>
    </div>

    <!-- Member Code Partitions Grid -->
    <div>
        <div class="mb-4">
            <h2 class="text-lg font-bold text-white tracking-tight">Dedicated Member Workspace Partitions</h2>
            <p class="text-xs text-slate-400">Each member develops exclusively within their designated MVC directories to avoid conflicts.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Member 1: Rifaat Nuha -->
            <div class="bg-slate-900 border-2 border-red-500/40 rounded-2xl p-6 relative">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-red-400 tracking-wider">MEMBER 1 &bull; 23301320</span>
                    <span class="px-2 py-0.5 bg-red-600 text-white text-[10px] font-bold rounded-full">Your Assigned Domain</span>
                </div>
                <h3 class="text-base font-bold text-white">Rifaat Nuha</h3>
                <p class="text-xs text-slate-400 mb-4 font-medium">Donor Experience, Eligibility & Retention</p>

                <div class="space-y-2 text-xs text-slate-300 mb-4">
                    <div class="bg-slate-950 p-2.5 rounded-lg border border-slate-800 font-mono text-[11px]">
                        <span class="text-slate-400">Controllers:</span> app/Http/Controllers/Member1_Rifaat/<br>
                        <span class="text-slate-400">Models:</span> app/Models/Member1_Rifaat/<br>
                        <span class="text-slate-400">Views:</span> resources/views/member1_rifaat/
                    </div>
                </div>

                <div class="text-xs font-semibold text-slate-400 mb-2">Assigned Features:</div>
                <ul class="text-xs space-y-1.5 text-slate-300">
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-red-400"></span><span class="font-bold text-red-400">F-01:</span> Donor Eligibility Engine (Interval & Medical Rules)</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-red-400"></span><span class="font-bold text-red-400">F-02:</span> Digital Donor Card (PDF & Dynamic QR)</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-red-400"></span><span class="font-bold text-red-400">F-09:</span> Appointment Scheduling (Slot Concurrency)</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-red-400"></span><span class="font-bold text-red-400">F-12:</span> Reminder Scheduler Daemon (Cron Jobs)</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-red-400"></span><span class="font-bold text-red-400">F-13:</span> Donor Reliability Scoring Algorithm</li>
                </ul>
            </div>

            <!-- Member 2: Siam -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-400 tracking-wider">MEMBER 2 &bull; 23101011</span>
                    <span class="px-2 py-0.5 bg-slate-800 text-slate-400 text-[10px] font-bold rounded-full">Siam</span>
                </div>
                <h3 class="text-base font-bold text-white">Md Abdul Ahad Khan Siam</h3>
                <p class="text-xs text-slate-400 mb-4 font-medium">Emergency Coordination & Spatial Matching</p>

                <div class="space-y-2 text-xs text-slate-300 mb-4">
                    <div class="bg-slate-950 p-2.5 rounded-lg border border-slate-800 font-mono text-[11px]">
                        <span class="text-slate-400">Controllers:</span> app/Http/Controllers/Member2_Siam/<br>
                        <span class="text-slate-400">Models:</span> app/Models/Member2_Siam/<br>
                        <span class="text-slate-400">Views:</span> resources/views/member2_siam/
                    </div>
                </div>

                <div class="text-xs font-semibold text-slate-400 mb-2">Assigned Features:</div>
                <ul class="text-xs space-y-1.5 text-slate-400">
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span><span class="font-semibold text-slate-300">F-03:</span> Blood Request Lifecycle & States</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span><span class="font-semibold text-slate-300">F-04:</span> Biological ABO/Rh Compatibility Engine</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span><span class="font-semibold text-slate-300">F-05:</span> Outreach & Escalation Queues</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span><span class="font-semibold text-slate-300">F-06:</span> Emergency (SOS) Broadcast Dispatch</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span><span class="font-semibold text-slate-300">F-15:</span> Custom Haversine Distance Calculation</li>
                </ul>
            </div>

            <!-- Member 3: Mashud -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-400 tracking-wider">MEMBER 3 &bull; 22299053</span>
                    <span class="px-2 py-0.5 bg-slate-800 text-slate-400 text-[10px] font-bold rounded-full">Mashud</span>
                </div>
                <h3 class="text-base font-bold text-white">Mashud Hasan</h3>
                <p class="text-xs text-slate-400 mb-4 font-medium">Blood Bank Operations & Clinical Safety</p>

                <div class="space-y-2 text-xs text-slate-300 mb-4">
                    <div class="bg-slate-950 p-2.5 rounded-lg border border-slate-800 font-mono text-[11px]">
                        <span class="text-slate-400">Controllers:</span> app/Http/Controllers/Member3_Mashud/<br>
                        <span class="text-slate-400">Models:</span> app/Models/Member3_Mashud/<br>
                        <span class="text-slate-400">Views:</span> resources/views/member3_mashud/
                    </div>
                </div>

                <div class="text-xs font-semibold text-slate-400 mb-2">Assigned Features:</div>
                <ul class="text-xs space-y-1.5 text-slate-400">
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span><span class="font-semibold text-slate-300">F-07:</span> FEFO Blood Inventory Engine</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span><span class="font-semibold text-slate-300">F-08:</span> Expiry & Low-Stock Automated Alerts</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span><span class="font-semibold text-slate-300">F-10:</span> Donor Vitals Screening & Deferral</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span><span class="font-semibold text-slate-300">F-11:</span> Blood Allocation & OTP Verification</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span><span class="font-semibold text-slate-300">F-20:</span> Camp Coordination & Volunteer Sync</li>
                </ul>
            </div>

            <!-- Member 4: Reehay -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-400 tracking-wider">MEMBER 4 &bull; 23201674</span>
                    <span class="px-2 py-0.5 bg-slate-800 text-slate-400 text-[10px] font-bold rounded-full">Reehay</span>
                </div>
                <h3 class="text-base font-bold text-white">Reehay Sabah Samama</h3>
                <p class="text-xs text-slate-400 mb-4 font-medium">Governance, Security, Analytics & KYC</p>

                <div class="space-y-2 text-xs text-slate-300 mb-4">
                    <div class="bg-slate-950 p-2.5 rounded-lg border border-slate-800 font-mono text-[11px]">
                        <span class="text-slate-400">Controllers:</span> app/Http/Controllers/Member4_Reehay/<br>
                        <span class="text-slate-400">Models:</span> app/Models/Member4_Reehay/<br>
                        <span class="text-slate-400">Views:</span> resources/views/member4_reehay/
                    </div>
                </div>

                <div class="text-xs font-semibold text-slate-400 mb-2">Assigned Features:</div>
                <ul class="text-xs space-y-1.5 text-slate-400">
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span><span class="font-semibold text-slate-300">F-14:</span> Abuse & Duplicate Request Engine</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span><span class="font-semibold text-slate-300">F-16:</span> Operational Analytics & Real-Time Charts</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span><span class="font-semibold text-slate-300">F-17:</span> Multi-Channel Notification Center</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span><span class="font-semibold text-slate-300">F-18:</span> Immutable Audit Trail & PDF/CSV Export</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span><span class="font-semibold text-slate-300">F-19:</span> Hospital KYC Document Verification</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
