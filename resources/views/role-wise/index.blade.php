@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">
                        <i class="fas fa-users"></i> Role-wise User Management
                    </h2>
                    <p class="mb-0">Manage users and their current work assignments by role</p>
                </div>
                <div class="card-body p-0">
                    <!-- Role Tabs -->
                    <ul class="nav nav-tabs" id="roleTabs" role="tablist">
                        @foreach($roleData as $roleName => $data)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                    id="{{ strtolower(str_replace(' ', '-', $roleName)) }}-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#{{ strtolower(str_replace(' ', '-', $roleName)) }}" 
                                    type="button" 
                                    role="tab">
                                <i class="fas fa-user-tag"></i> {{ $roleName }}
                                <span class="badge bg-secondary ms-2">{{ $data['users']->count() }}</span>
                            </button>
                        </li>
                        @endforeach
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="roleTabsContent">
                        @foreach($roleData as $roleName => $data)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                             id="{{ strtolower(str_replace(' ', '-', $roleName)) }}" 
                             role="tabpanel">
                            
                            <!-- Role Statistics -->
                            <div class="row p-3 bg-light">
                                <div class="col-md-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary">{{ $data['users']->count() }}</h5>
                                            <p class="card-text">Total Users</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h5 class="card-title text-success">{{ $data['users']->where('current_work', '!=', [])->count() }}</h5>
                                            <p class="card-text">Users with Work</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h5 class="card-title text-info">{{ $data['users']->sum(function($user) { return count($user['current_work']); }) }}</h5>
                                            <p class="card-text">Total Assignments</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h5 class="card-title text-warning">{{ $data['users']->where('current_work', '!=', [])->count() > 0 ? round(($data['users']->where('current_work', '!=', [])->count() / $data['users']->count()) * 100, 1) : 0 }}%</h5>
                                            <p class="card-text">Active Rate</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Users List -->
                            <div class="p-3">
                                @if($data['users']->count() > 0)
                                    <div class="row">
                                        @foreach($data['users'] as $user)
                                        <div class="col-lg-6 col-xl-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0">
                                                        <i class="fas fa-user"></i> {{ $user['name'] }}
                                                    </h6>
                                                    <span class="badge bg-{{ count($user['current_work']) > 0 ? 'success' : 'secondary' }}">
                                                        {{ count($user['current_work']) }} Active
                                                    </span>
                                                </div>
                                                <div class="card-body">
                                                    <p class="text-muted mb-2">
                                                        <i class="fas fa-envelope"></i> {{ $user['email'] }}
                                                    </p>
                                                    
                                                    @if(count($user['current_work']) > 0)
                                                        <h6 class="text-primary mb-3">Current Work Assignments:</h6>
                                                        <div class="work-assignments" style="max-height: 300px; overflow-y: auto;">
                                                            @foreach($user['current_work'] as $work)
                                                            <div class="work-item border rounded p-2 mb-2">
                                                                <div class="d-flex justify-content-between align-items-start">
                                                                    <div class="flex-grow-1">
                                                                        <h6 class="mb-1 text-dark">{{ $work['custom_id'] ?? 'N/A' }}</h6>
                                                                        <p class="mb-1 text-muted small">{{ $work['name_of_applicant'] }}</p>
                                                                        <p class="mb-1 text-muted small">{{ $work['project_name'] }}</p>
                                                                        @if($work['loan_amount_requested'])
                                                                            <p class="mb-1 text-success small">
                                                                                <i class="fas fa-rupee-sign"></i> {{ number_format((float)$work['loan_amount_requested']) }}
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                    <div class="text-end">
                                                                        <span class="badge bg-{{ $work['status'] == 'Surveying' ? 'warning' : ($work['status'] == 'Reporting' ? 'info' : ($work['status'] == 'Checking' ? 'primary' : 'success')) }}">
                                                                            {{ $work['status'] }}
                                                                        </span>
                                                                        @if($work['assignment_date'])
                                                                            <p class="mb-0 text-muted small">{{ \Carbon\Carbon::parse($work['assignment_date'])->format('d M Y') }}</p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="mt-2">
                                                                    <div class="row text-center">
                                                                        <div class="col-4">
                                                                            <small class="text-muted">Type</small><br>
                                                                            <span class="badge bg-light text-dark">{{ $work['work_type'] }}</span>
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <small class="text-muted">Payment</small><br>
                                                                            <span class="badge bg-{{ $work['payment_status'] == 'Paid' ? 'success' : 'warning' }}">
                                                                                {{ $work['payment_status'] }}
                                                                            </span>
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <small class="text-muted">Delivery</small><br>
                                                                            <span class="badge bg-{{ $work['delivery_status'] == 'Delivery Done' ? 'success' : 'warning' }}">
                                                                                {{ $work['delivery_status'] }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="text-center text-muted py-3">
                                                            <i class="fas fa-inbox fa-2x mb-2"></i>
                                                            <p class="mb-0">No current work assignments</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No users found for this role</h5>
                                        <p class="text-muted">Users will appear here once they are assigned to this role.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
.nav-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    color: #6c757d;
    font-weight: 500;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link:hover {
    border-color: #dee2e6;
    color: #495057;
}

.nav-tabs .nav-link.active {
    border-color: #007bff;
    color: #007bff;
    background-color: transparent;
}

.work-item {
    background-color: #f8f9fa;
    transition: all 0.2s ease;
}

.work-item:hover {
    background-color: #e9ecef;
    transform: translateY(-1px);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.badge {
    font-size: 0.75em;
}

.work-assignments::-webkit-scrollbar {
    width: 6px;
}

.work-assignments::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.work-assignments::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.work-assignments::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>

<script>
// Auto-refresh data every 5 minutes
setInterval(function() {
    // You can add AJAX call here to refresh data
    console.log('Auto-refresh triggered');
}, 300000);

// Add click handlers for better UX
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling for work assignments
    const workAssignments = document.querySelectorAll('.work-assignments');
    workAssignments.forEach(function(element) {
        if (element.scrollHeight > element.clientHeight) {
            element.style.border = '1px solid #dee2e6';
        }
    });
});
</script>
@endsection
