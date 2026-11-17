<?php

namespace App\Http\Controllers\Member1_Rifaat;

use App\Http\Controllers\Controller;
use App\Models\Member1_Rifaat\BloodInventory;
use App\Models\Member1_Rifaat\StockAlert;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class AlertController extends Controller
{
    /**
     * Display the blood stock alerts and expiry dashboard (Feature R8).
     */
    public function index(Request $request): View
    {
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $threshold = 5;

        // Group counts summary
        $groupStats = [];
        foreach ($bloodGroups as $bg) {
            $count = BloodInventory::where('blood_group', $bg)
                ->where('status', BloodInventory::STATUS_AVAILABLE)
                ->where('expiry_date', '>', Carbon::now())
                ->count();

            $groupStats[$bg] = [
                'count' => $count,
                'is_low' => $count < $threshold,
                'is_out' => $count === 0,
            ];
        }

        // Active alerts
        $activeAlerts = StockAlert::active()
            ->orderByRaw("FIELD(severity, 'critical', 'warning', 'info')")
            ->latest()
            ->get();

        // Near-expiry units (within next 7 days)
        $nearExpiryUnits = BloodInventory::where('status', BloodInventory::STATUS_AVAILABLE)
            ->where('expiry_date', '>', Carbon::now())
            ->where('expiry_date', '<=', Carbon::now()->addDays(7))
            ->orderBy('expiry_date', 'asc')
            ->get();

        // Total inventory count
        $totalUnits = BloodInventory::where('status', BloodInventory::STATUS_AVAILABLE)
            ->where('expiry_date', '>', Carbon::now())
            ->count();

        return view('member1_rifaat.inventory.alerts', [
            'groupStats' => $groupStats,
            'activeAlerts' => $activeAlerts,
            'nearExpiryUnits' => $nearExpiryUnits,
            'totalUnits' => $totalUnits,
            'threshold' => $threshold,
        ]);
    }

    /**
     * Trigger on-demand automated evaluation scan.
     */
    public function scan(): RedirectResponse
    {
        Artisan::call('blood:check-alerts');

        return redirect()->route('inventory.alerts')
            ->with('success', 'Automated inventory sweep completed successfully. Thresholds and expiry windows updated.');
    }

    /**
     * Mark an alert as acknowledged and resolved.
     */
    public function resolve(int $id): RedirectResponse
    {
        $alert = StockAlert::findOrFail($id);
        $alert->update([
            'is_resolved' => true,
            'resolved_at' => Carbon::now(),
        ]);

        return redirect()->route('inventory.alerts')
            ->with('success', "Alert #{$id} marked as resolved.");
    }

    /**
     * Seed realistic sample units for testing (platelet near expiry, low stock, etc.)
     */
    public function seedSampleUnits(): RedirectResponse
    {
        // Sample unit 1: Platelets expiring in 24 hours (Critical)
        BloodInventory::updateOrCreate(
            ['bag_serial_number' => 'BAG-PLT-0982'],
            [
                'blood_group' => 'O+',
                'component_type' => BloodInventory::COMPONENT_PLATELETS,
                'volume_ml' => 300,
                'collection_date' => Carbon::now()->subDays(4),
                'expiry_date' => Carbon::now()->addHours(20), // < 48h
                'status' => BloodInventory::STATUS_AVAILABLE,
                'storage_location' => 'Agitator Unit 1',
                'hospital_name' => 'Central Blood Bank',
            ]
        );

        // Sample unit 2: Whole blood expiring in 3 days (Warning)
        BloodInventory::updateOrCreate(
            ['bag_serial_number' => 'BAG-WB-4412'],
            [
                'blood_group' => 'B+',
                'component_type' => BloodInventory::COMPONENT_WHOLE_BLOOD,
                'volume_ml' => 450,
                'collection_date' => Carbon::now()->subDays(32),
                'expiry_date' => Carbon::now()->addDays(3), // < 7 days
                'status' => BloodInventory::STATUS_AVAILABLE,
                'storage_location' => 'Refrig 2, Shelf A',
                'hospital_name' => 'Central Blood Bank',
            ]
        );

        // Sample unit 3: Fresh units
        for ($i = 1; $i <= 3; $i++) {
            BloodInventory::updateOrCreate(
                ['bag_serial_number' => 'BAG-WB-FRESH-' . $i],
                [
                    'blood_group' => 'A+',
                    'component_type' => BloodInventory::COMPONENT_WHOLE_BLOOD,
                    'volume_ml' => 450,
                    'collection_date' => Carbon::now()->subDays(2),
                    'expiry_date' => Carbon::now()->addDays(33),
                    'status' => BloodInventory::STATUS_AVAILABLE,
                    'storage_location' => 'Refrig 1, Shelf C',
                    'hospital_name' => 'Central Blood Bank',
                ]
            );
        }

        Artisan::call('blood:check-alerts');

        return redirect()->route('inventory.alerts')
            ->with('success', 'Sample units generated and automated alert evaluation triggered!');
    }
}
