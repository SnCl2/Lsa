@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="max-width: 1400px;">
    <!-- Page Header & Filter -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-5">
        <div>
            <h2 class="font-weight-bolder text-dark mb-1" style="letter-spacing: -0.5px;">
                Incomplete Works
            </h2>
            <p class="text-muted mb-0">Monitor and track ongoing tasks across different age categories.</p>
        </div>
        
        <form method="GET" action="{{ route('works.incomplete') }}" class="d-flex align-items-center mt-3 mt-lg-0 p-2 bg-white rounded-pill shadow-sm" style="border: 1px solid #edf2f7;">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <div class="d-flex align-items-center px-3">
                <i class="fas fa-calendar-alt text-primary mr-2"></i>
                <input type="month" name="month" class="form-control border-0 shadow-none font-weight-bold text-secondary" style="background: transparent; outline: none; width: 150px;" value="{{ $month }}">
            </div>
            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 font-weight-bold" style="transition: transform 0.2s;">
                Apply
            </button>
        </form>
    </div>

    <!-- KPI Cards (Tabs) -->
    <div class="row mb-5">
        <!-- Recent -->
        <div class="col-md-4 mb-3 mb-md-0">
            <a href="{{ route('works.incomplete', ['month' => $month, 'tab' => 'recent', 'search' => request('search')]) }}" class="text-decoration-none block-link">
                <div class="kpi-card {{ $tab === 'recent' ? 'active-recent' : '' }}">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="kpi-subtitle text-info mb-1">< 5 DAYS</p>
                            <h4 class="kpi-title text-dark mb-0">Recent Tasks</h4>
                        </div>
                        <div class="kpi-number text-info">
                            {{ $recentCount }}
                        </div>
                    </div>
                    <div class="kpi-bar bg-info mt-3" style="width: {{ $recentCount > 0 ? '100%' : '10%' }}; opacity: 0.2;"></div>
                </div>
            </a>
        </div>
        
        <!-- Old -->
        <div class="col-md-4 mb-3 mb-md-0">
            <a href="{{ route('works.incomplete', ['month' => $month, 'tab' => 'old', 'search' => request('search')]) }}" class="text-decoration-none block-link">
                <div class="kpi-card {{ $tab === 'old' ? 'active-old' : '' }}">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="kpi-subtitle text-warning mb-1">5 - 9 DAYS</p>
                            <h4 class="kpi-title text-dark mb-0">Aging Tasks</h4>
                        </div>
                        <div class="kpi-number text-warning">
                            {{ $oldCount }}
                        </div>
                    </div>
                    <div class="kpi-bar bg-warning mt-3" style="width: {{ $oldCount > 0 ? '100%' : '10%' }}; opacity: 0.4;"></div>
                </div>
            </a>
        </div>

        <!-- Very Old -->
        <div class="col-md-4">
            <a href="{{ route('works.incomplete', ['month' => $month, 'tab' => 'very_old', 'search' => request('search')]) }}" class="text-decoration-none block-link">
                <div class="kpi-card {{ $tab === 'very_old' ? 'active-very-old' : '' }}">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="kpi-subtitle text-danger mb-1">10+ DAYS</p>
                            <h4 class="kpi-title text-dark mb-0">Critical Tasks</h4>
                        </div>
                        <div class="kpi-number text-danger">
                            {{ $veryOldCount }}
                        </div>
                    </div>
                    <div class="kpi-bar bg-danger mt-3" style="width: {{ $veryOldCount > 0 ? '100%' : '10%' }}; opacity: 0.2;"></div>
                </div>
            </a>
        </div>
    </div>

    <!-- Main Data Table -->
    <div class="card data-card border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white py-4 px-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center border-bottom border-light">
            <h5 class="mb-3 mb-md-0 font-weight-bolder text-dark">
                Task List
                @if($tab === 'recent') <span class="badge premium-badge bg-info-soft text-info ml-2">Recent</span>
                @elseif($tab === 'old') <span class="badge premium-badge bg-warning-soft text-warning ml-2">Old</span>
                @elseif($tab === 'very_old') <span class="badge premium-badge bg-danger-soft text-danger ml-2">Very Old</span>
                @endif
            </h5>
            
            <form method="GET" action="{{ route('works.incomplete') }}" class="m-0">
                <input type="hidden" name="month" value="{{ $month }}">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="search-box">
                    <i class="fas fa-search text-muted ml-3"></i>
                    <input type="text" name="search" class="form-control border-0 shadow-none pl-2 pr-3" placeholder="Search applicant, ID..." value="{{ request('search') }}">
                </div>
            </form>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table premium-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="pl-4">Age</th>
                            <th>Details</th>
                            <th>Applicant</th>
                            <th>Valuation Values</th>
                            <th>Status</th>
                            <th>Team</th>
                            <th class="pr-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($works as $work)
                            @php
                                $daysOld = max(0, intval($work->created_at->startOfDay()->diffInDays(now()->startOfDay())));
                                $badgeClass = 'bg-info-soft text-info';
                                if($daysOld >= 10) $badgeClass = 'bg-danger-soft text-danger';
                                elseif($daysOld >= 5) $badgeClass = 'bg-warning-soft text-warning';
                            @endphp
                            <tr class="table-row-animate">
                                <td class="pl-4">
                                    <span class="premium-badge {{ $badgeClass }}">
                                        {{ $daysOld }} {{ $daysOld == 1 ? 'Day' : 'Days' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="font-weight-bolder text-dark">{{ $work->custom_id ?? 'N/A' }}</div>
                                    <div class="text-muted small">
                                        <i class="far fa-calendar-alt mr-1"></i> {{ $work->created_at->format('d M, Y') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ $work->name_of_applicant }}</div>
                                    @if($work->project_name)
                                        <div class="text-muted small text-truncate" style="max-width: 200px;">{{ $work->project_name }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="small"><strong>Actual:</strong> {{ $work->actual_value !== null ? number_format($work->actual_value, 2) : '—' }}</div>
                                    <div class="small"><strong>Realised:</strong> {{ $work->realised_value !== null ? number_format($work->realised_value, 2) : '—' }}</div>
                                    <div class="small"><strong>Fair:</strong> {{ $work->fair_market_value !== null ? number_format($work->fair_market_value, 2) : '—' }}</div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column align-items-start">
                                        <span class="badge badge-light border border-secondary text-secondary mb-1 px-2 py-1">{{ $work->status }}</span>
                                        @if($work->result)
                                            <span class="badge badge-dark px-2 py-1">{{ $work->result }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="team-avatars">
                                        @if($work->surveyor)<div class="avatar" title="Surveyor: {{ $work->surveyor->name }}">{{ substr($work->surveyor->name, 0, 1) }}</div>@endif
                                        @if($work->reporter)<div class="avatar bg-primary" title="Reporter: {{ $work->reporter->name }}">{{ substr($work->reporter->name, 0, 1) }}</div>@endif
                                        @if($work->checker)<div class="avatar bg-success" title="Checker: {{ $work->checker->name }}">{{ substr($work->checker->name, 0, 1) }}</div>@endif
                                        @if(!$work->surveyor && !$work->reporter && !$work->checker)
                                            <span class="text-muted small">Unassigned</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="pr-4 text-right">
                                    <a href="{{ route('works.show', $work->id) }}" class="action-btn text-primary" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('works.edit', $work->id) }}" class="action-btn text-warning ml-2" title="Edit"><i class="fas fa-pen"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-icon bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                            <i class="fas fa-check-circle fa-2x text-success" style="opacity: 0.5;"></i>
                                        </div>
                                        <h5 class="font-weight-bold text-dark">All Caught Up!</h5>
                                        <p class="text-muted">There are no tasks pending in this category.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($works->hasPages())
        <div class="card-footer bg-white border-top border-light py-3 px-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <small class="text-muted font-weight-bold mb-3 mb-md-0">
                Showing {{ $works->firstItem() ?? 0 }} to {{ $works->lastItem() ?? 0 }} of {{ $works->total() }} entries
            </small>
            <div class="custom-pagination">
                {{ $works->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<style>
/* Premium Aesthetic Variables & Base */
:root {
    --bg-light: #eef2f6;
    --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -1px rgba(0, 0, 0, 0.04);
    --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    
    --info-soft: #e0f2fe;
    --info-text: #0369a1;
    --warning-soft: #fef3c7;
    --warning-text: #b45309;
    --danger-soft: #fee2e2;
    --danger-text: #b91c1c;
}

body {
    background-color: var(--bg-light) !important;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

.text-muted {
    color: #475569 !important;
}

/* KPI Cards */
.block-link { display: block; }
.kpi-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--card-shadow);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    border: 1px solid #d1d5db;
}
.kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--card-shadow-hover);
}
.kpi-subtitle {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.5px;
}
.kpi-title {
    font-size: 1.25rem;
    font-weight: 800;
}
.kpi-number {
    font-size: 2.5rem;
    font-weight: 900;
    line-height: 1;
}
.kpi-bar {
    height: 4px;
    border-radius: 2px;
    transition: width 1s ease-out;
}

.active-recent { border-bottom: 4px solid #0dcaf0; }
.active-old { border-bottom: 4px solid #ffc107; }
.active-very-old { border-bottom: 4px solid #dc3545; }

/* Custom Search Box */
.search-box {
    display: flex;
    align-items: center;
    background: var(--bg-light);
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    width: 250px;
    transition: all 0.2s ease;
}
.search-box:focus-within {
    border-color: #cbd5e1;
    box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    background: #ffffff;
}

/* Data Table */
.data-card { 
    border-radius: 16px; 
    border: 1px solid #d1d5db !important;
}
.premium-table { margin-bottom: 0; }
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

/* Soft Badges */
.premium-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.75rem;
}
.bg-info-soft { background-color: var(--info-soft); }
.text-info { color: var(--info-text) !important; }
.bg-warning-soft { background-color: var(--warning-soft); }
.text-warning { color: var(--warning-text) !important; }
.bg-danger-soft { background-color: var(--danger-soft); }
.text-danger { color: var(--danger-text) !important; }

/* Team Avatars */
.team-avatars { display: flex; align-items: center; }
.avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #64748b;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: bold;
    border: 2px solid white;
    margin-left: -8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
}
.avatar:first-child { margin-left: 0; }
.avatar:hover { transform: translateY(-2px); z-index: 10; }

/* Actions */
.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: #f8fafc;
    transition: all 0.2s;
}
.action-btn:hover {
    background: #e2e8f0;
    transform: scale(1.05);
}
</style>
@endsection
