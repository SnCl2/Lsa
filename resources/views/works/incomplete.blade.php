@extends('layouts.app')

@section('content')
<div class="container-fluid mt-5" style="padding-left: 10px; padding-right: 10px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0 text-primary"><i class="fas fa-exclamation-circle"></i> Incomplete Works Dashboard</h1>
    </div>

    <!-- Month Filter -->
    <div class="card mb-4 shadow-sm border-0 rounded-lg">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <h5 class="mb-0 text-secondary font-weight-bold"><i class="fas fa-calendar-alt"></i> Filter by Month</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('works.incomplete') }}" class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label font-weight-bold text-muted small text-uppercase">Select Month</label>
                    <input type="month" name="month" class="form-control form-control-lg bg-light" value="{{ $month }}">
                </div>
                <!-- Preserve search if active -->
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <div class="col-md-2 mt-3 mt-md-0">
                    <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm"><i class="fas fa-filter"></i> Apply Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Age Categories (Tabs) -->
    <div class="row mb-4">
        <!-- Recent Tab -->
        <div class="col-md-4 mb-3 mb-md-0">
            <a href="{{ route('works.incomplete', ['month' => $month, 'tab' => 'recent', 'search' => request('search')]) }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 tab-card {{ $tab === 'recent' ? 'active-tab-recent shadow' : '' }}">
                    <div class="card-body text-center py-4">
                        <div class="icon-circle bg-info-light text-info mb-3 mx-auto">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <h4 class="card-title font-weight-bold text-dark mb-1">Recent</h4>
                        <p class="text-muted mb-2 small font-weight-bold text-uppercase">< 5 Days Old</p>
                        <h1 class="font-weight-bold text-info display-4 mb-0">{{ $recentCount }}</h1>
                    </div>
                </div>
            </a>
        </div>
        <!-- Old Tab -->
        <div class="col-md-4 mb-3 mb-md-0">
            <a href="{{ route('works.incomplete', ['month' => $month, 'tab' => 'old', 'search' => request('search')]) }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 tab-card {{ $tab === 'old' ? 'active-tab-old shadow' : '' }}">
                    <div class="card-body text-center py-4">
                        <div class="icon-circle bg-warning-light text-warning mb-3 mx-auto">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                        <h4 class="card-title font-weight-bold text-dark mb-1">Old</h4>
                        <p class="text-muted mb-2 small font-weight-bold text-uppercase">5 to 9 Days Old</p>
                        <h1 class="font-weight-bold text-warning display-4 mb-0">{{ $oldCount }}</h1>
                    </div>
                </div>
            </a>
        </div>
        <!-- Very Old Tab -->
        <div class="col-md-4">
            <a href="{{ route('works.incomplete', ['month' => $month, 'tab' => 'very_old', 'search' => request('search')]) }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 tab-card {{ $tab === 'very_old' ? 'active-tab-very-old shadow' : '' }}">
                    <div class="card-body text-center py-4">
                        <div class="icon-circle bg-danger-light text-danger mb-3 mx-auto">
                            <i class="fas fa-fire fa-2x"></i>
                        </div>
                        <h4 class="card-title font-weight-bold text-dark mb-1">Very Old</h4>
                        <p class="text-muted mb-2 small font-weight-bold text-uppercase">10+ Days Old</p>
                        <h1 class="font-weight-bold text-danger display-4 mb-0">{{ $veryOldCount }}</h1>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header bg-white py-4 d-flex flex-column flex-md-row justify-content-between align-items-center border-bottom">
            <h5 class="mb-3 mb-md-0 font-weight-bold text-dark">
                <i class="fas fa-list-ul text-primary mr-2"></i> 
                Incomplete Works
                @if($tab === 'recent') <span class="badge badge-info ml-2">Recent (< 5 Days)</span>
                @elseif($tab === 'old') <span class="badge badge-warning text-dark ml-2">Old (5-9 Days)</span>
                @elseif($tab === 'very_old') <span class="badge badge-danger ml-2">Very Old (10+ Days)</span>
                @endif
            </h5>
            
            <form method="GET" action="{{ route('works.incomplete') }}" class="form-inline w-100 w-md-auto">
                <input type="hidden" name="month" value="{{ $month }}">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="input-group w-100">
                    <input type="text" name="search" class="form-control bg-light border-0" placeholder="Search applicant, ID..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary px-4" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="pl-4">Age</th>
                            <th>Created Date</th>
                            <th>Custom ID</th>
                            <th>Applicant</th>
                            <th>Current Status</th>
                            <th>Assigned Users</th>
                            <th class="pr-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($works as $work)
                            @php
                                $daysOld = now()->diffInDays($work->created_at);
                                $badgeClass = 'badge-info';
                                if($daysOld >= 10) $badgeClass = 'badge-danger';
                                elseif($daysOld >= 5) $badgeClass = 'badge-warning text-dark';
                            @endphp
                            <tr>
                                <td class="pl-4">
                                    <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.85rem;">
                                        {{ $daysOld }} {{ $daysOld == 1 ? 'Day' : 'Days' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ $work->created_at->format('d M, Y') }}</div>
                                    <div class="small text-muted">{{ $work->created_at->format('h:i A') }}</div>
                                </td>
                                <td>
                                    <span class="font-weight-bold text-primary">{{ $work->custom_id ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ $work->name_of_applicant }}</div>
                                    @if($work->project_name)
                                        <div class="small text-muted text-truncate" style="max-width: 150px;">{{ $work->project_name }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-secondary px-2 py-1">{{ $work->status }}</span>
                                    @if($work->result)
                                        <span class="badge badge-dark px-2 py-1 ml-1">{{ $work->result }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small">
                                        <span class="text-muted">S:</span> <span class="font-weight-bold">{{ $work->surveyor?->name ?? '-' }}</span><br>
                                        <span class="text-muted">R:</span> <span class="font-weight-bold">{{ $work->reporter?->name ?? '-' }}</span><br>
                                        <span class="text-muted">C:</span> <span class="font-weight-bold">{{ $work->checker?->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="pr-4 text-center">
                                    <div class="btn-group shadow-sm" role="group">
                                        <a href="{{ route('works.show', $work->id) }}" class="btn btn-sm btn-light text-primary border" title="View"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('works.edit', $work->id) }}" class="btn btn-sm btn-light text-warning border" title="Edit"><i class="fas fa-edit"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted py-4">
                                        <div class="icon-circle bg-light text-secondary mx-auto mb-3" style="width: 80px; height: 80px;">
                                            <i class="fas fa-inbox fa-3x"></i>
                                        </div>
                                        <h5 class="font-weight-bold">No works found</h5>
                                        <p>There are no incomplete works in this category for the selected month.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($works->hasPages())
        <div class="card-footer bg-white border-top py-3 px-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <small class="text-muted font-weight-bold mb-3 mb-md-0">
                    Showing {{ $works->firstItem() ?? 0 }} to {{ $works->lastItem() ?? 0 }} of {{ $works->total() }} entries
                </small>
                <div>
                    {{ $works->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
/* Dashboard Styles */
.rounded-lg { border-radius: 0.75rem !important; }
.rounded-pill { border-radius: 50rem !important; }

.tab-card {
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    cursor: pointer;
    border-radius: 12px;
    background-color: #f8f9fa;
}
.tab-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    background-color: #ffffff;
}

.active-tab-recent {
    background-color: #ffffff;
    border-bottom: 4px solid #17a2b8 !important;
}
.active-tab-old {
    background-color: #ffffff;
    border-bottom: 4px solid #ffc107 !important;
}
.active-tab-very-old {
    background-color: #ffffff;
    border-bottom: 4px solid #dc3545 !important;
}

.icon-circle {
    width: 65px;
    height: 65px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.bg-info-light { background-color: rgba(23, 162, 184, 0.1); }
.bg-warning-light { background-color: rgba(255, 193, 7, 0.15); }
.bg-danger-light { background-color: rgba(220, 53, 69, 0.1); }

.table th { 
    border-top: none; 
    letter-spacing: 0.5px;
    font-weight: 600;
}
.table td {
    vertical-align: middle;
}
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.03);
}

.btn-light {
    background-color: #f8f9fa;
}
.btn-light:hover {
    background-color: #e2e6ea;
}
</style>
@endsection
