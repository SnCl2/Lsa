<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Work;
use App\Models\LoanType;
use App\Models\ProjectName;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class InChargeWorkFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('loan_types')) {
            Schema::create('loan_types', function ($table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('project_names')) {
            Schema::create('project_names', function ($table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        }
        if (!Schema::hasColumn('works', 'is_hold')) {
            Schema::table('works', function ($table) {
                $table->boolean('is_hold')->default(0)->nullable();
            });
        }
    }

    private function createInChargeUser(): User
    {
        $user = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'In-Charge']);
        $user->roles()->attach($role->id);
        return $user;
    }

    private function createSuperAdminUser(): User
    {
        $user = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'Super Admin']);
        $user->roles()->attach($role->id);
        return $user;
    }

    public function test_in_charge_user_create_form_has_fields_in_part_1_and_hides_workflow_fields()
    {
        $user = $this->createInChargeUser();
        LoanType::firstOrCreate(['name' => 'Home Loan']);
        ProjectName::firstOrCreate(['name' => 'Green Valley']);

        $response = $this->actingAs($user)->get(route('works.create'));
        $response->assertStatus(200);

        // Part 1 contains these fields
        $content = $response->getContent();
        $part1Content = substr($content, strpos($content, 'id="part1Section"'), strpos($content, 'id="part2Section"') - strpos($content, 'id="part1Section"'));

        $this->assertStringContainsString('id="project_name"', $part1Content);
        $this->assertStringContainsString('id="loan_amount_requested"', $part1Content);
        $this->assertStringContainsString('id="loan_type"', $part1Content);
        $this->assertStringContainsString('id="work_type"', $part1Content);
        $this->assertStringContainsString('id="valuer"', $part1Content);

        // Workflow and assignee fields are NOT present for In-Charge
        $response->assertDontSee('id="result"', false);
        $response->assertDontSee('id="status"', false);
        $response->assertDontSee('id="is_hold"', false);
        $response->assertDontSee('id="payment_status"', false);
        $response->assertDontSee('id="delivery_status"', false);
        $response->assertDontSee('id="assignee_surveyor"', false);
        $response->assertDontSee('id="assignee_reporter"', false);
        $response->assertDontSee('id="assignee_checker"', false);
        $response->assertDontSee('id="assignee_delivery"', false);
    }

    public function test_super_admin_sees_workflow_and_assignee_fields_in_create_form()
    {
        $admin = $this->createSuperAdminUser();

        $response = $this->actingAs($admin)->get(route('works.create'));
        $response->assertStatus(200);

        $response->assertSee('id="result"', false);
        $response->assertSee('id="status"', false);
        $response->assertSee('id="is_hold"', false);
        $response->assertSee('id="payment_status"', false);
        $response->assertSee('id="delivery_status"', false);
        $response->assertSee('id="assignee_surveyor"', false);
        $response->assertSee('id="assignee_reporter"', false);
        $response->assertSee('id="assignee_checker"', false);
        $response->assertSee('id="assignee_delivery"', false);
    }

    public function test_in_charge_can_create_work_with_part_1_fields()
    {
        $user = $this->createInChargeUser();

        $response = $this->actingAs($user)->post(route('works.store'), [
            'name_of_applicant' => 'Jane Smith',
            'number_of_applicants' => '9876543210',
            'source' => 'Website',
            'address_line_1' => 'Holding No: 45',
            'project_name' => 'Sunset Hills',
            'loan_amount_requested' => '75 Lakh',
            'loan_type' => 'Home Loan',
            'work_type' => 'Valuation',
            'valuer' => 'b',
            'form_step' => 'full',
        ]);

        $response->assertRedirect(route('works.myWorks'));
        $this->assertDatabaseHas('works', [
            'name_of_applicant' => 'Jane Smith',
            'project_name' => 'Sunset Hills',
            'loan_amount_requested' => '75 Lakh',
            'work_type' => 'Valuation',
            'valuer' => 'b',
            'status' => 'New File',
            'payment_status' => 'Payment Due',
            'delivery_status' => 'Delivery Due',
            'is_hold' => 0,
        ]);
    }

    public function test_in_charge_edit_form_hides_workflow_fields_and_preserves_existing_values_on_update()
    {
        $user = $this->createInChargeUser();

        $work = Work::factory()->create([
            'created_by' => $user->id,
            'name_of_applicant' => 'Original Name',
            'status' => 'Surveying',
            'payment_status' => 'Paid',
            'delivery_status' => 'Delivery Done',
            'is_hold' => 1,
            'result' => 'Positive',
        ]);

        // Edit form check
        $response = $this->actingAs($user)->get(route('works.edit', $work->id));
        $response->assertStatus(200);
        $response->assertDontSee('id="result"', false);
        $response->assertDontSee('id="status"', false);
        $response->assertDontSee('id="is_hold"', false);
        $response->assertDontSee('id="payment_status"', false);
        $response->assertDontSee('id="delivery_status"', false);
        $response->assertDontSee('id="assignee_surveyor"', false);

        // Update without workflow fields
        $updateResponse = $this->actingAs($user)->put(route('works.update', $work->id), [
            'name_of_applicant' => 'Updated Name',
            'number_of_applicants' => '1234567890',
            'address_line_1' => 'Premises No: 88',
        ]);

        $updateResponse->assertRedirect(route('works.myWorks'));

        // Verify updated fields and preserved workflow fields
        $work->refresh();
        $this->assertEquals('Updated Name', $work->name_of_applicant);
        $this->assertEquals('Surveying', $work->status);
        $this->assertEquals('Paid', $work->payment_status);
        $this->assertEquals('Delivery Done', $work->delivery_status);
        $this->assertEquals(1, $work->is_hold);
        $this->assertEquals('Positive', $work->result);
    }
}
