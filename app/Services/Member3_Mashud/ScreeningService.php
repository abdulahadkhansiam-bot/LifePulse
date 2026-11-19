<?php

namespace App\Services\Member3_Mashud;

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
}