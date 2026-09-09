<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToggleResultTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_toggle_result_to_positive()
    {
        $user = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'In-Charge']);
        $user->roles()->attach($role->id);

        $work = Work::factory()->create([
            'result' => null,
            'remarks' => null,
        ]);

        $response = $this->actingAs($user)->postJson(route('works.toggleResult', $work->id), [
            'result' => 'Positive',
            'remarks' => 'Looks good',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'result' => 'Positive',
        ]);

        $this->assertDatabaseHas('works', [
            'id' => $work->id,
            'result' => 'Positive',
            'remarks' => 'Looks good',
        ]);
    }

    public function test_user_can_toggle_result_to_negative()
    {
        $user = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'In-Charge']);
        $user->roles()->attach($role->id);

        $work = Work::factory()->create([
            'result' => null,
            'remarks' => null,
        ]);

        $response = $this->actingAs($user)->postJson(route('works.toggleResult', $work->id), [
            'result' => 'Negative',
            'remarks' => 'Rejected property',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'result' => 'Negative',
        ]);

        $this->assertDatabaseHas('works', [
            'id' => $work->id,
            'result' => 'Negative',
            'remarks' => 'Rejected property',
        ]);
    }

    public function test_user_can_toggle_result_off_to_null()
    {
        $user = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'In-Charge']);
        $user->roles()->attach($role->id);

        $work = Work::factory()->create([
            'result' => 'Positive',
        ]);

        $response = $this->actingAs($user)->postJson(route('works.toggleResult', $work->id), [
            'result' => 'null',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'result' => null,
        ]);

        $this->assertDatabaseHas('works', [
            'id' => $work->id,
            'result' => null,
        ]);
    }

    public function test_bank_branch_user_cannot_toggle_result()
    {
        $user = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'Bank Branch']);
        $user->roles()->attach($role->id);

        $work = Work::factory()->create([
            'result' => null,
        ]);

        $response = $this->actingAs($user)->postJson(route('works.toggleResult', $work->id), [
            'result' => 'Positive',
        ]);

        $response->assertStatus(403);
    }

    public function test_custom_id_is_displayed_to_all_users_regardless_of_role()
    {
        // 1. Check Super Admin on works.index
        $admin = User::factory()->create();
        $adminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin->roles()->attach($adminRole->id);

        $work1 = Work::factory()->create([
            'custom_id' => 'CID-ADMIN-1111',
        ]);

        $adminResponse = $this->actingAs($admin)->get(route('works.index'));
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('CID-ADMIN-1111');

        // 2. Check Surveyor on works.surveyor
        $surveyor = User::factory()->create();
        $surveyorRole = Role::firstOrCreate(['name' => 'Surveyor']);
        $surveyor->roles()->attach($surveyorRole->id);

        $work2 = Work::factory()->create([
            'assignee_surveyor' => $surveyor->id,
            'custom_id' => 'CID-SURV-2222',
        ]);

        $surveyorResponse = $this->actingAs($surveyor)->get(route('works.surveyor'));
        $surveyorResponse->assertStatus(200);
        $surveyorResponse->assertSee('CID-SURV-2222');

        // 3. Check Surveyor on works.show
        $showResponse = $this->actingAs($surveyor)->get(route('works.show', $work2->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('CID-SURV-2222');

        // 4. Check Bank Branch user on works.bankBranch
        $bankUser = User::factory()->create();
        $bankRole = Role::firstOrCreate(['name' => 'Bank Branch']);
        $bankUser->roles()->attach($bankRole->id);

        $work3 = Work::factory()->create([
            'bank_branch' => $bankUser->id,
            'custom_id' => 'CID-BANK-3333',
        ]);

        $bankResponse = $this->actingAs($bankUser)->get(route('works.bankBranch'));
        $bankResponse->assertStatus(200);
        $bankResponse->assertSee('CID-BANK-3333');
    }
}
