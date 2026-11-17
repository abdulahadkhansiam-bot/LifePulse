<?php

namespace App\Http\Controllers\Member1_Rifaat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member1_Rifaat\EligibilityCheckRequest;
use App\Models\Member1_Rifaat\DonorProfile;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EligibilityController extends Controller
{
    /**
     * Display the donor health evaluation and current eligibility status.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $profile = $user->donorProfile ?? new DonorProfile([
            'blood_group' => $user->blood_group,
            'is_eligible' => false,
            'eligibility_status' => DonorProfile::STATUS_PENDING,
        ]);

        return view('member1_rifaat.eligibility', [
            'user' => $user,
            'profile' => $profile,
            'bloodGroups' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
        ]);
    }

    /**
     * Process the medical declaration and calculate next eligible date.
     */
    public function evaluate(EligibilityCheckRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        $dob = Carbon::parse($validated['date_of_birth']);
        $age = $dob->age;
        $weight = (float) $validated['weight_kg'];
        $gender = $validated['gender'];
        $bloodGroup = $validated['blood_group'] ?? $user->blood_group;

        $isEligible = true;
        $eligibilityStatus = DonorProfile::STATUS_ELIGIBLE;
        $ineligibilityReasons = [];
        $nextEligibleDate = Carbon::today();

        // 1. Age Rule (18 to 65 years)
        if ($age < 18) {
            $isEligible = false;
            $eligibilityStatus = DonorProfile::STATUS_TEMPORARILY_DEFERRED;
            $eighteenthBirthday = $dob->copy()->addYears(18);
            if ($eighteenthBirthday->gt($nextEligibleDate)) {
                $nextEligibleDate = $eighteenthBirthday;
            }
            $ineligibilityReasons[] = "Underage: Donors must be at least 18 years old (Current age: {$age}). Eligible on {$eighteenthBirthday->format('M d, Y')}.";
        } elseif ($age > 65) {
            $isEligible = false;
            $eligibilityStatus = DonorProfile::STATUS_PERMANENTLY_DEFERRED;
            $ineligibilityReasons[] = "Age limit: Voluntary donors over 65 years require in-person senior medical clearance.";
        }

        // 2. Weight Rule (>= 45kg female, >= 50kg male/other)
        $minWeight = ($gender === 'female') ? 45.0 : 50.0;
        if ($weight < $minWeight) {
            $isEligible = false;
            if ($eligibilityStatus !== DonorProfile::STATUS_PERMANENTLY_DEFERRED) {
                $eligibilityStatus = DonorProfile::STATUS_TEMPORARILY_DEFERRED;
            }
            $ineligibilityReasons[] = "Body weight requirement: Your recorded weight ({$weight} kg) is below the minimum clinical standard of {$minWeight} kg.";
        }

        // 3. Donation Interval Gap Cooldown Rule (90 days for whole blood, 14 days for platelets)
        if (! empty($validated['last_donation_date'])) {
            $lastDonationDate = Carbon::parse($validated['last_donation_date']);
            $donationType = $validated['last_donation_type'] ?? 'whole_blood';
            $requiredCooldownDays = ($donationType === 'platelets') ? 14 : 90;

            $cooldownEndDate = $lastDonationDate->copy()->addDays($requiredCooldownDays);

            if (Carbon::today()->lt($cooldownEndDate)) {
                $isEligible = false;
                if ($eligibilityStatus !== DonorProfile::STATUS_PERMANENTLY_DEFERRED) {
                    $eligibilityStatus = DonorProfile::STATUS_TEMPORARILY_DEFERRED;
                }
                $daysRemaining = (int) Carbon::today()->diffInDays($cooldownEndDate);
                if ($cooldownEndDate->gt($nextEligibleDate)) {
                    $nextEligibleDate = $cooldownEndDate;
                }
                $ineligibilityReasons[] = "Interval cooldown active: A minimum of {$requiredCooldownDays} days is required between {$donationType} donations. {$daysRemaining} day(s) remaining until {$cooldownEndDate->format('M d, Y')}.";
            }
        }

        // 4. Health Declaration Affirmative Response Checks
        if ($validated['has_chronic_illness']) {
            $isEligible = false;
            $eligibilityStatus = DonorProfile::STATUS_PERMANENTLY_DEFERRED;
            $ineligibilityReasons[] = "Chronic health condition reported: Permanent deferral enforced to protect donor and recipient safety.";
        }

        if ($validated['has_recent_tattoo']) {
            $isEligible = false;
            if ($eligibilityStatus !== DonorProfile::STATUS_PERMANENTLY_DEFERRED) {
                $eligibilityStatus = DonorProfile::STATUS_TEMPORARILY_DEFERRED;
            }
            $tattooDeferralEnd = Carbon::today()->addMonths(6);
            if ($tattooDeferralEnd->gt($nextEligibleDate)) {
                $nextEligibleDate = $tattooDeferralEnd;
            }
            $ineligibilityReasons[] = "Recent tattoo/body piercing: 6-month clinical deferral window enforced for viral screening.";
        }

        if ($validated['has_recent_surgery']) {
            $isEligible = false;
            if ($eligibilityStatus !== DonorProfile::STATUS_PERMANENTLY_DEFERRED) {
                $eligibilityStatus = DonorProfile::STATUS_TEMPORARILY_DEFERRED;
            }
            $surgeryDeferralEnd = Carbon::today()->addMonths(6);
            if ($surgeryDeferralEnd->gt($nextEligibleDate)) {
                $nextEligibleDate = $surgeryDeferralEnd;
            }
            $ineligibilityReasons[] = "Recent major surgical procedure: 6-month recovery period required before whole blood donation.";
        }

        if ($validated['has_fever_infection']) {
            $isEligible = false;
            if ($eligibilityStatus !== DonorProfile::STATUS_PERMANENTLY_DEFERRED) {
                $eligibilityStatus = DonorProfile::STATUS_TEMPORARILY_DEFERRED;
            }
            $infectionDeferralEnd = Carbon::today()->addDays(14);
            if ($infectionDeferralEnd->gt($nextEligibleDate)) {
                $nextEligibleDate = $infectionDeferralEnd;
            }
            $ineligibilityReasons[] = "Active illness/infection or medication: Temporary 14-day deferral until full clinical recovery.";
        }

        if ($validated['is_pregnant_or_breastfeeding'] && $gender === 'female') {
            $isEligible = false;
            if ($eligibilityStatus !== DonorProfile::STATUS_PERMANENTLY_DEFERRED) {
                $eligibilityStatus = DonorProfile::STATUS_TEMPORARILY_DEFERRED;
            }
            $pregnancyDeferralEnd = Carbon::today()->addMonths(6);
            if ($pregnancyDeferralEnd->gt($nextEligibleDate)) {
                $nextEligibleDate = $pregnancyDeferralEnd;
            }
            $ineligibilityReasons[] = "Pregnancy or lactation: Deferral active to maintain maternal iron and nutritional reserves.";
        }

        if (! empty($validated['hemoglobin_g_dl'])) {
            $hemoglobin = (float) $validated['hemoglobin_g_dl'];
            $minHemoglobin = 12.5;
            if ($hemoglobin < $minHemoglobin) {
                $isEligible = false;
                if ($eligibilityStatus !== DonorProfile::STATUS_PERMANENTLY_DEFERRED) {
                    $eligibilityStatus = DonorProfile::STATUS_TEMPORARILY_DEFERRED;
                }
                $hbDeferralEnd = Carbon::today()->addDays(30);
                if ($hbDeferralEnd->gt($nextEligibleDate)) {
                    $nextEligibleDate = $hbDeferralEnd;
                }
                $ineligibilityReasons[] = "Low hemoglobin: Your level ({$hemoglobin} g/dL) is below safe minimum ({$minHemoglobin} g/dL). 30-day iron replenishment advised.";
            }
        }

        // Save evaluation results atomically
        DB::transaction(function () use (
            $user,
            $validated,
            $age,
            $weight,
            $bloodGroup,
            $isEligible,
            $eligibilityStatus,
            $nextEligibleDate,
            $ineligibilityReasons
        ) {
            DonorProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'gender' => $validated['gender'],
                    'date_of_birth' => $validated['date_of_birth'],
                    'age' => $age,
                    'weight_kg' => $weight,
                    'blood_group' => $bloodGroup,
                    'last_donation_date' => $validated['last_donation_date'] ?? null,
                    'last_donation_type' => $validated['last_donation_type'] ?? 'whole_blood',
                    'has_recent_tattoo' => $validated['has_recent_tattoo'],
                    'has_recent_surgery' => $validated['has_recent_surgery'],
                    'has_fever_infection' => $validated['has_fever_infection'],
                    'has_chronic_illness' => $validated['has_chronic_illness'],
                    'is_pregnant_or_breastfeeding' => $validated['is_pregnant_or_breastfeeding'],
                    'hemoglobin_g_dl' => $validated['hemoglobin_g_dl'] ?? null,
                    'is_eligible' => $isEligible,
                    'eligibility_status' => $eligibilityStatus,
                    'next_eligible_date' => $isEligible ? Carbon::today() : $nextEligibleDate,
                    'ineligibility_reasons' => $ineligibilityReasons,
                    'last_evaluated_at' => Carbon::now(),
                ]
            );

            // Sync blood group with user record if changed
            if ($bloodGroup && $user->blood_group !== $bloodGroup) {
                $user->update(['blood_group' => $bloodGroup]);
            }
        });

        if ($isEligible) {
            return redirect()->route('donor.eligibility')
                ->with('success', 'Health evaluation completed: You are medically ELIGIBLE to donate blood!');
        }

        return redirect()->route('donor.eligibility')
            ->with('info', 'Health evaluation completed: You are currently deferred from blood donation. Check details below.');
    }
}
