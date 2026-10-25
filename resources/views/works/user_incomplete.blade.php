@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="max-width: 1400px;">
    <!-- Page Header & Action Controls -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4">
        <div>
            <div class="d-flex align-items-center mb-1">
                <h2 class="font-weight-bolder text-dark mb-0 mr-3" style="letter-spacing: -0.5px;">
                    User Incomplete Works
                </h2>
                <a href="{{ route('works.incomplete') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 shadow-sm font-weight-bold">
                    <i class="fas fa-arrow-left mr-1"></i> General Incomplete Works
                </a>
            </div>
            <p class="text-muted mb-0">
                Track incomplete works by staff member & role with urgency priority (critical tasks on top, recent at the bottom).
            </p>
        </div>
        
        <div class="d-flex align-items-center flex-wrap mt-3 mt-lg-0" style="gap: 10px;">
            @if(auth()->user() && (auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin')))
            <button type="button" class="btn btn-success rounded-pill px-4 py-2 font-weight-bold shadow-sm" id="export-user-csv-btn">
                <i class="fas fa-file-csv mr-1"></i> Export CSV
            </button>
            @endif
        </div>
    </div>

    <!-- Filter & User Selector Card -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; border: 1px solid #e2e8f0 !important;">
        <div class="card-body p-4 bg-white" style="border-radius: 16px;">
            <form method="GET" action="{{ route('works.user-incomplete') }}" id="userIncompleteForm" class="m-0">
                @if(request('tab'))
                    <input type="hidden" name="tab" value="{{ request('tab') }}">
                @endif
                <div class="row align-items-end" style="gap: 0;">
                    <!-- Role Filter -->
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label for="role" class="small font-weight-bold text-uppercase text-muted mb-1">
                            <i class="fas fa-user-tag text-primary mr-1"></i> Staff Role
                        </label>
                        <select name="role" id="role_filter" class="form-control select2-search">
                            <option value="">All Roles</option>
                            @foreach($roles as $r)
                                <option value="{{ $r }}" {{ $selectedRole === $r ? 'selected' : '' }}>{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- User Selector -->
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label for="user_id" class="small font-weight-bold text-uppercase text-muted mb-1">
                            <i class="fas fa-user text-primary mr-1"></i> Staff User <span class="text-danger">*</span>
                        </label>
                        <select name="user_id" id="user_filter" class="form-control select2-search" data-placeholder="Select staff user...">
                            <option value="">-- Select Staff User --</option>
                            @foreach($allUsers as $u)
                                @php
                                    $userRoleTitles = $u->roles->pluck('name')->implode(', ');
                                @endphp
                                <option value="{{ $u->id }}" 
                                    data-roles="{{ $u->roles->pluck('name')->toJson() }}"
                                    {{ (string)$selectedUserId === (string)$u->id ? 'selected' : '' }}>
                                    {{ $u->name }} ({{ $userRoleTitles }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Month Filter -->
                    <div class="col-md-2 mb-3 mb-md-0">
                        <label for="month" class="small font-weight-bold text-uppercase text-muted mb-1">
                            <i class="far fa-calendar-alt text-primary mr-1"></i> Month (Optional)
                        </label>
                        <input type="month" name="month" id="month_filter" class="form-control font-weight-bold" value="{{ $month }}" placeholder="All Months">
                    </div>

                    <!-- Sort Order: "resent will be in bottom" -->
                    <div class="col-md-2 mb-3 mb-md-0">
                        <label for="sort" class="small font-weight-bold text-uppercase text-muted mb-1">
                            <i class="fas fa-sort-amount-down-alt text-primary mr-1"></i> Priority Order
                        </label>
                        <select name="sort" id="sort_filter" class="form-control font-weight-bold">
                            <option value="oldest_first" {{ $sort === 'oldest_first' ? 'selected' : '' }}>Oldest First (Recent at Bottom)</option>
                            <option value="newest_first" {{ $sort === 'newest_first' ? 'selected' : '' }}>Newest First</option>
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="col-md-1 d-flex align-items-center" style="gap: 6px;">
                        <button type="submit" class="btn btn-primary btn-block rounded-pill py-2 font-weight-bold shadow-sm" title="Apply Filter">
                            Apply
                        </button>
                        <a href="{{ route('works.user-incomplete') }}" class="btn btn-outline-secondary rounded-pill py-2 px-3 font-weight-bold shadow-sm" title="Reset Filters">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($selectedUser)
        <!-- Selected User Info Banner -->
        <div class="alert alert-light border shadow-sm mb-4 p-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center" style="border-radius: 14px; background: #ffffff;">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <div class="rounded-circle bg-primary text-white font-weight-bold d-flex align-items-center justify-content-center mr-3 shadow-sm" style="width: 48px; height: 48px; font-size: 1.25rem;">
                    {{ strtoupper(substr($selectedUser->name, 0, 1)) }}
                </div>
                <div>
                    <h5 class="font-weight-bold text-dark mb-0">{{ $selectedUser->name }}</h5>
                    <span class="text-muted small">
                        <i class="fas fa-envelope mr-1"></i> {{ $selectedUser->email }}
                        <span class="mx-2">•</span>
                        <i class="fas fa-id-badge mr-1"></i> Roles: 
                        @foreach($selectedUser->roles as $ur)
                            <span class="badge badge-light border text-primary font-weight-bold mr-1">{{ $ur->name }}</span>
                        @endforeach
                        @if($selectedRole)
                            <span class="badge badge-info px-2 py-1 font-weight-bold">Filtered Role: {{ $selectedRole }}</span>
                        @endif
                    </span>
                </div>
            </div>
            <div>
                <span class="badge badge-secondary px-3 py-2 font-weight-bold" style="font-size: 0.85rem;">
                    <i class="fas fa-clock mr-1"></i> Sort: {{ $sort === 'oldest_first' ? 'Critical/Oldest on top, Recent at bottom' : 'Newest first' }}
                </span>
            </div>
        </div>

        <!-- KPI Cards (Ordered: Critical First -> Aging -> Recent at Right/Bottom) -->
        <div class="row mb-4">
            <!-- Critical Tasks (10+ Days) - Top Priority -->
            <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                <a href="{{ route('works.user-incomplete', array_merge(request()->query(), ['tab' => 'very_old'])) }}" class="text-decoration-none block-link">
                    <div class="kpi-card {{ $tab === 'very_old' ? 'active-very-old' : '' }}">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="kpi-subtitle text-danger mb-1">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> 10+ DAYS
                                </p>
                                <h4 class="kpi-title text-dark mb-0">Critical Tasks</h4>
                                <small class="text-muted">Top urgency</small>
                            </div>
                            <div class="kpi-number text-danger">
                                {{ $veryOldCount }}
                            </div>
                        </div>
                        <div class="kpi-bar bg-danger mt-3" style="width: {{ $veryOldCount > 0 ? '100%' : '10%' }}; opacity: 0.3;"></div>
                    </div>
                </a>
            </div>

            <!-- Aging Tasks (5 - 9 Days) -->
            <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                <a href="{{ route('works.user-incomplete', array_merge(request()->query(), ['tab' => 'old'])) }}" class="text-decoration-none block-link">
                    <div class="kpi-card {{ $tab === 'old' ? 'active-old' : '' }}">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="kpi-subtitle text-warning mb-1">
                                    <i class="fas fa-hourglass-half mr-1"></i> 5 - 9 DAYS
                                </p>
                                <h4 class="kpi-title text-dark mb-0">Aging Tasks</h4>
                                <small class="text-muted">Moderate delay</small>
                            </div>
                            <div class="kpi-number text-warning">
                                {{ $oldCount }}
                            </div>
                        </div>
                        <div class="kpi-bar bg-warning mt-3" style="width: {{ $oldCount > 0 ? '100%' : '10%' }}; opacity: 0.4;"></div>
                    </div>
                </a>
            </div>

            <!-- Recent Tasks (< 5 Days) - Positioned at end/bottom of urgency hierarchy -->
            <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                <a href="{{ route('works.user-incomplete', array_merge(request()->query(), ['tab' => 'recent'])) }}" class="text-decoration-none block-link">
                    <div class="kpi-card {{ $tab === 'recent' ? 'active-recent' : '' }}">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="kpi-subtitle text-info mb-1">
                                    <i class="fas fa-calendar-check mr-1"></i> &lt; 5 DAYS
                                </p>
                                <h4 class="kpi-title text-dark mb-0">Recent Tasks</h4>
                                <small class="text-muted">Fresh assignments</small>
                            </div>
                            <div class="kpi-number text-info">
                                {{ $recentCount }}
                            </div>
                        </div>
                        <div class="kpi-bar bg-info mt-3" style="width: {{ $recentCount > 0 ? '100%' : '10%' }}; opacity: 0.25;"></div>
                    </div>
                </a>
            </div>

            <!-- Total Incomplete Tasks Card -->
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('works.user-incomplete', array_merge(request()->query(), ['tab' => 'all'])) }}" class="text-decoration-none block-link">
                    <div class="kpi-card {{ $tab === 'all' ? 'active-total' : '' }}">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="kpi-subtitle text-primary mb-1">
                                    <i class="fas fa-tasks mr-1"></i> ALL ACTIVE
                                </p>
                                <h4 class="kpi-title text-dark mb-0">Total Incomplete</h4>
                                <small class="text-muted">
                                    @if($holdCount > 0)
                                        <span class="text-danger font-weight-bold">{{ $holdCount }} On Hold</span>
                                    @else
                                        Active ongoing
                                    @endif
                                </small>
                            </div>
                            <div class="kpi-number text-primary">
                                {{ $totalCount }}
                            </div>
                        </div>
                        <div class="kpi-bar bg-primary mt-3" style="width: {{ $totalCount > 0 ? '100%' : '10%' }}; opacity: 0.25;"></div>
                    </div>
                </a>
            </div>
        </div>

        @if(!empty($stageBreakdown))
        <!-- Stage Breakdown KPI Bar -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px; border: 1px solid #e2e8f0 !important;">
            <div class="card-body p-3 bg-white" style="border-radius: 14px;">
                <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px;">
                    <div class="font-weight-bold text-dark small text-uppercase">
                        <i class="fas fa-layer-group text-primary mr-1"></i> Workflow Stage Breakdown:
                    </div>
                    <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
                        @foreach($stageBreakdown as $stgName => $stgCount)
                            <span class="badge badge-light border px-3 py-2 font-weight-bold" style="font-size: 0.8rem;">
                                {{ $stgName }}: <span class="text-primary font-weight-bolder ml-1">{{ $stgCount }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Main Task List Data Table -->
        <div class="card data-card border-0 shadow-sm overflow-hidden mb-5">
            <div class="card-header bg-white py-4 px-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center border-bottom border-light">
                <div class="d-flex align-items-center flex-wrap mb-2 mb-md-0">
                    <h5 class="mb-0 font-weight-bolder text-dark mr-3">
                        Task List
                    </h5>
                    @if($tab === 'very_old')
                        <span class="badge premium-badge bg-danger-soft text-danger">Critical (10+ Days)</span>
                    @elseif($tab === 'old')
                        <span class="badge premium-badge bg-warning-soft text-warning">Aging (5 - 9 Days)</span>
                    @elseif($tab === 'recent')
                        <span class="badge premium-badge bg-info-soft text-info">Recent (&lt; 5 Days)</span>
                    @else
                        <span class="badge premium-badge bg-primary-soft text-primary">All Incomplete Tasks</span>
                    @endif

                    @if($sort === 'oldest_first')
                        <span class="badge badge-light border text-muted ml-2 small" title="Critical/Oldest tasks on top, recent tasks at the bottom">
                            <i class="fas fa-arrow-down mr-1 text-danger"></i> Recent at bottom
                        </span>
                    @endif
                </div>
                
                <form method="GET" action="{{ route('works.user-incomplete') }}" class="m-0">
                    <input type="hidden" name="role" value="{{ $selectedRole }}">
                    <input type="hidden" name="user_id" value="{{ $selectedUserId }}">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <input type="hidden" name="sort" value="{{ $sort }}">
                    @if($month)
                        <input type="hidden" name="month" value="{{ $month }}">
                    @endif
                    <div class="search-box">
                        <i class="fas fa-search text-muted ml-3"></i>
                        <input type="text" name="search" class="form-control border-0 shadow-none pl-2 pr-3" placeholder="Search applicant, ID, project..." value="{{ request('search') }}">
                    </div>
                </form>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table premium-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="pl-4">Age / Urgency</th>
                                <th>Ref & Date</th>
                                <th>Applicant & Project</th>
                                <th>Bank / Branch</th>
                                <th>Valuation Values</th>
                                <th>Stage & Status</th>
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
                                            @if($daysOld >= 10)
                                                <i class="fas fa-fire mr-1"></i>
                                            @endif
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
                                            <div class="text-muted small text-truncate" style="max-width: 200px;">
                                                <i class="fas fa-building mr-1 text-secondary"></i>{{ $work->project_name }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ $work->bank_name ?? 'N/A' }}</div>
                                        @if($work->bankBranch)
                                            <div class="text-muted small">{{ $work->bankBranch->name }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small"><strong>FMV:</strong> {{ $work->actual_value !== null ? '₹' . number_format($work->actual_value, 2) : '—' }}</div>
                                        <div class="small"><strong>RV:</strong> {{ $work->realised_value !== null ? '₹' . number_format($work->realised_value, 2) : '—' }}</div>
                                        <div class="small"><strong>DV:</strong> {{ $work->fair_market_value !== null ? '₹' . number_format($work->fair_market_value, 2) : '—' }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column align-items-start">
                                            <span class="badge badge-light border border-secondary text-secondary mb-1 px-2 py-1">{{ $work->status }}</span>
                                            @if($work->is_hold)
                                                <span class="badge badge-danger px-2 py-1 mb-1"><i class="fas fa-pause mr-1"></i>On Hold</span>
                                            @endif
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
                                    <td colspan="8" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-icon bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                                <i class="fas fa-check-circle fa-2x text-success" style="opacity: 0.5;"></i>
                                            </div>
                                            <h5 class="font-weight-bold text-dark">No Incomplete Tasks</h5>
                                            <p class="text-muted">This staff member has zero incomplete tasks in the selected category.</p>
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

    @else
        <!-- No User Selected: Staff Incomplete Overview Leaderboard -->
        <div class="card border-0 shadow-sm mb-5" style="border-radius: 16px; border: 1px solid #d1d5db !important;">
            <div class="card-header bg-white py-4 px-4 border-bottom border-light d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="font-weight-bolder text-dark mb-1">
                        <i class="fas fa-users-cog text-primary mr-2"></i>Staff Incomplete Tasks Overview
                    </h5>
                    <p class="text-muted small mb-0">Select any staff user from the filter above or click "View Tasks" below to inspect their incomplete tasks.</p>
                </div>
                <span class="badge badge-primary px-3 py-2 rounded-pill font-weight-bold">
                    {{ count($userSummaryList) }} Staff with Incomplete Tasks
                </span>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table premium-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="pl-4">Staff Name</th>
                                <th>Role(s)</th>
                                <th>Critical (10+ Days)</th>
                                <th>Aging (5 - 9 Days)</th>
                                <th>Recent (&lt; 5 Days)</th>
                                <th>Total Incomplete</th>
                                <th class="pr-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($userSummaryList as $row)
                                <tr class="table-row-animate">
                                    <td class="pl-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light border text-dark font-weight-bold d-flex align-items-center justify-content-center mr-3" style="width: 38px; height: 38px; font-size: 0.95rem;">
                                                {{ strtoupper(substr($row['user']->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-weight-bold text-dark">{{ $row['user']->name }}</div>
                                                <div class="text-muted small">{{ $row['user']->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @foreach($row['roles'] as $r)
                                            <span class="badge badge-light border text-secondary font-weight-bold mr-1">{{ $r }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($row['critical'] > 0)
                                            <span class="badge premium-badge bg-danger-soft text-danger">
                                                <i class="fas fa-fire mr-1"></i> {{ $row['critical'] }} Critical
                                            </span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($row['aging'] > 0)
                                            <span class="badge premium-badge bg-warning-soft text-warning">
                                                {{ $row['aging'] }} Aging
                                            </span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($row['recent'] > 0)
                                            <span class="badge premium-badge bg-info-soft text-info">
                                                {{ $row['recent'] }} Recent
                                            </span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="font-weight-bolder text-primary" style="font-size: 1.1rem;">
                                            {{ $row['total'] }}
                                        </span>
                                    </td>
                                    <td class="pr-4 text-right">
                                        <a href="{{ route('works.user-incomplete', ['user_id' => $row['user']->id, 'role' => $selectedRole ?: ($row['roles'][0] ?? null), 'month' => $month]) }}" class="btn btn-sm btn-primary rounded-pill px-3 font-weight-bold shadow-sm">
                                            View Tasks <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-icon bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                                <i class="fas fa-check-circle fa-2x text-success" style="opacity: 0.5;"></i>
                                            </div>
                                            <h5 class="font-weight-bold text-dark">No Pending Incomplete Tasks</h5>
                                            <p class="text-muted">All works are currently completed across all staff members.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
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
    --primary-soft: #ede9fe;
    --primary-text: #4f46e5;
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
    font-size: 1.15rem;
    font-weight: 800;
}
.kpi-number {
    font-size: 2.3rem;
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
.active-total { border-bottom: 4px solid #4f46e5; }

/* Search Box */
.search-box {
    display: flex;
    align-items: center;
    background: var(--bg-light);
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    width: 280px;
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
.bg-info-soft { background-color: var(--info-soft); color: var(--info-text); }
.bg-warning-soft { background-color: var(--warning-soft); color: var(--warning-text); }
.bg-danger-soft { background-color: var(--danger-soft); color: var(--danger-text); }
.bg-primary-soft { background-color: var(--primary-soft); color: var(--primary-text); }

/* Team Avatars */
.team-avatars { display: flex; align-items: center; }
.avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background-color: #cbd5e1;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
    margin-right: -6px;
    border: 2px solid #fff;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #f1f5f9;
    transition: all 0.2s ease;
}
.action-btn:hover {
    background: #e2e8f0;
    transform: scale(1.05);
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Role-dependent User dropdown data
    const usersByRole = @json($usersByRole ?? []);
    const allStaffUsers = @json($allStaffUsersFormatted ?? []);
    let currentSelectedUserId = '{{ $selectedUserId }}';

    function initSelect2($elem, placeholder) {
        if (typeof $.fn.select2 !== 'undefined' && $elem && $elem.length) {
            $elem.select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: placeholder || 'Search or select...',
                allowClear: true
            });
        }
    }

    // Initialize all select2-search elements except #user_filter which is initialized by updateUserOptions
    if (typeof $.fn.select2 !== 'undefined') {
        $('.select2-search').not('#user_filter').each(function() {
            const placeholder = $(this).data('placeholder') || 'Search or select...';
            initSelect2($(this), placeholder);
        });
    }

    function updateUserOptions(selectedRole, preserveCurrent) {
        const $userSelect = $('#user_filter');
        if (!$userSelect.length) return;

        const currentlySelected = preserveCurrent ? ($userSelect.val() || currentSelectedUserId) : '';

        // Destroy existing select2 instance before updating options to prevent DOM desync
        if (typeof $.fn.select2 !== 'undefined' && $userSelect.hasClass('select2-hidden-accessible')) {
            $userSelect.select2('destroy');
        }

        $userSelect.empty();

        let listToRender = [];
        let placeholderText = '-- Select Staff User --';

        if (selectedRole && usersByRole[selectedRole]) {
            listToRender = usersByRole[selectedRole].map(function(u) {
                return { id: u.id, text: u.name };
            });
            placeholderText = '-- Choose ' + selectedRole + ' (' + listToRender.length + ') --';
        } else {
            listToRender = allStaffUsers.map(function(u) {
                return {
                    id: u.id,
                    text: u.name + (u.roles_text ? ' (' + u.roles_text + ')' : '')
                };
            });
            placeholderText = '-- Select Staff User (' + listToRender.length + ') --';
        }

        // Add blank/placeholder option
        $userSelect.append(new Option(placeholderText, '', false, false));

        let isSelectedStillValid = false;
        listToRender.forEach(function(item) {
            const isSelected = String(item.id) === String(currentlySelected);
            if (isSelected) isSelectedStillValid = true;
            $userSelect.append(new Option(item.text, item.id, false, isSelected));
        });

        if (!isSelectedStillValid) {
            $userSelect.val('');
            currentSelectedUserId = '';
        } else {
            $userSelect.val(currentlySelected);
        }

        // Re-initialize select2 with new placeholder
        initSelect2($userSelect, placeholderText);
        $userSelect.trigger('change');
    }

    // Initial load: populate user options matching pre-selected role (or all roles)
    const $roleFilter = $('#role_filter');
    const initialRole = $roleFilter.length ? $roleFilter.val() : '';
    updateUserOptions(initialRole, true);

    // When role changes, immediately update user dropdown
    if ($roleFilter.length) {
        $roleFilter.on('change select2:select select2:clear', function() {
            const selectedRole = $(this).val();
            updateUserOptions(selectedRole, false);
        });
    }

    // Export CSV
    const exportBtn = document.getElementById('export-user-csv-btn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function () {
            const params = new URLSearchParams(window.location.search);
            const exportUrl = '{{ route("works.user-incomplete.export") }}' + '?' + params.toString();
            window.location.href = exportUrl;
        });
    }
});
</script>
@endsection
