@extends('layouts.app')

@section('content')
<div class="container-fluid mt-5" style="padding-left: 10px; padding-right: 10px;">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 mt-2">
        <h3 class="mb-0 text-primary"><i class="fas fa-exclamation-circle"></i> Incomplete Works</h3>
        
        <form method="GET" action="{{ route('works.incomplete') }}" class="form-inline mt-2 mt-md-0">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <div class="input-group input-group-sm shadow-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-light font-weight-bold border-right-0"><i class="fas fa-calendar-alt mr-2"></i> Month</span>
                </div>
                <input type="month" name="month" class="form-control border-left-0" value="{{ $month }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Age Categories (Tabs) -->
    <div class="row mb-4">
        <!-- Recent Tab -->
        <div class="col-md-4 mb-3 mb-md-0">
            <a href="{{ route('works.incomplete', ['month' => $month, 'tab' => 'recent', 'search' => request('search')]) }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 tab-card {{ $tab === 'recent' ? 'active-tab-recent shadow' : '' }}">
                    <div class="card-body d-flex align-items-center justify-content-between py-2 px-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-info-light text-info mr-3 shadow-sm" style="width: 45px; height: 45px;">
                                <i class="fas fa-clock fa-lg"></i>
                            </div>
                            <div class="text-left">
                                <h6 class="card-title font-weight-bold text-dark mb-0">Recent</h6>
                                <small class="text-muted font-weight-bold text-uppercase" style="font-size: 0.7rem;">< 5 Days</small>
                            </div>
                        </div>
                        <h3 class="font-weight-bold text-info mb-0">{{ $recentCount }}</h3>
                    </div>
                </div>
            </a>
        </div>
        <!-- Old Tab -->
        <div class="col-md-4 mb-3 mb-md-0">
            <a href="{{ route('works.incomplete', ['month' => $month, 'tab' => 'old', 'search' => request('search')]) }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 tab-card {{ $tab === 'old' ? 'active-tab-old shadow' : '' }}">
                    <div class="card-body d-flex align-items-center justify-content-between py-2 px-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-warning-light text-warning mr-3 shadow-sm" style="width: 45px; height: 45px;">
                                <i class="fas fa-exclamation-triangle fa-lg"></i>
                            </div>
                            <div class="text-left">
                                <h6 class="card-title font-weight-bold text-dark mb-0">Old</h6>
                                <small class="text-muted font-weight-bold text-uppercase" style="font-size: 0.7rem;">5 - 9 Days</small>
                            </div>
                        </div>
                        <h3 class="font-weight-bold text-warning mb-0">{{ $oldCount }}</h3>
                    </div>
                </div>
            </a>
        </div>
        <!-- Very Old Tab -->
        <div class="col-md-4">
            <a href="{{ route('works.incomplete', ['month' => $month, 'tab' => 'very_old', 'search' => request('search')]) }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 tab-card {{ $tab === 'very_old' ? 'active-tab-very-old shadow' : '' }}">
                    <div class="card-body d-flex align-items-center justify-content-between py-2 px-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-danger-light text-danger mr-3 shadow-sm" style="width: 45px; height: 45px;">
                                <i class="fas fa-fire fa-lg"></i>
                            </div>
                            <div class="text-left">
                                <h6 class="card-title font-weight-bold text-dark mb-0">Very Old</h6>
                                <small class="text-muted font-weight-bold text-uppercase" style="font-size: 0.7rem;">10+ Days</small>
                            </div>
                        </div>
                        <h3 class="font-weight-bold text-danger mb-0">{{ $veryOldCount }}</h3>
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
                <div class="input-group input-group-sm w-100">
                    <input type="text" name="search" class="form-control bg-light border-0" placeholder="Search applicant, ID..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary px-3" type="submit"><i class="fas fa-search"></i></button>
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
                                $daysOld = max(0, intval($work->created_at->startOfDay()->diffInDays(now()->startOfDay())));
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
