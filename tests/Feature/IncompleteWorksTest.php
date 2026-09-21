<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
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
        $role = Role::firstOrCreate(['name' => 'Super Admin']);
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

    public function test_incomplete_works_page_contains_export_csv_button()
    {
        $response = $this->get(route('works.incomplete'));

        $response->assertStatus(200);
        $response->assertSee('id="export-csv-btn"', false);
        $response->assertSee('Export CSV');
    }

    public function test_incomplete_works_csv_export_structure_and_data()
    {
        $now = now();

        $incompleteWork = Work::factory()->create([
            'name_of_applicant' => 'Incomplete Export Client',
            'custom_id' => 'INC-999',
            'status' => 'Pending',
            'created_at' => $now->copy()->subDays(2),
        ]);

        $completedWork = Work::factory()->create([
            'name_of_applicant' => 'Completed Export Client',
            'custom_id' => 'COM-999',
            'status' => 'Completed',
            'created_at' => $now->copy()->subDays(2),
        ]);

        $response = $this->get(route('works.incomplete.export', [
            'tab' => 'recent',
            'month' => $now->format('Y-m'),
        ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="incomplete_works_export.csv"');

        $content = $response->streamedContent();

        // Validate Header row structure matches /works/index
        $expectedHeader = 'SL. NO.,DATE,"REF NO.","CUSTOMER NAME","CONT. NUMBER",BANK,BRANCH,"DONE BY","CHECK BY","INSPECTION BY",INCHARGE,STATUS,"ON HOLD",RESULT,"ASSIGNMENT DATE","PRINT DATE",RSD,REGION,SUB-BRANCH,SOURCER,HLST/SSL,"INVOICE NO.","INVOICE DATE","PRINCIPLE AMOUNT",GST,"INVOICE AMOUNT","BILL STATUS",REMARKS';
        $this->assertStringContainsString('CUSTOMER NAME', $content);
        $this->assertStringContainsString('REF NO.', $content);
        $this->assertStringContainsString('PRINCIPLE AMOUNT', $content);
        $this->assertStringContainsString('BILL STATUS', $content);

        // Validate data row includes incomplete work and excludes completed work
        $this->assertStringContainsString('Incomplete Export Client', $content);
        $this->assertStringContainsString('INC-999', $content);
        $this->assertStringNotContainsString('Completed Export Client', $content);
        $this->assertStringNotContainsString('COM-999', $content);
    }

    public function test_incomplete_works_csv_export_filters_by_tab()
    {
        $now = now();

        Work::factory()->create([
            'name_of_applicant' => 'Recent Tab Applicant',
            'status' => 'Pending',
            'created_at' => $now->copy()->subDays(2),
        ]);

        Work::factory()->create([
            'name_of_applicant' => 'Aging Tab Applicant',
            'status' => 'Pending',
            'created_at' => $now->copy()->subDays(7),
        ]);

        $response = $this->get(route('works.incomplete.export', [
            'tab' => 'old',
            'month' => $now->format('Y-m'),
        ]));

        $response->assertStatus(200);
        $content = $response->streamedContent();

        $this->assertStringContainsString('Aging Tab Applicant', $content);
        $this->assertStringNotContainsString('Recent Tab Applicant', $content);
    }

    public function test_incomplete_works_csv_structure_is_identical_to_works_index_export()
    {
        $indexExport = $this->get(route('works.export'))->streamedContent();
        $incompleteExport = $this->get(route('works.incomplete.export'))->streamedContent();

        $indexHeader = strtok($indexExport, "\r\n");
        $incompleteHeader = strtok($incompleteExport, "\r\n");

        $this->assertNotEmpty($indexHeader);
        $this->assertEquals($indexHeader, $incompleteHeader);
    }

    public function test_incomplete_works_csv_export_filters_by_search()
    {
        $now = now();

        Work::factory()->create([
            'name_of_applicant' => 'Alice Wonder',
            'status' => 'Pending',
            'created_at' => $now->copy()->subDays(2),
        ]);

        Work::factory()->create([
            'name_of_applicant' => 'Bob Marley',
            'status' => 'Pending',
            'created_at' => $now->copy()->subDays(2),
        ]);

        $response = $this->get(route('works.incomplete.export', [
            'tab' => 'recent',
            'month' => $now->format('Y-m'),
            'search' => 'Alice',
        ]));

        $response->assertStatus(200);
        $content = $response->streamedContent();

        $this->assertStringContainsString('Alice Wonder', $content);
        $this->assertStringNotContainsString('Bob Marley', $content);
    }

    public function test_incomplete_works_csv_export_filters_by_bank_branch_and_month()
    {
        $now = now();
        $branch1 = User::factory()->create(['name' => 'Branch One']);
        $branch2 = User::factory()->create(['name' => 'Branch Two']);

        Work::factory()->create([
            'name_of_applicant' => 'Client Branch One',
            'bank_branch' => $branch1->id,
            'status' => 'Pending',
            'created_at' => $now->copy()->subDays(2),
        ]);

        Work::factory()->create([
            'name_of_applicant' => 'Client Branch Two',
            'bank_branch' => $branch2->id,
            'status' => 'Pending',
            'created_at' => $now->copy()->subDays(2),
        ]);

        // Test branch filter
        $response = $this->get(route('works.incomplete.export', [
            'tab' => 'recent',
            'month' => $now->format('Y-m'),
            'bank_branch' => $branch1->id,
        ]));

        $response->assertStatus(200);
        $content = $response->streamedContent();

        $this->assertStringContainsString('Client Branch One', $content);
        $this->assertStringNotContainsString('Client Branch Two', $content);

        // Test month filter for past month
        $pastMonth = $now->copy()->subMonths(2);
        Work::factory()->create([
            'name_of_applicant' => 'Past Month Client',
            'status' => 'Pending',
            'created_at' => $pastMonth,
        ]);

        $pastResponse = $this->get(route('works.incomplete.export', [
            'tab' => 'very_old',
            'month' => $pastMonth->format('Y-m'),
        ]));

        $pastResponse->assertStatus(200);
        $pastContent = $pastResponse->streamedContent();
        $this->assertStringContainsString('Past Month Client', $pastContent);
        $this->assertStringNotContainsString('Client Branch One', $pastContent);
    }
}


