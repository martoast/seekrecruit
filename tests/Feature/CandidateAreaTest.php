<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CandidateAreaTest extends TestCase
{
    use RefreshDatabase;

    private User $juan;
    private User $ana;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->juan = User::where('email', 'juan@example.com')->firstOrFail();
        $this->ana = User::where('email', 'ana@example.com')->firstOrFail();
    }

    public function test_candidate_dashboard_loads(): void
    {
        $this->actingAs($this->juan)
            ->get('/candidate')
            ->assertOk()
            ->assertSeeText('Welcome back');
    }

    public function test_candidate_profile_page_loads(): void
    {
        $this->actingAs($this->juan)
            ->get('/candidate/profile')
            ->assertOk()
            ->assertSeeText('My Profile');
    }

    public function test_candidate_can_update_profile(): void
    {
        $this->actingAs($this->juan)
            ->put('/candidate/profile', [
                'university' => 'Updated University',
                'degree' => 'Computer Science',
                'location' => 'Tijuana',
                'phone' => '+52 664 000 0000',
                'bio' => 'New bio',
                'skills' => 'PHP,Laravel,Testing',
                'semester' => 9,
                'graduation_year' => 2025,
                'age' => 24,
                'gender' => 'male',
                'linkedin_url' => 'https://linkedin.com/in/juan',
            ])
            ->assertRedirect();

        $profile = $this->juan->fresh()->candidateProfile;
        $this->assertSame('Updated University', $profile->university);
        $this->assertSame(['PHP', 'Laravel', 'Testing'], $profile->skills);
    }

    public function test_candidate_applications_index_loads(): void
    {
        $this->actingAs($this->juan)
            ->get('/candidate/applications')
            ->assertOk()
            ->assertSeeText('My Applications');
    }

    public function test_candidate_can_apply_to_open_position(): void
    {
        // Juan already has some seeded applications; use a position he hasn't applied to yet.
        $appliedPositionIds = $this->juan->candidateProfile
            ->applications()
            ->pluck('position_id')
            ->all();

        $unappliedPosition = Position::whereNotIn('id', $appliedPositionIds)
            ->where('status', 'open')
            ->firstOrFail();

        $this->actingAs($this->juan)
            ->post('/candidate/applications', [
                'position_id' => $unappliedPosition->id,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('applications', [
            'candidate_id' => $this->juan->candidateProfile->id,
            'position_id' => $unappliedPosition->id,
        ]);
    }

    public function test_candidate_cannot_apply_twice_to_same_position(): void
    {
        $existing = $this->juan->candidateProfile->applications()->first();

        $this->actingAs($this->juan)
            ->post('/candidate/applications', [
                'position_id' => $existing->position_id,
            ])
            ->assertRedirect()
            ->assertSessionHas('error');

        $count = Application::where('candidate_id', $this->juan->candidateProfile->id)
            ->where('position_id', $existing->position_id)
            ->count();
        $this->assertSame(1, $count);
    }

    public function test_candidate_can_view_own_application_detail(): void
    {
        $application = $this->juan->candidateProfile->applications()->first();

        $this->actingAs($this->juan)
            ->get("/candidate/applications/{$application->id}")
            ->assertOk();
    }

    public function test_candidate_cannot_view_other_candidates_application(): void
    {
        $otherApp = $this->ana->candidateProfile->applications()->first();

        $this->actingAs($this->juan)
            ->get("/candidate/applications/{$otherApp->id}")
            ->assertForbidden();
    }

    public function test_candidate_referrals_page_loads(): void
    {
        $this->actingAs($this->juan)
            ->get('/candidate/referrals')
            ->assertOk()
            ->assertSeeText('Referral');
    }

    public function test_candidate_can_send_referral(): void
    {
        $this->actingAs($this->juan)
            ->post('/candidate/referrals', ['referred_email' => 'new-friend@example.com'])
            ->assertRedirect();

        $this->assertDatabaseHas('referrals', [
            'referrer_id' => $this->juan->id,
            'referred_email' => 'new-friend@example.com',
        ]);
    }

    public function test_candidate_cannot_access_admin(): void
    {
        $this->actingAs($this->juan)
            ->get('/admin')
            ->assertRedirect(route('candidate.dashboard'));
    }

    public function test_candidate_cannot_access_admin_clients(): void
    {
        $this->actingAs($this->juan)
            ->get('/admin/clients')
            ->assertRedirect();
    }
}
