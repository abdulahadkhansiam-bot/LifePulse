<?php

namespace Tests\Feature;

use App\Models\Member4_Reehay\BloodRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmergencyEscalationR5Test extends TestCase
{
    use RefreshDatabase;

    // ─── Feature R5: Emergency Escalation ─────────────────────

    public function test_emergency_broadcast_dashboard_renders(): void
    {
        $user = User::factory()->create(['role' => 'hospital']);

        $response = $this->actingAs($user)->get('/emergency/broadcast');

        $response->assertStatus(200);
        $response->assertSee('Emergency Blood Broadcast');
    }

    public function test_requester_can_activate_emergency_on_existing_request(): void
    {
        $user = User::factory()->create(['role' => 'requester']);

        $request = BloodRequest::create([
            'user_id' => $user->id,
            'patient_name' => 'Emergency Patient',
            'blood_group' => 'O-',
            'component_type' => 'whole_blood',
            'units_needed' => 4,
            'hospital_name' => 'Dhaka Medical College',
            'urgency' => 'normal',
            'deadline_at' => Carbon::now()->addHours(6),
            'status' => BloodRequest::STATUS_OPEN,
        ]);

        $response = $this->actingAs($user)->post("/emergency/{$request->id}/activate", [
            'emergency_level' => 'sos_critical',
            'contact_phone' => '+880-1234-567890',
        ]);

        $response->assertRedirect('/emergency/broadcast');
        $response->assertSessionHas('success');

        $request->refresh();
        $this->assertTrue($request->is_emergency);
        $this->assertEquals(BloodRequest::EMERGENCY_SOS_CRITICAL, $request->emergency_level);
        $this->assertTrue($request->is_broadcast_pinned);
        $this->assertEquals(10, $request->current_radius_km);
        $this->assertEquals(BloodRequest::URGENCY_CRITICAL, $request->urgency);
    }

    public function test_emergency_radial_sweep_expands_10_to_25_to_50(): void
    {
        $user = User::factory()->create(['role' => 'requester']);

        $request = BloodRequest::create([
            'user_id' => $user->id,
            'patient_name' => 'Radius Test',
            'blood_group' => 'A+',
            'component_type' => 'platelets',
            'units_needed' => 2,
            'hospital_name' => 'Test Hospital',
            'urgency' => 'critical',
            'deadline_at' => Carbon::now()->addHours(3),
            'status' => BloodRequest::STATUS_OPEN,
            'is_emergency' => true,
            'emergency_level' => 'sos_critical',
            'current_radius_km' => 10,
            'is_broadcast_pinned' => true,
            'last_escalated_at' => Carbon::now()->subMinutes(15),
        ]);

        // First escalation: 10 → 25
        $response = $this->actingAs($user)->post("/emergency/{$request->id}/escalate");
        $request->refresh();
        $this->assertEquals(25, $request->current_radius_km);
        $this->assertEquals(1, $request->escalation_count);

        // Second escalation: 25 → 50
        $response = $this->actingAs($user)->post("/emergency/{$request->id}/escalate");
        $request->refresh();
        $this->assertEquals(50, $request->current_radius_km);
        $this->assertEquals(2, $request->escalation_count);

        // Third attempt: already at max
        $response = $this->actingAs($user)->post("/emergency/{$request->id}/escalate");
        $response->assertSessionHas('info');
    }

    public function test_hospital_staff_can_create_sos_emergency_directly(): void
    {
        $user = User::factory()->create(['role' => 'hospital']);

        $response = $this->actingAs($user)->post('/emergency/create-sos', [
            'patient_name' => 'Direct SOS Patient',
            'blood_group' => 'AB-',
            'component_type' => 'red_cells',
            'units_needed' => 5,
            'hospital_name' => 'National Heart Foundation',
            'city' => 'Dhaka',
            'deadline_at' => Carbon::now()->addHours(2)->format('Y-m-d\TH:i'),
            'contact_phone' => '+880-9999-999999',
            'notes' => 'Open heart surgery emergency',
        ]);

        $response->assertRedirect('/emergency/broadcast');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('blood_requests', [
            'patient_name' => 'Direct SOS Patient',
            'is_emergency' => true,
            'emergency_level' => BloodRequest::EMERGENCY_SOS_CRITICAL,
            'is_broadcast_pinned' => true,
            'current_radius_km' => 10,
            'urgency' => BloodRequest::URGENCY_CRITICAL,
        ]);
    }

    public function test_emergency_deactivation_unpins_broadcast(): void
    {
        $user = User::factory()->create(['role' => 'requester']);

        $request = BloodRequest::create([
            'user_id' => $user->id,
            'patient_name' => 'Deactivate Test',
            'blood_group' => 'B+',
            'component_type' => 'whole_blood',
            'units_needed' => 1,
            'hospital_name' => 'Test Hospital',
            'urgency' => 'critical',
            'deadline_at' => Carbon::now()->addHours(5),
            'status' => BloodRequest::STATUS_OPEN,
            'is_emergency' => true,
            'emergency_level' => 'sos_critical',
            'is_broadcast_pinned' => true,
            'current_radius_km' => 25,
        ]);

        $response = $this->actingAs($user)->post("/emergency/{$request->id}/deactivate");

        $response->assertRedirect('/emergency/broadcast');

        $request->refresh();
        $this->assertFalse($request->is_emergency);
        $this->assertFalse($request->is_broadcast_pinned);
    }

    public function test_cannot_activate_emergency_on_fulfilled_request(): void
    {
        $user = User::factory()->create(['role' => 'requester']);

        $request = BloodRequest::create([
            'user_id' => $user->id,
            'patient_name' => 'Fulfilled No Emergency',
            'blood_group' => 'O+',
            'component_type' => 'whole_blood',
            'units_needed' => 1,
            'units_fulfilled' => 1,
            'hospital_name' => 'Test Hospital',
            'urgency' => 'normal',
            'deadline_at' => Carbon::now()->addHours(5),
            'status' => BloodRequest::STATUS_FULFILLED,
        ]);

        $response = $this->actingAs($user)->post("/emergency/{$request->id}/activate", [
            'emergency_level' => 'sos_critical',
        ]);

        $response->assertSessionHas('error');
    }

    public function test_auto_escalation_needs_radial_escalation_logic(): void
    {
        $request = new BloodRequest([
            'is_emergency' => true,
            'status' => BloodRequest::STATUS_OPEN,
            'current_radius_km' => 10,
            'last_escalated_at' => Carbon::now()->subMinutes(15), // 15 mins ago > 10 min interval
        ]);

        $this->assertTrue($request->needsRadialEscalation());

        // Not due yet
        $request->last_escalated_at = Carbon::now()->subMinutes(5);
        $this->assertFalse($request->needsRadialEscalation());

        // Already at max
        $request->current_radius_km = 50;
        $request->last_escalated_at = Carbon::now()->subMinutes(15);
        $this->assertFalse($request->needsRadialEscalation());
    }

    public function test_expired_emergency_requests_auto_unpin(): void
    {
        $user = User::factory()->create(['role' => 'hospital']);

        BloodRequest::create([
            'user_id' => $user->id,
            'patient_name' => 'Auto Unpin Test',
            'blood_group' => 'O-',
            'component_type' => 'whole_blood',
            'units_needed' => 2,
            'hospital_name' => 'Test Hospital',
            'urgency' => 'critical',
            'deadline_at' => Carbon::now()->subMinutes(10), // Already past
            'status' => BloodRequest::STATUS_OPEN,
            'is_emergency' => true,
            'emergency_level' => 'sos_critical',
            'is_broadcast_pinned' => true,
            'current_radius_km' => 25,
        ]);

        // Visiting emergency broadcast triggers auto-expiration
        $this->actingAs($user)->get('/emergency/broadcast');

        $this->assertDatabaseHas('blood_requests', [
            'patient_name' => 'Auto Unpin Test',
            'status' => BloodRequest::STATUS_EXPIRED,
            'is_emergency' => false,
            'is_broadcast_pinned' => false,
        ]);
    }

    public function test_pinned_broadcasts_visible_to_all_authenticated_users(): void
    {
        $requester = User::factory()->create(['role' => 'requester']);
        $donor = User::factory()->create(['role' => 'donor']);

        BloodRequest::create([
            'user_id' => $requester->id,
            'patient_name' => 'Visible To All',
            'blood_group' => 'A-',
            'component_type' => 'whole_blood',
            'units_needed' => 3,
            'hospital_name' => 'Test Hospital',
            'urgency' => 'critical',
            'deadline_at' => Carbon::now()->addHours(4),
            'status' => BloodRequest::STATUS_OPEN,
            'is_emergency' => true,
            'emergency_level' => 'sos_critical',
            'is_broadcast_pinned' => true,
            'current_radius_km' => 10,
        ]);

        // Donor (different user) should see the pinned broadcast
        $response = $this->actingAs($donor)->get('/emergency/broadcast');
        $response->assertStatus(200);
        $response->assertSee('Visible To All');
    }
}
