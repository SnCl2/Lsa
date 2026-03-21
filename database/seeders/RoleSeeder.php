<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = ['Super Admin', 'KKDA Admin', 'In-Charge', 'Surveyor', 'Reporter', 'Checker', 'Delivery Person', 'Bank Branch', 'Accountant'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
