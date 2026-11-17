@extends('layouts.app', ['title' => 'Blood Stock & Expiry Alerts - LifePulse'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <!-- Header with Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span>
                <h1 class="text-2xl font-bold text-white tracking-tight">Blood Stock & Expiry Alert System</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Automated evaluation of group inventory thresholds and shelf-life expiry windows (Platelets &le; 48h, Whole Blood &le; 7 days)</p>
        </div>

        <div class="flex items-center gap-2">
            <form method="POST" action="{{ route('inventory.alerts.scan') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white font-bold text-xs rounded-xl shadow-md shadow-red-600/30 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 fill-none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Run Automated Scan</span>
                </button>
            </form>

            <form method="POST" action="{{ route('inventory.alerts.seed') }}">
                @csrf
                <button type="submit" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold text-xs rounded-xl transition">
                    + Load Test Units
                </button>
            </form>

            <a href="{{ route('dashboard') }}" class="px-3.5 py-2 bg-slate-900 border border-slate-800 text-slate-400 hover:text-white font-semibold text-xs rounded-xl transition">
                &larr; Dashboard
            </a>
        </div>
    </div>

    <!-- Group Threshold Status Cards (8 Blood Groups) -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-sm font-bold text-white">Group Stock Level Matrix</h2>
                <p class="text-[11px] text-slate-400">Minimum safe threshold: <strong>{{ $threshold }} units</strong> per blood group</p>
            </div>
            <span class="text-xs text-slate-400">Total Available Units: <strong class="text-white text-sm font-mono">{{ $totalUnits }}</strong></span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
            @foreach ($groupStats as $group => $stat)
                <div class="p-3.5 rounded-xl border text-center transition
                    @if ($stat['is_out']) bg-red-950/40 border-red-500/50 text-red-300
                    @elseif ($stat['is_low']) bg-amber-950/30 border-amber-500/40 text-amber-300
                    @else bg-slate-950/80 border-slate-800 text-slate-300
                    @endif">
                    <span class="text-base font-extrabold block text-white">{{ $group }}</span>
                    <span class="text-xl font-mono font-bold mt-1 block
                        @if ($stat['is_out']) text-red-500
                        @elseif ($stat['is_low']) text-amber-400
                        @else text-emerald-400
                        @endif">
                        {{ $stat['count'] }}
                    </span>
                    <span class="text-[10px] uppercase font-bold tracking-wider mt-1 block
                        @if ($stat['is_out']) text-red-400
                        @elseif ($stat['is_low']) text-amber-300
                        @else text-emerald-400
                        @endif">
                        @if ($stat['is_out']) Stockout
                        @elseif ($stat['is_low']) Low Stock
                        @else Safe
                        @endif
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Active System Alerts Feed -->
        <div class="lg:col-span-1 bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <div>
                    <h2 class="text-sm font-bold text-white">Active System Alerts</h2>
                    <p class="text-[11px] text-slate-400">Unresolved threshold and expiry warnings</p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold font-mono
                    {{ $activeAlerts->count() > 0 ? 'bg-red-500/20 text-red-400' : 'bg-emerald-500/10 text-emerald-400' }}">
                    {{ $activeAlerts->count() }} Active
                </span>
            </div>

            <div class="space-y-3 max-h-[500px] overflow-y-auto pr-1">
                @forelse ($activeAlerts as $alert)
                    <div class="p-3.5 rounded-xl border text-xs relative space-y-2
                        @if ($alert->severity === 'critical') bg-red-950/40 border-red-500/40 text-red-200
                        @elseif ($alert->severity === 'warning') bg-amber-950/30 border-amber-500/40 text-amber-200
                        @else bg-slate-950/80 border-slate-800 text-slate-300
                        @endif">
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                @if ($alert->severity === 'critical') bg-red-600 text-white
                                @elseif ($alert->severity === 'warning') bg-amber-500 text-slate-950
                                @else bg-slate-700 text-white
                                @endif">
                                {{ $alert->alert_type }}
                            </span>
                            <span class="text-[10px] text-slate-400">{{ $alert->created_at->diffForHumans() }}</span>
                        </div>

                        <p class="text-xs leading-relaxed font-medium">{{ $alert->message }}</p>

                        <div class="pt-1 flex justify-end">
                            <form method="POST" action="{{ route('inventory.alerts.resolve', $alert->id) }}">
                                @csrf
                                <button type="submit" class="text-[11px] text-slate-400 hover:text-white font-semibold underline">
                                    Mark Resolved &check;
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-slate-500 text-xs">
                        <span class="text-2xl block mb-2">&check;</span>
                        All group stocks are above threshold and no units nearing expiry.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Near-Expiry Units Table -->
        <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <div>
                    <h2 class="text-sm font-bold text-white">Units Nearing Shelf-Life Expiry</h2>
                    <p class="text-[11px] text-slate-400">Available blood units expiring within the next 7 days</p>
                </div>
                <span class="text-xs text-slate-400 font-mono">{{ $nearExpiryUnits->count() }} unit(s) flagged</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-800 text-slate-400 text-[11px] uppercase tracking-wider">
                            <th class="py-2.5 px-3">Serial No.</th>
                            <th class="py-2.5 px-3">Type</th>
                            <th class="py-2.5 px-3">Component</th>
                            <th class="py-2.5 px-3">Expiry Date</th>
                            <th class="py-2.5 px-3">Time Left</th>
                            <th class="py-2.5 px-3">Location</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 font-mono">
                        @forelse ($nearExpiryUnits as $unit)
                            @php
                                $hours = $unit->hoursUntilExpiry();
                            @endphp
                            <tr class="hover:bg-slate-950/40">
                                <td class="py-2.5 px-3 font-bold text-white">{{ $unit->bag_serial_number }}</td>
                                <td class="py-2.5 px-3 font-bold text-red-400">{{ $unit->blood_group }}</td>
                                <td class="py-2.5 px-3 text-slate-300 font-sans">{{ ucfirst(str_replace('_', ' ', $unit->component_type)) }}</td>
                                <td class="py-2.5 px-3 text-slate-400">{{ $unit->expiry_date->format('M d, H:i') }}</td>
                                <td class="py-2.5 px-3">
                                    @if ($hours <= 24)
                                        <span class="px-2 py-0.5 rounded bg-red-600/20 text-red-400 font-bold text-[10px] border border-red-500/30">
                                            &le; {{ round($hours) }}h (Critical)
                                        </span>
                                    @elseif ($hours <= 48)
                                        <span class="px-2 py-0.5 rounded bg-amber-500/20 text-amber-400 font-bold text-[10px] border border-amber-500/30">
                                            {{ round($hours) }}h (Urgent)
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-blue-500/20 text-blue-300 text-[10px]">
                                            {{ round($hours / 24, 1) }} days
                                        </span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-3 text-slate-400 font-sans text-[11px]">{{ $unit->storage_location }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-slate-500 font-sans">
                                    No available units currently nearing expiry.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
