<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_home_page_loads(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSeeText('Where Talent');
    }

    public function test_positions_index_loads_and_excludes_drafts(): void
    {
        $response = $this->get('/positions');
        $response->assertOk()
            ->assertSeeText('Open Positions')
            // Open positions visible
            ->assertSeeText('Junior Software Developer')
            ->assertSeeText('Full Stack Developer')
            // Draft position hidden from public listing
            ->assertDontSeeText('Data Analyst');
    }

    public function test_position_detail_shows_description_and_meta(): void
    {
        $this->get('/positions/1')
            ->assertOk()
            ->assertSeeText('Junior Software Developer')
            ->assertSeeText('Tijuana');
    }

    public function test_position_detail_for_draft_position_still_visible_via_direct_url(): void
    {
        // Draft status hides from listing but detail page still renders —
        // candidates won't find it unless sent a direct link.
        $this->get('/positions/7')
            ->assertOk()
            ->assertSeeText('Data Analyst');
    }

    public function test_login_page_loads(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSeeText('Welcome back');
    }

    public function test_register_page_loads(): void
    {
        $this->get('/register')
            ->assertOk()
            ->assertSeeText('Start your journey');
    }

    public function test_forgot_password_page_loads(): void
    {
        $this->get('/forgot-password')
            ->assertOk()
            ->assertSeeText('Forgot password');
    }

    public function test_reset_password_page_loads_with_query_args(): void
    {
        $this->get('/reset-password?token=abc123&email=foo@example.com')
            ->assertOk()
            ->assertSeeText('Reset password');
    }
}
