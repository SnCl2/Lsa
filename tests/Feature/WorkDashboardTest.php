<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Work;
use App\Models\Inspection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class WorkDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_dashboard()
    {
        $response = $this->get(route('works.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_unauthorized_user_cannot_access_dashboard()
    {
        // Surveyor role does not have access
        $surveyor = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'Surveyor']);
        $surveyor->roles()->attach($role->id);

        $response = $this->actingAs($surveyor)->get(route('works.dashboard'));
        $response->assertStatus(403);
    }

    public function test_authorized_user_can_access_dashboard_and_see_kpis()
    {
        // Super Admin has access
        $admin = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin->roles()->attach($role->id);
        
        $now = Carbon::now();

        // 1. Create a Work for this month
        Work::factory()->create([
            'assignment_date' => $now,
            'status' => 'Reporting',
            'payment_status' => 'Pending',
            'delivery_status' => 'Pending',
            'invoice_amount' => 500.00,
            'billing_done_at' => $now,
            'reporting_started_at' => $now->copy()->subHours(2),
            'reporting_ended_at' => $now, // 120 mins turnaround
        ]);

        // 2. Create an Inspection to test property types chart
        Inspection::factory()->create([
            'property_type' => 'Flat',
            'work_id' => 1
        ]);

        $response = $this->actingAs($admin)->get(route('works.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('works.dashboard');
        
        // Assert the view has the calculated KPI variables
        $response->assertViewHas('thisMonthCount');
        $response->assertViewHas('volumeTrend');
        $response->assertViewHas('invoicedThisMonth');
        $response->assertViewHas('pendingPaymentCount');
        $response->assertViewHas('avgTurnaroundHours');
        $response->assertViewHas('pendingDeliverables');
        $response->assertViewHas('inflowData');
        $response->assertViewHas('statusDistribution');
        $response->assertViewHas('topBranches');
        $response->assertViewHas('propertyTypes');

        // Verify some specific values rendered on the dashboard view
        $response->assertSee('Analytics Dashboard'); // Page title
        $response->assertSee('500'); // Invoice amount formatting
    }
}
