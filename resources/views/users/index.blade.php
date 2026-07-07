@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="max-width: 1400px;">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5">
        <div>
            <h2 class="font-weight-bolder text-dark mb-1" style="letter-spacing: -0.5px;">User Management</h2>
            <p class="text-muted mb-0">Manage system access, roles, and user profiles.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('users.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm font-weight-bold">
                <i class="fas fa-plus mr-1"></i> Add New User
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-lg mb-4 d-flex align-items-center">
            <i class="fas fa-check-circle fa-lg mr-3"></i>
            <span class="font-weight-bold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Filters & Search -->
    <div class="card premium-card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('users.index') }}" class="row align-items-end">
                
                <!-- Search -->
                <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
                    <label class="form-label text-uppercase text-muted font-weight-bold small mb-2">Search Users</label>
                    <div class="input-group input-group-sm premium-input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" name="search" class="form-control border-left-0 bg-light" 
                               placeholder="Search by name or email..." 
                               value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Role Filter -->
                <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                    <label class="form-label text-uppercase text-muted font-weight-bold small mb-2">Filter by Role</label>
                    <select class="form-control form-control-sm premium-select bg-light" name="role">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Login Permission Filter -->
                <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                    <label class="form-label text-uppercase text-muted font-weight-bold small mb-2">Login Access</label>
                    <select class="form-control form-control-sm premium-select bg-light" name="can_login">
                        <option value="">All Statuses</option>
                        <option value="1" {{ request('can_login') == '1' ? 'selected' : '' }}>Allowed</option>
                        <option value="0" {{ request('can_login') == '0' ? 'selected' : '' }}>Blocked</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="col-lg-2 col-md-6">
                    <div class="d-flex w-100 gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1 font-weight-bold">
                            Filter
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-light btn-sm flex-grow-1 font-weight-bold border ml-2 text-muted" title="Reset Filters">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card premium-card border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white py-4 px-4 d-flex justify-content-between align-items-center border-bottom border-light">
            <h5 class="mb-0 font-weight-bolder text-dark">
                User Directory
                <span class="badge premium-badge bg-primary-soft text-primary ml-2">{{ $users->count() }} Total</span>
            </h5>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table premium-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="pl-4">User</th>
                            <th>Role(s)</th>
                            <th class="text-center">Login Access</th>
                            <th class="pr-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($users->count() === 0)
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-icon bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                            <i class="fas fa-users-slash fa-2x text-muted" style="opacity: 0.5;"></i>
                                        </div>
                                        <h5 class="font-weight-bold text-dark">No users found</h5>
                                        <p class="text-muted">Try adjusting your search criteria or add a new user.</p>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach($users as $user)
                                <tr class="table-row-animate">
                                    <td class="pl-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary text-white mr-3">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-weight-bolder text-dark">{{ $user->name }}</div>
                                                <div class="small text-muted">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($user->roles as $role)
                                                <span class="badge premium-badge bg-secondary-soft text-secondary mr-1">{{ $role->name }}</span>
                                            @endforeach
                                            @if($user->roles->isEmpty())
                                                <span class="text-muted small">No roles assigned</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @php $canLogin = optional($user->userRoleRelations->first())->can_login; @endphp
                                        <button
                                            class="login-toggle-btn {{ $canLogin ? 'active' : '' }}"
                                            data-user-id="{{ $user->id }}"
                                            data-url="{{ route('users.toggleLogin', $user->id) }}"
                                            title="{{ $canLogin ? 'Block login access' : 'Allow login access' }}"
                                        >
                                            <span class="toggle-track shadow-sm">
                                                <span class="toggle-thumb"></span>
                                            </span>
                                            <span class="toggle-label {{ $canLogin ? 'text-success' : 'text-danger' }}">{{ $canLogin ? 'Allowed' : 'Blocked' }}</span>
                                        </button>
                                    </td>
                                    <td class="pr-4 text-right">
                                        <div class="d-inline-flex align-items-center gap-2">
                                            @if($canLogin)
                                                <form action="{{ route('users.impersonate', $user->id) }}" method="POST" class="m-0 d-inline" onsubmit="return confirm('Login as {{ $user->name }}?');">
                                                    @csrf
                                                    <button type="submit" class="action-btn text-purple bg-purple-soft ml-1" title="Impersonate User">
                                                        <i class="fas fa-user-secret"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('users.edit', $user->id) }}" class="action-btn text-warning bg-warning-soft ml-1" title="Edit">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="m-0 d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="action-btn text-danger bg-danger-soft ml-1" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
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

<div id="login-toast"></div>

