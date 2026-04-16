<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Application;
use App\Models\Client;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederIntegrityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_three_clients_exist(): void
    {
        $this->assertSame(3, Client::count());
        $this->assertNotNull(Client::where('slug', 'jae-tijuana')->first());
        $this->assertNotNull(Client::where('slug', 'acme-engineering')->first());
        $this->assertNotNull(Client::where('slug', 'tijuana-electronics')->first());
    }

    public function test_one_super_admin_exists_with_no_client(): void
    {
        $supers = User::where('role', UserRole::SUPER_ADMIN->value)->get();
        $this->assertCount(1, $supers);
        $this->assertNull($supers->first()->client_id);
        $this->assertSame('admin@seekrecruit.com', $supers->first()->email);
    }

    public function test_three_hr_admins_exist_each_with_a_client(): void
    {
        $hrs = User::where('role', UserRole::HR_ADMIN->value)->get();
        $this->assertCount(3, $hrs);
        foreach ($hrs as $hr) {
            $this->assertNotNull($hr->client_id, "HR admin {$hr->email} should be bound to a client");
        }
    }

    public function test_every_position_has_a_client(): void
    {
        $orphaned = Position::whereNull('client_id')->count();
        $this->assertSame(0, $orphaned, 'Every position must be associated with a client');
    }

    public function test_seeded_applications_exist(): void
    {
        $this->assertGreaterThan(0, Application::count());
    }

    public function test_no_legacy_jae_staff_role_remains(): void
    {
        $this->assertSame(0, User::where('role', 'jae_staff')->count());
    }

    public function test_position_enums_cast_correctly(): void
    {
        $position = Position::first();
        $this->assertInstanceOf(\App\Enums\PositionStatus::class, $position->status);
        $this->assertInstanceOf(\App\Enums\EmploymentType::class, $position->employment_type);
        $this->assertInstanceOf(\App\Enums\Modality::class, $position->modality);
    }
}
