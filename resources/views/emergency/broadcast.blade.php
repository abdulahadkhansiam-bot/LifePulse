@extends('layouts.app', ['title' => 'Emergency Blood Broadcast - LifePulse'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- Emergency Header Banner --}}
    <div class="bg-gradient-to-r from-red-900/80 via-red-950 to-slate-900 border border-red-500/40 rounded-2xl p-6 sm:p-8 shadow-xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <span class="text-3xl">🚨</span>
                    <h1 class="text-2xl font-bold text-white tracking-tight">Emergency Blood Broadcast</h1>
                </div>
                <p class="text-sm text-red-200/70 mt-1">Override batch throttles, expand radial sweeps, and pin SOS alerts across all active dashboards.</p>
            </div>
            <a href="{{ route('blood.requests') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold border border-slate-700 transition text-center">
                ← Back to Requests
            </a>
        </div>
    </div>

    {{-- Emergency Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-slate-900/80 border border-red-500/30 rounded-2xl p-5">
            <span class="text-xs text-slate-400 font-medium block">Active Emergencies</span>
            <div class="mt-1 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-red-400 animate-pulse"></span>
                <span class="text-xl font-extrabold text-red-400">{{ $stats['active_emergencies'] }}</span>
            </div>
        </div>

        <div class="bg-slate-900/80 border border-red-500/30 rounded-2xl p-5">
            <span class="text-xs text-slate-400 font-medium block">Pinned Broadcasts</span>
            <div class="mt-1 flex items-center gap-2">
                <span class="text-xl font-extrabold text-amber-400">{{ $stats['total_pinned'] }}</span>
            </div>
        </div>

        <div class="bg-slate-900/80 border border-red-500/30 rounded-2xl p-5">
            <span class="text-xs text-slate-400 font-medium block">SOS Critical</span>
            <div class="mt-1 flex items-center gap-2">
                <span class="text-xl font-extrabold text-red-500">{{ $stats['sos_critical'] }}</span>
            </div>
        </div>

        <div class="bg-slate-900/80 border border-slate-700 rounded-2xl p-5">
            <span class="text-xs text-slate-400 font-medium block">Max Radius Active</span>
            <div class="mt-1 flex items-center gap-2">
                <span class="text-xl font-extrabold text-white">{{ $stats['max_radius'] }} km</span>
            </div>
        </div>
    </div>

    {{-- Create SOS Request Form --}}
    <div class="bg-red-950/30 border border-red-500/30 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center gap-2 mb-4">
            <span class="text-lg">⚡</span>
            <h2 class="text-base font-bold text-white">Create SOS Emergency Request</h2>
        </div>
        <p class="text-xs text-red-200/60 mb-4">This creates a blood request with <strong>immediate emergency escalation</strong>: broadcast pinned across all dashboards, batch throttles overridden, radial sweep starts at 10 km.</p>

        <form method="POST" action="{{ route('emergency.create-sos') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="sos_patient_name" class="block text-xs font-semibold text-slate-300 mb-1">Patient Name *</label>
                    <input type="text" name="patient_name" id="sos_patient_name" value="{{ old('patient_name') }}" required maxlength="150"
                        class="w-full bg-slate-950 border border-red-500/30 rounded-xl px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-red-500 focus:outline-none transition">
                </div>

                <div>
                    <label for="sos_blood_group" class="block text-xs font-semibold text-slate-300 mb-1">Blood Group *</label>
                    <select name="blood_group" id="sos_blood_group" required
                        class="w-full bg-slate-950 border border-red-500/30 rounded-xl px-3 py-2 text-sm text-white focus:border-red-500 focus:outline-none transition">
                        <option value="">Select...</option>
                        @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                            <option value="{{ $bg }}">{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="sos_component" class="block text-xs font-semibold text-slate-300 mb-1">Component Type *</label>
                    <select name="component_type" id="sos_component" required
                        class="w-full bg-slate-950 border border-red-500/30 rounded-xl px-3 py-2 text-sm text-white focus:border-red-500 focus:outline-none transition">
                        <option value="whole_blood">Whole Blood</option>
                        <option value="red_cells">Red Cells (PRBC)</option>
                        <option value="platelets">Platelets</option>
                        <option value="plasma">Plasma</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="sos_units" class="block text-xs font-semibold text-slate-300 mb-1">Units Needed *</label>
                    <input type="number" name="units_needed" id="sos_units" value="1" min="1" max="20" required
                        class="w-full bg-slate-950 border border-red-500/30 rounded-xl px-3 py-2 text-sm text-white focus:border-red-500 focus:outline-none transition">
                </div>

                <div>
                    <label for="sos_hospital" class="block text-xs font-semibold text-slate-300 mb-1">Hospital *</label>
                    <input type="text" name="hospital_name" id="sos_hospital" value="{{ old('hospital_name') }}" required maxlength="200"
                        class="w-full bg-slate-950 border border-red-500/30 rounded-xl px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-red-500 focus:outline-none transition">
                </div>

                <div>
                    <label for="sos_city" class="block text-xs font-semibold text-slate-300 mb-1">City</label>
                    <input type="text" name="city" id="sos_city" maxlength="100"
                        class="w-full bg-slate-950 border border-red-500/30 rounded-xl px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-red-500 focus:outline-none transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="sos_deadline" class="block text-xs font-semibold text-slate-300 mb-1">Deadline *</label>
                    <input type="datetime-local" name="deadline_at" id="sos_deadline" required
                        class="w-full bg-slate-950 border border-red-500/30 rounded-xl px-3 py-2 text-sm text-white focus:border-red-500 focus:outline-none transition">
                </div>

                <div>
                    <label for="sos_phone" class="block text-xs font-semibold text-slate-300 mb-1">Emergency Contact</label>
                    <input type="text" name="contact_phone" id="sos_phone" maxlength="25" placeholder="+880-XXXX-XXXXXX"
                        class="w-full bg-slate-950 border border-red-500/30 rounded-xl px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-red-500 focus:outline-none transition">
                </div>

                <div>
                    <label for="sos_notes" class="block text-xs font-semibold text-slate-300 mb-1">Notes</label>
                    <input type="text" name="notes" id="sos_notes" maxlength="1000"
                        class="w-full bg-slate-950 border border-red-500/30 rounded-xl px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-red-500 focus:outline-none transition">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-red-600/30 transition">
                    🚨 Broadcast SOS Emergency
                </button>
            </div>
        </form>
    </div>

    {{-- Pinned Emergency Broadcasts (visible to ALL users) --}}
    <div class="bg-slate-900 border border-red-500/20 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-base font-bold text-white">📡 Pinned Emergency Alerts</h2>
                <p class="text-xs text-slate-400">These alerts are pinned across ALL active dashboards system-wide.</p>
            </div>
            <span class="px-2.5 py-1 rounded-full bg-red-500/10 text-red-400 text-xs font-bold border border-red-500/20 animate-pulse">
                LIVE
            </span>
        </div>

        @if ($pinnedBroadcasts->isEmpty())
            <div class="text-center py-10 text-slate-500">
                <p class="font-semibold text-sm">No active emergency broadcasts</p>
                <p class="text-xs mt-1">All is calm — no emergency blood requests are active.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($pinnedBroadcasts as $broadcast)
                    <div class="p-4 rounded-xl bg-red-950/30 border border-red-500/40 space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3.5">
                                <div class="w-14 h-14 rounded-xl bg-red-600/30 text-red-400 font-extrabold text-xl flex items-center justify-center shrink-0 border border-red-500/40 animate-pulse">
                                    {{ $broadcast->blood_group }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="text-sm font-bold text-white">{{ $broadcast->patient_name }}</h3>
                                        <span class="px-2 py-0.5 rounded bg-red-600 text-white text-[10px] font-bold uppercase">
                                            {{ $broadcast->emergency_level === 'sos_critical' ? '🚨 SOS CRITICAL' : '⚠️ URGENT ESCALATED' }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-red-200/60 mt-0.5">
                                        {{ $broadcast->hospital_name }}
                                        @if ($broadcast->city) &bull; {{ $broadcast->city }} @endif
                                        &bull; {{ $broadcast->units_fulfilled }}/{{ $broadcast->units_needed }} {{ ucfirst(str_replace('_', ' ', $broadcast->component_type)) }}
                                        &bull; {{ number_format($broadcast->hoursRemaining(), 1) }}h remaining
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Radial Sweep Indicator --}}
                        <div class="flex items-center gap-4 flex-wrap">
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-slate-400">Radial Sweep:</span>
                                @foreach ([10, 25, 50] as $tier)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $broadcast->current_radius_km >= $tier ? 'bg-red-600/30 text-red-400 border border-red-500/40' : 'bg-slate-800 text-slate-500 border border-slate-700' }}">
                                        {{ $tier }} km
                                    </span>
                                @endforeach
                            </div>
                            <span class="text-[11px] text-slate-500">
                                Escalation #{{ $broadcast->escalation_count }}
                                @if ($broadcast->last_escalated_at)
                                    &bull; Last: {{ $broadcast->last_escalated_at->diffForHumans() }}
                                @endif
                            </span>
                        </div>

                        {{-- Progress --}}
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="text-slate-400">Fulfilment</span>
                                <span class="text-red-400 font-bold">{{ $broadcast->fulfilmentPercent() }}%</span>
                            </div>
                            <div class="w-full bg-slate-800 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full transition-all duration-500" style="width: {{ $broadcast->fulfilmentPercent() }}%"></div>
                            </div>
                        </div>

                        {{-- Contact + Actions --}}
                        <div class="flex items-center gap-2 flex-wrap">
                            @if ($broadcast->contact_phone)
                                <span class="text-xs text-slate-400">📞 {{ $broadcast->contact_phone }}</span>
                            @endif

                            @if ($broadcast->user_id === $user->id)
                                @if ($broadcast->current_radius_km < 50)
                                    <form method="POST" action="{{ route('emergency.escalate', $broadcast->id) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold rounded-lg transition">
                                            ⚡ Expand Radius
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('emergency.deactivate', $broadcast->id) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-lg border border-slate-700 transition"
                                        onclick="return confirm('Deactivate this emergency broadcast?')">
                                        Deactivate Emergency
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- My Emergency Requests (for the requesting user) --}}
    @if ($myEmergencies->isNotEmpty())
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm">
            <h2 class="text-base font-bold text-white mb-4">Your Emergency Requests</h2>
            <div class="space-y-2">
                @foreach ($myEmergencies as $em)
                    <div class="p-3 rounded-lg bg-slate-950/60 border border-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-bold text-red-400">{{ $em->blood_group }}</span>
                            <span class="text-xs text-white">{{ $em->patient_name }}</span>
                            <span class="text-[10px] text-slate-400">{{ $em->hospital_name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-600/20 text-red-400 border border-red-500/30">
                                {{ $em->current_radius_km }} km
                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-{{ $em->statusBadgeColor() }}-500/20 text-{{ $em->statusBadgeColor() }}-400">
                                {{ ucfirst($em->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
