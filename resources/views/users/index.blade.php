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
                                        @php $canLogin = optional($user->userRoleRelations->first())->can_login; @endphp
                                        <button
                                            class="login-toggle-btn {{ $canLogin ? 'active' : '' }}"
                                            data-user-id="{{ $user->id }}"
                                            data-url="{{ route('users.toggleLogin', $user->id) }}"
                                            title="{{ $canLogin ? 'Click to block login' : 'Click to allow login' }}"
                                        >
                                            <span class="toggle-track">
                                                <span class="toggle-thumb"></span>
                                            </span>
                                            <span class="toggle-label">{{ $canLogin ? 'Allowed' : 'Blocked' }}</span>
                                        </button>
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

/* ── Login Toggle Switch ── */
.login-toggle-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 2px 0;
}

.toggle-track {
    position: relative;
    width: 44px;
    height: 24px;
    background: #dc3545;
    border-radius: 999px;
    transition: background 0.25s;
    flex-shrink: 0;
}

.login-toggle-btn.active .toggle-track {
    background: #28a745;
}

.toggle-thumb {
    position: absolute;
    top: 3px;
    left: 3px;
    width: 18px;
    height: 18px;
    background: #fff;
    border-radius: 50%;
    transition: transform 0.25s;
    box-shadow: 0 1px 4px rgba(0,0,0,0.3);
}

.login-toggle-btn.active .toggle-thumb {
    transform: translateX(20px);
}

.toggle-label {
    font-size: 0.82rem;
    font-weight: 600;
    color: #dc3545;
    min-width: 46px;
}

.login-toggle-btn.active .toggle-label {
    color: #28a745;
}

.login-toggle-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* ── Toast ── */
#login-toast {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    min-width: 260px;
    padding: 14px 20px;
    border-radius: 10px;
    color: #fff;
    font-weight: 500;
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    opacity: 0;
    transform: translateY(16px);
    transition: opacity 0.3s, transform 0.3s;
    pointer-events: none;
}
#login-toast.show {
    opacity: 1;
    transform: translateY(0);
}
#login-toast.success { background: #28a745; }
#login-toast.danger  { background: #dc3545; }
</style>

<div id="login-toast"></div>

<script>
(function () {
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    function showToast(msg, type) {
        const t = document.getElementById('login-toast');
        t.textContent = msg;
        t.className = 'show ' + type;
        clearTimeout(t._timer);
        t._timer = setTimeout(() => { t.className = ''; }, 3000);
    }

    document.querySelectorAll('.login-toggle-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            btn.disabled = true;
            const url = btn.dataset.url;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const isActive = data.can_login;
                    btn.classList.toggle('active', isActive);
                    btn.querySelector('.toggle-label').textContent = isActive ? 'Allowed' : 'Blocked';
                    btn.title = isActive ? 'Click to block login' : 'Click to allow login';
                    showToast(data.message, isActive ? 'success' : 'danger');
                } else {
                    showToast('Something went wrong.', 'danger');
                }
            })
            .catch(() => showToast('Network error.', 'danger'))
            .finally(() => { btn.disabled = false; });
        });
    });
}());
</script>
@endsection
