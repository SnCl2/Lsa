<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserRole;
use App\Models\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class IncompleteWorksTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $admin = User::factory()->create();
        $role = UserRole::firstOrCreate(['name' => 'Super Admin']);
        $admin->roles()->attach($role->id);
        $this->actingAs($admin);
    }

    public function test_incomplete_works_dashboard_displays_recent_tab()
    {
        $now = now();

        // Recent work (e.g., 2 days old)
        Work::factory()->create([
            'name_of_applicant' => 'Recent Applicant',
            'status' => 'Pending',
            'created_at' => $now->copy()->subDays(2),
        ]);

        // Completed work (should not appear)
        Work::factory()->create([
            'name_of_applicant' => 'Completed Applicant',
            'status' => 'Completed',
            'created_at' => $now->copy()->subDays(2),
        ]);

        $response = $this->get(route('works.incomplete', ['tab' => 'recent']));
        
        $response->assertStatus(200);
        $response->assertSee('Recent Applicant');
        $response->assertDontSee('Completed Applicant');
    }

    public function test_incomplete_works_dashboard_displays_old_tab()
    {
        $now = now();

        Work::factory()->create([
            'name_of_applicant' => 'Old Applicant',
            'status' => 'Pending',
            'created_at' => $now->copy()->subDays(6), // 6 days old, should be in 'old'
        ]);

        $response = $this->get(route('works.incomplete', ['tab' => 'old']));
        
        $response->assertStatus(200);
        $response->assertSee('Old Applicant');
    }

    public function test_incomplete_works_dashboard_displays_very_old_tab()
    {
        $now = now();

        Work::factory()->create([
            'name_of_applicant' => 'Very Old Applicant',
            'status' => 'Pending',
            'created_at' => $now->copy()->subDays(12), // 12 days old, should be in 'very_old'
        ]);

        $response = $this->get(route('works.incomplete', ['tab' => 'very_old']));
        
        $response->assertStatus(200);
        $response->assertSee('Very Old Applicant');
    }

    public function test_incomplete_works_search_filter()
    {
        $now = now();

        Work::factory()->create([
            'name_of_applicant' => 'John Wick',
            'status' => 'Pending',
            'created_at' => $now->copy()->subDays(2),
        ]);

        Work::factory()->create([
            'name_of_applicant' => 'Jane Doe',
            'status' => 'Pending',
            'created_at' => $now->copy()->subDays(2),
        ]);

        $response = $this->get(route('works.incomplete', ['tab' => 'recent', 'search' => 'Wick']));
        
        $response->assertStatus(200);
        $response->assertSee('John Wick');
        $response->assertDontSee('Jane Doe');
    }
}
