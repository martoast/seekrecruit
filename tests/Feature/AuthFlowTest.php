<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_candidate_registration_creates_user_and_profile(): void
    {
        $this->post('/register', [
            'name' => 'Test Candidate',
            'email' => 'new-candidate@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'gender' => 'male',
            'terms' => 'on',
        ])->assertRedirect(route('candidate.profile.edit'));

        $user = User::where('email', 'new-candidate@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame(UserRole::CANDIDATE, $user->role);
        $this->assertNotNull($user->candidateProfile);
        $this->assertAuthenticatedAs($user);
    }

    public function test_candidate_can_log_in_and_is_redirected_to_candidate_dashboard(): void
    {
        $this->post('/login', [
            'email' => 'juan@example.com',
            'password' => 'password',
        ])->assertRedirect(route('candidate.dashboard'));

        $this->assertAuthenticatedAs(User::where('email', 'juan@example.com')->first());
    }

    public function test_hr_admin_can_log_in_and_is_redirected_to_admin_dashboard(): void
    {
        $this->post('/login', [
            'email' => 'maria@seekrecruit.com',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));
    }

    public function test_super_admin_can_log_in_and_is_redirected_to_admin_dashboard(): void
    {
        $this->post('/login', [
            'email' => 'admin@seekrecruit.com',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        $this->post('/login', [
            'email' => 'juan@example.com',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_logout_invalidates_session(): void
    {
        $user = User::where('email', 'juan@example.com')->first();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }

    public function test_authenticated_user_cannot_visit_login_page(): void
    {
        $user = User::where('email', 'juan@example.com')->first();

        $this->actingAs($user)
            ->get('/login')
            ->assertRedirect(route('candidate.dashboard'));
    }

    public function test_authenticated_admin_cannot_visit_login_page(): void
    {
        $user = User::where('email', 'maria@seekrecruit.com')->first();

        $this->actingAs($user)
            ->get('/login')
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_registration_rejects_duplicate_email(): void
    {
        $this->post('/register', [
            'name' => 'Duplicate',
            'email' => 'juan@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'gender' => 'male',
            'terms' => 'on',
        ])->assertSessionHasErrors('email');
    }

    public function test_password_is_hashed_on_registration(): void
    {
        $this->post('/register', [
            'name' => 'Hash Test',
            'email' => 'hash-test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'gender' => 'male',
            'terms' => 'on',
        ]);

        $user = User::where('email', 'hash-test@example.com')->first();
        $this->assertNotSame('password123', $user->password);
        $this->assertTrue(Hash::check('password123', $user->password));
    }
}
