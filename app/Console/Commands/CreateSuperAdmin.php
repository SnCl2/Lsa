<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use App\Models\UserRoleRelation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    protected $signature = 'create:superadmin';
    protected $description = 'Create a default Super Admin user';

    public function handle()
    {
        $this->info("Creating Super Admin...");

        // Check if Super Admin already exists
        if (User::whereHas('roles', function ($query) {
            $query->where('name', 'Super Admin');
        })->exists()) {
            $this->error("Super Admin already exists!");
            return;
        }

        // Collect user details
        $name = $this->ask('Enter Super Admin Name');
        $email = $this->ask('Enter Super Admin Email');
        $password = $this->secret('Enter Super Admin Password');

        // Validate email uniqueness
        if (User::where('email', $email)->exists()) {
            $this->error("A user with this email already exists!");
            return;
        }

        // Create User
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        // Assign "Super Admin" role
        $role = Role::where('name', 'Super Admin')->first();
        if (!$role) {
            $this->error("Super Admin role not found! Please add it to the roles table.");
            return;
        }

        $user->roles()->attach($role->id);

        // Set can_login permission
        UserRoleRelation::create([
            'user_id' => $user->id,
            'role_id' => $role->id,
            'can_login' => true,
        ]);

        $this->info("Super Admin created successfully!");
    }
}
