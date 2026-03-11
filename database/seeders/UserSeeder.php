<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRoleRelation;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Fetch all roles except "Super Admin"
        $roles = Role::where('name', '!=', 'Super Admin')->get();

        foreach ($roles as $role) {
            $user = User::create([
                'name' => $role->name . ' User',
                'email' => strtolower(str_replace(' ', '_', $role->name)) . '@example.com',
                'password' => Hash::make('password'),
            ]);

            // Assign role to the user
            UserRoleRelation::create([
                'user_id' => $user->id,
                'role_id' => $role->id,
                'can_login' => true,
            ]);
        }
    }
}
