@extends('layouts.app', ['title' => 'Donor Health Evaluation - LifePulse'])

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Donor Health Evaluation</h1>
            <p class="text-xs text-slate-400 mt-1">Automated clinical criteria and interval cooldown calculator</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-slate-400 hover:text-white px-3 py-1.5 rounded-lg border border-slate-800 bg-slate-900 transition self-start sm:self-auto">
            &larr; Back to Dashboard
        </a>
    </div>

    <!-- Eligibility Status Card -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-800">
            <div>
                <span class="text-xs text-slate-400 uppercase tracking-wider font-semibold block">Current Medical Status</span>
                <div class="flex items-center gap-2 mt-1">
                    @if ($profile->is_eligible)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-bold">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Medically Eligible to Donate
                        </span>
                    @elseif ($profile->eligibility_status === 'temporarily_deferred')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-sm font-bold">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                            Temporarily Deferred
                        </span>
                    @elseif ($profile->eligibility_status === 'permanently_deferred')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-500/10 border border-red-500/30 text-red-400 text-sm font-bold">
                            <span class="w-2 h-2 rounded-full bg-red-400"></span>
                            Permanently Ineligible
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-400 text-sm font-bold">
                            <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                            Evaluation Pending
                        </span>
                    @endif
                </div>
            </div>

            <div class="text-left sm:text-right text-xs text-slate-400">
                <span>Last Evaluated:</span>
                <span class="font-medium text-white block sm:inline ml-1">
                    {{ $profile->last_evaluated_at ? $profile->last_evaluated_at->diffForHumans() : 'Never evaluated' }}
                </span>
            </div>
        </div>

        @if (! $profile->is_eligible && $profile->next_eligible_date && $profile->eligibility_status === 'temporarily_deferred')
            <!-- Countdown Section -->
            <div class="mt-4 p-4 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold text-lg">
                        ⏳
                    </div>
                    <div>
                        <span class="text-xs text-amber-300 font-bold block">Cooldown Active</span>
                        <span class="text-xs text-amber-200/80">Next projected eligible date: <strong>{{ $profile->next_eligible_date->format('F d, Y') }}</strong></span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-extrabold text-amber-400 font-mono">{{ $profile->daysRemaining() }}</span>
                    <span class="text-[10px] text-amber-300 block uppercase font-bold">Days Left</span>
                </div>
            </div>
        @endif

        @if (!empty($profile->ineligibility_reasons))
            <!-- Itemized Reasons -->
            <div class="mt-4 p-4 rounded-xl bg-slate-950/80 border border-red-500/30 space-y-2">
                <span class="text-xs font-bold text-red-400 flex items-center gap-1.5">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    Clinical Findings:
                </span>
                <ul class="text-xs text-slate-300 space-y-1 list-disc list-inside">
                    @foreach ($profile->ineligibility_reasons as $reason)
                        <li>{{ $reason }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Health Declaration Form -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-sm">
        <div class="mb-6">
            <h2 class="text-lg font-bold text-white tracking-tight">Clinical Health Questionnaire</h2>
            <p class="text-xs text-slate-400 mt-0.5">Please provide accurate information to evaluate your donation safety.</p>
        </div>

        <form method="POST" action="{{ route('donor.eligibility.evaluate') }}" class="space-y-6">
            @csrf

            <!-- Section 1: Demographics & Physical Measurements -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-red-400 uppercase tracking-wider">1. Basic Physical Metrics</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label for="gender" class="block text-xs font-semibold text-slate-300 mb-1">Gender <span class="text-red-500">*</span></label>
                        <select name="gender" id="gender" required class="w-full px-3 py-2.5 rounded-lg bg-slate-950 border border-slate-700 text-white text-xs focus:ring-2 focus:ring-red-500 focus:outline-none">
                            <option value="male" {{ old('gender', $profile->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $profile->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $profile->gender) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div>
                        <label for="date_of_birth" class="block text-xs font-semibold text-slate-300 mb-1">Date of Birth <span class="text-red-500">*</span></label>
                        <input type="date" name="date_of_birth" id="date_of_birth" required value="{{ old('date_of_birth', optional($profile->date_of_birth)->format('Y-m-d')) }}" class="w-full px-3 py-2 rounded-lg bg-slate-950 border border-slate-700 text-white text-xs focus:ring-2 focus:ring-red-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="weight_kg" class="block text-xs font-semibold text-slate-300 mb-1">Weight (in kg) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.5" name="weight_kg" id="weight_kg" required value="{{ old('weight_kg', $profile->weight_kg) }}" placeholder="e.g. 62" class="w-full px-3 py-2 rounded-lg bg-slate-950 border border-slate-700 text-white text-xs focus:ring-2 focus:ring-red-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="blood_group" class="block text-xs font-semibold text-slate-300 mb-1">Blood Group</label>
                        <select name="blood_group" id="blood_group" class="w-full px-3 py-2.5 rounded-lg bg-slate-950 border border-slate-700 text-white text-xs focus:ring-2 focus:ring-red-500 focus:outline-none">
                            <option value="">Select Blood Group</option>
                            @foreach ($bloodGroups as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group', $profile->blood_group ?? $user->blood_group) === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 2: Last Donation History -->
            <div class="space-y-4 pt-4 border-t border-slate-800">
                <h3 class="text-xs font-bold text-red-400 uppercase tracking-wider">2. Donation History & Cooldown</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="last_donation_date" class="block text-xs font-semibold text-slate-300 mb-1">Last Donation Date (Leave empty if first time)</label>
                        <input type="date" name="last_donation_date" id="last_donation_date" value="{{ old('last_donation_date', optional($profile->last_donation_date)->format('Y-m-d')) }}" class="w-full px-3 py-2 rounded-lg bg-slate-950 border border-slate-700 text-white text-xs focus:ring-2 focus:ring-red-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="last_donation_type" class="block text-xs font-semibold text-slate-300 mb-1">Donation Component Type</label>
                        <select name="last_donation_type" id="last_donation_type" class="w-full px-3 py-2.5 rounded-lg bg-slate-950 border border-slate-700 text-white text-xs focus:ring-2 focus:ring-red-500 focus:outline-none">
                            <option value="whole_blood" {{ old('last_donation_type', $profile->last_donation_type) === 'whole_blood' ? 'selected' : '' }}>Whole Blood (90-day cooldown)</option>
                            <option value="platelets" {{ old('last_donation_type', $profile->last_donation_type) === 'platelets' ? 'selected' : '' }}>Platelet Apheresis (14-day cooldown)</option>
                            <option value="plasma" {{ old('last_donation_type', $profile->last_donation_type) === 'plasma' ? 'selected' : '' }}>Plasma Donation (28-day cooldown)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 3: Health Screening Questions -->
            <div class="space-y-3 pt-4 border-t border-slate-800">
                <h3 class="text-xs font-bold text-red-400 uppercase tracking-wider">3. Health Screening Questions</h3>
                
                <div class="space-y-3 text-xs">
                    <label class="flex items-start gap-3 p-3 rounded-xl bg-slate-950/60 border border-slate-800 hover:border-slate-700 cursor-pointer">
                        <input type="checkbox" name="has_recent_tattoo" value="1" {{ old('has_recent_tattoo', $profile->has_recent_tattoo) ? 'checked' : '' }} class="mt-0.5 w-4 h-4 rounded bg-slate-900 border-slate-700 text-red-600 focus:ring-red-500">
                        <div>
                            <span class="font-semibold text-white block">Tattoos or Body Piercing</span>
                            <span class="text-slate-400">Have you received a tattoo, ear/body piercing, or acupuncture within the last 6 months?</span>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-3 rounded-xl bg-slate-950/60 border border-slate-800 hover:border-slate-700 cursor-pointer">
                        <input type="checkbox" name="has_recent_surgery" value="1" {{ old('has_recent_surgery', $profile->has_recent_surgery) ? 'checked' : '' }} class="mt-0.5 w-4 h-4 rounded bg-slate-900 border-slate-700 text-red-600 focus:ring-red-500">
                        <div>
                            <span class="font-semibold text-white block">Recent Surgeries or Procedures</span>
                            <span class="text-slate-400">Have you undergone any major surgical procedure or dental extraction within the last 6 months?</span>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-3 rounded-xl bg-slate-950/60 border border-slate-800 hover:border-slate-700 cursor-pointer">
                        <input type="checkbox" name="has_fever_infection" value="1" {{ old('has_fever_infection', $profile->has_fever_infection) ? 'checked' : '' }} class="mt-0.5 w-4 h-4 rounded bg-slate-900 border-slate-700 text-red-600 focus:ring-red-500">
                        <div>
                            <span class="font-semibold text-white block">Active Illness or Medication</span>
                            <span class="text-slate-400">Do you currently have a fever, persistent cough, cold, flu, or are you taking antibiotics?</span>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-3 rounded-xl bg-slate-950/60 border border-slate-800 hover:border-slate-700 cursor-pointer">
                        <input type="checkbox" name="has_chronic_illness" value="1" {{ old('has_chronic_illness', $profile->has_chronic_illness) ? 'checked' : '' }} class="mt-0.5 w-4 h-4 rounded bg-slate-900 border-slate-700 text-red-600 focus:ring-red-500">
                        <div>
                            <span class="font-semibold text-white block">Chronic Health Conditions</span>
                            <span class="text-slate-400">Have you ever been diagnosed with Hepatitis B/C, HIV, cardiac disease, epilepsy, or malignant conditions?</span>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-3 rounded-xl bg-slate-950/60 border border-slate-800 hover:border-slate-700 cursor-pointer">
                        <input type="checkbox" name="is_pregnant_or_breastfeeding" value="1" {{ old('is_pregnant_or_breastfeeding', $profile->is_pregnant_or_breastfeeding) ? 'checked' : '' }} class="mt-0.5 w-4 h-4 rounded bg-slate-900 border-slate-700 text-red-600 focus:ring-red-500">
                        <div>
                            <span class="font-semibold text-white block">Pregnancy / Breastfeeding (Female Donors)</span>
                            <span class="text-slate-400">Are you currently pregnant, delivered a baby within the last 6 months, or actively breastfeeding?</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Optional Hemoglobin -->
            <div class="pt-2">
                <label for="hemoglobin_g_dl" class="block text-xs font-semibold text-slate-300 mb-1">Recent Hemoglobin Level (Optional, in g/dL)</label>
                <input type="number" step="0.1" name="hemoglobin_g_dl" id="hemoglobin_g_dl" value="{{ old('hemoglobin_g_dl', $profile->hemoglobin_g_dl) }}" placeholder="e.g. 13.5 (Minimum required is 12.5)" class="w-full sm:w-1/2 px-3 py-2 rounded-lg bg-slate-950 border border-slate-700 text-white text-xs focus:ring-2 focus:ring-red-500 focus:outline-none">
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-800">
                <button type="submit" class="w-full py-3 px-6 bg-red-600 hover:bg-red-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-red-600/30 transition">
                    Run Clinical Evaluation Engine
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
