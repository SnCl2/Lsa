@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-md-4">
    <!-- Breadcrumbs, Title & Actions Header -->
    <div class="row align-items-center justify-content-between mb-3 no-print">
        <div class="col-12 col-lg-auto mb-3 mb-lg-0">
            <div class="d-flex align-items-center">
                <div class="header-icon-box bg-primary text-white rounded-lg shadow-sm mr-3 p-3 text-center">
                    <i class="fas fa-chart-pie fa-2x"></i>
                </div>
                <div>
                    <h1 class="h2 text-primary font-weight-bold mb-1">Operations & Work Performance Dashboard</h1>
                    <p class="text-muted mb-0 font-weight-500">
                        <i class="far fa-calendar-alt mr-1 text-primary"></i> Period: 
                        <strong class="text-dark">{{ $dateRangeLabel }}</strong>
                        @if($period === 'today')
                            <span class="badge badge-success ml-2 font-weight-normal px-2 py-1"><i class="fas fa-circle fa-xs mr-1"></i> Today</span>
                        @elseif($period === 'this_month')
                            <span class="badge badge-info ml-2 font-weight-normal px-2 py-1"><i class="fas fa-calendar-alt fa-xs mr-1"></i> Current Month</span>
                        @elseif($period === 'current_fy')
                            <span class="badge badge-primary ml-2 font-weight-normal px-2 py-1"><i class="fas fa-university fa-xs mr-1"></i> {{ $currentFyLabel }}</span>
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
            <h2 class="font-weight-bold mb-1">KKDA LSA OPERATIONS & WORK REPORT</h2>
            <h5 class="text-secondary mb-1">Period: {{ $dateRangeLabel }} ({{ $dateFrom }} to {{ $dateTo }})</h5>
            <small class="text-muted">Generated on {{ now()->format('d M Y, h:i A') }} | Performance, Role Matrix & Branch Segmentation</small>
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

    <!-- Quick Date Preset Pills Bar (1-Click Switching) -->
    <div class="card border-0 shadow-sm rounded-xl mb-3 no-print bg-white">
        <div class="card-body py-2 px-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <span class="text-xs font-weight-bold text-uppercase text-muted mr-3 my-1">
                    <i class="fas fa-history text-primary mr-1"></i> Quick Presets:
                </span>
                <div class="d-inline-flex flex-wrap gap-1 my-1">
                    <!-- Today -->
                    <a href="{{ route('works.daily-report', array_merge(request()->except(['date_from', 'date_to', 'date']), ['period' => 'today'])) }}" 
                       class="btn btn-xs rounded-pill mr-1 mb-1 font-weight-600 {{ $period === 'today' ? 'btn-primary' : 'btn-outline-secondary' }}">
                        <i class="fas fa-clock mr-1"></i> Today
                    </a>

                    <!-- Previous Day -->
                    <a href="{{ route('works.daily-report', array_merge(request()->except(['date_from', 'date_to', 'date']), ['period' => 'yesterday'])) }}" 
                       class="btn btn-xs rounded-pill mr-1 mb-1 font-weight-600 {{ $period === 'yesterday' ? 'btn-primary' : 'btn-outline-secondary' }}">
                        <i class="fas fa-backward mr-1"></i> Previous Day
                    </a>

                    <!-- This Month -->
                    <a href="{{ route('works.daily-report', array_merge(request()->except(['date_from', 'date_to', 'date']), ['period' => 'this_month'])) }}" 
                       class="btn btn-xs rounded-pill mr-1 mb-1 font-weight-600 {{ $period === 'this_month' ? 'btn-primary' : 'btn-outline-secondary' }}">
                        <i class="fas fa-calendar-alt mr-1"></i> This Month ({{ $thisMonthLabel }})
                    </a>

                    <!-- Previous Month -->
                    <a href="{{ route('works.daily-report', array_merge(request()->except(['date_from', 'date_to', 'date']), ['period' => 'prev_month'])) }}" 
                       class="btn btn-xs rounded-pill mr-1 mb-1 font-weight-600 {{ $period === 'prev_month' ? 'btn-primary' : 'btn-outline-secondary' }}">
                        <i class="fas fa-calendar-minus mr-1"></i> Previous Month ({{ $prevMonthLabel }})
                    </a>

                    <!-- Current FY -->
                    <a href="{{ route('works.daily-report', array_merge(request()->except(['date_from', 'date_to', 'date']), ['period' => 'current_fy'])) }}" 
                       class="btn btn-xs rounded-pill mr-1 mb-1 font-weight-600 {{ $period === 'current_fy' ? 'btn-primary' : 'btn-outline-secondary' }}">
                        <i class="fas fa-university mr-1"></i> Current FY ({{ $currentFyLabel }})
                    </a>

                    <!-- Previous FY -->
                    <a href="{{ route('works.daily-report', array_merge(request()->except(['date_from', 'date_to', 'date']), ['period' => 'prev_fy'])) }}" 
                       class="btn btn-xs rounded-pill mr-1 mb-1 font-weight-600 {{ $period === 'prev_fy' ? 'btn-primary' : 'btn-outline-secondary' }}">
                        <i class="fas fa-landmark mr-1"></i> Previous FY ({{ $prevFyLabel }})
                    </a>

                    <!-- Custom Range Button -->
                    <button type="button" onclick="toggleCustomRange()" 
                            class="btn btn-xs rounded-pill mb-1 font-weight-600 {{ $period === 'custom' ? 'btn-primary' : 'btn-outline-secondary' }}">
                        <i class="fas fa-sliders-h mr-1"></i> Custom Range
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Filter Toolbar Card -->
    <div class="card border-0 shadow-sm rounded-xl mb-4 no-print filter-card">
        <div class="card-body p-3 p-md-4">
            <form action="{{ route('works.daily-report') }}" method="GET" class="mb-0" id="dailyReportFilterForm">
                <div class="row align-items-end">
                    <!-- Period Dropdown -->
                    <div class="col-12 col-sm-6 col-lg-3 mb-3 mb-lg-0">
                        <label class="font-weight-bold text-xs text-uppercase text-secondary mb-1">
                            <i class="far fa-calendar-check text-primary mr-1"></i> Date Period
                        </label>
                        <select name="period" id="periodSelect" class="form-control form-control-sm custom-select" onchange="handlePeriodChange(this.value)">
                            @foreach($datePresets as $pKey => $pLabel)
                                <option value="{{ $pKey }}" {{ $period === $pKey ? 'selected' : '' }}>
                                    {{ $pLabel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Custom Date From & To -->
                    <div class="col-12 col-sm-6 col-lg-3 mb-3 mb-lg-0" id="customDateRangeInputs">
                        <label class="font-weight-bold text-xs text-uppercase text-secondary mb-1">
                            <i class="fas fa-calendar-day text-info mr-1"></i> Date Range
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="date" name="date_from" id="dateFromInput" class="form-control font-weight-bold" 
                                   value="{{ $dateFrom }}" title="Date From" onchange="markCustomPeriod()">
                            <div class="input-group-prepend input-group-append">
                                <span class="input-group-text bg-light text-muted px-2 border-left-0 border-right-0">to</span>
                            </div>
                            <input type="date" name="date_to" id="dateToInput" class="form-control font-weight-bold" 
                                   value="{{ $dateTo }}" title="Date To" onchange="markCustomPeriod()">
                        </div>
                    </div>

                    <!-- Bank Branch Filter -->
                    <div class="col-12 col-sm-6 col-lg-2 mb-3 mb-lg-0">
                        <label class="font-weight-bold text-xs text-uppercase text-secondary mb-1">
                            <i class="fas fa-university text-primary mr-1"></i> Bank Branch
                        </label>
                        <select name="bank_branch" class="form-control form-control-sm custom-select" onchange="document.getElementById('dailyReportFilterForm').submit()">
                            <option value="">-- All Branches --</option>
                            @foreach($bankBranches as $id => $name)
                                <option value="{{ $id }}" {{ (string)$selectedBranch === (string)$id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Role Filter -->
                    <div class="col-12 col-sm-6 col-lg-2 mb-3 mb-lg-0">
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

                    <!-- Status Filter & Submit Group -->
                    <div class="col-12 col-lg-2 mb-3 mb-lg-0">
                        <label class="font-weight-bold text-xs text-uppercase text-secondary mb-1">
                            <i class="fas fa-tasks text-success mr-1"></i> Status / Result
                        </label>
                        <div class="input-group input-group-sm">
                            <select name="status" class="form-control form-control-sm custom-select" onchange="document.getElementById('dailyReportFilterForm').submit()">
                                <option value="">-- All Statuses --</option>
                                @foreach($availableStatuses as $statusKey => $statusLabel)
                                    <option value="{{ $statusKey }}" {{ $selectedStatus === $statusKey ? 'selected' : '' }}>
                                        {{ $statusLabel }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary" title="Apply Filter">
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                                @if($selectedBranch || $selectedRole || $selectedStatus || $search || $period !== 'today')
                                    <a href="{{ route('works.daily-report') }}" class="btn btn-outline-danger" title="Reset to Today">
                                        <i class="fas fa-redo"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                <!-- Secondary Search Row -->
                @if($selectedUserRole)
                    <input type="hidden" name="user_role" value="{{ $selectedUserRole }}">
                @endif
                @if($selectedUserId)
                    <input type="hidden" name="user_id" value="{{ $selectedUserId }}">
                @endif
                <div class="row align-items-center mt-3 pt-3 border-top">
                    <div class="col-12 col-md-6 mb-2 mb-md-0">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0 text-muted"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" name="search" class="form-control border-left-0" 
                                   placeholder="Quick search by applicant, custom ID, bank name, remarks..." value="{{ $search }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-outline-primary">Search</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 text-md-right">
                        <small class="text-muted">
                            Showing results from <strong class="text-dark">{{ $dateFrom }}</strong> to <strong class="text-dark">{{ $dateTo }}</strong>
                        </small>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- TOP SECTION: Overall Work Done in Period - Executive KPI Dashboard -->
    <div class="section-title-wrap mb-3 d-flex align-items-center justify-content-between">
        <h3 class="h5 text-dark font-weight-bold mb-0">
            <i class="fas fa-tachometer-alt text-primary mr-2"></i> Overall Work Done ({{ $dateRangeLabel }})
        </h3>
        <span class="badge badge-pill badge-light border text-muted px-3 py-1 font-weight-normal">
            {{ $totalActiveWorks }} Total Files Active in Period
        </span>
    </div>

    <!-- Primary Stage Volume KPI Cards -->
    <div class="row mb-3">
        <!-- Total Active -->
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
                    <small class="text-muted text-xs">Files touched in period</small>
                </div>
            </div>
        </div>

        <!-- Created (In-Charge) -->
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

        <!-- Surveyed (Surveyor) -->
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

        <!-- Reported (Reporter) -->
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

        <!-- Checked (Checker) -->
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

        <!-- Delivered (Delivery) -->
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

    <!-- DASHBOARD REDESIGNED TABS CONTAINER (REPLACING DIV 8) -->
    <div class="card border-0 shadow-sm rounded-xl mb-4 overflow-hidden" id="reportTabsContainer">
        <!-- Tab Navigation Header -->
        <div class="card-header bg-white border-bottom px-4 pt-3 pb-0 no-print">
            <ul class="nav nav-tabs custom-dashboard-tabs border-0" id="redesignedReportTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active py-3 px-4 font-weight-bold" id="user-wise-tab" data-toggle="tab" href="#tab-user-wise" role="tab" aria-controls="tab-user-wise" aria-selected="true">
                        <i class="fas fa-user-check text-primary mr-2"></i> User Wise Report
                        @if($userReportData)
                            <span class="badge badge-primary-subtle ml-2">{{ $userReportData['user']->name }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body p-4 bg-light-gradient">
            <div class="tab-content" id="redesignedReportTabContent">
                
                <!-- TAB: USER WISE REPORT -->
                <div class="tab-pane fade show active" id="tab-user-wise" role="tabpanel" aria-labelledby="user-wise-tab">
                    
                    <!-- USER SELECTION CONTROL CARD -->
                    <div class="card border-0 shadow-sm rounded-xl mb-4 bg-white">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between flex-wrap pb-3 mb-3 border-bottom">
                                <div>
                                    <h5 class="font-weight-bold text-dark mb-1">
                                        <i class="fas fa-users-cog text-primary mr-2"></i> Staff Performance & Activity Analysis
                                    </h5>
                                    <p class="text-muted text-sm mb-0">
                                        Select a staff role and user to inspect detailed metrics, bank branch distribution, turnaround duration, and category segmentations.
                                    </p>
                                </div>
                                <div class="mt-2 mt-md-0">
                                    <span class="badge badge-light border text-muted px-3 py-2 font-weight-normal">
                                        <i class="far fa-calendar-alt text-primary mr-1"></i> Active Date Filter: <strong>{{ $dateRangeLabel }}</strong>
                                    </span>
                                </div>
                            </div>

                            <form method="GET" action="{{ route('works.daily-report') }}" id="userReportFilterForm">
                                <!-- Maintain page date filters -->
                                <input type="hidden" name="period" value="{{ $period }}">
                                <input type="hidden" name="date_from" value="{{ $dateFrom }}">
                                <input type="hidden" name="date_to" value="{{ $dateTo }}">
                                @if($selectedBranch)
                                    <input type="hidden" name="bank_branch" value="{{ $selectedBranch }}">
                                @endif
                                @if($selectedStatus)
                                    <input type="hidden" name="status" value="{{ $selectedStatus }}">
                                @endif

                                <div class="row align-items-end">
                                    <!-- Step 1: Role Selection -->
                                    <div class="col-12 col-md-4 mb-3 mb-md-0">
                                        <label for="selectUserRole" class="font-weight-600 text-xs text-uppercase text-secondary mb-1">
                                            <span class="badge badge-primary rounded-circle mr-1" style="width: 18px; height: 18px; display: inline-flex; align-items: center; justify-content: center; font-size: 10px;">1</span>
                                            Select Role <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0 text-muted"><i class="fas fa-user-tag"></i></span>
                                            </div>
                                            <select name="user_role" id="selectUserRole" class="form-control border-left-0 font-weight-bold" onchange="onRoleChange(this.value)">
                                                <option value="">-- Choose Role --</option>
                                                @foreach($reportStaffRoles as $roleName)
                                                    <option value="{{ $roleName }}" {{ $selectedUserRole === $roleName ? 'selected' : '' }}>
                                                        {{ $roleName }} ({{ count($usersByRole[$roleName] ?? []) }} staff)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Step 2: User Selection -->
                                    <div class="col-12 col-md-5 mb-3 mb-md-0">
                                        <label for="selectUserId" class="font-weight-600 text-xs text-uppercase text-secondary mb-1">
                                            <span class="badge badge-info rounded-circle mr-1" style="width: 18px; height: 18px; display: inline-flex; align-items: center; justify-content: center; font-size: 10px;">2</span>
                                            Select Staff Member <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0 text-muted"><i class="fas fa-user"></i></span>
                                            </div>
                                            <select name="user_id" id="selectUserId" class="form-control border-left-0 font-weight-bold">
                                                <option value="">-- Select a role first --</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="col-12 col-md-3 d-flex">
                                        <button type="submit" class="btn btn-primary btn-block shadow-sm font-weight-600">
                                            <i class="fas fa-chart-pie mr-1"></i> Generate Report
                                        </button>
                                        @if($selectedUserId)
                                            <a href="{{ route('works.daily-report', ['period' => $period, 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" 
                                               class="btn btn-outline-secondary ml-2" title="Reset Selection">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if(!$userReportData)
                        <!-- EMPTY STATE: Prompt to select Role & User -->
                        <div class="card border-0 shadow-sm rounded-xl p-5 text-center bg-white my-3">
                            <div class="py-4">
                                <div class="empty-icon-wrap mb-3 mx-auto">
                                    <div class="rounded-circle bg-light-primary d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                        <i class="fas fa-user-check text-primary fa-2x"></i>
                                    </div>
                                </div>
                                <h4 class="font-weight-bold text-dark mb-2">Select a Staff Role & User Above</h4>
                                <p class="text-muted mx-auto mb-4" style="max-width: 580px;">
                                    Please select a <strong>Role</strong> (e.g. Reporter, Checker, Surveyor, In-Charge) and a specific <strong>User</strong> from the dropdown above to view their comprehensive work volume, Bank Branch segmentation, average turnaround time, status breakdown, result breakdown, valuer, project name, loan type, and work type segmentation.
                                </p>
                                <div class="d-inline-flex align-items-center text-xs text-muted bg-light px-3 py-2 rounded-pill border">
                                    <i class="fas fa-info-circle text-info mr-2"></i> All aggregated counts will strictly respect the active date filter: <strong>{{ $dateRangeLabel }}</strong>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- USER REPORT RESULTS -->
                        
                        <!-- 1. USER PROFILE & PERIOD SUMMARY BANNER -->
                        <div class="card border-0 shadow-sm rounded-xl mb-4 bg-white overflow-hidden">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    <div class="col-12 col-lg-7 d-flex align-items-center mb-3 mb-lg-0">
                                        <div class="avatar-circle-lg rounded-circle bg-primary text-white font-weight-bold d-flex align-items-center justify-content-center mr-3 shadow-sm" style="width: 60px; height: 60px; font-size: 1.3rem;">
                                            {{ strtoupper(substr($userReportData['user']->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center flex-wrap">
                                                <h4 class="font-weight-bold text-dark mb-0 mr-2">{{ $userReportData['user']->name }}</h4>
                                                <span class="badge badge-primary px-3 py-1 font-weight-600 text-uppercase text-xs mr-2">{{ $userReportData['role'] }}</span>
                                                <span class="text-muted text-xs">ID: #{{ $userReportData['user']->id }}</span>
                                            </div>
                                            <div class="text-muted text-sm mt-1">
                                                <i class="far fa-envelope mr-1 text-secondary"></i> {{ $userReportData['user']->email }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-5 text-lg-right">
                                        <div class="d-inline-block text-left text-lg-right">
                                            <span class="text-xs text-uppercase font-weight-bold text-muted d-block mb-1">Active Date Filter</span>
                                            <span class="badge badge-light border text-dark px-3 py-2 font-weight-bold text-sm">
                                                <i class="far fa-calendar-alt text-primary mr-1"></i> {{ $dateRangeLabel }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. EXECUTIVE KPI CARDS ROW -->
                        <div class="row mb-4">
                            <!-- Total Works Done -->
                            <div class="col-6 col-md-3 col-xl mb-3">
                                <div class="card border-0 shadow-sm rounded-xl h-100 kpi-card border-top-primary bg-white">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="text-xs font-weight-bold text-uppercase text-muted">Total Works</span>
                                            <div class="kpi-icon-pill bg-light-primary text-primary">
                                                <i class="fas fa-folder-open"></i>
                                            </div>
                                        </div>
                                        <div class="h3 font-weight-bold text-dark mb-1">{{ $userReportData['totalWorks'] }}</div>
                                        <span class="text-xs text-muted">Works touched in period</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Completed Works -->
                            <div class="col-6 col-md-3 col-xl mb-3">
                                <div class="card border-0 shadow-sm rounded-xl h-100 kpi-card border-top-success bg-white">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="text-xs font-weight-bold text-uppercase text-muted">Completed</span>
                                            <div class="kpi-icon-pill bg-light-success text-success">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                        </div>
                                        <div class="h3 font-weight-bold text-success mb-1">{{ $userReportData['completedCount'] }}</div>
                                        <span class="text-xs text-muted">
                                            {{ $userReportData['totalWorks'] > 0 ? round(($userReportData['completedCount'] / $userReportData['totalWorks']) * 100, 1) : 0 }}% completion rate
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- In Progress / Active -->
                            <div class="col-6 col-md-3 col-xl mb-3">
                                <div class="card border-0 shadow-sm rounded-xl h-100 kpi-card border-top-info bg-white">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="text-xs font-weight-bold text-uppercase text-muted">In Progress</span>
                                            <div class="kpi-icon-pill bg-light-info text-info">
                                                <i class="fas fa-spinner"></i>
                                            </div>
                                        </div>
                                        <div class="h3 font-weight-bold text-info mb-1">{{ $userReportData['inProgressCount'] }}</div>
                                        <span class="text-xs text-muted">Active workflow files</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Hold / Canceled -->
                            <div class="col-6 col-md-3 col-xl mb-3">
                                <div class="card border-0 shadow-sm rounded-xl h-100 kpi-card border-top-warning bg-white">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="text-xs font-weight-bold text-uppercase text-muted">Hold / Canceled</span>
                                            <div class="kpi-icon-pill bg-light-warning text-warning">
                                                <i class="fas fa-pause-circle"></i>
                                            </div>
                                        </div>
                                        <div class="h3 font-weight-bold text-dark mb-1">
                                            {{ $userReportData['holdCount'] + $userReportData['canceledCount'] }}
                                        </div>
                                        <span class="text-xs text-muted">
                                            Hold: {{ $userReportData['holdCount'] }} | Canceled: {{ $userReportData['canceledCount'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- AVERAGE TIME CARD (SPECIAL EMPHASIS FOR REPORTER & CHECKER) -->
                            @if($userReportData['avgReportingMinutes'] !== null || $userReportData['avgCheckingMinutes'] !== null)
                                <div class="col-12 col-md-6 col-xl mb-3">
                                    <div class="card border-0 shadow-sm rounded-xl h-100 kpi-card border-top-purple bg-white">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <span class="text-xs font-weight-bold text-uppercase text-muted">
                                                    @if($userReportData['role'] === 'Reporter' || ($userReportData['avgReportingMinutes'] !== null && $userReportData['avgCheckingMinutes'] === null))
                                                        <i class="far fa-clock text-warning mr-1"></i> Avg Report Time
                                                    @elseif($userReportData['role'] === 'Checker' || ($userReportData['avgCheckingMinutes'] !== null && $userReportData['avgReportingMinutes'] === null))
                                                        <i class="far fa-clock text-success mr-1"></i> Avg Check Time
                                                    @else
                                                        <i class="far fa-clock text-primary mr-1"></i> Turnaround Time
                                                    @endif
                                                </span>
                                                <div class="kpi-icon-pill bg-light-purple text-purple">
                                                    <i class="fas fa-stopwatch"></i>
                                                </div>
                                            </div>

                                            @if($userReportData['role'] === 'Reporter' || ($userReportData['avgReportingMinutes'] !== null && $userReportData['avgCheckingMinutes'] === null))
                                                <div class="h3 font-weight-bold text-dark mb-1">
                                                    {{ $userReportData['avgReportingFormatted'] ?? '-' }}
                                                </div>
                                                <span class="text-xs text-muted">
                                                    {{ $userReportData['avgReportingMinutes'] }}m avg ({{ $userReportData['timedReportingCount'] }} timed files)
                                                </span>
                                            @elseif($userReportData['role'] === 'Checker' || ($userReportData['avgCheckingMinutes'] !== null && $userReportData['avgReportingMinutes'] === null))
                                                <div class="h3 font-weight-bold text-dark mb-1">
                                                    {{ $userReportData['avgCheckingFormatted'] ?? '-' }}
                                                </div>
                                                <span class="text-xs text-muted">
                                                    {{ $userReportData['avgCheckingMinutes'] }}m avg ({{ $userReportData['timedCheckingCount'] }} timed files)
                                                </span>
                                            @else
                                                <div class="d-flex justify-content-between align-items-center mt-1">
                                                    <div>
                                                        <small class="text-muted d-block text-xxs">Report</small>
                                                        <strong class="text-dark">{{ $userReportData['avgReportingFormatted'] ?? '-' }}</strong>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block text-xxs">Check</small>
                                                        <strong class="text-dark">{{ $userReportData['avgCheckingFormatted'] ?? '-' }}</strong>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @elseif($userReportData['role'] === 'Reporter' || $userReportData['role'] === 'Checker')
                                <div class="col-12 col-md-6 col-xl mb-3">
                                    <div class="card border-0 shadow-sm rounded-xl h-100 kpi-card bg-white">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <span class="text-xs font-weight-bold text-uppercase text-muted">
                                                    Avg {{ $userReportData['role'] === 'Reporter' ? 'Report' : 'Check' }} Time
                                                </span>
                                                <div class="kpi-icon-pill bg-light text-muted">
                                                    <i class="far fa-clock"></i>
                                                </div>
                                            </div>
                                            <div class="h4 font-weight-bold text-muted mb-1">No Timed Data</div>
                                            <span class="text-xs text-muted">Duration logs pending for period</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- 3. BANK BRANCH SEGMENTATION (FULL CARD) -->
                        <div class="card border-0 shadow-sm rounded-xl mb-4 bg-white overflow-hidden">
                            <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between flex-wrap">
                                <div>
                                    <h6 class="font-weight-bold text-dark mb-0">
                                        <i class="fas fa-university text-primary mr-2"></i> Bank Branch Wise Segmentation
                                    </h6>
                                    <small class="text-muted">Work count and distribution across bank branches for {{ $userReportData['user']->name }}</small>
                                </div>
                                <span class="badge badge-primary-subtle px-3 py-1 font-weight-600 mt-2 mt-sm-0">
                                    {{ $userReportData['branchSeg']->count() }} Branches Served
                                </span>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-items-center mb-0 segmentation-table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="border-0 px-4 py-3 text-xs font-weight-bold text-uppercase text-muted">Bank Branch</th>
                                                <th class="border-0 px-3 py-3 text-xs font-weight-bold text-uppercase text-muted text-center" style="width: 140px;">Works Count</th>
                                                <th class="border-0 px-3 py-3 text-xs font-weight-bold text-uppercase text-muted" style="width: 250px;">Share / Progress</th>
                                                <th class="border-0 px-4 py-3 text-xs font-weight-bold text-uppercase text-muted text-right" style="width: 110px;">Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($userReportData['branchSeg'] as $branchName => $seg)
                                                <tr>
                                                    <td class="px-4 py-3 font-weight-600 text-dark">
                                                        <i class="fas fa-building text-secondary mr-2 font-size-sm"></i> {{ $branchName }}
                                                    </td>
                                                    <td class="px-3 py-3 text-center">
                                                        <span class="badge badge-light border text-primary font-weight-bold px-3 py-1 text-sm">
                                                            {{ $seg['count'] }}
                                                        </span>
                                                    </td>
                                                    <td class="px-3 py-3">
                                                        <div class="d-flex align-items-center">
                                                            <div class="progress flex-grow-1" style="height: 7px; border-radius: 4px; background-color: #edf2f9;">
                                                                <div class="progress-bar bg-primary" role="progressbar" 
                                                                     style="width: {{ $seg['percentage'] }}%;" 
                                                                     aria-valuenow="{{ $seg['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3 text-right font-weight-bold text-dark text-sm">
                                                        {{ $seg['percentage'] }}%
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-4 text-muted">
                                                        No branch activity recorded for this user in the selected period.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- 4. TWO-COLUMN SEGMENTATION GRIDS -->

                        <!-- ROW A: STATUS WISE & RESULT WISE -->
                        <div class="row mb-4">
                            <!-- Status Wise Segmentation -->
                            <div class="col-12 col-lg-6 mb-4 mb-lg-0">
                                <div class="card border-0 shadow-sm rounded-xl h-100 bg-white overflow-hidden">
                                    <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                                        <h6 class="font-weight-bold text-dark mb-0">
                                            <i class="fas fa-tasks text-info mr-2"></i> Status Wise Segmentation
                                        </h6>
                                        <span class="badge badge-info-subtle px-2 py-1 font-weight-600">{{ $userReportData['statusSeg']->count() }} Statuses</span>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-items-center mb-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="border-0 px-4 py-2 text-xs font-weight-bold text-uppercase text-muted">Status</th>
                                                        <th class="border-0 px-3 py-2 text-xs font-weight-bold text-uppercase text-muted text-center">Count</th>
                                                        <th class="border-0 px-4 py-2 text-xs font-weight-bold text-uppercase text-muted text-right">Share</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($userReportData['statusSeg'] as $statusName => $seg)
                                                        @php
                                                            $badgeClass = match($statusName) {
                                                                'Completed' => 'badge-success',
                                                                'Checking' => 'badge-info',
                                                                'Reporting' => 'badge-primary',
                                                                'Surveying' => 'badge-warning',
                                                                'Hold' => 'badge-secondary',
                                                                default => 'badge-light border text-dark'
                                                            };
                                                        @endphp
                                                        <tr>
                                                            <td class="px-4 py-3">
                                                                <span class="badge {{ $badgeClass }} px-2 py-1 font-weight-600">{{ $statusName }}</span>
                                                            </td>
                                                            <td class="px-3 py-3 text-center font-weight-bold text-dark">
                                                                {{ $seg['count'] }}
                                                            </td>
                                                            <td class="px-4 py-3 text-right">
                                                                <span class="font-weight-bold text-muted text-sm">{{ $seg['percentage'] }}%</span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="text-center py-3 text-muted">No data</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Result Wise Segmentation -->
                            <div class="col-12 col-lg-6">
                                <div class="card border-0 shadow-sm rounded-xl h-100 bg-white overflow-hidden">
                                    <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                                        <h6 class="font-weight-bold text-dark mb-0">
                                            <i class="fas fa-clipboard-check text-success mr-2"></i> Result Wise Segmentation
                                        </h6>
                                        <span class="badge badge-success-subtle px-2 py-1 font-weight-600">{{ $userReportData['resultSeg']->count() }} Results</span>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-items-center mb-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="border-0 px-4 py-2 text-xs font-weight-bold text-uppercase text-muted">Result</th>
                                                        <th class="border-0 px-3 py-2 text-xs font-weight-bold text-uppercase text-muted text-center">Count</th>
                                                        <th class="border-0 px-4 py-2 text-xs font-weight-bold text-uppercase text-muted text-right">Share</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($userReportData['resultSeg'] as $resultName => $seg)
                                                        @php
                                                            $resBadgeClass = match($resultName) {
                                                                'Positive' => 'badge-success',
                                                                'Negative' => 'badge-danger',
                                                                'Canceled' => 'badge-secondary',
                                                                'Return' => 'badge-warning',
                                                                default => 'badge-light border text-muted'
                                                            };
                                                        @endphp
                                                        <tr>
                                                            <td class="px-4 py-3">
                                                                <span class="badge {{ $resBadgeClass }} px-2 py-1 font-weight-600">{{ $resultName }}</span>
                                                            </td>
                                                            <td class="px-3 py-3 text-center font-weight-bold text-dark">
                                                                {{ $seg['count'] }}
                                                            </td>
                                                            <td class="px-4 py-3 text-right">
                                                                <span class="font-weight-bold text-muted text-sm">{{ $seg['percentage'] }}%</span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="text-center py-3 text-muted">No data</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ROW B: VALUER & WORK TYPE -->
                        <div class="row mb-4">
                            <!-- Valuer Wise Segmentation -->
                            <div class="col-12 col-lg-6 mb-4 mb-lg-0">
                                <div class="card border-0 shadow-sm rounded-xl h-100 bg-white overflow-hidden">
                                    <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                                        <h6 class="font-weight-bold text-dark mb-0">
                                            <i class="fas fa-stamp text-warning mr-2"></i> Valuer Wise Segmentation
                                        </h6>
                                        <span class="badge badge-warning-subtle px-2 py-1 font-weight-600">{{ $userReportData['valuerSeg']->count() }} Valuers</span>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-items-center mb-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="border-0 px-4 py-2 text-xs font-weight-bold text-uppercase text-muted">Valuer Code / Name</th>
                                                        <th class="border-0 px-3 py-2 text-xs font-weight-bold text-uppercase text-muted text-center">Count</th>
                                                        <th class="border-0 px-4 py-2 text-xs font-weight-bold text-uppercase text-muted text-right">Share</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($userReportData['valuerSeg'] as $valuerName => $seg)
                                                        <tr>
                                                            <td class="px-4 py-3 font-weight-600 text-dark">
                                                                <span class="badge badge-light border text-dark px-2 py-1 mr-2 font-weight-bold">Valuer {{ $valuerName }}</span>
                                                            </td>
                                                            <td class="px-3 py-3 text-center font-weight-bold text-dark">
                                                                {{ $seg['count'] }}
                                                            </td>
                                                            <td class="px-4 py-3 text-right">
                                                                <span class="font-weight-bold text-muted text-sm">{{ $seg['percentage'] }}%</span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="text-center py-3 text-muted">No data</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Work Type Wise Segmentation -->
                            <div class="col-12 col-lg-6">
                                <div class="card border-0 shadow-sm rounded-xl h-100 bg-white overflow-hidden">
                                    <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                                        <h6 class="font-weight-bold text-dark mb-0">
                                            <i class="fas fa-briefcase text-secondary mr-2"></i> Work Type Wise Segmentation
                                        </h6>
                                        <span class="badge badge-light border text-dark px-2 py-1 font-weight-600">{{ $userReportData['workTypeSeg']->count() }} Types</span>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-items-center mb-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="border-0 px-4 py-2 text-xs font-weight-bold text-uppercase text-muted">Work Type</th>
                                                        <th class="border-0 px-3 py-2 text-xs font-weight-bold text-uppercase text-muted text-center">Count</th>
                                                        <th class="border-0 px-4 py-2 text-xs font-weight-bold text-uppercase text-muted text-right">Share</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($userReportData['workTypeSeg'] as $workTypeName => $seg)
                                                        <tr>
                                                            <td class="px-4 py-3 font-weight-600 text-dark">
                                                                <i class="fas fa-layer-group text-muted mr-2 font-size-sm"></i> {{ $workTypeName }}
                                                            </td>
                                                            <td class="px-3 py-3 text-center font-weight-bold text-dark">
                                                                {{ $seg['count'] }}
                                                            </td>
                                                            <td class="px-4 py-3 text-right">
                                                                <span class="font-weight-bold text-muted text-sm">{{ $seg['percentage'] }}%</span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="text-center py-3 text-muted">No data</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ROW C: PROJECT NAME & LOAN TYPE -->
                        <div class="row mb-4">
                            <!-- Project Name Wise Segmentation -->
                            <div class="col-12 col-lg-6 mb-4 mb-lg-0">
                                <div class="card border-0 shadow-sm rounded-xl h-100 bg-white overflow-hidden">
                                    <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="font-weight-bold text-dark mb-0">
                                                <i class="fas fa-city text-primary mr-2"></i> Project Name Wise Segmentation
                                            </h6>
                                            <small class="text-muted">Count grouped by project / property</small>
                                        </div>
                                        <span class="badge badge-primary-subtle px-2 py-1 font-weight-600">{{ $userReportData['projectSeg']->count() }} Projects</span>
                                    </div>
                                    <div class="card-body p-0" style="max-height: 340px; overflow-y: auto;">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-items-center mb-0">
                                                <thead class="thead-light sticky-top">
                                                    <tr>
                                                        <th class="border-0 px-4 py-2 text-xs font-weight-bold text-uppercase text-muted">Project Name</th>
                                                        <th class="border-0 px-3 py-2 text-xs font-weight-bold text-uppercase text-muted text-center">Count</th>
                                                        <th class="border-0 px-4 py-2 text-xs font-weight-bold text-uppercase text-muted text-right">Share</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($userReportData['projectSeg'] as $projectName => $seg)
                                                        <tr>
                                                            <td class="px-4 py-2 font-weight-600 text-dark">
                                                                @if($projectName === 'None')
                                                                    <span class="text-muted font-italic">Non-Project / Direct Property</span>
                                                                @else
                                                                    <i class="far fa-building text-primary mr-1 font-size-sm"></i> {{ $projectName }}
                                                                @endif
                                                            </td>
                                                            <td class="px-3 py-2 text-center font-weight-bold text-dark">
                                                                {{ $seg['count'] }}
                                                            </td>
                                                            <td class="px-4 py-2 text-right">
                                                                <span class="font-weight-bold text-muted text-sm">{{ $seg['percentage'] }}%</span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="text-center py-3 text-muted">No data</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Loan Type Wise Segmentation -->
                            <div class="col-12 col-lg-6">
                                <div class="card border-0 shadow-sm rounded-xl h-100 bg-white overflow-hidden">
                                    <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="font-weight-bold text-dark mb-0">
                                                <i class="fas fa-hand-holding-usd text-success mr-2"></i> Loan Type Wise Segmentation
                                            </h6>
                                            <small class="text-muted">Count grouped by loan category</small>
                                        </div>
                                        <span class="badge badge-success-subtle px-2 py-1 font-weight-600">{{ $userReportData['loanTypeSeg']->count() }} Loan Types</span>
                                    </div>
                                    <div class="card-body p-0" style="max-height: 340px; overflow-y: auto;">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-items-center mb-0">
                                                <thead class="thead-light sticky-top">
                                                    <tr>
                                                        <th class="border-0 px-4 py-2 text-xs font-weight-bold text-uppercase text-muted">Loan Type</th>
                                                        <th class="border-0 px-3 py-2 text-xs font-weight-bold text-uppercase text-muted text-center">Count</th>
                                                        <th class="border-0 px-4 py-2 text-xs font-weight-bold text-uppercase text-muted text-right">Share</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($userReportData['loanTypeSeg'] as $loanTypeName => $seg)
                                                        <tr>
                                                            <td class="px-4 py-2 font-weight-600 text-dark">
                                                                <span class="badge badge-light border text-dark font-weight-bold px-2 py-1">{{ $loanTypeName }}</span>
                                                            </td>
                                                            <td class="px-3 py-2 text-center font-weight-bold text-dark">
                                                                {{ $seg['count'] }}
                                                            </td>
                                                            <td class="px-4 py-2 text-right">
                                                                <span class="font-weight-bold text-muted text-sm">{{ $seg['percentage'] }}%</span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="text-center py-3 text-muted">No data</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 5. DETAILED WORK LOG FOR THIS USER -->
                        <div class="card border-0 shadow-sm rounded-xl mb-4 bg-white overflow-hidden">
                            <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between flex-wrap">
                                <div>
                                    <h6 class="font-weight-bold text-dark mb-0">
                                        <i class="fas fa-list-ul text-primary mr-2"></i> Individual Works Log (Showing Latest {{ $userReportData['recentWorks']->count() }})
                                    </h6>
                                    <small class="text-muted">Direct drilldown of files handled by {{ $userReportData['user']->name }} in this period</small>
                                </div>
                                <span class="badge badge-light border text-muted px-3 py-1 font-weight-normal mt-2 mt-sm-0">
                                    {{ $userReportData['totalWorks'] }} Total Works
                                </span>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-items-center mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="border-0 px-4 py-3 text-xs font-weight-bold text-uppercase text-muted">Work ID</th>
                                                <th class="border-0 px-3 py-3 text-xs font-weight-bold text-uppercase text-muted">Applicant</th>
                                                <th class="border-0 px-3 py-3 text-xs font-weight-bold text-uppercase text-muted">Bank Branch</th>
                                                <th class="border-0 px-3 py-3 text-xs font-weight-bold text-uppercase text-muted">Valuer</th>
                                                <th class="border-0 px-3 py-3 text-xs font-weight-bold text-uppercase text-muted">Loan Type</th>
                                                <th class="border-0 px-3 py-3 text-xs font-weight-bold text-uppercase text-muted">Status</th>
                                                <th class="border-0 px-3 py-3 text-xs font-weight-bold text-uppercase text-muted">Result</th>
                                                @if($userReportData['role'] === 'Reporter')
                                                    <th class="border-0 px-3 py-3 text-xs font-weight-bold text-uppercase text-muted text-center">Report Time</th>
                                                @elseif($userReportData['role'] === 'Checker')
                                                    <th class="border-0 px-3 py-3 text-xs font-weight-bold text-uppercase text-muted text-center">Check Time</th>
                                                @endif
                                                <th class="border-0 px-4 py-3 text-xs font-weight-bold text-uppercase text-muted text-right">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($userReportData['recentWorks'] as $w)
                                                <tr>
                                                    <td class="px-4 py-3">
                                                        <span class="badge badge-light border text-dark font-weight-bold">{{ $w->custom_id }}</span>
                                                    </td>
                                                    <td class="px-3 py-3 font-weight-600 text-dark">
                                                        {{ $w->name_of_applicant }}
                                                    </td>
                                                    <td class="px-3 py-3 text-sm text-dark">
                                                        {{ $w->bankBranch ? $w->bankBranch->name : ($w->bank_name ?: '-') }}
                                                    </td>
                                                    <td class="px-3 py-3">
                                                        <span class="badge badge-light border">{{ strtoupper($w->valuer ?: '-') }}</span>
                                                    </td>
                                                    <td class="px-3 py-3 text-sm">
                                                        {{ $w->loan_type ?: '-' }}
                                                    </td>
                                                    <td class="px-3 py-3">
                                                        <span class="badge badge-light border text-dark">{{ $w->status }}</span>
                                                    </td>
                                                    <td class="px-3 py-3">
                                                        @if($w->result === 'Positive')
                                                            <span class="badge badge-success-subtle text-success font-weight-600">Positive</span>
                                                        @elseif($w->result === 'Negative')
                                                            <span class="badge badge-danger-subtle text-danger font-weight-600">Negative</span>
                                                        @elseif($w->result === 'Canceled')
                                                            <span class="badge badge-secondary-subtle text-secondary font-weight-600">Canceled</span>
                                                        @else
                                                            <span class="text-muted text-xs">{{ $w->result ?: 'Pending' }}</span>
                                                        @endif
                                                    </td>
                                                    @if($userReportData['role'] === 'Reporter')
                                                        <td class="px-3 py-3 text-center text-sm font-weight-600">
                                                            {{ $w->reporting_duration_minutes ? $w->reporting_duration_minutes . 'm' : '-' }}
                                                        </td>
                                                    @elseif($userReportData['role'] === 'Checker')
                                                        <td class="px-3 py-3 text-center text-sm font-weight-600">
                                                            {{ $w->checking_duration_minutes ? $w->checking_duration_minutes . 'm' : '-' }}
                                                        </td>
                                                    @endif
                                                    <td class="px-4 py-3 text-right">
                                                        <a href="{{ route('works.show', $w->id) }}" target="_blank" class="btn btn-xs btn-outline-primary" title="View Work Details">
                                                            <i class="fas fa-eye"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center py-4 text-muted">
                                                        No works logged for this period.
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
            </div>
        </div>
    </div>
</div>

<script>
    function handlePeriodChange(val) {
        if (val !== 'custom') {
            document.getElementById('dailyReportFilterForm').submit();
        } else {
            document.getElementById('dateFromInput').focus();
        }
    }

    function markCustomPeriod() {
        document.getElementById('periodSelect').value = 'custom';
    }

    function toggleCustomRange() {
        document.getElementById('periodSelect').value = 'custom';
        document.getElementById('dateFromInput').focus();
    }

    // Dynamic Role -> User selection for User Wise Report
    const usersByRoleMap = @json($usersByRole ?? []);
    const preselectedUserId = @json($selectedUserId ?? null);

    function onRoleChange(role) {
        const userSelect = document.getElementById('selectUserId');
        if (!userSelect) return;
        
        userSelect.innerHTML = '';
        
        if (!role || !usersByRoleMap[role] || usersByRoleMap[role].length === 0) {
            const defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.textContent = role ? '-- No staff found for role ' + role + ' --' : '-- Select a role first --';
            userSelect.appendChild(defaultOpt);
            return;
        }
        
        const chooseOpt = document.createElement('option');
        chooseOpt.value = '';
        chooseOpt.textContent = '-- Choose ' + role + ' (' + usersByRoleMap[role].length + ' staff) --';
        userSelect.appendChild(chooseOpt);
        
        usersByRoleMap[role].forEach(function(u) {
            const opt = document.createElement('option');
            opt.value = u.id;
            opt.textContent = u.name + (u.email ? ' (' + u.email + ')' : '');
            if (preselectedUserId && parseInt(preselectedUserId) === parseInt(u.id)) {
                opt.selected = true;
            }
            userSelect.appendChild(opt);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('selectUserRole');
        if (roleSelect && roleSelect.value) {
            onRoleChange(roleSelect.value);
        }
    });
</script>

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
    .badge-purple-subtle  { background-color: #f3e8fd; color: #6f42c1; }
    .border-top-purple    { border-top: 4px solid #6f42c1 !important; }
    .bg-light-purple      { background-color: #f3e8fd !important; }
    .text-purple          { color: #6f42c1 !important; }

    .btn-xs {
        padding: 0.2rem 0.5rem;
        font-size: 0.75rem;
        line-height: 1.3;
        border-radius: 4px;
    }

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
