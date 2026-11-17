<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LifePulse - Blood Donation & Emergency Coordination Platform</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes pulse-subtle {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .live-dot { animation: pulse-subtle 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col antialiased">
    <!-- Emergency Notification Strip -->
    <div class="bg-gradient-to-r from-red-950 via-rose-900 to-red-950 px-4 py-2 text-xs sm:text-sm font-medium text-rose-100 border-b border-rose-800/60">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-400 live-dot"></span>
                <span><strong>24/7 Emergency Blood Coordination Network</strong> &bull; Need urgent blood?</span>
            </div>
            <div class="flex items-center gap-4 text-xs">
                <span class="hidden sm:inline">Emergency Hotline: <strong class="text-white underline">10678</strong> (Toll-Free)</span>
                <span class="bg-rose-800/80 px-2.5 py-0.5 rounded-full text-white font-semibold">Live Network</span>
            </div>
        </div>
    </div>

    <!-- Navigation Header -->
    <header class="border-b border-slate-800/80 bg-slate-900/80 backdrop-blur sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                    <div class="w-10 h-10 rounded-xl bg-red-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-red-500/30 group-hover:scale-105 transition">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-bold tracking-tight text-white">Life<span class="text-red-500">Pulse</span></span>
                        <span class="hidden sm:inline-block ml-2 text-[10px] font-bold px-2 py-0.5 rounded bg-red-500/10 text-red-400 border border-red-500/20 uppercase tracking-wider">Blood Network</span>
                    </div>
                </a>
            </div>

            <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="#how-it-works" class="text-slate-300 hover:text-white transition">How It Works</a>
                <a href="#urgent-requests" class="text-slate-300 hover:text-white transition">Urgent Requests</a>
                <a href="#compatibility" class="text-slate-300 hover:text-white transition">Blood Guide</a>
                <a href="#camps" class="text-slate-300 hover:text-white transition">Donation Camps</a>
                <a href="#faq" class="text-slate-300 hover:text-white transition">Donor FAQ</a>
            </nav>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold shadow-md shadow-red-600/30 transition">
                        My Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-slate-300 hover:text-white px-3 py-1.5 rounded-lg hover:bg-slate-800 transition text-xs sm:text-sm font-semibold">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold shadow-md shadow-red-600/30 transition">
                        Register as Donor
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-1">
        <!-- Hero Section -->
        <section class="relative overflow-hidden py-16 sm:py-24 border-b border-slate-800/80">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-red-950/40 via-slate-950 to-slate-950 -z-10"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-semibold mb-6">
                    <span class="w-2 h-2 rounded-full bg-red-500 live-dot"></span>
                    <span>Every Drop Counts &bull; 100% Free Voluntary Coordination</span>
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-white max-w-5xl mx-auto leading-tight">
                    Connecting Donors With <br class="hidden sm:inline"/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 via-rose-400 to-amber-400">Patients Who Need It Most</span>
                </h1>

                <p class="mt-6 text-base sm:text-xl text-slate-300 max-w-3xl mx-auto leading-relaxed">
                    LifePulse bridges the critical gap between voluntary blood donors, emergency patients, and certified hospital blood banks. Find nearby verified donors in minutes.
                </p>

                <!-- Primary CTA Buttons -->
                <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ route('register') }}" class="px-7 py-3.5 rounded-xl bg-red-600 hover:bg-red-500 font-bold text-white text-sm shadow-xl shadow-red-600/30 transition flex items-center gap-2">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        <span>Become a Donor</span>
                    </a>
                    <a href="#urgent-requests" class="px-7 py-3.5 rounded-xl bg-slate-900 hover:bg-slate-800 font-semibold text-slate-200 text-sm border border-slate-700 shadow-md transition flex items-center gap-2">
                        <span>View Urgent Requests</span>
                        <span class="text-red-400 font-bold">&darr;</span>
                    </a>
                </div>

                <!-- Live Network Statistics -->
                <div class="mt-14 grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
                    <div class="bg-slate-900/80 border border-slate-800/80 rounded-2xl p-5 text-center">
                        <div class="text-2xl sm:text-3xl font-extrabold text-white">15,400+</div>
                        <div class="text-xs text-slate-400 mt-1 font-medium">Voluntary Donors</div>
                    </div>
                    <div class="bg-slate-900/80 border border-slate-800/80 rounded-2xl p-5 text-center">
                        <div class="text-2xl sm:text-3xl font-extrabold text-red-500">9,850+</div>
                        <div class="text-xs text-slate-400 mt-1 font-medium">Units Coordinated</div>
                    </div>
                    <div class="bg-slate-900/80 border border-slate-800/80 rounded-2xl p-5 text-center">
                        <div class="text-2xl sm:text-3xl font-extrabold text-emerald-400">98.2%</div>
                        <div class="text-xs text-slate-400 mt-1 font-medium">Fulfillment Rate</div>
                    </div>
                    <div class="bg-slate-900/80 border border-slate-800/80 rounded-2xl p-5 text-center">
                        <div class="text-2xl sm:text-3xl font-extrabold text-amber-400">&lt; 3 Min</div>
                        <div class="text-xs text-slate-400 mt-1 font-medium">Match Latency</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quick Blood Donor Search Bar -->
        <section class="max-w-5xl mx-auto px-4 -mt-8 relative z-20">
            <div class="bg-slate-900 border border-slate-700/80 rounded-2xl p-6 shadow-2xl">
                <div class="text-sm font-bold text-white mb-3 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                    <span>Quick Donor Proximity Search</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Blood Group</label>
                        <select class="w-full px-3 py-2.5 rounded-lg bg-slate-950 border border-slate-700 text-white text-xs focus:ring-2 focus:ring-red-500 focus:outline-none">
                            <option value="">All Blood Groups</option>
                            <option value="A+">A+ (A Positive)</option>
                            <option value="A-">A- (A Negative)</option>
                            <option value="B+">B+ (B Positive)</option>
                            <option value="B-">B- (B Negative)</option>
                            <option value="AB+">AB+ (AB Positive)</option>
                            <option value="AB-">AB- (AB Negative)</option>
                            <option value="O+">O+ (O Positive)</option>
                            <option value="O-">O- (O Negative)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Region / City</label>
                        <input type="text" placeholder="e.g. Dhaka, Dhanmondi" class="w-full px-3 py-2.5 rounded-lg bg-slate-950 border border-slate-700 text-white placeholder-slate-500 text-xs focus:ring-2 focus:ring-red-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Urgency</label>
                        <select class="w-full px-3 py-2.5 rounded-lg bg-slate-950 border border-slate-700 text-white text-xs focus:ring-2 focus:ring-red-500 focus:outline-none">
                            <option value="critical">Critical SOS (Immediate)</option>
                            <option value="today">Within 24 Hours</option>
                            <option value="scheduled">Planned Surgery</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <a href="{{ route('register') }}" class="w-full py-2.5 px-4 bg-red-600 hover:bg-red-500 text-white font-bold text-xs rounded-lg text-center shadow-md transition">
                            Search Available Donors
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="py-20 border-b border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="text-xs font-bold text-red-500 uppercase tracking-wider">Fast & Reliable Process</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-1">How LifePulse Works</h2>
                    <p class="mt-3 text-slate-400 text-sm sm:text-base">Connecting donors with hospital blood banks and patients through an automated 3-step pipeline.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Step 1 -->
                    <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 relative">
                        <div class="w-12 h-12 rounded-xl bg-red-600/10 border border-red-500/20 text-red-500 font-extrabold text-xl flex items-center justify-center mb-5">
                            01
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Post Request or Pledge</h3>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                            Patients or hospital staff submit blood needs with required blood group, units, and hospital location. Voluntary donors register availability and health status.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 relative">
                        <div class="w-12 h-12 rounded-xl bg-rose-600/10 border border-rose-500/20 text-rose-500 font-extrabold text-xl flex items-center justify-center mb-5">
                            02
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Automated Compatibility Match</h3>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                            LifePulse runs real-time ABO/Rh biological matching and proximity filters, immediately dispatching alerts to eligible donors within the required radius.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 relative">
                        <div class="w-12 h-12 rounded-xl bg-amber-600/10 border border-amber-500/20 text-amber-500 font-extrabold text-xl flex items-center justify-center mb-5">
                            03
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Safe Hospital Donation</h3>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                            Donors complete pre-donation clinical vitals screening at the hospital. Collected units are verified and safely allocated to save the patient's life.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Urgent Blood Requests Live Board -->
        <section id="urgent-requests" class="py-20 bg-slate-900/40 border-b border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                    <div>
                        <span class="text-xs font-bold text-red-500 uppercase tracking-wider">Live Requests</span>
                        <h2 class="text-3xl font-extrabold text-white mt-1">Urgent Patient Blood Needs</h2>
                        <p class="mt-2 text-slate-400 text-sm">Verified hospital requests currently waiting for voluntary donors.</p>
                    </div>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-xs font-bold text-red-400 hover:text-red-300 transition">
                        <span>Pledge Blood for a Patient</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Request Card 1 -->
                    <div class="bg-slate-900 border border-red-500/30 rounded-2xl p-6 relative overflow-hidden">
                        <div class="absolute top-0 right-0 bg-red-600 text-white text-[10px] font-bold px-3 py-1 rounded-bl-lg uppercase tracking-wider">
                            Critical SOS
                        </div>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-3xl font-extrabold text-red-500">O-</span>
                            <div>
                                <h3 class="text-sm font-bold text-white">Emergency Surgery Patient</h3>
                                <p class="text-xs text-slate-400">Dhaka Medical College Hospital</p>
                            </div>
                        </div>
                        <div class="space-y-1.5 text-xs text-slate-300 border-t border-slate-800 pt-3">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Required Units:</span>
                                <span class="font-bold text-white">2 Bags (Whole Blood)</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Time Window:</span>
                                <span class="font-bold text-rose-400">Within 3 Hours</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Location:</span>
                                <span>Bakshibazar, Dhaka</span>
                            </div>
                        </div>
                        <a href="{{ route('register') }}" class="mt-4 block w-full text-center py-2 bg-red-600/20 hover:bg-red-600/30 text-red-400 border border-red-500/30 font-bold text-xs rounded-lg transition">
                            Respond to Emergency
                        </a>
                    </div>

                    <!-- Request Card 2 -->
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 relative">
                        <div class="absolute top-0 right-0 bg-amber-500 text-slate-950 text-[10px] font-bold px-3 py-1 rounded-bl-lg uppercase tracking-wider">
                            Urgent
                        </div>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-3xl font-extrabold text-white">B+</span>
                            <div>
                                <h3 class="text-sm font-bold text-white">Thalassemia Care Patient</h3>
                                <p class="text-xs text-slate-400">National Institute of Diseases</p>
                            </div>
                        </div>
                        <div class="space-y-1.5 text-xs text-slate-300 border-t border-slate-800 pt-3">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Required Units:</span>
                                <span class="font-bold text-white">1 Bag (PRBC)</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Time Window:</span>
                                <span class="font-bold text-amber-400">Within 12 Hours</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Location:</span>
                                <span>Mohakhali, Dhaka</span>
                            </div>
                        </div>
                        <a href="{{ route('register') }}" class="mt-4 block w-full text-center py-2 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-lg border border-slate-700 transition">
                            Pledge Donation
                        </a>
                    </div>

                    <!-- Request Card 3 -->
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 relative">
                        <div class="absolute top-0 right-0 bg-blue-600 text-white text-[10px] font-bold px-3 py-1 rounded-bl-lg uppercase tracking-wider">
                            Platelets
                        </div>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-3xl font-extrabold text-white">A+</span>
                            <div>
                                <h3 class="text-sm font-bold text-white">Dengue Critical Patient</h3>
                                <p class="text-xs text-slate-400">Kurmitola General Hospital</p>
                            </div>
                        </div>
                        <div class="space-y-1.5 text-xs text-slate-300 border-t border-slate-800 pt-3">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Required Units:</span>
                                <span class="font-bold text-white">4 Units (Platelet Apheresis)</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Time Window:</span>
                                <span class="font-bold text-blue-400">Within 8 Hours</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Location:</span>
                                <span>Cantonment, Dhaka</span>
                            </div>
                        </div>
                        <a href="{{ route('register') }}" class="mt-4 block w-full text-center py-2 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-lg border border-slate-700 transition">
                            Pledge Donation
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Blood Type Compatibility Guide -->
        <section id="compatibility" class="py-20 border-b border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="text-xs font-bold text-red-500 uppercase tracking-wider">Medical Knowledge</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-1">Blood Compatibility Matrix</h2>
                    <p class="mt-2 text-slate-400 text-sm">Understanding ABO and Rh compatibility ensures every recipient receives safe, life-saving blood.</p>
                </div>

                <div class="overflow-x-auto bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl max-w-4xl mx-auto">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 text-xs uppercase tracking-wider">
                                <th class="py-3 px-4">Blood Type</th>
                                <th class="py-3 px-4">Can Donate To</th>
                                <th class="py-3 px-4">Can Receive From</th>
                                <th class="py-3 px-4">Type Feature</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 font-mono">
                            <tr>
                                <td class="py-3 px-4 font-bold text-red-400">O Negative (O-)</td>
                                <td class="py-3 px-4 text-slate-200">Everyone (All Types)</td>
                                <td class="py-3 px-4 text-slate-400">O- only</td>
                                <td class="py-3 px-4"><span class="px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-400 text-[11px] font-sans font-bold">Universal Red Cell Donor</span></td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 font-bold text-white">O Positive (O+)</td>
                                <td class="py-3 px-4 text-slate-200">O+, A+, B+, AB+</td>
                                <td class="py-3 px-4 text-slate-400">O+, O-</td>
                                <td class="py-3 px-4 text-slate-400 font-sans text-xs">Most Common Blood Type</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 font-bold text-white">A Negative (A-)</td>
                                <td class="py-3 px-4 text-slate-200">A-, A+, AB-, AB+</td>
                                <td class="py-3 px-4 text-slate-400">A-, O-</td>
                                <td class="py-3 px-4 text-slate-400 font-sans text-xs">Crucial for Rh- patients</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 font-bold text-white">A Positive (A+)</td>
                                <td class="py-3 px-4 text-slate-200">A+, AB+</td>
                                <td class="py-3 px-4 text-slate-400">A+, A-, O+, O-</td>
                                <td class="py-3 px-4 text-slate-400 font-sans text-xs">High Clinical Demand</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 font-bold text-white">B Negative (B-)</td>
                                <td class="py-3 px-4 text-slate-200">B-, B+, AB-, AB+</td>
                                <td class="py-3 px-4 text-slate-400">B-, O-</td>
                                <td class="py-3 px-4 text-slate-400 font-sans text-xs">Rare Blood Component</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 font-bold text-white">B Positive (B+)</td>
                                <td class="py-3 px-4 text-slate-200">B+, AB+</td>
                                <td class="py-3 px-4 text-slate-400">B+, B-, O+, O-</td>
                                <td class="py-3 px-4 text-slate-400 font-sans text-xs">Frequent Transfusions</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 font-bold text-white">AB Negative (AB-)</td>
                                <td class="py-3 px-4 text-slate-200">AB-, AB+</td>
                                <td class="py-3 px-4 text-slate-400">All Negative Types</td>
                                <td class="py-3 px-4 text-slate-400 font-sans text-xs">Rarest Blood Type</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 font-bold text-amber-400">AB Positive (AB+)</td>
                                <td class="py-3 px-4 text-slate-200">AB+ only</td>
                                <td class="py-3 px-4 text-slate-400">Everyone (All Types)</td>
                                <td class="py-3 px-4"><span class="px-2 py-0.5 rounded bg-blue-500/10 text-blue-400 text-[11px] font-sans font-bold">Universal Recipient</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Community Donation Camps -->
        <section id="camps" class="py-20 bg-slate-900/40 border-b border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="text-xs font-bold text-red-500 uppercase tracking-wider">Community Drives</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-1">Upcoming Blood Donation Camps</h2>
                    <p class="mt-2 text-slate-400 text-sm">Join organized community drives near you to donate safely under medical supervision.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-xs font-bold">Open for Registration</span>
                                <span class="text-xs text-slate-500">Dhaka Central</span>
                            </div>
                            <h3 class="text-lg font-bold text-white">City University Voluntary Blood Drive</h3>
                            <p class="text-xs text-slate-400 mt-1">Organized in collaboration with Central Blood Bank. Certified clinical staff and refresh station on site.</p>
                            
                            <div class="mt-4 space-y-1.5 text-xs text-slate-300">
                                <div><strong class="text-slate-400">Date & Time:</strong> Saturday, Nov 28 &bull; 9:00 AM - 4:00 PM</div>
                                <div><strong class="text-slate-400">Venue:</strong> University Auditorium, Panthapath</div>
                                <div><strong class="text-slate-400">Target:</strong> 150 Units</div>
                            </div>
                        </div>
                        <a href="{{ route('register') }}" class="mt-6 block text-center py-2.5 bg-red-600 hover:bg-red-500 text-white font-semibold text-xs rounded-xl transition">
                            Register for This Camp
                        </a>
                    </div>

                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2.5 py-1 rounded-full bg-blue-500/10 text-blue-400 text-xs font-bold">Next Weekend</span>
                                <span class="text-xs text-slate-500">Uttara Sector 7</span>
                            </div>
                            <h3 class="text-lg font-bold text-white">Community Health & Blood Donation Camp</h3>
                            <p class="text-xs text-slate-400 mt-1">Free vitals checkup, donor blood grouping, and emergency unit replenishment.</p>
                            
                            <div class="mt-4 space-y-1.5 text-xs text-slate-300">
                                <div><strong class="text-slate-400">Date & Time:</strong> Friday, Dec 04 &bull; 10:00 AM - 5:00 PM</div>
                                <div><strong class="text-slate-400">Venue:</strong> Sector 7 Community Center</div>
                                <div><strong class="text-slate-400">Target:</strong> 100 Units</div>
                            </div>
                        </div>
                        <a href="{{ route('register') }}" class="mt-6 block text-center py-2.5 bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 font-semibold text-xs rounded-xl transition">
                            Register for This Camp
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Donor FAQ Section -->
        <section id="faq" class="py-20 border-b border-slate-800">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <span class="text-xs font-bold text-red-500 uppercase tracking-wider">Frequently Asked Questions</span>
                    <h2 class="text-3xl font-extrabold text-white mt-1">Donor Health & Eligibility</h2>
                </div>

                <div class="space-y-4 text-xs sm:text-sm">
                    <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                        <h3 class="font-bold text-white mb-1.5">Who can donate blood?</h3>
                        <p class="text-slate-400 leading-relaxed">
                            Any healthy individual aged between 18 and 65 years, weighing at least 50 kg, with normal blood pressure and hemoglobin levels (&gt;= 12.5 g/dL).
                        </p>
                    </div>

                    <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                        <h3 class="font-bold text-white mb-1.5">How often can I donate whole blood?</h3>
                        <p class="text-slate-400 leading-relaxed">
                            A healthy adult can donate whole blood every 90 days (3 months). Platelet apheresis donations can be made more frequently as your red cells are returned.
                        </p>
                    </div>

                    <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                        <h3 class="font-bold text-white mb-1.5">Is blood donation safe?</h3>
                        <p class="text-slate-400 leading-relaxed">
                            100% safe. Every needle and collection pack is sterile, single-use, and disposed of immediately. You cannot contract any infection from donating blood.
                        </p>
                    </div>

                    <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                        <h3 class="font-bold text-white mb-1.5">Does LifePulse charge any fees?</h3>
                        <p class="text-slate-400 leading-relaxed">
                            No. LifePulse is a strictly voluntary, free healthcare coordination network. Commercial blood trading or financial compensation is prohibited.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Professional Healthcare Footer -->
    <footer class="border-t border-slate-800 bg-slate-950 py-12 text-xs text-slate-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-red-600 flex items-center justify-center text-white font-bold text-base shadow-md shadow-red-600/30">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        </div>
                        <span class="text-lg font-bold text-white">Life<span class="text-red-500">Pulse</span></span>
                    </div>
                    <p class="text-slate-400 leading-relaxed text-xs">
                        A real-time voluntary blood donation and emergency matching platform dedicated to saving lives across hospitals and community networks.
                    </p>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-white mb-3">Quick Navigation</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="#how-it-works" class="hover:text-white transition">How It Works</a></li>
                        <li><a href="#urgent-requests" class="hover:text-white transition">Urgent Requests</a></li>
                        <li><a href="#compatibility" class="hover:text-white transition">Blood Compatibility Guide</a></li>
                        <li><a href="#camps" class="hover:text-white transition">Donation Camps</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-white mb-3">For Donors & Patients</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="{{ route('register') }}" class="hover:text-white transition">Register as Donor</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Sign In to Account</a></li>
                        <li><a href="#faq" class="hover:text-white transition">Eligibility Criteria</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition">Hospital Staff Portal</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-white mb-3">Emergency & Support</h4>
                    <div class="space-y-2 text-xs">
                        <div class="p-3 rounded-xl bg-red-950/40 border border-red-900/60">
                            <span class="text-red-400 font-bold block text-sm">Hotline: 10678</span>
                            <span class="text-[11px] text-slate-400">24/7 Toll-Free Emergency Dispatch</span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-2">
                            Emergency Email: support@lifepulse.org
                        </p>
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-900 flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-500 gap-4">
                <p>&copy; 2026 LifePulse Blood Donation Coordination Network. All rights reserved.</p>
                <p>Medical Disclaimer: LifePulse coordinates voluntary donors. All blood units must be clinically screened and cross-matched by certified medical facilities.</p>
            </div>
        </div>
    </footer>
</body>
</html>
