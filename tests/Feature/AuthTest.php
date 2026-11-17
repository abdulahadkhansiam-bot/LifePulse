<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_donor_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Rifat Nuha',
            'email' => 'rifatnuha4@gmail.com',
            'password' => 'Pass@1234!',
            'password_confirmation' => 'Pass@1234!',
            'role' => 'donor',
            'blood_group' => 'B+',
            'phone' => '01712345678',
            'city' => 'Dhaka',
            'address' => 'Dhanmondi',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'rifatnuha4@gmail.com',
            'role' => 'donor',
            'blood_group' => 'B+',
        ]);
    }

    public function test_duplicate_email_registration_is_rejected(): void
    {
        User::create([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'password' => Hash::make('Pass@1234!'),
            'role' => 'donor',
            'blood_group' => 'O+',
        ]);

        $response = $this->post('/register', [
            'name' => 'Another User',
            'email' => 'Existing@Example.COM', // case-insensitive check
            'password' => 'Pass@1234!',
            'password_confirmation' => 'Pass@1234!',
            'role' => 'donor',
            'blood_group' => 'O+',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_role_cannot_be_selected_at_registration(): void
    {
        $response = $this->post('/register', [
            'name' => 'Attacker',
            'email' => 'attacker@example.com',
            'password' => 'Pass@1234!',
            'password_confirmation' => 'Pass@1234!',
            'role' => 'admin', // disallowed
            'blood_group' => 'O+',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertGuest();
    }

    public function test_donor_registration_requires_blood_group(): void
    {
        $response = $this->post('/register', [
            'name' => 'Donor Without Blood',
            'email' => 'donor@example.com',
            'password' => 'Pass@1234!',
            'password_confirmation' => 'Pass@1234!',
            'role' => 'donor',
            'blood_group' => '', // empty blood group for donor
        ]);

        $response->assertSessionHasErrors('blood_group');
        $this->assertGuest();
    }

    public function test_password_confirmation_mismatch_is_rejected(): void
    {
        $response = $this->post('/register', [
            'name' => 'Mismatch Test',
            'email' => 'mismatch@example.com',
            'password' => 'Pass@1234!',
            'password_confirmation' => 'Different@1234!',
            'role' => 'requester',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'login_test@example.com',
            'password' => Hash::make('Pass@1234!'),
            'role' => 'donor',
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'email' => 'LOGIN_TEST@example.com', // case insensitive
            'password' => 'Pass@1234!',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        User::create([
            'name' => 'Test User',
            'email' => 'invalid_pass@example.com',
            'password' => Hash::make('Pass@1234!'),
            'role' => 'donor',
        ]);

        $response = $this->post('/login', [
            'email' => 'invalid_pass@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_inactive_users_cannot_login(): void
    {
        User::create([
            'name' => 'Suspended User',
            'email' => 'suspended@example.com',
            'password' => Hash::make('Pass@1234!'),
            'role' => 'donor',
            'status' => 'suspended', // inactive
        ]);

        $response = $this->post('/login', [
            'email' => 'suspended@example.com',
            'password' => 'Pass@1234!',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_unauthenticated_user_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_users_can_logout(): void
    {
        $user = User::create([
            'name' => 'Logout User',
            'email' => 'logout@example.com',
            'password' => Hash::make('Pass@1234!'),
            'role' => 'donor',
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
