<?php

namespace App\Services\Member3_Mashud;
use App\Models\Member1_Rifaat\DonorProfile;
use App\Models\Member3_Mashud\Screening;
use Illuminate\Support\Facades\DB;

class ScreeningService
{
    /**
     * Compare the vitals with the limits in config/screening.php.
     * Returns a list of reasons that failed. An empty list means all good.
     */
    public function failedChecks(array $vitals): array
    {
        $limits = config('screening.limits');
        $failed = [];

        if ($vitals['weight_kg'] < $limits['min_weight_kg']) {
            $failed[] = 'low_weight';
        }

        if ($vitals['hemoglobin_g_dl'] < $limits['min_hemoglobin']) {
            $failed[] = 'low_hemoglobin';
        }

        if ($vitals['systolic'] < $limits['min_systolic']
            || $vitals['systolic'] > $limits['max_systolic']
            || $vitals['diastolic'] < $limits['min_diastolic']
            || $vitals['diastolic'] > $limits['max_diastolic']) {
            $failed[] = 'blood_pressure';
        }

        if ($vitals['pulse'] < $limits['min_pulse']
            || $vitals['pulse'] > $limits['max_pulse']) {
            $failed[] = 'pulse';
        }

        if ($vitals['temperature_c'] > $limits['max_temperature_c']) {
            $failed[] = 'fever';
        }

        return $failed;
    }

    /**
     * How many days to defer: the longest wait among the failed reasons.
     */
    public function deferralDays(array $failed): int
    {
        $days = config('screening.deferral_days');

        $waits = array_map(fn ($reason) => $days[$reason], $failed);

        return $waits ? max($waits) : 0;
    }
    
    public function record(array $data): Screening
    {
        $failed = $this->failedChecks($data);

        if (empty($failed)) {
            $outcome = Screening::OUTCOME_ACCEPTED;
            $returnDate = null;
        } else {
            $outcome = Screening::OUTCOME_DEFERRED;
            $returnDate = now()->addDays($this->deferralDays($failed));
        }

                return DB::transaction(function () use ($data, $failed, $outcome, $returnDate) {

            // Step 3: write in the notebook (screenings table)
            $screening = Screening::create([
                'donor_id'        => $data['donor_id'],
                'screened_by'     => $data['screened_by'],
                'appointment_id'  => $data['appointment_id'] ?? null,
                'weight_kg'       => $data['weight_kg'],
                'systolic'        => $data['systolic'],
                'diastolic'       => $data['diastolic'],
                'pulse'           => $data['pulse'],
                'temperature_c'   => $data['temperature_c'],
                'hemoglobin_g_dl' => $data['hemoglobin_g_dl'],
                'outcome'         => $outcome,
                'reason'          => $failed ? implode(', ', $failed) : null,
                'deferral_until'  => $returnDate,
                'notes'           => $data['notes'] ?? null,
                'screened_at'     => now(),
            ]);

            // Step 4: update the donor's card (donor_profiles table)
            DonorProfile::where('user_id', $data['donor_id'])
                ->firstOrFail()
                ->update([
                    'is_eligible'           => empty($failed),
                    'eligibility_status'    => empty($failed)
                        ? DonorProfile::STATUS_ELIGIBLE
                        : DonorProfile::STATUS_TEMPORARILY_DEFERRED,
                    'ineligibility_reasons' => $failed ?: null,
                    'next_eligible_date'    => $returnDate,
                    'weight_kg'             => $data['weight_kg'],
                    'hemoglobin_g_dl'       => $data['hemoglobin_g_dl'],
                    'last_evaluated_at'     => now(),
                ]);

            return $screening;
        });
    }
}