<?php

namespace Tests\Feature;

use App\Models\Member1_Rifaat\DonorProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonorEligibilityR1Test extends TestCase
{
    use RefreshDatabase;

    public function test_eligibility_screen_can_be_rendered(): void
    {
        $user = User::factory()->create(['role' => 'donor']);

        $response = $this->actingAs($user)->get('/donor/eligibility');

        $response->assertStatus(200);
        $response->assertSee('Donor Health Evaluation');
    }

    public function test_healthy_donor_passes_eligibility_evaluation(): void
    {
        $user = User::factory()->create(['role' => 'donor', 'blood_group' => 'O+']);

        $response = $this->actingAs($user)->post('/donor/eligibility', [
            'gender' => 'male',
            'date_of_birth' => Carbon::now()->subYears(25)->format('Y-m-d'),
            'weight_kg' => 68.5,
            'blood_group' => 'O+',
            'last_donation_date' => Carbon::now()->subDays(120)->format('Y-m-d'), // > 90 days
            'last_donation_type' => 'whole_blood',
            'has_recent_tattoo' => false,
            'has_recent_surgery' => false,
            'has_fever_infection' => false,
            'has_chronic_illness' => false,
            'is_pregnant_or_breastfeeding' => false,
            'hemoglobin_g_dl' => 14.2,
        ]);

        $response->assertRedirect('/donor/eligibility');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('donor_profiles', [
            'user_id' => $user->id,
            'is_eligible' => true,
            'eligibility_status' => DonorProfile::STATUS_ELIGIBLE,
        ]);
    }

    public function test_underage_donor_is_deferred_until_18th_birthday(): void
    {
        $user = User::factory()->create(['role' => 'donor']);

        $response = $this->actingAs($user)->post('/donor/eligibility', [
            'gender' => 'male',
            'date_of_birth' => Carbon::now()->subYears(16)->format('Y-m-d'), // 16 years old
            'weight_kg' => 55,
            'blood_group' => 'A+',
            'has_recent_tattoo' => false,
            'has_recent_surgery' => false,
            'has_fever_infection' => false,
            'has_chronic_illness' => false,
            'is_pregnant_or_breastfeeding' => false,
        ]);

        $response->assertRedirect('/donor/eligibility');

        $profile = DonorProfile::where('user_id', $user->id)->first();
        $this->assertFalse($profile->is_eligible);
        $this->assertEquals(DonorProfile::STATUS_TEMPORARILY_DEFERRED, $profile->eligibility_status);
        $this->assertStringContainsString('Underage', $profile->ineligibility_reasons[0]);
    }

    public function test_overage_donor_is_permanently_deferred(): void
    {
        $user = User::factory()->create(['role' => 'donor']);

        $response = $this->actingAs($user)->post('/donor/eligibility', [
            'gender' => 'male',
            'date_of_birth' => Carbon::now()->subYears(68)->format('Y-m-d'), // 68 years old
            'weight_kg' => 70,
            'blood_group' => 'B+',
            'has_recent_tattoo' => false,
            'has_recent_surgery' => false,
            'has_fever_infection' => false,
            'has_chronic_illness' => false,
            'is_pregnant_or_breastfeeding' => false,
        ]);

        $response->assertRedirect('/donor/eligibility');

        $profile = DonorProfile::where('user_id', $user->id)->first();
        $this->assertFalse($profile->is_eligible);
        $this->assertEquals(DonorProfile::STATUS_PERMANENTLY_DEFERRED, $profile->eligibility_status);
    }

    public function test_underweight_donor_is_deferred(): void
    {
        $user = User::factory()->create(['role' => 'donor']);

        $response = $this->actingAs($user)->post('/donor/eligibility', [
            'gender' => 'male',
            'date_of_birth' => Carbon::now()->subYears(22)->format('Y-m-d'),
            'weight_kg' => 44.0, // < 50kg for male
            'blood_group' => 'AB+',
            'has_recent_tattoo' => false,
            'has_recent_surgery' => false,
            'has_fever_infection' => false,
            'has_chronic_illness' => false,
            'is_pregnant_or_breastfeeding' => false,
        ]);

        $profile = DonorProfile::where('user_id', $user->id)->first();
        $this->assertFalse($profile->is_eligible);
        $this->assertStringContainsString('Body weight requirement', $profile->ineligibility_reasons[0]);
    }

    public function test_donation_interval_cooldown_enforces_90_days(): void
    {
        $user = User::factory()->create(['role' => 'donor']);

        $response = $this->actingAs($user)->post('/donor/eligibility', [
            'gender' => 'male',
            'date_of_birth' => Carbon::now()->subYears(25)->format('Y-m-d'),
            'weight_kg' => 70,
            'blood_group' => 'O-',
            'last_donation_date' => Carbon::now()->subDays(30)->format('Y-m-d'), // only 30 days ago!
            'last_donation_type' => 'whole_blood',
            'has_recent_tattoo' => false,
            'has_recent_surgery' => false,
            'has_fever_infection' => false,
            'has_chronic_illness' => false,
            'is_pregnant_or_breastfeeding' => false,
        ]);

        $profile = DonorProfile::where('user_id', $user->id)->first();
        $this->assertFalse($profile->is_eligible);
        $this->assertEquals(DonorProfile::STATUS_TEMPORARILY_DEFERRED, $profile->eligibility_status);
        $this->assertGreaterThan(0, $profile->daysRemaining());
        $this->assertStringContainsString('Interval cooldown active', $profile->ineligibility_reasons[0]);
    }

    public function test_chronic_illness_sets_permanent_deferral(): void
    {
        $user = User::factory()->create(['role' => 'donor']);

        $response = $this->actingAs($user)->post('/donor/eligibility', [
            'gender' => 'female',
            'date_of_birth' => Carbon::now()->subYears(30)->format('Y-m-d'),
            'weight_kg' => 58,
            'blood_group' => 'A+',
            'has_recent_tattoo' => false,
            'has_recent_surgery' => false,
            'has_fever_infection' => false,
            'has_chronic_illness' => true, // Chronic condition reported
            'is_pregnant_or_breastfeeding' => false,
        ]);

        $profile = DonorProfile::where('user_id', $user->id)->first();
        $this->assertFalse($profile->is_eligible);
        $this->assertEquals(DonorProfile::STATUS_PERMANENTLY_DEFERRED, $profile->eligibility_status);
    }
}
