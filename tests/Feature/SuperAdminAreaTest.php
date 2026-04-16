<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Application;
use App\Models\Client;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminAreaTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Client $jae;
    private Client $acme;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('email', 'admin@seekrecruit.com')->firstOrFail();
        $this->jae = Client::where('slug', 'jae-tijuana')->firstOrFail();
        $this->acme = Client::where('slug', 'acme-engineering')->firstOrFail();
    }

    public function test_dashboard_loads_platform_wide(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin')
            ->assertOk();
    }

    public function test_dashboard_loads_filtered_by_client(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin?client_id=' . $this->jae->id)
            ->assertOk()
            ->assertSeeText('JAE Tijuana');
    }

    public function test_positions_index_shows_all_clients(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/positions');
        $response->assertOk();
        $response->assertSeeText('Junior Software Developer');    // JAE
        $response->assertSeeText('Full Stack Developer');          // Acme
        $response->assertSeeText('Electronics Technician');        // TJE
    }

    public function test_super_admin_can_create_position_for_any_client(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/positions', [
                'client_id' => $this->acme->id,
                'title' => 'Super Admin Created',
                'description' => 'From super admin',
                'requirements' => 'Any',
                'location' => 'Remote',
                'employment_type' => 'full_time',
                'modality' => 'remote',
                'status' => 'open',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('positions', [
            'title' => 'Super Admin Created',
            'client_id' => $this->acme->id,
        ]);
    }

    public function test_super_admin_can_edit_any_clients_position(): void
    {
        $acmePosition = Position::where('client_id', $this->acme->id)->firstOrFail();

        $this->actingAs($this->admin)
            ->get("/admin/positions/{$acmePosition->id}/edit")
            ->assertOk();
    }

    public function test_super_admin_can_update_application_status_cross_client(): void
    {
        $acmeApp = Application::whereHas('position', fn ($q) => $q->where('client_id', $this->acme->id))->first();
        if (! $acmeApp) {
            $this->markTestSkipped('No Acme application in seed data');
        }

        $this->actingAs($this->admin)
            ->put("/admin/applications/{$acmeApp->id}/status", ['status' => 'evaluation'])
            ->assertRedirect();

        $this->assertDatabaseHas('applications', [
            'id' => $acmeApp->id,
            'status' => 'evaluation',
        ]);
    }

    // ---------------- Clients CRUD ----------------

    public function test_clients_index_loads(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/clients')
            ->assertOk()
            ->assertSeeText('JAE Tijuana')
            ->assertSeeText('Acme Engineering')
            ->assertSeeText('Tijuana Electronics');
    }

    public function test_clients_create_page_loads(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/clients/create')
            ->assertOk();
    }

    public function test_super_admin_can_create_a_new_client(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/clients', [
                'name' => 'Brand New Co',
                'slug' => 'brand-new-co',
                'industry' => 'Logistics',
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.clients.index'));

        $this->assertDatabaseHas('clients', ['slug' => 'brand-new-co']);
    }

    public function test_super_admin_can_update_client(): void
    {
        $this->actingAs($this->admin)
            ->put("/admin/clients/{$this->acme->id}", [
                'name' => 'Acme Engineering Renamed',
                'slug' => 'acme-engineering',
                'industry' => 'Automotive',
                'is_active' => 1,
            ])
            ->assertRedirect();

        $this->assertSame('Acme Engineering Renamed', $this->acme->fresh()->name);
    }

    public function test_super_admin_can_soft_delete_client_and_cascade_positions(): void
    {
        $acmePositionCount = Position::where('client_id', $this->acme->id)->count();
        $this->assertGreaterThan(0, $acmePositionCount);

        $this->actingAs($this->admin)
            ->delete("/admin/clients/{$this->acme->id}")
            ->assertRedirect(route('admin.clients.index'));

        // Client is soft-deleted
        $this->assertSoftDeleted('clients', ['id' => $this->acme->id]);

        // Positions cascaded — soft-deleted
        $this->assertSame(0, Position::where('client_id', $this->acme->id)->count());

        // HR admin for this client is now orphaned (client_id nulled)
        $jorge = User::where('email', 'jorge@acme.com')->first();
        $this->assertNull($jorge->client_id);
    }

    public function test_client_show_page_loads(): void
    {
        $this->actingAs($this->admin)
            ->get("/admin/clients/{$this->jae->id}")
            ->assertOk()
            ->assertSeeText('JAE Tijuana');
    }

    // ---------------- Admin User CRUD ----------------

    public function test_admins_index_loads(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/admins')
            ->assertOk()
            ->assertSeeText('Maria Garcia')
            ->assertSeeText('Jorge Ramirez');
    }

    public function test_super_admin_can_create_hr_admin(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/admins', [
                'name' => 'New HR Admin',
                'email' => 'new-hr@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'client_id' => $this->jae->id,
            ])
            ->assertRedirect(route('admin.admins.index'));

        $user = User::where('email', 'new-hr@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame(UserRole::HR_ADMIN, $user->role);
        $this->assertSame($this->jae->id, $user->client_id);
    }

    public function test_super_admin_can_update_hr_admin(): void
    {
        $maria = User::where('email', 'maria@seekrecruit.com')->first();

        $this->actingAs($this->admin)
            ->put("/admin/admins/{$maria->id}", [
                'name' => 'Maria G.',
                'email' => 'maria@seekrecruit.com',
                'client_id' => $this->acme->id,
            ])
            ->assertRedirect();

        $this->assertSame('Maria G.', $maria->fresh()->name);
        $this->assertSame($this->acme->id, $maria->fresh()->client_id);
    }

    public function test_super_admin_cannot_edit_another_super_admin(): void
    {
        $this->actingAs($this->admin)
            ->get("/admin/admins/{$this->admin->id}/edit")
            ->assertForbidden();
    }

    public function test_super_admin_cannot_delete_an_hr_admin_who_is_themselves(): void
    {
        $this->actingAs($this->admin)
            ->delete("/admin/admins/{$this->admin->id}")
            ->assertForbidden();
    }

    public function test_super_admin_can_delete_hr_admin(): void
    {
        $jorge = User::where('email', 'jorge@acme.com')->first();

        $this->actingAs($this->admin)
            ->delete("/admin/admins/{$jorge->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('users', ['id' => $jorge->id]);
    }
}
