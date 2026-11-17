<?php

namespace App\Console\Commands;

use App\Models\Member1_Rifaat\BloodInventory;
use App\Models\Member1_Rifaat\StockAlert;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckBloodStockAlertsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blood:check-alerts {--threshold=5 : Minimum unit threshold per blood group}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Feature R8: Daily automated evaluation of blood inventory expiration windows and low-stock thresholds.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting automated blood stock & expiry evaluation...');
        $threshold = (int) $this->option('threshold');
        $now = Carbon::now();

        $generatedAlertsCount = 0;

        DB::transaction(function () use ($now, $threshold, &$generatedAlertsCount) {
            // 1. Mark already expired units
            $expiredUnits = BloodInventory::where('status', BloodInventory::STATUS_AVAILABLE)
                ->where('expiry_date', '<', $now)
                ->get();

            foreach ($expiredUnits as $unit) {
                $unit->update(['status' => BloodInventory::STATUS_EXPIRED]);
                StockAlert::create([
                    'alert_type' => StockAlert::TYPE_EXPIRED,
                    'severity' => StockAlert::SEVERITY_CRITICAL,
                    'blood_group' => $unit->blood_group,
                    'component_type' => $unit->component_type,
                    'message' => "Unit {$unit->bag_serial_number} ({$unit->blood_group} {$unit->component_type}) expired on {$unit->expiry_date->format('M d, Y H:i')} and has been segregated.",
                    'details' => ['unit_id' => $unit->id, 'bag_serial_number' => $unit->bag_serial_number],
                ]);
                $generatedAlertsCount++;
            }

            // 2. Near Expiry Checks (Platelets <= 48h, Whole Blood/RBC <= 7 days)
            $availableUnits = BloodInventory::where('status', BloodInventory::STATUS_AVAILABLE)->get();

            foreach ($availableUnits as $unit) {
                $hoursLeft = $unit->hoursUntilExpiry();

                // Platelets critical window: <= 48 hours
                if ($unit->component_type === BloodInventory::COMPONENT_PLATELETS && $hoursLeft <= 48 && $hoursLeft > 0) {
                    $exists = StockAlert::where('alert_type', StockAlert::TYPE_CRITICAL_EXPIRY)
                        ->where('is_resolved', false)
                        ->whereJsonContains('details->bag_serial_number', $unit->bag_serial_number)
                        ->exists();

                    if (! $exists) {
                        StockAlert::create([
                            'alert_type' => StockAlert::TYPE_CRITICAL_EXPIRY,
                            'severity' => StockAlert::SEVERITY_CRITICAL,
                            'blood_group' => $unit->blood_group,
                            'component_type' => $unit->component_type,
                            'message' => "CRITICAL: Platelet unit {$unit->bag_serial_number} ({$unit->blood_group}) expires in " . round($hoursLeft, 1) . " hours!",
                            'details' => ['unit_id' => $unit->id, 'bag_serial_number' => $unit->bag_serial_number, 'hours_left' => $hoursLeft],
                        ]);
                        $generatedAlertsCount++;
                    }
                }
                // Whole Blood & Red Cells: <= 7 days (168h), high priority if <= 24h
                elseif (in_array($unit->component_type, [BloodInventory::COMPONENT_WHOLE_BLOOD, BloodInventory::COMPONENT_RED_CELLS]) && $hoursLeft <= 168 && $hoursLeft > 0) {
                    $severity = ($hoursLeft <= 24) ? StockAlert::SEVERITY_CRITICAL : StockAlert::SEVERITY_WARNING;
                    $alertType = ($hoursLeft <= 24) ? StockAlert::TYPE_CRITICAL_EXPIRY : StockAlert::TYPE_NEAR_EXPIRY;

                    $exists = StockAlert::where('alert_type', $alertType)
                        ->where('is_resolved', false)
                        ->whereJsonContains('details->bag_serial_number', $unit->bag_serial_number)
                        ->exists();

                    if (! $exists) {
                        StockAlert::create([
                            'alert_type' => $alertType,
                            'severity' => $severity,
                            'blood_group' => $unit->blood_group,
                            'component_type' => $unit->component_type,
                            'message' => "Unit {$unit->bag_serial_number} ({$unit->blood_group} {$unit->component_type}) expires in " . round($hoursLeft / 24, 1) . " days (" . round($hoursLeft) . " hours).",
                            'details' => ['unit_id' => $unit->id, 'bag_serial_number' => $unit->bag_serial_number, 'hours_left' => $hoursLeft],
                        ]);
                        $generatedAlertsCount++;
                    }
                }
            }

            // 3. Low-Stock & Stockout Checks per Blood Group
            $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
            foreach ($bloodGroups as $bg) {
                $count = BloodInventory::where('blood_group', $bg)
                    ->where('status', BloodInventory::STATUS_AVAILABLE)
                    ->count();

                if ($count === 0) {
                    $exists = StockAlert::where('alert_type', StockAlert::TYPE_STOCKOUT)
                        ->where('blood_group', $bg)
                        ->where('is_resolved', false)
                        ->exists();

                    if (! $exists) {
                        StockAlert::create([
                            'alert_type' => StockAlert::TYPE_STOCKOUT,
                            'severity' => StockAlert::SEVERITY_CRITICAL,
                            'blood_group' => $bg,
                            'message' => "CRITICAL STOCKOUT: Zero available units of {$bg} in inventory!",
                            'details' => ['available_count' => 0, 'threshold' => $threshold],
                        ]);
                        $generatedAlertsCount++;
                    }
                } elseif ($count < $threshold) {
                    $exists = StockAlert::where('alert_type', StockAlert::TYPE_LOW_STOCK)
                        ->where('blood_group', $bg)
                        ->where('is_resolved', false)
                        ->exists();

                    if (! $exists) {
                        StockAlert::create([
                            'alert_type' => StockAlert::TYPE_LOW_STOCK,
                            'severity' => StockAlert::SEVERITY_WARNING,
                            'blood_group' => $bg,
                            'message' => "Low stock warning: {$bg} stock has fallen to {$count} units (Minimum threshold: {$threshold}).",
                            'details' => ['available_count' => $count, 'threshold' => $threshold],
                        ]);
                        $generatedAlertsCount++;
                    }
                }
            }
        });

        $this->info("Scan complete. {$generatedAlertsCount} new alert(s) processed.");
        return self::SUCCESS;
    }
}
