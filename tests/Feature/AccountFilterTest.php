<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Work;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup necessary data
        $accountant = User::factory()->create();
        $role = UserRole::create(['name' => 'Accountant']);
        $accountant->roles()->attach($role->id);
        $this->actingAs($accountant);

        // Create some sample works
        Work::factory()->create([
            'name_of_applicant' => 'John Doe',
            'result' => 'Positive',
            'is_billing_done' => false,
            'created_at' => now()->subDays(10)
        ]);

        Work::factory()->create([
            'name_of_applicant' => 'Jane Smith',
            'result' => 'Positive',
            'is_billing_done' => true,
            'payment_status' => 'Payment Due',
            'invoice_number' => 'INV-001',
            'invoice_date' => now()->subDays(5)
        ]);
    }

    public function test_account_index_basic_display()
    {
        $response = $this->get(route('account.index'));
        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertDontSee('Jane Smith'); // Billed should not be in pending tab
    }

    public function test_account_search_filter()
    {
        $response = $this->get(route('account.index', ['search' => 'John']));
        $response->assertStatus(200);
        $response->assertSee('John Doe');
        
        $response = $this->get(route('account.index', ['search' => 'XYZ']));
        $response->assertStatus(200);
        $response->assertDontSee('John Doe');
    }

    public function test_account_tab_filters()
    {
        $response = $this->get(route('account.index', ['tab' => 'billed']));
        $response->assertStatus(200);
        $response->assertSee('Jane Smith');
        $response->assertDontSee('John Doe');
    }

    public function test_account_invoice_date_filter()
    {
        $response = $this->get(route('account.index', [
            'tab' => 'billed',
            'invoice_date_from' => now()->subDays(7)->format('Y-m-d'),
            'invoice_date_to' => now()->subDays(1)->format('Y-m-d'),
        ]));
        $response->assertStatus(200);
        $response->assertSee('Jane Smith');

        $response = $this->get(route('account.index', [
            'tab' => 'billed',
            'invoice_date_from' => now()->subDays(2)->format('Y-m-d'),
        ]));
        $response->assertStatus(200);
        $response->assertDontSee('Jane Smith');
    }
}
