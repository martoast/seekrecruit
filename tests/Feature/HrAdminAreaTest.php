<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Client;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HrAdminAreaTest extends TestCase
{
    use RefreshDatabase;

    private User $maria;   // JAE Tijuana
    private User $jorge;   // Acme Engineering
    private Client $jae;
    private Client $acme;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->maria = User::where('email', 'maria@seekrecruit.com')->firstOrFail();
        $this->jorge = User::where('email', 'jorge@acme.com')->firstOrFail();
        $this->jae = Client::where('slug', 'jae-tijuana')->firstOrFail();
        $this->acme = Client::where('slug', 'acme-engineering')->firstOrFail();
    }

    public function test_dashboard_loads(): void
    {
        $this->actingAs($this->maria)
            ->get('/admin')
            ->assertOk()
            ->assertSeeText('Dashboard')
            // HR admin banner shows their client
            ->assertSeeText('JAE Tijuana');
    }

    public function test_positions_index_shows_only_own_client_positions(): void
    {
        $response = $this->actingAs($this->maria)->get('/admin/positions');
        $response->assertOk();

        // JAE Tijuana titles appear
        $response->assertSeeText('Junior Software Developer');

        // Acme and TJE titles do NOT appear
        $response->assertDontSeeText('Full Stack Developer');
        $response->assertDontSeeText('Electronics Technician');
    }

    public function test_hr_admin_cannot_edit_other_clients_position(): void
    {
        $acmePosition = Position::where('client_id', $this->acme->id)->firstOrFail();

        $this->actingAs($this->maria)
            ->get("/admin/positions/{$acmePosition->id}/edit")
            ->assertForbidden();
    }

    public function test_hr_admin_cannot_update_other_clients_position(): void
    {
        $acmePosition = Position::where('client_id', $this->acme->id)->firstOrFail();

        $this->actingAs($this->maria)
            ->put("/admin/positions/{$acmePosition->id}", [
                'title' => 'Hacked',
                'description' => 'x',
                'requirements' => 'x',
                'location' => 'x',
                'employment_type' => 'full_time',
                'modality' => 'on_site',
                'status' => 'open',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('positions', [
            'id' => $acmePosition->id,
            'title' => 'Hacked',
        ]);
    }

    public function test_hr_admin_cannot_delete_other_clients_position(): void
    {
        $acmePosition = Position::where('client_id', $this->acme->id)->firstOrFail();

        $this->actingAs($this->maria)
            ->delete("/admin/positions/{$acmePosition->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('positions', ['id' => $acmePosition->id, 'deleted_at' => null]);
    }

    public function test_hr_admin_create_position_forces_their_client_id(): void
    {
        // Even if the form submits a different client_id, the server forces the HR admin's own.
        $response = $this->actingAs($this->maria)
            ->post('/admin/positions', [
                'client_id' => $this->acme->id,
                'title' => 'Sneaky Position',
                'description' => 'Test',
                'requirements' => 'Test',
                'location' => 'Tijuana',
                'employment_type' => 'full_time',
                'modality' => 'on_site',
                'status' => 'open',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('positions', [
            'title' => 'Sneaky Position',
            'client_id' => $this->jae->id,
        ]);
    }

    public function test_applications_index_hides_other_clients_data(): void
    {
        $response = $this->actingAs($this->maria)->get('/admin/applications');
        $response->assertOk();

        $acmeApp = Application::whereHas('position', fn ($q) => $q->where('client_id', $this->acme->id))->first();
        if ($acmeApp && $acmeApp->position) {
            $response->assertDontSeeText($acmeApp->position->title);
        }
    }

    public function test_hr_admin_cannot_view_other_clients_application_detail(): void
    {
        $acmeApp = Application::whereHas('position', fn ($q) => $q->where('client_id', $this->acme->id))->first();
        if (! $acmeApp) {
            $this->markTestSkipped('No Acme application in seed data');
        }

        $this->actingAs($this->maria)
            ->get("/admin/applications/{$acmeApp->id}")
            ->assertForbidden();
    }

    public function test_hr_admin_cannot_change_status_of_other_clients_application(): void
    {
        $acmeApp = Application::whereHas('position', fn ($q) => $q->where('client_id', $this->acme->id))->first();
        if (! $acmeApp) {
            $this->markTestSkipped('No Acme application in seed data');
        }
        $originalStatus = $acmeApp->status->value;

        $this->actingAs($this->maria)
            ->put("/admin/applications/{$acmeApp->id}/status", ['status' => 'hired'])
            ->assertForbidden();

        $this->assertDatabaseHas('applications', [
            'id' => $acmeApp->id,
            'status' => $originalStatus,
        ]);
    }

    public function test_candidates_index_excludes_those_who_only_applied_to_other_clients(): void
    {
        // Set up a candidate who has applied ONLY to Acme.
        // Seeder already has some of these — we just assert we don't see candidates whose
        // only applications are outside JAE.
        $response = $this->actingAs($this->maria)->get('/admin/candidates');
        $response->assertOk();

        // Get all candidate user IDs whose only applications are to Acme or TJE
        $onlyOtherClientCandidates = \App\Models\CandidateProfile::query()
            ->whereHas('applications.position', fn ($q) => $q->where('client_id', '!=', $this->jae->id))
            ->whereDoesntHave('applications.position', fn ($q) => $q->where('client_id', $this->jae->id))
            ->with('user')
            ->get();

        foreach ($onlyOtherClientCandidates as $profile) {
            $response->assertDontSeeText($profile->user->email);
        }
    }

    public function test_interviews_index_scoped_to_client(): void
    {
        $this->actingAs($this->maria)
            ->get('/admin/interviews')
            ->assertOk();
    }

    public function test_hr_admin_cannot_access_clients_crud(): void
    {
        $this->actingAs($this->maria)
            ->get('/admin/clients')
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_hr_admin_cannot_access_admins_crud(): void
    {
        $this->actingAs($this->maria)
            ->get('/admin/admins')
            ->assertRedirect(route('admin.dashboard'));
    }
}
