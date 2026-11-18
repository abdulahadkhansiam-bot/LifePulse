<?php

namespace Tests\Feature;

use App\Models\Member4_Reehay\BloodRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BloodRequestTrackingR4Test extends TestCase
{
    use RefreshDatabase;

    // ─── Feature R4: Blood Request Tracking ───────────────────

    public function test_blood_request_dashboard_renders(): void
    {
        $user = User::factory()->create(['role' => 'requester']);

        $response = $this->actingAs($user)->get('/blood/requests');

        $response->assertStatus(200);
        $response->assertSee('Blood Request Tracking');
    }

    public function test_requester_can_create_blood_request(): void
    {
        $user = User::factory()->create(['role' => 'requester']);

        $response = $this->actingAs($user)->post('/blood/requests', [
            'patient_name' => 'Test Patient',
            'blood_group' => 'O+',
            'component_type' => 'whole_blood',
            'units_needed' => 3,
            'hospital_name' => 'Dhaka Medical College',
            'city' => 'Dhaka',
            'urgency' => 'urgent',
            'deadline_at' => Carbon::now()->addHours(24)->format('Y-m-d\TH:i'),
            'notes' => 'Urgent surgery',
            'contact_phone' => '+880-1234-567890',
        ]);

        $response->assertRedirect('/blood/requests');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('blood_requests', [
            'user_id' => $user->id,
            'patient_name' => 'Test Patient',
            'blood_group' => 'O+',
            'status' => BloodRequest::STATUS_OPEN,
            'units_needed' => 3,
            'units_fulfilled' => 0,
        ]);
    }

    public function test_request_status_transitions_from_open_to_partial(): void
    {
        $user = User::factory()->create(['role' => 'requester']);

        $request = BloodRequest::create([
            'user_id' => $user->id,
            'patient_name' => 'Partial Test',
            'blood_group' => 'B+',
            'component_type' => 'whole_blood',
            'units_needed' => 3,
            'hospital_name' => 'Test Hospital',
            'urgency' => 'normal',
            'deadline_at' => Carbon::now()->addHours(12),
            'status' => BloodRequest::STATUS_OPEN,
        ]);

        $response = $this->actingAs($user)->post("/blood/requests/{$request->id}/fulfill", [
            'units' => 1,
        ]);

        $response->assertRedirect('/blood/requests');

        $request->refresh();
        $this->assertEquals(BloodRequest::STATUS_PARTIAL, $request->status);
        $this->assertEquals(1, $request->units_fulfilled);
    }

    public function test_request_status_transitions_from_partial_to_fulfilled(): void
    {
        $user = User::factory()->create(['role' => 'requester']);

        $request = BloodRequest::create([
            'user_id' => $user->id,
            'patient_name' => 'Fulfill Test',
            'blood_group' => 'A-',
            'component_type' => 'platelets',
            'units_needed' => 2,
            'units_fulfilled' => 1,
            'hospital_name' => 'Test Hospital',
            'urgency' => 'critical',
            'deadline_at' => Carbon::now()->addHours(6),
            'status' => BloodRequest::STATUS_PARTIAL,
        ]);

        $response = $this->actingAs($user)->post("/blood/requests/{$request->id}/fulfill", [
            'units' => 1,
        ]);

        $request->refresh();
        $this->assertEquals(BloodRequest::STATUS_FULFILLED, $request->status);
        $this->assertEquals(2, $request->units_fulfilled);
    }

    public function test_overdue_requests_auto_expire(): void
    {
        $user = User::factory()->create(['role' => 'requester']);

        BloodRequest::create([
            'user_id' => $user->id,
            'patient_name' => 'Expired Test',
            'blood_group' => 'AB+',
            'component_type' => 'whole_blood',
            'units_needed' => 1,
            'hospital_name' => 'Test Hospital',
            'urgency' => 'normal',
            'deadline_at' => Carbon::now()->subHours(2), // Already past deadline
            'status' => BloodRequest::STATUS_OPEN,
        ]);

        // Visiting the dashboard triggers auto-expiration
        $this->actingAs($user)->get('/blood/requests');

        $this->assertDatabaseHas('blood_requests', [
            'patient_name' => 'Expired Test',
            'status' => BloodRequest::STATUS_EXPIRED,
        ]);
    }

    public function test_requester_can_manually_expire_a_request(): void
    {
        $user = User::factory()->create(['role' => 'requester']);

        $request = BloodRequest::create([
            'user_id' => $user->id,
            'patient_name' => 'Manual Expire',
            'blood_group' => 'O-',
            'component_type' => 'whole_blood',
            'units_needed' => 2,
            'hospital_name' => 'Test Hospital',
            'urgency' => 'normal',
            'deadline_at' => Carbon::now()->addHours(10),
            'status' => BloodRequest::STATUS_OPEN,
        ]);

        $response = $this->actingAs($user)->post("/blood/requests/{$request->id}/expire");

        $response->assertRedirect('/blood/requests');

        $request->refresh();
        $this->assertEquals(BloodRequest::STATUS_EXPIRED, $request->status);
    }

    public function test_cannot_fulfill_an_expired_request(): void
    {
        $user = User::factory()->create(['role' => 'requester']);

        $request = BloodRequest::create([
            'user_id' => $user->id,
            'patient_name' => 'Already Expired',
            'blood_group' => 'B-',
            'component_type' => 'whole_blood',
            'units_needed' => 1,
            'hospital_name' => 'Test Hospital',
            'urgency' => 'normal',
            'deadline_at' => Carbon::now()->addHours(5),
            'status' => BloodRequest::STATUS_EXPIRED,
        ]);

        $response = $this->actingAs($user)->post("/blood/requests/{$request->id}/fulfill", [
            'units' => 1,
        ]);

        $response->assertSessionHas('error');
    }

    public function test_cannot_expire_a_fulfilled_request(): void
    {
        $user = User::factory()->create(['role' => 'requester']);

        $request = BloodRequest::create([
            'user_id' => $user->id,
            'patient_name' => 'Already Fulfilled',
            'blood_group' => 'A+',
            'component_type' => 'whole_blood',
            'units_needed' => 1,
            'units_fulfilled' => 1,
            'hospital_name' => 'Test Hospital',
            'urgency' => 'normal',
            'deadline_at' => Carbon::now()->addHours(5),
            'status' => BloodRequest::STATUS_FULFILLED,
        ]);

        $response = $this->actingAs($user)->post("/blood/requests/{$request->id}/expire");

        $response->assertSessionHas('error');
    }

    public function test_fulfilment_percent_calculated_correctly(): void
    {
        $request = new BloodRequest([
            'units_needed' => 4,
            'units_fulfilled' => 2,
        ]);

        $this->assertEquals(50, $request->fulfilmentPercent());
    }

    public function test_process_requests_artisan_command_expires_overdue(): void
    {
        $user = User::factory()->create(['role' => 'requester']);

        BloodRequest::create([
            'user_id' => $user->id,
            'patient_name' => 'Command Expire Test',
            'blood_group' => 'O+',
            'component_type' => 'whole_blood',
            'units_needed' => 1,
            'hospital_name' => 'Test Hospital',
            'urgency' => 'normal',
            'deadline_at' => Carbon::now()->subMinutes(30),
            'status' => BloodRequest::STATUS_OPEN,
        ]);

        $this->artisan('blood:process-requests')->assertSuccessful();

        $this->assertDatabaseHas('blood_requests', [
            'patient_name' => 'Command Expire Test',
            'status' => BloodRequest::STATUS_EXPIRED,
        ]);
    }
}
