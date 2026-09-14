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

    public function test_daily_report_bank_branch_segmentation()
    {
        $admin = User::factory()->create();
        $adminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin->roles()->attach($adminRole->id);

        $bankRole = Role::firstOrCreate(['name' => 'Bank Branch']);
        $branchA = User::factory()->create(['name' => 'SBI Salt Lake Branch']);
        $branchA->roles()->attach($bankRole->id);

        $branchB = User::factory()->create(['name' => 'HDFC Park Street Branch']);
        $branchB->roles()->attach($bankRole->id);

        $surveyorRole = Role::firstOrCreate(['name' => 'Surveyor']);
        $surveyor = User::factory()->create(['name' => 'Alice Surveyor']);
        $surveyor->roles()->attach($surveyorRole->id);

        $reporterRole = Role::firstOrCreate(['name' => 'Reporter']);
        $reporter = User::factory()->create(['name' => 'Bob Reporter']);
        $reporter->roles()->attach($reporterRole->id);

        $now = Carbon::now();

        // Work 1 under Branch A
        $work1 = Work::factory()->create([
            'bank_name' => 'SBI',
            'bank_branch' => $branchA->id,
            'created_at' => $now,
            'created_by' => $admin->id,
            'assignee_surveyor' => $surveyor->id,
            'status' => 'Surveying',
        ]);

        Inspection::factory()->create([
            'work_id' => $work1->id,
            'created_by' => $surveyor->id,
            'created_at' => $now,
        ]);

        // Work 2 under Branch B
        $work2 = Work::factory()->create([
            'bank_name' => 'HDFC',
            'bank_branch' => $branchB->id,
            'created_at' => $now,
            'created_by' => $admin->id,
            'assignee_reporter' => $reporter->id,
            'reporting_started_at' => $now->copy()->subMinutes(20),
            'reporting_ended_at' => $now,
            'status' => 'Reporting',
            'result' => 'Positive',
        ]);

        $response = $this->actingAs($admin)->get(route('works.daily-report', ['date' => $now->toDateString()]));

        $response->assertStatus(200);
        $response->assertViewHas('branchSegmentation');
        $response->assertViewHas('roleWorkMatrix');

        $branchSegmentation = $response->viewData('branchSegmentation');
        $this->assertArrayHasKey($branchA->id, $branchSegmentation);
        $this->assertArrayHasKey($branchB->id, $branchSegmentation);

        // Branch A verification
        $this->assertEquals('SBI Salt Lake Branch', $branchSegmentation[$branchA->id]['branch_name']);
        $this->assertEquals(1, $branchSegmentation[$branchA->id]['surveyed']);
        $this->assertArrayHasKey('Surveyor', $branchSegmentation[$branchA->id]['users_by_role']);
        $this->assertArrayHasKey($surveyor->id, $branchSegmentation[$branchA->id]['users_by_role']['Surveyor']);

        // Branch B verification
        $this->assertEquals('HDFC Park Street Branch', $branchSegmentation[$branchB->id]['branch_name']);
        $this->assertEquals(1, $branchSegmentation[$branchB->id]['reported']);
        $this->assertEquals(1, $branchSegmentation[$branchB->id]['positive']);
        $this->assertArrayHasKey('Reporter', $branchSegmentation[$branchB->id]['users_by_role']);
        $this->assertArrayHasKey($reporter->id, $branchSegmentation[$branchB->id]['users_by_role']['Reporter']);
    }

    public function test_daily_report_filtering_by_bank_branch_and_role()
    {
        $admin = User::factory()->create();
        $adminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin->roles()->attach($adminRole->id);

        $bankRole = Role::firstOrCreate(['name' => 'Bank Branch']);
        $branchA = User::factory()->create(['name' => 'Branch Alpha']);
        $branchA->roles()->attach($bankRole->id);

        $branchB = User::factory()->create(['name' => 'Branch Beta']);
        $branchB->roles()->attach($bankRole->id);

        $now = Carbon::now();

        Work::factory()->create([
            'bank_branch' => $branchA->id,
            'created_at' => $now,
            'created_by' => $admin->id,
        ]);

        Work::factory()->create([
            'bank_branch' => $branchB->id,
            'created_at' => $now,
            'created_by' => $admin->id,
        ]);

        // Filter by branch A
        $responseBranchA = $this->actingAs($admin)->get(route('works.daily-report', [
            'date' => $now->toDateString(),
            'bank_branch' => $branchA->id,
        ]));

        $responseBranchA->assertStatus(200);
        $this->assertEquals(1, $responseBranchA->viewData('totalActiveWorks'));
        $this->assertArrayHasKey($branchA->id, $responseBranchA->viewData('branchSegmentation'));
        $this->assertArrayNotHasKey($branchB->id, $responseBranchA->viewData('branchSegmentation'));
    }

    public function test_daily_report_csv_export_includes_bank_branch_and_roles()
    {
        $admin = User::factory()->create();
        $adminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin->roles()->attach($adminRole->id);

        $bankRole = Role::firstOrCreate(['name' => 'Bank Branch']);
        $branch = User::factory()->create(['name' => 'Kolkata Main Branch']);
        $branch->roles()->attach($bankRole->id);

        $now = Carbon::now();

        Work::factory()->create([
            'bank_name' => 'Punjab National Bank',
            'bank_branch' => $branch->id,
            'created_at' => $now,
            'created_by' => $admin->id,
            'status' => 'New File',
        ]);

        $exportResponse = $this->actingAs($admin)->get(route('works.daily-report', [
            'date' => $now->toDateString(),
            'action' => 'export',
        ]));

        $exportResponse->assertStatus(200);
        $content = $exportResponse->streamedContent();

        $this->assertStringContainsString('BANK BRANCH SEGMENTATION SUMMARY', $content);
        $this->assertStringContainsString('Kolkata Main Branch', $content);
        $this->assertStringContainsString('Punjab National Bank', $content);
        $this->assertStringContainsString('STAFF PERFORMANCE BY ROLE', $content);
    }

    public function test_daily_report_this_month_and_previous_month_filters()
    {
        $admin = User::factory()->create();
        $adminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin->roles()->attach($adminRole->id);

        $now = Carbon::now();
        $prevMonthDate = $now->copy()->subMonthNoOverflow()->startOfMonth()->addDays(5);

        // Work in this month
        $workThisMonth = Work::factory()->create([
            'created_at' => $now,
            'created_by' => $admin->id,
            'status' => 'New File',
        ]);

        // Work in previous month
        $workPrevMonth = Work::factory()->create([
            'created_at' => $prevMonthDate,
            'created_by' => $admin->id,
            'status' => 'New File',
        ]);

        // Query: this_month
        $responseThisMonth = $this->actingAs($admin)->get(route('works.daily-report', ['period' => 'this_month']));
        $responseThisMonth->assertStatus(200);
        $this->assertEquals('this_month', $responseThisMonth->viewData('period'));
        $this->assertEquals(1, $responseThisMonth->viewData('createdCount'));
        $worksThisMonth = $responseThisMonth->viewData('detailedWorks');
        $this->assertTrue($worksThisMonth->contains('id', $workThisMonth->id));
        $this->assertFalse($worksThisMonth->contains('id', $workPrevMonth->id));

        // Query: prev_month
        $responsePrevMonth = $this->actingAs($admin)->get(route('works.daily-report', ['period' => 'prev_month']));
        $responsePrevMonth->assertStatus(200);
        $this->assertEquals('prev_month', $responsePrevMonth->viewData('period'));
        $this->assertEquals(1, $responsePrevMonth->viewData('createdCount'));
        $worksPrevMonth = $responsePrevMonth->viewData('detailedWorks');
        $this->assertTrue($worksPrevMonth->contains('id', $workPrevMonth->id));
        $this->assertFalse($worksPrevMonth->contains('id', $workThisMonth->id));
    }

    public function test_daily_report_custom_date_range_and_financial_year()
    {
        $admin = User::factory()->create();
        $adminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin->roles()->attach($adminRole->id);

        $now = Carbon::now();

        $workToday = Work::factory()->create([
            'created_at' => $now,
            'created_by' => $admin->id,
        ]);

        // Query: custom range
        $responseCustom = $this->actingAs($admin)->get(route('works.daily-report', [
            'period' => 'custom',
            'date_from' => $now->copy()->subDays(2)->toDateString(),
            'date_to' => $now->copy()->addDays(2)->toDateString(),
        ]));

        $responseCustom->assertStatus(200);
        $this->assertEquals('custom', $responseCustom->viewData('period'));
        $this->assertTrue($responseCustom->viewData('detailedWorks')->contains('id', $workToday->id));

        // Query: current_fy
        $responseFy = $this->actingAs($admin)->get(route('works.daily-report', ['period' => 'current_fy']));
        $responseFy->assertStatus(200);
        $this->assertEquals('current_fy', $responseFy->viewData('period'));
        $this->assertTrue($responseFy->viewData('detailedWorks')->contains('id', $workToday->id));
    }
}
