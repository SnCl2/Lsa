<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\UserRoleRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles', 'userRoleRelations');

        // Search Functionality
        if ($request->has('search') && $request->input('search') != '') {
            $search = trim($request->input('search'));
            if ($search != '') {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
        }

        // Filter by Role
        if ($request->has('role') && $request->role != '') {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Filter by Can Login
        if ($request->has('can_login') && $request->can_login !== '' && $request->can_login !== null) {
            $canLogin = $request->can_login === '1' || $request->can_login === 1 ? 1 : 0;
            $query->whereHas('userRoleRelations', function ($q) use ($canLogin) {
                $q->where('can_login', $canLogin);
            });
        }

        $users = $query->orderBy('name', 'asc')->get();
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'can_login' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Attach roles
            $user->roles()->attach($request->roles);

            // Set login permission
            foreach ($request->roles as $role) {
                UserRoleRelation::updateOrCreate(
                    ['user_id' => $user->id, 'role_id' => $role],
                    ['can_login' => $request->can_login ?? true]
                );
            }
        });

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'can_login' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request, $user) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Update password only if provided
            if ($request->filled('password')) {
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            // Sync roles
            $user->roles()->sync($request->roles);

            // Update login permissions
            foreach ($request->roles as $role) {
                UserRoleRelation::updateOrCreate(
                    ['user_id' => $user->id, 'role_id' => $role],
                    ['can_login' => $request->can_login ?? true]
                );
            }
        });

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        DB::transaction(function () use ($user) {
            $user->roles()->detach(); // Remove role associations
            $user->delete(); // Soft delete user
        });

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function resetPassword(Request $request, User $user)
    {
        try {
            // Debug: Log the request data
            \Log::info('Password reset request data:', $request->all());
            
            $request->validate([
                'new_password' => 'required|string|min:6',
                'confirm_password' => 'required|same:new_password',
            ], [
                'new_password.required' => 'New password is required.',
                'new_password.min' => 'Password must be at least 6 characters.',
                'confirm_password.required' => 'Password confirmation is required.',
                'confirm_password.same' => 'Password confirmation does not match.',
            ]);

            // Update user's password
            $updated = $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            \Log::info('Password update result:', ['updated' => $updated, 'user_id' => $user->id]);

            return redirect()->route('users.edit', $user->id)
                ->with('success', 'Password has been reset successfully!');
                
        } catch (\Exception $e) {
            \Log::error('Password reset error:', ['error' => $e->getMessage(), 'user_id' => $user->id]);
            return redirect()->route('users.edit', $user->id)
                ->with('error', 'Error resetting password: ' . $e->getMessage());
        }
    }

    /**
     * Impersonate a user (login as that user)
     */
    public function impersonate(User $user): RedirectResponse
    {
        $currentUser = Auth::user();

        // Verify current user has Super Admin or KKDA Admin role
        if (!$currentUser->roles->contains('name', 'Super Admin') && 
            !$currentUser->roles->contains('name', 'KKDA Admin')) {
            abort(403, 'Unauthorized. Only Super Admin and KKDA Admin can impersonate users.');
        }

        // Prevent impersonating other admins (optional security measure)
        if ($user->roles->contains('name', 'Super Admin') || 
            $user->roles->contains('name', 'KKDA Admin')) {
            return redirect()->route('users.index')
                ->with('error', 'Cannot impersonate other administrators.');
        }

        // Check if user can login
        $canLogin = $user->userRoleRelations->first()?->can_login ?? false;
        if (!$canLogin) {
            return redirect()->route('users.index')
                ->with('error', 'This user does not have login permission.');
        }

        // Store original admin user ID in session
        session()->put('impersonating_from', $currentUser->id);
        session()->put('impersonating_as', $user->id);

        // Log impersonation action
        \Log::info('User impersonation', [
            'admin_id' => $currentUser->id,
            'admin_name' => $currentUser->name,
            'impersonated_user_id' => $user->id,
            'impersonated_user_name' => $user->name,
        ]);

        // Log in as the target user
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', "You are now logged in as {$user->name}. Click 'Stop Impersonating' to return to your account.");
    }

    /**
     * Stop impersonating and return to admin account
     */
    public function stopImpersonating(): RedirectResponse
    {
        $originalAdminId = session()->get('impersonating_from');

        if (!$originalAdminId) {
            return redirect()->route('dashboard')
                ->with('error', 'No active impersonation session found.');
        }

        $originalAdmin = User::find($originalAdminId);

        if (!$originalAdmin) {
            // Clear session and redirect if admin no longer exists
            session()->forget(['impersonating_from', 'impersonating_as']);
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Original admin account not found. Please log in again.');
        }

        // Log stop impersonation action
        $impersonatedUser = Auth::user();
        \Log::info('User impersonation stopped', [
            'admin_id' => $originalAdmin->id,
            'admin_name' => $originalAdmin->name,
            'impersonated_user_id' => $impersonatedUser->id ?? null,
            'impersonated_user_name' => $impersonatedUser->name ?? null,
        ]);

        // Log in as the original admin user
        Auth::login($originalAdmin);

        // Clear impersonation session data
        session()->forget(['impersonating_from', 'impersonating_as']);

        return redirect()->route('users.index')
            ->with('success', 'You have returned to your admin account.');
    }

    /**
     * Toggle login permission for a user (AJAX)
     */
    public function toggleLogin(User $user)
    {
        // Get current can_login state (from first relation, default false)
        $currentState = $user->userRoleRelations->first()?->can_login ?? false;
        $newState = !$currentState;

        // Update all role relations for this user
        if ($user->userRoleRelations->isEmpty()) {
            // No relations exist — create one per role (if user has roles)
            foreach ($user->roles as $role) {
                UserRoleRelation::updateOrCreate(
                    ['user_id' => $user->id, 'role_id' => $role->id],
                    ['can_login' => $newState]
                );
            }
        } else {
            $user->userRoleRelations()->update(['can_login' => $newState]);
        }

        return response()->json([
            'success' => true,
            'can_login' => $newState,
            'user_id' => $user->id,
            'message' => $newState
                ? "{$user->name} can now login."
                : "{$user->name} has been blocked from logging in.",
        ]);
    }
}
