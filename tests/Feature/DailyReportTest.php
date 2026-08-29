<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Work;
use App\Models\Inspection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class DailyReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_daily_report()
    {
        $response = $this->get(route('works.daily-report'));
        $response->assertRedirect(route('login'));
    }

    public function test_unauthorized_user_cannot_access_daily_report()
    {
        $surveyor = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'Surveyor']);
        $surveyor->roles()->attach($role->id);

        $response = $this->actingAs($surveyor)->get(route('works.daily-report'));
        $response->assertStatus(403);
    }

    public function test_authorized_user_can_access_daily_report_and_see_data()
    {
        $admin = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin->roles()->attach($role->id);

        $now = Carbon::now();

        // 1. Created work
        $work = Work::factory()->create([
            'created_at' => $now,
            'created_by' => $admin->id,
            'status' => 'Reporting',
            'delivery_status' => 'Pending',
        ]);

        // 2. Surveyed (Inspection created today)
        $surveyor = User::factory()->create();
        $surveyorRole = Role::firstOrCreate(['name' => 'Surveyor']);
        $surveyor->roles()->attach($surveyorRole->id);
        
        $inspection = Inspection::factory()->create([
            'work_id' => $work->id,
            'created_by' => $surveyor->id,
            'created_at' => $now,
        ]);

        // 3. Reported (reporting ended today)
        $reporter = User::factory()->create();
        $reporterRole = Role::firstOrCreate(['name' => 'Reporter']);
        $reporter->roles()->attach($reporterRole->id);
        
        $work->update([
            'assignee_reporter' => $reporter->id,
            'reporting_started_at' => $now->copy()->subMinutes(30),
            'reporting_ended_at' => $now,
        ]);

        $response = $this->actingAs($admin)->get(route('works.daily-report', ['date' => $now->toDateString()]));

        $response->assertStatus(200);
        $response->assertViewIs('works.daily_report');
        
        $response->assertViewHas('createdCount', 1);
        $response->assertViewHas('surveyedCount', 1);
        $response->assertViewHas('reportedCount', 1);
        $response->assertViewHas('checkedCount', 0);
        $response->assertViewHas('deliveredCount', 0);

        // Verify CSV export
        $exportResponse = $this->actingAs($admin)->get(route('works.daily-report', [
            'date' => $now->toDateString(),
            'action' => 'export'
        ]));

        $exportResponse->assertStatus(200);
        $exportResponse->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('DAILY REPORT FOR', $exportResponse->streamedContent());
    }
}
