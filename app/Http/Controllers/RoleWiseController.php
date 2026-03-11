<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Work;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class RoleWiseController extends Controller
{
    /**
     * Display role-wise user management page
     */
    public function index()
    {
        // Get all roles except Super Admin, KKDA Admin, and Bank Branch
        $excludedRoles = ['Super Admin', 'KKDA Admin', 'Bank Branch'];
        $roles = Role::whereNotIn('name', $excludedRoles)->get();
        
        // Get users and their work assignments for each role
        $roleData = [];
        
        foreach ($roles as $role) {
            $users = User::whereHas('roles', function ($query) use ($role) {
                $query->where('name', $role->name);
            })->with(['roles'])->get();
            
            $roleData[$role->name] = [
                'role' => $role,
                'users' => $users->map(function ($user) use ($role) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'current_work' => $this->getCurrentWorkForUser($user, $role->name)
                    ];
                })
            ];
        }
        
        return view('role-wise.index', compact('roleData'));
    }
    
    /**
     * Get current work assignments for a user based on their role
     */
    private function getCurrentWorkForUser($user, $roleName)
    {
        $workQuery = Work::query();
        
        switch ($roleName) {
            case 'Surveyor':
                $workQuery->where('assignee_surveyor', $user->id)
                         ->where('status', 'Surveying');
                break;
                
            case 'Reporter':
                $workQuery->where('assignee_reporter', $user->id)
                         ->where('status', 'Reporting');
                break;
                
            case 'Checker':
                $workQuery->where('assignee_checker', $user->id)
                         ->where('status', 'Checking');
                break;
                
            case 'Delivery Person':
                $workQuery->where('assignee_delivery', $user->id)
                         ->whereIn('status', ['Completed', 'Delivery Due', 'Delivery Done']);
                break;
                
            case 'In-Charge':
                // In-Charge can see all works they created
                $workQuery->where('created_by', $user->id);
                break;
                
            default:
                return [];
        }
        
        return $workQuery->with(['surveyor', 'reporter', 'checker', 'deliveryPerson'])
                        ->orderBy('created_at', 'desc')
                        ->get()
                        ->map(function ($work) {
                            return [
                                'id' => $work->id,
                                'custom_id' => $work->custom_id,
                                'name_of_applicant' => $work->name_of_applicant,
                                'project_name' => $work->project_name,
                                'status' => $work->status,
                                'assignment_date' => $work->assignment_date,
                                'loan_amount_requested' => $work->loan_amount_requested,
                                'work_type' => $work->work_type,
                                'payment_status' => $work->payment_status,
                                'delivery_status' => $work->delivery_status,
                            ];
                        });
    }
    
    /**
     * Get work statistics for a specific role
     */
    public function getRoleStats($roleName)
    {
        $role = Role::where('name', $roleName)->first();
        
        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }
        
        $users = User::whereHas('roles', function ($query) use ($roleName) {
            $query->where('name', $roleName);
        })->get();
        
        $stats = [
            'total_users' => $users->count(),
            'users_with_work' => 0,
            'total_work_assignments' => 0,
            'work_by_status' => []
        ];
        
        foreach ($users as $user) {
            $userWork = $this->getCurrentWorkForUser($user, $roleName);
            if ($userWork->count() > 0) {
                $stats['users_with_work']++;
                $stats['total_work_assignments'] += $userWork->count();
                
                foreach ($userWork as $work) {
                    $status = $work['status'];
                    if (!isset($stats['work_by_status'][$status])) {
                        $stats['work_by_status'][$status] = 0;
                    }
                    $stats['work_by_status'][$status]++;
                }
            }
        }
        
        return response()->json($stats);
    }
}