<style>
/* Premium Aesthetic Variables & Base */
:root {
    --bg-light: #eef2f6;
    --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -1px rgba(0, 0, 0, 0.04);
}

body {
    background-color: var(--bg-light) !important;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

.text-muted { color: #475569 !important; }

/* Cards */
.premium-card {
    border-radius: 16px;
    border: 1px solid #d1d5db !important;
}

/* Inputs & Filters */
.premium-input-group .input-group-text { border-color: #d1d5db; }
.premium-input-group .form-control { border-color: #d1d5db; box-shadow: none; }
.premium-input-group .form-control:focus { border-color: #cbd5e1; background: #fff !important; }

.premium-select { border-color: #d1d5db; box-shadow: none; border-radius: 6px; height: calc(1.5em + .75rem + 2px); }
.premium-select:focus { border-color: #cbd5e1; background: #fff !important; }

/* Data Table */
.premium-table th {
    text-transform: uppercase;
    font-size: 0.75rem;
    font-weight: 800;
    letter-spacing: 0.05em;
    color: #334155;
    background-color: #f1f5f9;
    border-top: none;
    border-bottom: 2px solid #cbd5e1;
    padding: 16px 12px;
}
.premium-table td {
    padding: 16px 12px;
    vertical-align: middle;
    border-bottom: 1px solid #e2e8f0;
    color: #1e293b;
    font-weight: 500;
}
.table-row-animate { transition: background-color 0.2s ease; }
.table-row-animate:hover { background-color: #f8fafc; }

/* Badges & Colors */
.premium-badge { padding: 6px 12px; border-radius: 20px; font-weight: 700; font-size: 0.75rem; }
.bg-primary-soft { background-color: #cfe2ff; }
.text-primary { color: #084298 !important; }
.bg-secondary-soft { background-color: #e2e3e5; }
.text-secondary { color: #41464b !important; }

.bg-warning-soft { background-color: #fef3c7; }
.text-warning { color: #b45309 !important; }

.bg-danger-soft { background-color: #fee2e2; }
.text-danger { color: #b91c1c !important; }

.bg-purple-soft { background-color: #f3e8ff; }
.text-purple { color: #7e22ce !important; }

/* Avatars */
.avatar-circle {
    width: 40px; height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center; justify-content: center;
    font-weight: bold; font-size: 1.1rem;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* Actions */
.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
    transition: all 0.2s;
    cursor: pointer;
}
.action-btn:hover { transform: scale(1.05); filter: brightness(0.95); }

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
    background: #e2e8f0;
    border-radius: 999px;
    transition: background 0.25s;
    flex-shrink: 0;
    border: 1px solid #cbd5e1;
}
.login-toggle-btn.active .toggle-track {
    background: #22c55e;
    border-color: #16a34a;
}
.toggle-thumb {
    position: absolute;
    top: 2px; left: 2px;
    width: 18px; height: 18px;
    background: #fff;
    border-radius: 50%;
    transition: transform 0.25s;
    box-shadow: 0 1px 4px rgba(0,0,0,0.3);
}
.login-toggle-btn.active .toggle-thumb {
    transform: translateX(20px);
}
.toggle-label {
    font-size: 0.8rem;
    font-weight: 700;
    min-width: 50px;
    text-align: left;
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
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    pointer-events: none;
}
#login-toast.show { opacity: 1; transform: translateY(0); }
#login-toast.success { background: #22c55e; border: 1px solid #16a34a; }
#login-toast.danger  { background: #ef4444; border: 1px solid #dc2626; }
</style>

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
                    const label = btn.querySelector('.toggle-label');
                    label.textContent = isActive ? 'Allowed' : 'Blocked';
                    label.className = 'toggle-label ' + (isActive ? 'text-success' : 'text-danger');
                    btn.title = isActive ? 'Block login access' : 'Allow login access';
                    
                    // Show or hide the impersonate button dynamically based on login access
                    const tr = btn.closest('tr');
                    const impersonateForm = tr.querySelector('form[action*="impersonate"]');
                    if (isActive) {
                        if (!impersonateForm) {
                            // The impersonate form doesn't exist, this requires page refresh to fully rebuild properly with CSRF,
                            // or we can just rely on normal behaviour. A refresh is best for security tokens.
                            showToast('Login enabled. Refresh page to impersonate.', 'success');
                        } else {
                            impersonateForm.style.display = 'inline';
                            showToast(data.message, 'success');
                        }
                    } else {
                        if (impersonateForm) impersonateForm.style.display = 'none';
                        showToast(data.message, 'danger');
                    }
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
