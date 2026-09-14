@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-md-4">
    <!-- Breadcrumbs, Title & Actions Header -->
    <div class="row align-items-center justify-content-between mb-4 no-print">
        <div class="col-12 col-lg-auto mb-3 mb-lg-0">
            <div class="d-flex align-items-center">
                <div class="header-icon-box bg-primary text-white rounded-lg shadow-sm mr-3 p-3 text-center">
                    <i class="fas fa-chart-pie fa-2x"></i>
                </div>
                <div>
                    <h1 class="h2 text-primary font-weight-bold mb-1">Daily Operations & Performance Dashboard</h1>
                    <p class="text-muted mb-0 font-weight-500">
                        <i class="far fa-calendar-alt mr-1 text-primary"></i> Report for: 
                        <strong class="text-dark">{{ \Carbon\Carbon::parse($dateStr)->format('l, F j, Y') }}</strong>
                        @if($dateStr === \Carbon\Carbon::today()->toDateString())
                            <span class="badge badge-success ml-2 font-weight-normal px-2 py-1"><i class="fas fa-circle fa-xs mr-1"></i> Today</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-auto text-right">
            <!-- Action buttons -->
            <div class="d-inline-flex align-items-center flex-wrap">
                <a href="{{ route('works.daily-report', array_merge(request()->query(), ['action' => 'export'])) }}" 
                   class="btn btn-success shadow-sm mr-2 mb-2 mb-sm-0 font-weight-600">
                    <i class="fas fa-file-csv mr-1"></i> Export CSV
                </a>
                <button onclick="window.print()" class="btn btn-primary shadow-sm font-weight-600">
                    <i class="fas fa-print mr-1"></i> Print Report
                </button>
            </div>
        </div>
    </div>

    <!-- Print Header (Visible ONLY on print) -->
    <div class="print-header d-none mb-4">
        <div class="text-center pb-2 border-bottom">
            <h2 class="font-weight-bold mb-1">KKDA LSA DAILY OPERATIONS REPORT</h2>
            <h5 class="text-secondary mb-1">Date: {{ \Carbon\Carbon::parse($dateStr)->format('l, F j, Y') }}</h5>
            <small class="text-muted">Generated on {{ now()->format('d M Y, h:i A') }} | Daily Performance & Branch Segmentation</small>
        </div>
        @if($selectedBranch || $selectedRole || $selectedStatus || $search)
            <div class="p-2 bg-light text-muted small mt-2">
                <strong>Filters Applied:</strong>
                @if($selectedBranch) Branch: {{ $bankBranches[$selectedBranch] ?? 'ID '.$selectedBranch }} | @endif
                @if($selectedRole) Role: {{ $selectedRole }} | @endif
                @if($selectedStatus) Status: {{ $selectedStatus }} | @endif
                @if($search) Search: "{{ $search }}" @endif
            </div>
        @endif
    </div>

    <!-- Filter Toolbar Card -->
    <div class="card border-0 shadow-sm rounded-xl mb-4 no-print filter-card">
        <div class="card-body p-3 p-md-4">
            <form action="{{ route('works.daily-report') }}" method="GET" class="mb-0" id="dailyReportFilterForm">
                <div class="row align-items-end">
                    <!-- Date Presets & Picker -->
                    <div class="col-12 col-md-6 col-lg-3 mb-3 mb-lg-0">
                        <label class="font-weight-bold text-xs text-uppercase text-secondary mb-1">Date Selection</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <a href="{{ route('works.daily-report', array_merge(request()->query(), ['date' => \Carbon\Carbon::parse($dateStr)->subDay()->toDateString()])) }}" 
                                   class="btn btn-outline-secondary" title="Previous Day">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                                <a href="{{ route('works.daily-report', array_merge(request()->query(), ['date' => \Carbon\Carbon::today()->toDateString()])) }}" 
                                   class="btn btn-outline-secondary {{ $dateStr === \Carbon\Carbon::today()->toDateString() ? 'active font-weight-bold' : '' }}" title="Jump to Today">
                                    Today
                                </a>
                            </div>
                            <input type="date" name="date" class="form-control text-center font-weight-bold" value="{{ $dateStr }}" onchange="document.getElementById('dailyReportFilterForm').submit()">
                            <div class="input-group-append">
                                <a href="{{ route('works.daily-report', array_merge(request()->query(), ['date' => \Carbon\Carbon::parse($dateStr)->addDay()->toDateString()])) }}" 
                                   class="btn btn-outline-secondary" title="Next Day">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Branch Filter -->
                    <div class="col-12 col-sm-6 col-md-3 col-lg-3 mb-3 mb-lg-0">
                        <label class="font-weight-bold text-xs text-uppercase text-secondary mb-1">
                            <i class="fas fa-university text-primary mr-1"></i> Bank Branch
                        </label>
                        <select name="bank_branch" class="form-control form-control-sm custom-select" onchange="document.getElementById('dailyReportFilterForm').submit()">
                            <option value="">-- All Bank Branches --</option>
                            @foreach($bankBranches as $id => $name)
                                <option value="{{ $id }}" {{ (string)$selectedBranch === (string)$id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Role Filter -->
                    <div class="col-12 col-sm-6 col-md-3 col-lg-2 mb-3 mb-lg-0">
                        <label class="font-weight-bold text-xs text-uppercase text-secondary mb-1">
                            <i class="fas fa-user-tag text-info mr-1"></i> Staff Role
                        </label>
                        <select name="role" class="form-control form-control-sm custom-select" onchange="document.getElementById('dailyReportFilterForm').submit()">
                            <option value="">-- All Roles --</option>
                            @foreach($availableRoles as $roleKey => $roleLabel)
                                <option value="{{ $roleKey }}" {{ $selectedRole === $roleKey ? 'selected' : '' }}>
                                    {{ $roleLabel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-12 col-sm-6 col-md-3 col-lg-2 mb-3 mb-lg-0">
                        <label class="font-weight-bold text-xs text-uppercase text-secondary mb-1">
                            <i class="fas fa-tasks text-success mr-1"></i> Status / Result
                        </label>
                        <select name="status" class="form-control form-control-sm custom-select" onchange="document.getElementById('dailyReportFilterForm').submit()">
                            <option value="">-- All Statuses --</option>
                            @foreach($availableStatuses as $statusKey => $statusLabel)
                                <option value="{{ $statusKey }}" {{ $selectedStatus === $statusKey ? 'selected' : '' }}>
                                    {{ $statusLabel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Search & Submit -->
                    <div class="col-12 col-sm-6 col-md-3 col-lg-2 mb-3 mb-lg-0">
                        <label class="font-weight-bold text-xs text-uppercase text-secondary mb-1">
                            <i class="fas fa-search text-muted mr-1"></i> Search
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control" placeholder="ID, applicant..." value="{{ $search }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary" title="Apply Filter">
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                                @if($selectedBranch || $selectedRole || $selectedStatus || $search)
                                    <a href="{{ route('works.daily-report', ['date' => $dateStr]) }}" class="btn btn-outline-danger" title="Clear Filters">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- TOP SECTION: Overall Work Done Today - Executive KPI Dashboard -->
    <div class="section-title-wrap mb-3 d-flex align-items-center justify-content-between">
        <h3 class="h5 text-dark font-weight-bold mb-0">
            <i class="fas fa-tachometer-alt text-primary mr-2"></i> Overall Work Done Today & Executive Overview
        </h3>
        <span class="badge badge-pill badge-light border text-muted px-3 py-1 font-weight-normal">
            {{ $totalActiveWorks }} Total Files Active Today
        </span>
    </div>

    <!-- Primary Stage Volume KPI Cards -->
    <div class="row mb-3">
        <!-- Total Active Today -->
        <div class="col-6 col-md-4 col-xl-2 mb-3">
            <div class="card border-0 shadow-sm rounded-xl h-100 kpi-card border-top-primary">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-xs font-weight-bold text-uppercase text-muted">Total Active</span>
                        <div class="kpi-icon-pill bg-light-primary text-primary">
                            <i class="fas fa-layer-group"></i>
                        </div>
                    </div>
                    <div class="h3 font-weight-bold text-dark mb-0">{{ $totalActiveWorks }}</div>
                    <small class="text-muted text-xs">Files touched today</small>
                </div>
            </div>
        </div>

        <!-- Created Today (In-Charge) -->
        <div class="col-6 col-md-4 col-xl-2 mb-3">
            <div class="card border-0 shadow-sm rounded-xl h-100 kpi-card border-top-cyan">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-xs font-weight-bold text-uppercase text-muted">Created</span>
                        <div class="kpi-icon-pill bg-light-cyan text-cyan">
                            <i class="fas fa-folder-plus"></i>
                        </div>
                    </div>
                    <div class="h3 font-weight-bold text-cyan mb-0">{{ $createdCount }}</div>
                    <small class="text-muted text-xs">In-Charge intakes</small>
                </div>
            </div>
        </div>

        <!-- Surveyed Today (Surveyor) -->
        <div class="col-6 col-md-4 col-xl-2 mb-3">
            <div class="card border-0 shadow-sm rounded-xl h-100 kpi-card border-top-teal">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-xs font-weight-bold text-uppercase text-muted">Surveyed</span>
                        <div class="kpi-icon-pill bg-light-teal text-teal">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                    </div>
                    <div class="h3 font-weight-bold text-teal mb-0">{{ $surveyedCount }}</div>
                    <small class="text-muted text-xs">Field inspections</small>
                </div>
            </div>
        </div>

        <!-- Reported Today (Reporter) -->
        <div class="col-6 col-md-4 col-xl-2 mb-3">
            <div class="card border-0 shadow-sm rounded-xl h-100 kpi-card border-top-warning">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-xs font-weight-bold text-uppercase text-muted">Reported</span>
                        <div class="kpi-icon-pill bg-light-warning text-warning">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                    </div>
                    <div class="h3 font-weight-bold text-warning mb-0">{{ $reportedCount }}</div>
                    <small class="text-muted text-xs">Reports drafted</small>
                </div>
            </div>
        </div>

        <!-- Checked Today (Checker) -->
        <div class="col-6 col-md-4 col-xl-2 mb-3">
            <div class="card border-0 shadow-sm rounded-xl h-100 kpi-card border-top-success">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-xs font-weight-bold text-uppercase text-muted">Checked</span>
                        <div class="kpi-icon-pill bg-light-success text-success">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                    <div class="h3 font-weight-bold text-success mb-0">{{ $checkedCount }}</div>
                    <small class="text-muted text-xs">Quality verified</small>
                </div>
            </div>
        </div>

        <!-- Delivered Today (Delivery) -->
        <div class="col-6 col-md-4 col-xl-2 mb-3">
            <div class="card border-0 shadow-sm rounded-xl h-100 kpi-card border-top-dark">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-xs font-weight-bold text-uppercase text-muted">Delivered</span>
                        <div class="kpi-icon-pill bg-light-dark text-dark">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                    </div>
                    <div class="h3 font-weight-bold text-dark mb-0">{{ $deliveredCount }}</div>
                    <small class="text-muted text-xs">Delivered to banks</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Efficiency & Outcome Strip -->
    <div class="row mb-4">
        <!-- Results Breakdown Card -->
        <div class="col-12 col-lg-7 mb-3 mb-lg-0">
            <div class="card border-0 shadow-sm rounded-xl h-100">
                <div class="card-body py-2 px-3 d-flex align-items-center justify-content-around flex-wrap">
                    <div class="text-center px-3 py-1">
                        <span class="text-xs font-weight-bold text-uppercase text-muted d-block">Positive Results</span>
                        <span class="h4 font-weight-bold text-success mb-0">
                            <i class="fas fa-check-circle mr-1 fa-sm"></i>{{ $positiveCount }}
                        </span>
                    </div>
                    <div class="divider-vertical d-none d-sm-block"></div>
                    <div class="text-center px-3 py-1">
                        <span class="text-xs font-weight-bold text-uppercase text-muted d-block">Negative Results</span>
                        <span class="h4 font-weight-bold text-danger mb-0">
                            <i class="fas fa-times-circle mr-1 fa-sm"></i>{{ $negativeCount }}
                        </span>
                    </div>
                    <div class="divider-vertical d-none d-sm-block"></div>
                    <div class="text-center px-3 py-1">
                        <span class="text-xs font-weight-bold text-uppercase text-muted d-block">Canceled Works</span>
                        <span class="h4 font-weight-bold text-secondary mb-0">
                            <i class="fas fa-ban mr-1 fa-sm"></i>{{ $canceledCount }}
                        </span>
                    </div>
                    <div class="divider-vertical d-none d-sm-block"></div>
                    <div class="text-center px-3 py-1">
                        <span class="text-xs font-weight-bold text-uppercase text-muted d-block">On Hold</span>
                        <span class="h4 font-weight-bold text-warning mb-0">
                            <i class="fas fa-pause-circle mr-1 fa-sm"></i>{{ $holdCount }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Turnaround & Operational Stats Card -->
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm rounded-xl h-100 bg-light-gradient">
                <div class="card-body py-2 px-3 d-flex align-items-center justify-content-around flex-wrap">
                    <div class="text-center px-2 py-1">
                        <small class="text-muted text-xs d-block font-weight-600"><i class="far fa-clock text-warning mr-1"></i>Avg Report Time</small>
                        <span class="h5 font-weight-bold text-dark mb-0">{{ $avgReporting > 0 ? $avgReporting . 'm' : '-' }}</span>
                    </div>
                    <div class="divider-vertical d-none d-sm-block"></div>
                    <div class="text-center px-2 py-1">
                        <small class="text-muted text-xs d-block font-weight-600"><i class="far fa-clock text-success mr-1"></i>Avg Check Time</small>
                        <span class="h5 font-weight-bold text-dark mb-0">{{ $avgChecking > 0 ? $avgChecking . 'm' : '-' }}</span>
                    </div>
                    <div class="divider-vertical d-none d-sm-block"></div>
                    <div class="text-center px-2 py-1">
                        <small class="text-muted text-xs d-block font-weight-600"><i class="fas fa-university text-primary mr-1"></i>Active Branches</small>
                        <span class="h5 font-weight-bold text-primary mb-0">{{ $activeBranchCount }}</span>
                    </div>
                    <div class="divider-vertical d-none d-sm-block"></div>
                    <div class="text-center px-2 py-1">
                        <small class="text-muted text-xs d-block font-weight-600"><i class="fas fa-users text-info mr-1"></i>Active Staff</small>
                        <span class="h5 font-weight-bold text-info mb-0">{{ $activeStaffCount }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DASHBOARD TABS NAVIGATION -->
    <div class="card border-0 shadow-sm rounded-xl mb-4 overflow-hidden">
        <div class="card-header bg-white border-bottom p-0 no-print">
            <ul class="nav nav-tabs custom-dashboard-tabs border-0" id="reportTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active py-3 px-4 font-weight-bold" id="branch-tab" data-toggle="tab" href="#tab-branch" role="tab">
                        <i class="fas fa-university mr-2 text-primary"></i> 
                        Segmented by Bank Branch 
                        <span class="badge badge-primary-subtle ml-1">{{ count($branchSegmentation) }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-3 px-4 font-weight-bold" id="roles-tab" data-toggle="tab" href="#tab-roles" role="tab">
                        <i class="fas fa-user-tag mr-2 text-info"></i> 
                        Staff & Role Work Matrix 
                        <span class="badge badge-info-subtle ml-1">{{ $activeStaffCount }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-3 px-4 font-weight-bold" id="detailed-tab" data-toggle="tab" href="#tab-detailed" role="tab">
                        <i class="fas fa-list-alt mr-2 text-success"></i> 
                        Master Detailed Work Log 
                        <span class="badge badge-success-subtle ml-1">{{ $detailedWorks->count() }}</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body p-0">
            <div class="tab-content" id="reportTabsContent">

                <!-- ========================================== -->
                <!-- TAB 1: SEGMENTED BY BANK BRANCH (PRIMARY) -->
                <!-- ========================================== -->
                <div class="tab-pane fade show active p-3 p-md-4" id="tab-branch" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="h5 font-weight-bold text-dark mb-1">
                                <i class="fas fa-code-branch text-primary mr-2"></i> Bank Branch Activity Segmentation
                            </h4>
                            <p class="text-muted text-sm mb-0">Detailed view of which staff worked on which files for each bank branch today.</p>
                        </div>
                        <span class="badge badge-pill badge-primary px-3 py-2">
                            {{ count($branchSegmentation) }} Active {{ Str::plural('Branch', count($branchSegmentation)) }}
                        </span>
                    </div>

                    @forelse($branchSegmentation as $bId => $branch)
                        <div class="card border rounded-xl mb-4 shadow-sm overflow-hidden branch-segment-card">
                            <!-- Branch Header Bar -->
                            <div class="card-header bg-white border-bottom py-3 px-4">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-12 col-md-6 mb-2 mb-md-0">
                                        <div class="d-flex align-items-center">
                                            <div class="branch-avatar bg-light-primary text-primary rounded-circle p-2 mr-3 text-center">
                                                <i class="fas fa-building fa-lg"></i>
                                            </div>
                                            <div>
                                                <h5 class="font-weight-bold text-dark mb-0">
                                                    {{ $branch['branch_name'] }}
                                                    @if($branch['bank_name'] && $branch['bank_name'] !== 'N/A')
                                                        <span class="badge badge-light border text-secondary font-weight-normal ml-2">{{ $branch['bank_name'] }}</span>
                                                    @endif
                                                </h5>
                                                <small class="text-muted">
                                                    <i class="fas fa-folder-open mr-1"></i> {{ $branch['total_works'] }} {{ Str::plural('work', $branch['total_works']) }} active today
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Branch Stage Pills -->
                                    <div class="col-12 col-md-6 text-md-right">
                                        <div class="d-inline-flex flex-wrap gap-1 align-items-center">
                                            @if($branch['created'] > 0)
                                                <span class="badge badge-pill badge-cyan-subtle mr-1 mb-1" title="Files Created Today">
                                                    <i class="fas fa-plus mr-1"></i>{{ $branch['created'] }} Created
                                                </span>
                                            @endif
                                            @if($branch['surveyed'] > 0)
                                                <span class="badge badge-pill badge-teal-subtle mr-1 mb-1" title="Inspections Done Today">
                                                    <i class="fas fa-map-pin mr-1"></i>{{ $branch['surveyed'] }} Surveyed
                                                </span>
                                            @endif
                                            @if($branch['reported'] > 0)
                                                <span class="badge badge-pill badge-warning-subtle mr-1 mb-1" title="Reports Completed Today">
                                                    <i class="fas fa-file-alt mr-1"></i>{{ $branch['reported'] }} Reported
                                                </span>
                                            @endif
                                            @if($branch['checked'] > 0)
                                                <span class="badge badge-pill badge-success-subtle mr-1 mb-1" title="Quality Checked Today">
                                                    <i class="fas fa-check-double mr-1"></i>{{ $branch['checked'] }} Checked
                                                </span>
                                            @endif
                                            @if($branch['delivered'] > 0)
                                                <span class="badge badge-pill badge-dark-subtle mr-1 mb-1" title="Delivered Today">
                                                    <i class="fas fa-truck mr-1"></i>{{ $branch['delivered'] }} Delivered
                                                </span>
                                            @endif
                                            @if($branch['positive'] > 0)
                                                <span class="badge badge-pill badge-success mr-1 mb-1" title="Positive Results">
                                                    {{ $branch['positive'] }} Pos
                                                </span>
                                            @endif
                                            @if($branch['negative'] > 0)
                                                <span class="badge badge-pill badge-danger mr-1 mb-1" title="Negative Results">
                                                    {{ $branch['negative'] }} Neg
                                                </span>
                                            @endif
                                            @if($branch['canceled'] > 0)
                                                <span class="badge badge-pill badge-secondary mr-1 mb-1" title="Canceled">
                                                    {{ $branch['canceled'] }} Can
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Staff Assigned to this Branch -->
                                <div class="mt-3 pt-2 border-top d-flex align-items-center flex-wrap">
                                    <span class="text-xs font-weight-bold text-uppercase text-secondary mr-2 mb-1">
                                        <i class="fas fa-users mr-1"></i> Staff on this Branch:
                                    </span>
                                    @php $hasStaff = false; @endphp
                                    @foreach($branch['users_by_role'] as $role => $users)
                                        @foreach($users as $u)
                                            @php $hasStaff = true; @endphp
                                            <span class="badge badge-light border rounded-pill py-1 px-2 mr-2 mb-1 text-dark" title="{{ $role }}">
                                                <span class="text-primary font-weight-600">[{{ $role }}]</span> {{ $u['name'] }}
                                                <span class="badge badge-secondary ml-1">{{ $u['count'] }}</span>
                                            </span>
                                        @endforeach
                                    @endforeach
                                    @if(!$hasStaff)
                                        <span class="text-muted text-xs mb-1">No staff actions recorded today.</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Branch Works Table -->
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-items-center mb-0 table-sm">
                                        <thead class="bg-light-blue text-primary font-weight-bold text-xs">
                                            <tr>
                                                <th class="py-2 pl-3">Work Ref</th>
                                                <th>Applicant & Project</th>
                                                <th>Current Status</th>
                                                <th>Result</th>
                                                <th>In-Charge</th>
                                                <th>Surveyor & Time</th>
                                                <th>Reporter & Duration</th>
                                                <th>Checker & Duration</th>
                                                <th>Delivery</th>
                                                <th class="text-right pr-3">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($branch['works'] as $w)
                                                <tr>
                                                    <!-- Work ID -->
                                                    <td class="font-weight-bold pl-3 text-nowrap">
                                                        <a href="{{ route('works.show', $w['id']) }}" class="text-primary font-weight-bold" target="_blank">
                                                            {{ $w['custom_id'] }}
                                                        </a>
                                                        @if($w['is_hold'])
                                                            <span class="badge badge-warning text-xxs ml-1">HOLD</span>
                                                        @endif
                                                    </td>

                                                    <!-- Applicant -->
                                                    <td>
                                                        <div class="font-weight-600 text-dark">{{ $w['applicant_name'] }}</div>
                                                        @if($w['project_name'])
                                                            <small class="text-muted d-block text-truncate" style="max-width: 150px;">{{ $w['project_name'] }}</small>
                                                        @endif
                                                    </td>

                                                    <!-- Status -->
                                                    <td>
                                                        <span class="badge badge-status badge-{{ 
                                                            $w['status'] === 'Completed' ? 'success' : (
                                                            $w['status'] === 'Hold' ? 'warning' : (
                                                            $w['status'] === 'Canceled' ? 'danger' : 'info'))
                                                        }}">
                                                            {{ $w['status'] }}
                                                        </span>
                                                    </td>

                                                    <!-- Result -->
                                                    <td>
                                                        @if($w['result'])
                                                            <span class="badge badge-pill badge-{{ 
                                                                $w['result'] === 'Positive' ? 'success' : (
                                                                $w['result'] === 'Negative' ? 'danger' : 'secondary')
                                                            }} font-weight-bold">
                                                                {{ $w['result'] }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted text-xs">-</span>
                                                        @endif
                                                    </td>

                                                    <!-- In-Charge -->
                                                    <td>
                                                        @if($w['incharge_name'])
                                                            <span class="text-dark font-weight-500">{{ $w['incharge_name'] }}</span>
                                                            @if($w['is_created_today'])
                                                                <span class="badge badge-cyan-subtle text-xxs d-block font-weight-normal">Created Today</span>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>

                                                    <!-- Surveyor -->
                                                    <td>
                                                        @if($w['surveyor_name'])
                                                            <div class="font-weight-500 text-dark">{{ $w['surveyor_name'] }}</div>
                                                            @if($w['survey_time'] && $w['is_surveyed_today'])
                                                                <small class="text-teal font-weight-bold d-block"><i class="far fa-clock"></i> {{ $w['survey_time'] }}</small>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>

                                                    <!-- Reporter -->
                                                    <td>
                                                        @if($w['reporter_name'])
                                                            <div class="font-weight-500 text-dark">{{ $w['reporter_name'] }}</div>
                                                            @if($w['reporting_duration'] !== null && $w['is_reported_today'])
                                                                <small class="text-warning font-weight-bold d-block"><i class="far fa-clock"></i> {{ $w['reporting_duration'] }} mins</small>
                                                            @elseif($w['is_reported_today'])
                                                                <small class="text-success font-weight-bold d-block">Done today</small>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>

                                                    <!-- Checker -->
                                                    <td>
                                                        @if($w['checker_name'])
                                                            <div class="font-weight-500 text-dark">{{ $w['checker_name'] }}</div>
                                                            @if($w['checking_duration'] !== null && $w['is_checked_today'])
                                                                <small class="text-success font-weight-bold d-block"><i class="far fa-clock"></i> {{ $w['checking_duration'] }} mins</small>
                                                            @elseif($w['is_checked_today'])
                                                                <small class="text-success font-weight-bold d-block">Checked today</small>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>

                                                    <!-- Delivery -->
                                                    <td>
                                                        @if($w['delivery_status'] === 'Delivery Done')
                                                            <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Delivered</span>
                                                            @if($w['delivery_name'])
                                                                <small class="text-muted d-block">{{ $w['delivery_name'] }}</small>
                                                            @endif
                                                        @else
                                                            <span class="badge badge-light border text-muted">{{ $w['delivery_status'] ?? 'Pending' }}</span>
                                                        @endif
                                                    </td>

                                                    <!-- Action -->
                                                    <td class="text-right pr-3 text-nowrap no-print">
                                                        <a href="{{ route('works.show', $w['id']) }}" class="btn btn-xs btn-outline-primary" target="_blank" title="View Work Details">
                                                            <i class="fas fa-eye"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card border-0 shadow-sm rounded-xl p-5 text-center">
                            <div class="text-muted mb-3"><i class="fas fa-university fa-3x text-light-primary"></i></div>
                            <h5 class="font-weight-bold text-dark">No bank branch activities recorded for this date.</h5>
                            <p class="text-muted mb-0">Try changing the date selector above or adjusting your filter parameters.</p>
                        </div>
                    @endforelse
                </div>

                <!-- ========================================== -->
                <!-- TAB 2: STAFF & ROLE WORK MATRIX           -->
                <!-- ========================================== -->
                <div class="tab-pane fade p-3 p-md-4" id="tab-roles" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="h5 font-weight-bold text-dark mb-1">
                                <i class="fas fa-users-cog text-info mr-2"></i> Staff Work Distribution by Role
                            </h4>
                            <p class="text-muted text-sm mb-0">Detailed breakdown of which staff members performed which roles, branches covered, and turnaround efficiency.</p>
                        </div>
                    </div>

                    <!-- Role Sections Accordion / Cards -->
                    @php
                        $roleColors = [
                            'Surveyor' => ['border' => 'border-top-teal', 'badge' => 'badge-teal-subtle', 'text' => 'text-teal', 'icon' => 'fa-map-marked-alt'],
                            'Reporter' => ['border' => 'border-top-warning', 'badge' => 'badge-warning-subtle', 'text' => 'text-warning', 'icon' => 'fa-file-invoice'],
                            'Checker' => ['border' => 'border-top-success', 'badge' => 'badge-success-subtle', 'text' => 'text-success', 'icon' => 'fa-user-check'],
                            'Delivery Person' => ['border' => 'border-top-dark', 'badge' => 'badge-dark-subtle', 'text' => 'text-dark', 'icon' => 'fa-shipping-fast'],
                            'In-Charge' => ['border' => 'border-top-cyan', 'badge' => 'badge-cyan-subtle', 'text' => 'text-cyan', 'icon' => 'fa-folder-plus'],
                        ];
                    @endphp

                    @foreach(['Surveyor', 'Reporter', 'Checker', 'Delivery Person', 'In-Charge'] as $roleKey)
                        @if(isset($roleWorkMatrix[$roleKey]) && count($roleWorkMatrix[$roleKey]) > 0)
                            @php $style = $roleColors[$roleKey] ?? ['border' => 'border-top-primary', 'badge' => 'badge-primary-subtle', 'text' => 'text-primary', 'icon' => 'fa-user']; @endphp
                            <div class="card border rounded-xl mb-4 shadow-sm overflow-hidden {{ $style['border'] }}">
                                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="font-weight-bold mb-0 {{ $style['text'] }}">
                                            <i class="fas {{ $style['icon'] }} mr-2"></i> {{ $roleKey }} Work Output
                                        </h5>
                                        <small class="text-muted">{{ count($roleWorkMatrix[$roleKey]) }} active staff in this role today</small>
                                    </div>
                                    <span class="badge {{ $style['badge'] }} px-3 py-1 font-weight-bold font-size-sm">
                                        {{ array_sum(array_column($roleWorkMatrix[$roleKey], 'works_count')) }} Total Actions
                                    </span>
                                </div>

                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-items-center mb-0">
                                            <thead class="bg-light text-secondary font-weight-bold text-xs">
                                                <tr>
                                                    <th class="py-3 pl-4">Staff Member</th>
                                                    <th class="text-center">Works Completed</th>
                                                    @if(in_array($roleKey, ['Reporter', 'Checker']))
                                                        <th class="text-center">Avg Time</th>
                                                    @endif
                                                    <th>Bank Branches Serviced</th>
                                                    <th>Assigned Work IDs & Outcome</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($roleWorkMatrix[$roleKey] as $uId => $staff)
                                                    <tr>
                                                        <!-- Staff Info -->
                                                        <td class="font-weight-bold pl-4">
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm bg-light rounded-circle text-center mr-2 p-1">
                                                                    <i class="fas fa-user-circle fa-lg text-secondary"></i>
                                                                </div>
                                                                <div>
                                                                    <span class="text-dark font-weight-bold">{{ $staff['name'] }}</span>
                                                                    <small class="text-muted d-block text-truncate" style="max-width: 140px;">{{ $staff['email'] ?? '' }}</small>
                                                                </div>
                                                            </div>
                                                        </td>

                                                        <!-- Works Count -->
                                                        <td class="text-center">
                                                            <span class="badge badge-pill badge-primary font-weight-bold px-3 py-1">
                                                                {{ $staff['works_count'] }}
                                                            </span>
                                                        </td>

                                                        <!-- Avg Time if applicable -->
                                                        @if(in_array($roleKey, ['Reporter', 'Checker']))
                                                            <td class="text-center font-weight-bold">
                                                                @if(isset($staff['avg_duration']) && $staff['avg_duration'] > 0)
                                                                    <span class="text-dark"><i class="far fa-clock text-muted mr-1"></i> {{ $staff['avg_duration'] }}m</span>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                        @endif

                                                        <!-- Bank Branches Served -->
                                                        <td>
                                                            <div class="d-flex flex-wrap gap-1">
                                                                @foreach($staff['branches'] as $bName => $bCount)
                                                                    <span class="badge badge-light border mr-1 mb-1 font-weight-500">
                                                                        <i class="fas fa-building text-primary mr-1"></i> {{ $bName }} ({{ $bCount }})
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        </td>

                                                        <!-- Works List with Status & Result -->
                                                        <td>
                                                            <div class="d-flex flex-wrap gap-1">
                                                                @foreach($staff['works'] as $w)
                                                                    <a href="{{ route('works.show', $w['work_id']) }}" target="_blank" 
                                                                       class="badge badge-light border mr-1 mb-1 text-decoration-none text-dark p-1" 
                                                                       title="Applicant: {{ $w['applicant'] }} | Branch: {{ $w['branch'] }}">
                                                                        <strong class="text-primary">{{ $w['custom_id'] }}</strong>
                                                                        @if(!empty($w['result']))
                                                                            <span class="badge badge-{{ $w['result'] === 'Positive' ? 'success' : ($w['result'] === 'Negative' ? 'danger' : 'secondary') }} ml-1">
                                                                                {{ $w['result'] }}
                                                                            </span>
                                                                        @endif
                                                                        @if(!empty($w['duration']))
                                                                            <small class="text-muted ml-1">{{ $w['duration'] }}m</small>
                                                                        @elseif(!empty($w['time']))
                                                                            <small class="text-muted ml-1">{{ $w['time'] }}</small>
                                                                        @endif
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <!-- Staff Productivity Leaderboard -->
                    <div class="card border rounded-xl shadow-sm mt-4 overflow-hidden">
                        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                            <h5 class="h6 font-weight-bold text-primary mb-0">
                                <i class="fas fa-trophy mr-2 text-warning"></i> Consolidated Staff Performance Leaderboard
                            </h5>
                            <span class="badge badge-light border text-muted">All active users</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-items-center mb-0">
                                    <thead class="bg-light-blue text-primary font-weight-bold text-xs">
                                        <tr>
                                            <th class="py-3 pl-4">Staff Name</th>
                                            <th>Role</th>
                                            <th class="text-center">Files Created</th>
                                            <th class="text-center">Surveyed</th>
                                            <th class="text-center">Reported</th>
                                            <th>Avg Report Time</th>
                                            <th class="text-center">Checked</th>
                                            <th>Avg Check Time</th>
                                            <th class="text-center">Delivered</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($userActivity as $userId => $activity)
                                            <tr>
                                                <td class="font-weight-bold pl-4">
                                                    <i class="fas fa-user-circle mr-2 text-secondary"></i> {{ $activity['name'] }}
                                                </td>
                                                <td>
                                                    <span class="badge badge-pill badge-secondary">{{ $activity['role'] ?? '-' }}</span>
                                                </td>
                                                <td class="text-center font-weight-bold text-primary">{{ $activity['created'] ?? 0 }}</td>
                                                <td class="text-center font-weight-bold text-teal">{{ $activity['surveyed'] ?? 0 }}</td>
                                                <td class="text-center font-weight-bold text-warning">{{ $activity['reported'] ?? 0 }}</td>
                                                <td>
                                                    @if(isset($activity['avg_reporting_time']) && $activity['avg_reporting_time'] > 0)
                                                        <span class="text-dark font-weight-bold"><i class="far fa-clock mr-1 text-muted"></i> {{ $activity['avg_reporting_time'] }} mins</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center font-weight-bold text-success">{{ $activity['checked'] ?? 0 }}</td>
                                                <td>
                                                    @if(isset($activity['avg_checking_time']) && $activity['avg_checking_time'] > 0)
                                                        <span class="text-dark font-weight-bold"><i class="far fa-clock mr-1 text-muted"></i> {{ $activity['avg_checking_time'] }} mins</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center font-weight-bold text-dark">{{ $activity['delivered'] ?? 0 }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center text-muted py-4">
                                                    <i class="fas fa-info-circle mr-2"></i> No staff activity recorded for this date.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- TAB 3: MASTER DETAILED WORK LOG           -->
                <!-- ========================================== -->
                <div class="tab-pane fade p-3 p-md-4" id="tab-detailed" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="h5 font-weight-bold text-dark mb-1">
                                <i class="fas fa-database text-success mr-2"></i> Master Detailed Work Log
                            </h4>
                            <p class="text-muted text-sm mb-0">Consolidated tabular log of all works touched on the selected date.</p>
                        </div>
                        <span class="badge badge-success px-3 py-2 font-weight-bold">
                            {{ $detailedWorks->count() }} Total Works
                        </span>
                    </div>

                    <div class="card border rounded-xl shadow-sm overflow-hidden">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-items-center mb-0">
                                    <thead class="bg-light-blue text-primary font-weight-bold text-xs">
                                        <tr>
                                            <th class="py-3 pl-3">Custom ID</th>
                                            <th>Applicant & Project</th>
                                            <th>Bank & Branch</th>
                                            <th>In-Charge</th>
                                            <th>Surveyor & Time</th>
                                            <th>Reporter & Duration</th>
                                            <th>Checker & Duration</th>
                                            <th>Delivery Status</th>
                                            <th>Status / Result</th>
                                            <th class="text-right pr-3">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($detailedWorks as $work)
                                            <tr>
                                                <!-- Custom ID -->
                                                <td class="font-weight-bold pl-3 text-nowrap">
                                                    <a href="{{ route('works.show', $work->id) }}" class="text-decoration-none text-primary" target="_blank">
                                                        <i class="fas fa-external-link-alt mr-1 fa-xs no-print"></i> {{ $work->custom_id }}
                                                    </a>
                                                    @if($work->is_hold)
                                                        <span class="badge badge-warning text-xxs ml-1">HOLD</span>
                                                    @endif
                                                </td>

                                                <!-- Applicant -->
                                                <td>
                                                    <div class="font-weight-600 text-dark">{{ $work->name_of_applicant }}</div>
                                                    @if($work->project_name)
                                                        <small class="text-muted d-block text-truncate" style="max-width: 160px;">{{ $work->project_name }}</small>
                                                    @endif
                                                </td>

                                                <!-- Bank & Branch -->
                                                <td>
                                                    <div class="font-weight-500 text-dark">
                                                        {{ $work->bankBranch->name ?? ($work->bank_name ? $work->bank_name . ' (Direct)' : '-') }}
                                                    </div>
                                                    @if($work->bank_name && $work->bankBranch)
                                                        <small class="text-muted">{{ $work->bank_name }}</small>
                                                    @endif
                                                </td>

                                                <!-- In-Charge -->
                                                <td>{{ $work->creator->name ?? '-' }}</td>

                                                <!-- Surveyor -->
                                                <td>
                                                    @php $sName = ($work->inspection && $work->inspection->creator) ? $work->inspection->creator->name : ($work->surveyor->name ?? null); @endphp
                                                    @if($sName)
                                                        <div class="font-weight-bold text-dark">{{ $sName }}</div>
                                                        @if($work->inspection && $work->inspection->created_at)
                                                            <small class="text-muted"><i class="far fa-clock"></i> {{ $work->inspection->created_at->format('h:i A') }}</small>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>

                                                <!-- Reporter -->
                                                <td>
                                                    @if($work->reporter)
                                                        <div class="font-weight-bold text-dark">{{ $work->reporter->name }}</div>
                                                        @if($work->reporting_started_at && $work->reporting_ended_at)
                                                            <small class="text-muted d-block" title="Started at {{ $work->reporting_started_at->format('h:i A') }} - Ended at {{ $work->reporting_ended_at->format('h:i A') }}">
                                                                <i class="far fa-clock"></i> Duration: {{ $work->reporting_duration_minutes }} mins
                                                            </small>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>

                                                <!-- Checker -->
                                                <td>
                                                    @if($work->checker)
                                                        <div class="font-weight-bold text-dark">{{ $work->checker->name }}</div>
                                                        @if($work->checking_started_at && $work->checking_ended_at)
                                                            <small class="text-muted d-block" title="Started at {{ $work->checking_started_at->format('h:i A') }} - Ended at {{ $work->checking_ended_at->format('h:i A') }}">
                                                                <i class="far fa-clock"></i> Duration: {{ $work->checking_duration_minutes }} mins
                                                            </small>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>

                                                <!-- Delivery Status -->
                                                <td>
                                                    @if($work->delivery_status === 'Delivery Done')
                                                        <span class="badge badge-success"><i class="fas fa-check mr-1"></i> Delivered</span>
                                                        @if($work->deliveryPerson)
                                                            <small class="text-muted d-block">{{ $work->deliveryPerson->name }}</small>
                                                        @endif
                                                    @else
                                                        <span class="badge badge-warning"><i class="fas fa-hourglass-half mr-1"></i> {{ $work->delivery_status ?? 'Pending' }}</span>
                                                    @endif
                                                </td>

                                                <!-- Status / Result -->
                                                <td>
                                                    <div>
                                                        <span class="badge badge-status badge-{{ 
                                                            $work->status === 'Completed' ? 'success' : (
                                                            $work->status === 'Hold' ? 'warning' : (
                                                            $work->status === 'Canceled' ? 'danger' : 'info'))
                                                        }}">{{ $work->status }}</span>
                                                    </div>
                                                    @if($work->result)
                                                        <div class="mt-1">
                                                            <span class="badge badge-pill badge-{{ 
                                                                $work->result === 'Positive' ? 'success' : (
                                                                $work->result === 'Negative' ? 'danger' : (
                                                                $work->result === 'Canceled' ? 'secondary' : 'warning'))
                                                            }}">{{ $work->result }}</span>
                                                        </div>
                                                    @endif
                                                    @if($work->remarks)
                                                        <small class="text-muted d-block mt-1 text-truncate" style="max-width: 180px;" title="{{ $work->remarks }}">
                                                            {{ $work->remarks }}
                                                        </small>
                                                    @endif
                                                </td>

                                                <!-- Action -->
                                                <td class="text-right pr-3 text-nowrap no-print">
                                                    <a href="{{ route('works.show', $work->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                        <i class="fas fa-external-link-alt"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center text-muted py-5">
                                                    <i class="fas fa-info-circle fa-2x mb-3 text-secondary d-block"></i>
                                                    No active works recorded for this date with selected filters.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    /* Styling & Design Tokens */
    .rounded-xl {
        border-radius: 14px !important;
    }

    .font-size-sm {
        font-size: 0.825rem !important;
    }

    .font-weight-500 { font-weight: 500 !important; }
    .font-weight-600 { font-weight: 600 !important; }

    .text-xxs {
        font-size: 0.68rem !important;
    }

    /* KPI Top Borders */
    .border-top-primary { border-top: 4px solid #3f51b5 !important; }
    .border-top-cyan    { border-top: 4px solid #0284c7 !important; }
    .border-top-teal    { border-top: 4px solid #0d9488 !important; }
    .border-top-warning { border-top: 4px solid #d97706 !important; }
    .border-top-success { border-top: 4px solid #059669 !important; }
    .border-top-dark    { border-top: 4px solid #334155 !important; }

    /* KPI Colors & Badges */
    .text-cyan { color: #0284c7 !important; }
    .text-teal { color: #0d9488 !important; }

    .bg-light-primary { background-color: #eef2ff !important; }
    .bg-light-cyan    { background-color: #f0f9ff !important; }
    .bg-light-teal    { background-color: #f0fdfa !important; }
    .bg-light-warning { background-color: #fffbeb !important; }
    .bg-light-success { background-color: #ecfdf5 !important; }
    .bg-light-dark    { background-color: #f1f5f9 !important; }
    .bg-light-blue    { background-color: #f8fafc !important; }

    .badge-primary-subtle { background-color: #e0e7ff; color: #3730a3; }
    .badge-cyan-subtle    { background-color: #e0f2fe; color: #0369a1; }
    .badge-teal-subtle    { background-color: #ccfbf1; color: #0f766e; }
    .badge-warning-subtle { background-color: #fef3c7; color: #92400e; }
    .badge-success-subtle { background-color: #d1fae5; color: #065f46; }
    .badge-dark-subtle    { background-color: #e2e8f0; color: #1e293b; }
    .badge-info-subtle    { background-color: #e0f2fe; color: #0284c7; }

    .bg-light-gradient {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .divider-vertical {
        width: 1px;
        height: 38px;
        background-color: #e2e8f0;
    }

    .kpi-icon-pill {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .kpi-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05) !important;
    }

    .filter-card {
        background: #ffffff;
        border: 1px solid #eef2f6 !important;
    }

    /* Tabs Styling */
    .custom-dashboard-tabs .nav-link {
        color: #64748b;
        border: none;
        border-bottom: 3px solid transparent;
        background: transparent;
        transition: all 0.2s ease;
    }
    .custom-dashboard-tabs .nav-link:hover {
        color: #3f51b5;
        border-color: #c7d2fe;
    }
    .custom-dashboard-tabs .nav-link.active {
        color: #3f51b5;
        background-color: transparent;
        border-bottom: 3px solid #3f51b5;
    }

    .branch-segment-card {
        border-color: #e2e8f0 !important;
    }
    .branch-avatar {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Print Styles */
    @media print {
        .no-print {
            display: none !important;
        }
        .print-header {
            display: block !important;
        }
        body {
            background-color: #ffffff !important;
            color: #000000 !important;
            font-size: 11pt !important;
        }
        .container-fluid {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #cccccc !important;
            margin-bottom: 15px !important;
            page-break-inside: avoid;
        }
        .tab-content > .tab-pane {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            padding: 0 !important;
        }
        .table {
            border-collapse: collapse !important;
            width: 100% !important;
        }
        .table th, .table td {
            border: 1px solid #ddd !important;
            padding: 6px !important;
        }
    }
</style>
@endsection
