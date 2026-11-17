<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LifePulse - Blood Donation Coordination Platform</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .pulse-glow { box-shadow: 0 0 25px rgba(239, 68, 68, 0.4); }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col antialiased">
    <!-- Top Alert Banner -->
    <div class="bg-gradient-to-r from-red-900 via-rose-900 to-red-950 px-4 py-2.5 text-center text-xs sm:text-sm font-medium text-rose-100 border-b border-rose-800/50">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <span class="flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span><strong>CSE470: Software Engineering Project</strong> &bull; Section 12 &bull; Faculty: SOSB</span>
            </span>
            <span class="hidden md:inline bg-rose-800/60 px-2.5 py-0.5 rounded text-xs">Collaborator: <strong>RifaatNuha</strong></span>
        </div>
    </div>

    <!-- Navigation -->
    <header class="border-b border-slate-800 bg-slate-900/70 backdrop-blur sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-red-500/30">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-xl font-bold tracking-tight text-white">Life<span class="text-red-500">Pulse</span></span>
                    <span class="hidden sm:inline-block ml-2 text-xs font-semibold px-2 py-0.5 rounded bg-red-500/10 text-red-400 border border-red-500/20">Blood Coordination</span>
                </div>
            </div>

            <nav class="flex items-center gap-2 sm:gap-4 text-sm font-medium">
                <a href="#features" class="text-slate-300 hover:text-white px-3 py-1.5 rounded-lg hover:bg-slate-800 transition">Features</a>
                <a href="#team" class="text-slate-300 hover:text-white px-3 py-1.5 rounded-lg hover:bg-slate-800 transition">Team Workload</a>
                <a href="#tech" class="text-slate-300 hover:text-white px-3 py-1.5 rounded-lg hover:bg-slate-800 transition">Tech Stack</a>
                <a href="https://github.com/abdulahadkhansiam-bot/LifePulse" target="_blank" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-white px-3.5 py-1.5 rounded-lg border border-slate-700 text-xs sm:text-sm font-semibold transition">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                    <span>GitHub Repo</span>
                </a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="flex-1">
        <section class="relative overflow-hidden py-16 sm:py-24 border-b border-slate-800">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-red-950/40 via-slate-950 to-slate-950 -z-10"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-semibold mb-6">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    <span>Sprint 1 Setup Active &bull; Laravel 11 MVC Baseline</span>
                </div>

                <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-white max-w-4xl mx-auto leading-tight">
                    Smart Blood Donation & <br class="hidden sm:inline"/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 via-rose-400 to-amber-400">Emergency Coordination Platform</span>
                </h1>

                <p class="mt-6 text-lg sm:text-xl text-slate-400 max-w-3xl mx-auto leading-relaxed">
                    Automating donor-patient compatibility matching, emergency SOS broadcasting, medical screening, and FEFO hospital blood inventory to eliminate critical delays and unit expiration.
                </p>

                <!-- Status Cards -->
                <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
                    <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-4 text-left">
                        <span class="text-xs text-slate-400 font-medium">Localhost Server</span>
                        <div class="mt-1 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                            <span class="text-sm font-bold text-white">127.0.0.1:8000</span>
                        </div>
                        <span class="text-[11px] text-emerald-400">Online & Serving</span>
                    </div>

                    <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-4 text-left">
                        <span class="text-xs text-slate-400 font-medium">Database Engine</span>
                        <div class="mt-1 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                            <span class="text-sm font-bold text-white">MySQL (lifepulse)</span>
                        </div>
                        <span class="text-[11px] text-emerald-400">Connected & Migrated</span>
                    </div>

                    <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-4 text-left">
                        <span class="text-xs text-slate-400 font-medium">Git Version Control</span>
                        <div class="mt-1 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-400"></span>
                            <span class="text-sm font-bold text-white">RifaatNuha</span>
                        </div>
                        <span class="text-[11px] text-blue-400">Authenticated Collaborator</span>
                    </div>

                    <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-4 text-left">
                        <span class="text-xs text-slate-400 font-medium">Architecture</span>
                        <div class="mt-1 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-purple-400"></span>
                            <span class="text-sm font-bold text-white">Strict Laravel MVC</span>
                        </div>
                        <span class="text-[11px] text-purple-400">Rulebook Compliant</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Feature Distribution Matrix -->
        <section id="team" class="py-16 bg-slate-900/40 border-b border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-12">
                    <h2 class="text-2xl sm:text-3xl font-bold text-white">Fair 4-Member Workload Matrix</h2>
                    <p class="mt-2 text-slate-400 text-sm sm:text-base">Exactly 5 features per member ensuring equal technical complexity and full defensibility in the individual viva.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Member 1: Rifaat Nuha -->
                    <div class="bg-slate-900 border-2 border-red-500/50 rounded-2xl p-6 relative shadow-lg shadow-red-500/5">
                        <div class="absolute -top-3 right-4 bg-red-600 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                            Current Machine
                        </div>
                        <div class="text-xs font-semibold text-red-400 mb-1">MEMBER 1 &bull; 23301320</div>
                        <h3 class="text-lg font-bold text-white">Rifaat Nuha</h3>
                        <p class="text-xs text-slate-400 mt-1 mb-4 font-medium">Donor Experience, Eligibility & Retention</p>

                        <ul class="space-y-2 text-xs">
                            <li class="bg-slate-800/80 rounded-lg p-2.5 border border-slate-700/60">
                                <span class="font-bold text-red-400">F-01</span> Donor Eligibility Engine (Medical Rules)
                            </li>
                            <li class="bg-slate-800/80 rounded-lg p-2.5 border border-slate-700/60">
                                <span class="font-bold text-red-400">F-02</span> Digital Donor Card (PDF/QR Generation)
                            </li>
                            <li class="bg-slate-800/80 rounded-lg p-2.5 border border-slate-700/60">
                                <span class="font-bold text-red-400">F-09</span> Appointment Scheduling (Concurrency)
                            </li>
                            <li class="bg-slate-800/80 rounded-lg p-2.5 border border-slate-700/60">
                                <span class="font-bold text-red-400">F-12</span> Reminder Scheduler Daemon (Cron)
                            </li>
                            <li class="bg-slate-800/80 rounded-lg p-2.5 border border-slate-700/60">
                                <span class="font-bold text-red-400">F-13</span> Donor Reliability Scoring Algorithm
                            </li>
                        </ul>
                    </div>

                    <!-- Member 2: Siam -->
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm">
                        <div class="text-xs font-semibold text-slate-400 mb-1">MEMBER 2 &bull; 23101011</div>
                        <h3 class="text-lg font-bold text-white">Md Abdul Ahad Khan Siam</h3>
                        <p class="text-xs text-slate-400 mt-1 mb-4 font-medium">Emergency Coordination & Spatial Matching</p>

                        <ul class="space-y-2 text-xs text-slate-300">
                            <li class="bg-slate-800/50 rounded-lg p-2.5 border border-slate-800">
                                <span class="font-bold text-slate-400">F-03</span> Blood Request Lifecycle & States
                            </li>
                            <li class="bg-slate-800/50 rounded-lg p-2.5 border border-slate-800">
                                <span class="font-bold text-slate-400">F-04</span> Biological ABO/Rh Compatibility
                            </li>
                            <li class="bg-slate-800/50 rounded-lg p-2.5 border border-slate-800">
                                <span class="font-bold text-slate-400">F-05</span> Outreach & Escalation Queues
                            </li>
                            <li class="bg-slate-800/50 rounded-lg p-2.5 border border-slate-800">
                                <span class="font-bold text-slate-400">F-06</span> Emergency (SOS) Broadcast Dispatch
                            </li>
                            <li class="bg-slate-800/50 rounded-lg p-2.5 border border-slate-800">
                                <span class="font-bold text-slate-400">F-15</span> Custom Haversine Spherical Search
                            </li>
                        </ul>
                    </div>

                    <!-- Member 3: Mashud -->
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm">
                        <div class="text-xs font-semibold text-slate-400 mb-1">MEMBER 3 &bull; 22299053</div>
                        <h3 class="text-lg font-bold text-white">Mashud Hasan</h3>
                        <p class="text-xs text-slate-400 mt-1 mb-4 font-medium">Blood Bank Operations & Clinical Safety</p>

                        <ul class="space-y-2 text-xs text-slate-300">
                            <li class="bg-slate-800/50 rounded-lg p-2.5 border border-slate-800">
                                <span class="font-bold text-slate-400">F-07</span> FEFO Blood Inventory Engine
                            </li>
                            <li class="bg-slate-800/50 rounded-lg p-2.5 border border-slate-800">
                                <span class="font-bold text-slate-400">F-08</span> Expiry & Low-Stock Auto-Alerts
                            </li>
                            <li class="bg-slate-800/50 rounded-lg p-2.5 border border-slate-800">
                                <span class="font-bold text-slate-400">F-10</span> Donor Vitals Screening & Deferral
                            </li>
                            <li class="bg-slate-800/50 rounded-lg p-2.5 border border-slate-800">
                                <span class="font-bold text-slate-400">F-11</span> Blood Allocation & OTP Verification
                            </li>
                            <li class="bg-slate-800/50 rounded-lg p-2.5 border border-slate-800">
                                <span class="font-bold text-slate-400">F-20</span> Camp Coordination & Volunteer Sync
                            </li>
                        </ul>
                    </div>

                    <!-- Member 4: Reehay -->
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm">
                        <div class="text-xs font-semibold text-slate-400 mb-1">MEMBER 4 &bull; 23201674</div>
                        <h3 class="text-lg font-bold text-white">Reehay Sabah Samama</h3>
                        <p class="text-xs text-slate-400 mt-1 mb-4 font-medium">Governance, Security, Analytics & KYC</p>

                        <ul class="space-y-2 text-xs text-slate-300">
                            <li class="bg-slate-800/50 rounded-lg p-2.5 border border-slate-800">
                                <span class="font-bold text-slate-400">F-14</span> Abuse & Duplicate Request Engine
                            </li>
                            <li class="bg-slate-800/50 rounded-lg p-2.5 border border-slate-800">
                                <span class="font-bold text-slate-400">F-16</span> Operational Analytics & Charts
                            </li>
                            <li class="bg-slate-800/50 rounded-lg p-2.5 border border-slate-800">
                                <span class="font-bold text-slate-400">F-17</span> Multi-Channel Notification Center
                            </li>
                            <li class="bg-slate-800/50 rounded-lg p-2.5 border border-slate-800">
                                <span class="font-bold text-slate-400">F-18</span> Immutable Audit Trail & PDF Export
                            </li>
                            <li class="bg-slate-800/50 rounded-lg p-2.5 border border-slate-800">
                                <span class="font-bold text-slate-400">F-19</span> Hospital KYC License Verification
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Technical Architecture Overview -->
        <section id="tech" class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-12">
                    <h2 class="text-2xl sm:text-3xl font-bold text-white">Strict MVC Layer Separation</h2>
                    <p class="mt-2 text-slate-400 text-sm">Meeting all guidelines stipulated in the CSE470 Software Engineering Rulebook.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
                        <div class="w-10 h-10 rounded-lg bg-blue-500/10 text-blue-400 flex items-center justify-center font-bold mb-4 border border-blue-500/20">M</div>
                        <h3 class="text-lg font-bold text-white mb-2">Models (`app/Models`)</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Defines database schemas, Eloquent ORM relationships, interval math scopes, and internal domain validations. Zero direct HTML rendering.
                        </p>
                    </div>

                    <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
                        <div class="w-10 h-10 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center font-bold mb-4 border border-emerald-500/20">V</div>
                        <h3 class="text-lg font-bold text-white mb-2">Views (`resources/views`)</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Pure server-rendered Blade templates displaying data provided by controllers. Zero raw SQL queries or business logic allowed in views.
                        </p>
                    </div>

                    <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
                        <div class="w-10 h-10 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center font-bold mb-4 border border-amber-500/20">C</div>
                        <h3 class="text-lg font-bold text-white mb-2">Controllers (`app/Http/Controllers`)</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Handles HTTP routing, Form Request validation, business service dispatching, and view return coordination.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800 bg-slate-900 py-8 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-2">
            <p><strong>LifePulse &copy; 2026</strong> &bull; Developed by Section 12 Group &bull; CSE470 Software Engineering</p>
            <p class="text-slate-600">Strictly follows Academic Integrity & AI Usage Policy (Section 6 & 11 of CSE470 Rulebook)</p>
        </div>
    </footer>
</body>
</html>
