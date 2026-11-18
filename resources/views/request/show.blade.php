@extends('layouts.app', ['title' => 'Blood Request Tracking - LifePulse'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- Page Header --}}
    <div class="bg-gradient-to-r from-red-950/70 via-slate-900 to-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white tracking-tight">Blood Request Tracking</h1>
                <p class="text-sm text-slate-400 mt-1">Manage your blood requests through <span class="text-red-400 font-semibold">Open</span> → <span class="text-amber-400 font-semibold">Partial</span> → <span class="text-emerald-400 font-semibold">Fulfilled</span> / <span class="text-slate-400 font-semibold">Expired</span> lifecycle.</p>
            </div>
            <a href="{{ route('emergency.broadcast') }}" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white text-xs font-bold shadow-md shadow-red-600/20 transition text-center">
                🚨 Emergency SOS
            </a>
        </div>
    </div>

    {{-- Status Summary Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-slate-900/80 border border-red-500/30 rounded-2xl p-5">
            <span class="text-xs text-slate-400 font-medium block">Open Requests</span>
            <div class="mt-1 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                <span class="text-xl font-extrabold text-red-400">{{ $stats['open'] }}</span>
            </div>
            <span class="text-[11px] text-slate-500 mt-1 block">Awaiting donor pledges</span>
        </div>

        <div class="bg-slate-900/80 border border-amber-500/30 rounded-2xl p-5">
            <span class="text-xs text-slate-400 font-medium block">Partially Fulfilled</span>
            <div class="mt-1 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                <span class="text-xl font-extrabold text-amber-400">{{ $stats['partial'] }}</span>
            </div>
            <span class="text-[11px] text-slate-500 mt-1 block">Some units pledged</span>
        </div>

        <div class="bg-slate-900/80 border border-emerald-500/30 rounded-2xl p-5">
            <span class="text-xs text-slate-400 font-medium block">Fulfilled</span>
            <div class="mt-1 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                <span class="text-xl font-extrabold text-emerald-400">{{ $stats['fulfilled'] }}</span>
            </div>
            <span class="text-[11px] text-slate-500 mt-1 block">All units pledged</span>
        </div>

        <div class="bg-slate-900/80 border border-slate-700 rounded-2xl p-5">
            <span class="text-xs text-slate-400 font-medium block">Expired</span>
            <div class="mt-1 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-slate-500"></span>
                <span class="text-xl font-extrabold text-slate-400">{{ $stats['expired'] }}</span>
            </div>
            <span class="text-[11px] text-slate-500 mt-1 block">Deadline passed</span>
        </div>
    </div>

    {{-- New Request Form --}}
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm">
        <h2 class="text-base font-bold text-white mb-4">Create New Blood Request</h2>

        <form method="POST" action="{{ route('blood.requests.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="patient_name" class="block text-xs font-semibold text-slate-300 mb-1">Patient Name *</label>
                    <input type="text" name="patient_name" id="patient_name" value="{{ old('patient_name') }}" required maxlength="150"
                        class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-red-500 focus:outline-none transition">
                </div>

                <div>
                    <label for="blood_group" class="block text-xs font-semibold text-slate-300 mb-1">Blood Group *</label>
                    <select name="blood_group" id="blood_group" required
                        class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:border-red-500 focus:outline-none transition">
                        <option value="">Select...</option>
                        @foreach ($bloodGroups as $bg)
                            <option value="{{ $bg }}" {{ old('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="component_type" class="block text-xs font-semibold text-slate-300 mb-1">Component Type *</label>
                    <select name="component_type" id="component_type" required
                        class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:border-red-500 focus:outline-none transition">
                        <option value="whole_blood" {{ old('component_type') === 'whole_blood' ? 'selected' : '' }}>Whole Blood</option>
                        <option value="red_cells" {{ old('component_type') === 'red_cells' ? 'selected' : '' }}>Red Cells (PRBC)</option>
                        <option value="platelets" {{ old('component_type') === 'platelets' ? 'selected' : '' }}>Platelets</option>
                        <option value="plasma" {{ old('component_type') === 'plasma' ? 'selected' : '' }}>Plasma</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="units_needed" class="block text-xs font-semibold text-slate-300 mb-1">Units Needed *</label>
                    <input type="number" name="units_needed" id="units_needed" value="{{ old('units_needed', 1) }}" min="1" max="20" required
                        class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:border-red-500 focus:outline-none transition">
                </div>

                <div>
                    <label for="hospital_name" class="block text-xs font-semibold text-slate-300 mb-1">Hospital *</label>
                    <input type="text" name="hospital_name" id="hospital_name" value="{{ old('hospital_name') }}" required maxlength="200"
                        class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-red-500 focus:outline-none transition">
                </div>

                <div>
                    <label for="city" class="block text-xs font-semibold text-slate-300 mb-1">City</label>
                    <input type="text" name="city" id="city" value="{{ old('city') }}" maxlength="100"
                        class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-red-500 focus:outline-none transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="urgency" class="block text-xs font-semibold text-slate-300 mb-1">Urgency Level *</label>
                    <select name="urgency" id="urgency" required
                        class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:border-red-500 focus:outline-none transition">
                        <option value="normal" {{ old('urgency') === 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="urgent" {{ old('urgency') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="critical" {{ old('urgency') === 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                </div>

                <div>
                    <label for="deadline_at" class="block text-xs font-semibold text-slate-300 mb-1">Deadline *</label>
                    <input type="datetime-local" name="deadline_at" id="deadline_at" value="{{ old('deadline_at') }}" required
                        class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:border-red-500 focus:outline-none transition">
                </div>

                <div>
                    <label for="contact_phone" class="block text-xs font-semibold text-slate-300 mb-1">Contact Phone</label>
                    <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone') }}" maxlength="25"
                        class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-red-500 focus:outline-none transition">
                </div>
            </div>

            <div>
                <label for="notes" class="block text-xs font-semibold text-slate-300 mb-1">Additional Notes</label>
                <textarea name="notes" id="notes" rows="2" maxlength="1000"
                    class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-red-500 focus:outline-none transition">{{ old('notes') }}</textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-500 text-white font-bold text-xs rounded-xl shadow-md shadow-red-600/20 transition">
                    Submit Blood Request
                </button>
            </div>
        </form>
    </div>

    {{-- Request List --}}
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-base font-bold text-white">Your Blood Requests</h2>
                <p class="text-xs text-slate-400">Track each request's status as it progresses through the lifecycle.</p>
            </div>
            <span class="px-2.5 py-1 rounded-full bg-red-500/10 text-red-400 text-xs font-bold border border-red-500/20">
                {{ $requests->count() }} Total
            </span>
        </div>

        @if ($requests->isEmpty())
            <div class="text-center py-12 text-slate-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                <p class="font-semibold text-sm">No blood requests yet</p>
                <p class="text-xs mt-1">Create your first request using the form above.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($requests as $req)
                    @php
                        $badgeColor = $req->statusBadgeColor();
                        $bgBorder = match($req->status) {
                            'open' => 'border-red-500/30',
                            'partial' => 'border-amber-500/30',
                            'fulfilled' => 'border-emerald-500/30',
                            default => 'border-slate-700',
                        };
                    @endphp
                    <div class="p-4 rounded-xl bg-slate-950/80 border {{ $bgBorder }} space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3.5">
                                <div class="w-12 h-12 rounded-xl bg-{{ $badgeColor }}-600/20 text-{{ $badgeColor }}-500 font-extrabold text-xl flex items-center justify-center shrink-0 border border-{{ $badgeColor }}-500/30">
                                    {{ $req->blood_group }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="text-sm font-bold text-white">{{ $req->patient_name }}</h3>
                                        <span class="px-2 py-0.5 rounded bg-{{ $badgeColor }}-500/20 text-{{ $badgeColor }}-400 text-[10px] font-bold uppercase">
                                            {{ ucfirst($req->status) }}
                                        </span>
                                        @if ($req->is_emergency)
                                            <span class="px-2 py-0.5 rounded bg-red-600 text-white text-[10px] font-bold uppercase animate-pulse">
                                                🚨 EMERGENCY
                                            </span>
                                        @endif
                                        <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-300 text-[10px] font-bold uppercase">
                                            {{ $req->urgencyLabel() }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        {{ $req->hospital_name }}
                                        @if ($req->city) &bull; {{ $req->city }} @endif
                                        &bull; {{ ucfirst(str_replace('_', ' ', $req->component_type)) }}
                                        &bull; Deadline: {{ $req->deadline_at->format('M d, Y H:i') }}
                                        @if ($req->status !== 'fulfilled' && $req->status !== 'expired')
                                            ({{ number_format($req->hoursRemaining(), 1) }}h left)
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="text-slate-400">Fulfilment Progress</span>
                                <span class="text-{{ $badgeColor }}-400 font-bold">{{ $req->units_fulfilled }}/{{ $req->units_needed }} units ({{ $req->fulfilmentPercent() }}%)</span>
                            </div>
                            <div class="w-full bg-slate-800 rounded-full h-2">
                                <div class="bg-{{ $badgeColor }}-500 h-2 rounded-full transition-all duration-500" style="width: {{ $req->fulfilmentPercent() }}%"></div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        @if (in_array($req->status, ['open', 'partial']))
                            <div class="flex items-center gap-2 flex-wrap">
                                <form method="POST" action="{{ route('blood.requests.fulfill', $req->id) }}" class="flex items-center gap-2">
                                    @csrf
                                    <input type="number" name="units" value="1" min="1" max="{{ $req->units_needed - $req->units_fulfilled }}"
                                        class="w-16 bg-slate-800 border border-slate-700 rounded-lg px-2 py-1.5 text-xs text-white text-center focus:border-red-500 focus:outline-none">
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-lg transition">
                                        Record Pledge
                                    </button>
                                </form>

                                @if (! $req->is_emergency)
                                    <form method="POST" action="{{ route('emergency.activate', $req->id) }}">
                                        @csrf
                                        <input type="hidden" name="emergency_level" value="sos_critical">
                                        <button type="submit" class="px-3 py-1.5 bg-red-600 hover:bg-red-500 text-white text-xs font-bold rounded-lg transition">
                                            🚨 Escalate to SOS
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('blood.requests.expire', $req->id) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-lg border border-slate-700 transition"
                                        onclick="return confirm('Are you sure you want to expire this request?')">
                                        Mark Expired
                                    </button>
                                </form>
                            </div>
                        @endif

                        @if ($req->notes)
                            <p class="text-xs text-slate-500 italic">{{ $req->notes }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
