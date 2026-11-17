<?php

namespace Tests\Feature;

use App\Models\Member1_Rifaat\BloodInventory;
use App\Models\Member1_Rifaat\StockAlert;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class StockAlertsR8Test extends TestCase
{
    use RefreshDatabase;

    public function test_inventory_alerts_dashboard_renders(): void
    {
        $user = User::factory()->create(['role' => 'hospital']);

        $response = $this->actingAs($user)->get('/inventory/alerts');

        $response->assertStatus(200);
        $response->assertSee('Blood Stock & Expiry Alert System', false);
    }

    public function test_expired_units_are_segregated_and_alerted(): void
    {
        BloodInventory::create([
            'bag_serial_number' => 'EXP-001',
            'blood_group' => 'O+',
            'component_type' => 'whole_blood',
            'volume_ml' => 450,
            'collection_date' => Carbon::now()->subDays(45),
            'expiry_date' => Carbon::now()->subHours(2), // Already expired!
            'status' => BloodInventory::STATUS_AVAILABLE,
        ]);

        Artisan::call('blood:check-alerts');

        $this->assertDatabaseHas('blood_inventory', [
            'bag_serial_number' => 'EXP-001',
            'status' => BloodInventory::STATUS_EXPIRED,
        ]);

        $this->assertDatabaseHas('stock_alerts', [
            'alert_type' => StockAlert::TYPE_EXPIRED,
            'severity' => StockAlert::SEVERITY_CRITICAL,
            'blood_group' => 'O+',
        ]);
    }

    public function test_platelets_expiring_within_48_hours_trigger_critical_alert(): void
    {
        BloodInventory::create([
            'bag_serial_number' => 'PLT-48H-01',
            'blood_group' => 'A+',
            'component_type' => BloodInventory::COMPONENT_PLATELETS,
            'volume_ml' => 250,
            'collection_date' => Carbon::now()->subDays(3),
            'expiry_date' => Carbon::now()->addHours(30), // < 48 hours!
            'status' => BloodInventory::STATUS_AVAILABLE,
        ]);

        Artisan::call('blood:check-alerts');

        $this->assertDatabaseHas('stock_alerts', [
            'alert_type' => StockAlert::TYPE_CRITICAL_EXPIRY,
            'severity' => StockAlert::SEVERITY_CRITICAL,
            'component_type' => BloodInventory::COMPONENT_PLATELETS,
        ]);
    }

    public function test_zero_units_triggers_stockout_critical_alert(): void
    {
        // No units in database
        Artisan::call('blood:check-alerts', ['--threshold' => 5]);

        $this->assertDatabaseHas('stock_alerts', [
            'alert_type' => StockAlert::TYPE_STOCKOUT,
            'severity' => StockAlert::SEVERITY_CRITICAL,
            'blood_group' => 'O-',
        ]);
    }

    public function test_units_below_threshold_trigger_low_stock_warning(): void
    {
        // Insert 2 available units of B+ (below threshold of 5)
        for ($i = 1; $i <= 2; $i++) {
            BloodInventory::create([
                'bag_serial_number' => "B-POS-0{$i}",
                'blood_group' => 'B+',
                'component_type' => 'whole_blood',
                'volume_ml' => 450,
                'collection_date' => Carbon::now()->subDays(5),
                'expiry_date' => Carbon::now()->addDays(30),
                'status' => BloodInventory::STATUS_AVAILABLE,
            ]);
        }

        Artisan::call('blood:check-alerts', ['--threshold' => 5]);

        $this->assertDatabaseHas('stock_alerts', [
            'alert_type' => StockAlert::TYPE_LOW_STOCK,
            'severity' => StockAlert::SEVERITY_WARNING,
            'blood_group' => 'B+',
        ]);
    }

    public function test_staff_can_resolve_an_alert(): void
    {
        $user = User::factory()->create(['role' => 'hospital']);
        $alert = StockAlert::create([
            'alert_type' => StockAlert::TYPE_LOW_STOCK,
            'severity' => StockAlert::SEVERITY_WARNING,
            'blood_group' => 'AB+',
            'message' => 'Low stock on AB+',
            'is_resolved' => false,
        ]);

        $response = $this->actingAs($user)->post("/inventory/alerts/{$alert->id}/resolve");

        $response->assertRedirect('/inventory/alerts');

        $this->assertDatabaseHas('stock_alerts', [
            'id' => $alert->id,
            'is_resolved' => true,
        ]);
    }
}
