@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white shadow-lg rounded-lg">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-2xl font-semibold mb-0">User Management</h2>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New User
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters & Search Card -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-filter"></i> Filters & Search
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('users.index') }}" class="filter-form">
                <!-- Search Row -->
                <div class="row mb-3">
                    <div class="col-md-8">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                            <input type="text" name="search" class="form-control form-control-lg" 
                                   placeholder="Search by Name or Email..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="btn-group w-100" role="group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Filter Grid -->
                <div class="row">
                    <!-- Role Filter -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            <i class="fas fa-user-tag text-primary"></i> Role
                        </label>
                        <select class="form-control" name="role">
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Can Login Filter -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            <i class="fas fa-sign-in-alt text-info"></i> Login Permission
                        </label>
                        <select class="form-control" name="can_login">
                            <option value="">All Users</option>
                            <option value="1" {{ request('can_login') == '1' ? 'selected' : '' }}>✅ Can Login</option>
                            <option value="0" {{ request('can_login') == '0' ? 'selected' : '' }}>❌ Cannot Login</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-users"></i> Users
                <span class="badge badge-light ml-2">{{ $users->count() }} Total</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Can Login</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($users->count() === 0)
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p class="mb-0">No users found.</p>
                                        <p class="small">Try adjusting your search or filters.</p>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</td>
                                    <td>
                                        <span class="badge {{ optional($user->userRoleRelations->first())->can_login ? 'badge-success' : 'badge-danger' }}">
                                            {{ optional($user->userRoleRelations->first())->can_login ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-2">
                                            <a href="{{ route('users.edit', $user->id) }}" style="padding: 8px 12px; background-color: #eab308; color: white; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: background-color 0.2s; display: inline-block;" onmouseover="this.style.backgroundColor='#ca8a04'" onmouseout="this.style.backgroundColor='#eab308'">
                                                Edit
                                            </a>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display: inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" style="padding: 8px 12px; background-color: #dc3545; color: white; border: none; border-radius: 6px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#c82333'" onmouseout="this.style.backgroundColor='#dc3545'">
                                                    Delete
                                                </button>
                                            </form>
                                            @if(optional($user->userRoleRelations->first())->can_login)
                                                <form action="{{ route('users.impersonate', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to login as {{ $user->name }}? You will see the system from their perspective.')" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" style="padding: 8px 12px; background-color: #9333ea; color: white; border: none; border-radius: 6px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#7e22ce'" onmouseout="this.style.backgroundColor='#9333ea'">
                                                        <i class="fas fa-user-secret"></i> Login as User
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.filter-form .form-control {
    border-radius: 0.375rem;
    border: 1px solid #ced4da;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.filter-form .form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

.form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #495057;
}
</style>
@endsection
