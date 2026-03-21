@extends('layouts.app')

@section('content')
<div class="container-fluid mt-5" style="padding-left: 10px; padding-right: 10px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Work Management</h1>
        <a href="{{ route('works.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Work
        </a>
    </div>
    
    @if(auth()->user()->roles->contains('name', 'Super Admin') || 
        auth()->user()->roles->contains('name', 'In-Charge') || 
        auth()->user()->roles->contains('name', 'Reporter') || 
        auth()->user()->roles->contains('name', 'Surveyor') || 
        auth()->user()->roles->contains('name', 'Checker') || 
        auth()->user()->roles->contains('name', 'Delivery Person') || 
        auth()->user()->roles->contains('name', 'KKDA Admin'))
    <!-- Status Navigation Buttons -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
                @php
                    $statusCounts = isset($statusCounts) ? $statusCounts : [];
                    $resultCounts = isset($resultCounts) ? $resultCounts : [];
                    $currentStatus = request('status');
                    $currentResult = request('result');
                    $currentRouteName = request()->route()->getName();
                    // Determine the correct route for filtering based on current route
                    $statusRoute = 'works.index'; // default
                    if ($currentRouteName === 'works.reporter') {
                        $statusRoute = 'works.reporter';
                    } elseif ($currentRouteName === 'works.surveyor') {
                        $statusRoute = 'works.surveyor';
                    } elseif ($currentRouteName === 'works.checking') {
                        $statusRoute = 'works.checking';
                    } elseif ($currentRouteName === 'works.delivery') {
                        $statusRoute = 'works.delivery';
                    } elseif ($currentRouteName === 'works.bankBranch') {
                        $statusRoute = 'works.bankBranch';
                    } elseif ($currentRouteName === 'works.myWorks') {
                        $statusRoute = 'works.myWorks';
                    }
                @endphp
                <a href="{{ route($statusRoute) }}" 
                   class="btn btn-sm {{ !$currentStatus && !$currentResult ? 'btn-primary' : 'btn-outline-secondary' }}">
                    <i class="fas fa-list"></i> All Works
                    <span class="badge badge-light ml-2">{{ $works->total() }}</span>
                </a>
                <a href="{{ route($statusRoute, ['status' => 'New File']) }}" 
                   class="btn btn-sm {{ $currentStatus == 'New File' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="fas fa-file"></i> New File
                    <span class="badge badge-light ml-2">{{ $statusCounts['New File'] ?? 0 }}</span>
                </a>
                <a href="{{ route($statusRoute, ['status' => 'Surveying']) }}" 
                   class="btn btn-sm {{ $currentStatus == 'Surveying' ? 'btn-info' : 'btn-outline-info' }}">
                    <i class="fas fa-search"></i> Surveying
                    <span class="badge badge-light ml-2">{{ $statusCounts['Surveying'] ?? 0 }}</span>
                </a>
                <a href="{{ route($statusRoute, ['status' => 'Reporting']) }}" 
                   class="btn btn-sm {{ $currentStatus == 'Reporting' ? 'btn-warning' : 'btn-outline-warning' }}">
                    <i class="fas fa-file-alt"></i> Reporting
                    <span class="badge badge-light ml-2">{{ $statusCounts['Reporting'] ?? 0 }}</span>
                </a>
                <a href="{{ route($statusRoute, ['status' => 'Checking']) }}" 
                   class="btn btn-sm {{ $currentStatus == 'Checking' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                    <i class="fas fa-check-circle"></i> Checking
                    <span class="badge badge-light ml-2">{{ $statusCounts['Checking'] ?? 0 }}</span>
                </a>
                <a href="{{ route($statusRoute, ['is_printed' => '0']) }}" 
                   class="btn btn-sm {{ request('is_printed') === '0' && !$currentStatus && !$currentResult ? 'btn-warning' : 'btn-outline-warning' }}">
                    <i class="fas fa-clock"></i> Pending Printing
                    <span class="badge badge-light ml-2">{{ $pendingPrintCount ?? 0 }}</span>
                </a>
                <a href="{{ route($statusRoute, ['status' => 'Printing']) }}" 
                   class="btn btn-sm {{ $currentStatus == 'Printing' ? 'btn-warning' : 'btn-outline-warning' }}">
                    <i class="fas fa-print"></i> Printing
                    <span class="badge badge-light ml-2">{{ $statusCounts['Printing'] ?? 0 }}</span>
                </a>
                <a href="{{ route($statusRoute, ['status' => 'Completed']) }}" 
                   class="btn btn-sm {{ $currentStatus == 'Completed' ? 'btn-success' : 'btn-outline-success' }}">
                    <i class="fas fa-check-double"></i> Completed
                    <span class="badge badge-light ml-2">{{ $statusCounts['Completed'] ?? 0 }}</span>
                </a>
                <a href="{{ route($statusRoute, ['status' => 'Hold']) }}" 
                   class="btn btn-sm {{ $currentStatus == 'Hold' ? 'btn-danger' : 'btn-outline-danger' }}">
                    <i class="fas fa-pause"></i> Hold
                    <span class="badge badge-light ml-2">{{ $statusCounts['Hold'] ?? 0 }}</span>
                </a>
                <a href="{{ route($statusRoute, ['status' => 'Canceled']) }}" 
                   class="btn btn-sm {{ $currentStatus == 'Canceled' ? 'btn-dark' : 'btn-outline-dark' }}">
                    <i class="fas fa-times-circle"></i> Canceled
                    <span class="badge badge-light ml-2">{{ $statusCounts['Canceled'] ?? 0 }}</span>
                </a>
                
                <!-- Result Filter Buttons -->
                <span style="border-left: 2px solid #dee2e6; height: 30px; margin: 0 8px; display: inline-block; vertical-align: middle;"></span>
                <a href="{{ route($statusRoute, ['result' => 'Positive']) }}" 
                   class="btn btn-sm {{ $currentResult == 'Positive' ? 'btn-success' : 'btn-outline-success' }}">
                    <i class="fas fa-check-circle"></i> Positive
                    <span class="badge badge-light ml-2">{{ ($resultCounts['Positive'] ?? 0) + ($resultCounts['+ve'] ?? 0) }}</span>
                </a>
                <a href="{{ route($statusRoute, ['result' => 'Negative']) }}" 
                   class="btn btn-sm {{ $currentResult == 'Negative' ? 'btn-danger' : 'btn-outline-danger' }}">
                    <i class="fas fa-times-circle"></i> Negative
                    <span class="badge badge-light ml-2">{{ ($resultCounts['Negative'] ?? 0) + ($resultCounts['-ve'] ?? 0) }}</span>
                </a>
                <a href="{{ route($statusRoute, ['result' => 'Return']) }}" 
                   class="btn btn-sm {{ $currentResult == 'Return' ? 'btn-warning' : 'btn-outline-warning' }}">
                    <i class="fas fa-undo"></i> Return
                    <span class="badge badge-light ml-2">{{ $resultCounts['Return'] ?? 0 }}</span>
                </a>
            </div>
        </div>
    </div>
    @endif
    
    @if(auth()->user()->roles->contains('name', 'Super Admin') || 
    auth()->user()->roles->contains('name', 'In-Charge') || 
    auth()->user()->roles->contains('name', 'Reporter') || 
    auth()->user()->roles->contains('name', 'Surveyor') || 
    auth()->user()->roles->contains('name', 'Checker') || 
    auth()->user()->roles->contains('name', 'Delivery Person') || 
    auth()->user()->roles->contains('name', 'KKDA Admin'))
    <!-- Filters Card -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-filter"></i> Filters & Search
            </h5>
        </div>
        <div class="card-body">
            @php
                $currentRouteName = request()->route()->getName();
                // Determine the correct route for filtering based on current route
                $filterRoute = 'works.index'; // default
                if ($currentRouteName === 'works.reporter') {
                    $filterRoute = 'works.reporter';
                } elseif ($currentRouteName === 'works.surveyor') {
                    $filterRoute = 'works.surveyor';
                } elseif ($currentRouteName === 'works.checking') {
                    $filterRoute = 'works.checking';
                } elseif ($currentRouteName === 'works.delivery') {
                    $filterRoute = 'works.delivery';
                } elseif ($currentRouteName === 'works.bankBranch') {
                    $filterRoute = 'works.bankBranch';
                } elseif ($currentRouteName === 'works.myWorks') {
                    $filterRoute = 'works.myWorks';
                }
            @endphp
            <form method="GET" action="{{ route($filterRoute) }}" class="filter-form">
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
                                   placeholder="Search Applicant, Project, Status, Custom ID, or Assignment Date..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="btn-group w-100" role="group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route($filterRoute) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                            @if(auth()->user()->roles->contains('name', 'Super Admin') || 
                                auth()->user()->roles->contains('name', 'KKDA Admin'))
                            <button type="button" class="btn btn-success" id="export-csv-btn">
                                <i class="fas fa-file-csv"></i> Export CSV
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Filter Grid -->
                <div class="row">
                    <!-- Status Filter -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            <i class="fas fa-tasks text-primary"></i> Status
                        </label>
                        <select class="form-control" name="status">
                            <option value="">All Statuses</option>
                            <option value="New File" {{ request('status') == 'New File' ? 'selected' : '' }}>📄 New File</option>
                            <option value="Surveying" {{ request('status') == 'Surveying' ? 'selected' : '' }}>🔍 Surveying</option>
                            <option value="Reporting" {{ request('status') == 'Reporting' ? 'selected' : '' }}>📝 Reporting</option>
                            <option value="Checking" {{ request('status') == 'Checking' ? 'selected' : '' }}>✅ Checking</option>
                            <option value="Printing" {{ request('status') == 'Printing' ? 'selected' : '' }}>🖨️ Printing</option>
                            <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>🎉 Completed</option>
                        </select>
                    </div>
                    
                    <!-- Bank Branch Filter -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            <i class="fas fa-university text-info"></i> Bank Branch
                        </label>
                        <select class="form-control" id="bank_branch" name="bank_branch">
                            <option value="">All Bank Branches</option>
                            @foreach($usersByRole['Bank Branch'] as $id => $name)
                                 <option value="{{ $id }}" {{ (string)request('bank_branch') === (string)$id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Work Type Filter -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            <i class="fas fa-briefcase text-warning"></i> Work Type
                        </label>
                        <select class="form-control" name="work_type">
                            <option value="">All Work Types</option>
                            <option value="Valuation" {{ request('work_type') == 'Valuation' ? 'selected' : '' }}>🏠 Valuation</option>
                            <option value="Fair Rent Valuation" {{ request('work_type') == 'Fair Rent Valuation' ? 'selected' : '' }}>💰 Fair Rent Valuation</option>
                            <option value="Estimate" {{ request('work_type') == 'Estimate' ? 'selected' : '' }}>📊 Estimate</option>
                            <option value="Completion Certificate" {{ request('work_type') == 'Completion Certificate' ? 'selected' : '' }}>📋 Completion Certificate</option>
                            <option value="Vetting" {{ request('work_type') == 'Vetting' ? 'selected' : '' }}>🔍 Vetting</option>
                        </select>
                    </div>
                    
                    <!-- Payment Status Filter -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            <i class="fas fa-credit-card text-success"></i> Payment Status
                        </label>
                        <select class="form-control" name="payment_status">
                            <option value="">All Payment Statuses</option>
                            <option value="Payment Due" {{ request('payment_status') == 'Payment Due' ? 'selected' : '' }}>💰 Payment Due</option>
                            <option value="Paid" {{ request('payment_status') == 'Paid' ? 'selected' : '' }}>✅ Paid</option>
                        </select>
                    </div>
                    
                    <!-- Delivery Status Filter -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            <i class="fas fa-truck text-primary"></i> Delivery Status
                        </label>
                        <select class="form-control" name="delivery_status">
                            <option value="">All Delivery Statuses</option>
                            <option value="Delivery Due" {{ request('delivery_status') == 'Delivery Due' ? 'selected' : '' }}>📦 Delivery Due</option>
                            <option value="Delivery Done" {{ request('delivery_status') == 'Delivery Done' ? 'selected' : '' }}>✅ Delivery Done</option>
                        </select>
                    </div>

                    <!-- Print Status Filter -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            <i class="fas fa-print text-info"></i> Print Status
                        </label>
                        <select class="form-control" name="is_printed">
                            <option value="">All Print Statuses</option>
                            <option value="1" {{ request('is_printed') == '1' ? 'selected' : '' }}>🖨️ Printed</option>
                            <option value="0" {{ request('is_printed') == '0' ? 'selected' : '' }}>📄 Not Printed</option>
                        </select>
                    </div>

                    <!-- Result Filter -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            <i class="fas fa-check-circle text-success"></i> Result
                        </label>
                        <select class="form-control" name="result">
                            <option value="">All Results</option>
                            <option value="Positive" {{ request('result') == 'Positive' ? 'selected' : '' }}>✅ Positive</option>
                            <option value="Negative" {{ request('result') == 'Negative' ? 'selected' : '' }}>❌ Negative</option>
                            <option value="Hold" {{ request('result') == 'Hold' ? 'selected' : '' }}>⏸️ Hold</option>
                            <option value="Canceled" {{ request('result') == 'Canceled' ? 'selected' : '' }}>🚫 Canceled</option>
                            <option value="Return" {{ request('result') == 'Return' ? 'selected' : '' }}>↩️ Return</option>
                        </select>
                    </div>

                    <!-- Valuer Filter -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            <i class="fas fa-user-tie text-primary"></i> Valuer
                        </label>
                        <select class="form-control" name="valuer">
                            <option value="">All Valuers</option>
                            <option value="a" {{ request('valuer') == 'a' ? 'selected' : '' }}>A</option>
                            <option value="b" {{ request('valuer') == 'b' ? 'selected' : '' }}>B</option>
                            <option value="c" {{ request('valuer') == 'c' ? 'selected' : '' }}>C</option>
                            <option value="d" {{ request('valuer') == 'd' ? 'selected' : '' }}>D</option>
                        </select>
                    </div>

                    <!-- Source Filter -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            <i class="fas fa-share-square text-secondary"></i> Source
                        </label>
                        <input type="text" class="form-control" name="source" placeholder="Search by Source" value="{{ request('source') }}">
                    </div>

                    <!-- Created Date From -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            <i class="fas fa-calendar-alt text-dark"></i> Created Date (From)
                        </label>
                        <input type="date" class="form-control" name="created_date_from" value="{{ request('created_date_from') }}">
                    </div>

                    <!-- Created Date To -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            <i class="fas fa-calendar-check text-dark"></i> Created Date (To)
                        </label>
                        <input type="date" class="form-control" name="created_date_to" value="{{ request('created_date_to') }}">
                    </div>

                    <!-- Report Submit Date From -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            <i class="fas fa-file-export text-info"></i> Report Submit Date (From)
                        </label>
                        <input type="date" class="form-control" name="report_submit_date_from" value="{{ request('report_submit_date_from') }}">
                    </div>

                    <!-- Report Submit Date To -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            <i class="fas fa-file-export text-info"></i> Report Submit Date (To)
                        </label>
                        <input type="date" class="form-control" name="report_submit_date_to" value="{{ request('report_submit_date_to') }}">
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
    
        
    <!-- Results Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-list"></i> Work Entries
                <span class="badge badge-light ml-2">{{ $works->total() }} Total</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-dark">
                    <tr>
                        <th>Date / Assignment Date</th>
                        <th>ID / Custom ID</th>
                        <th>Contact Name</th>
                        <th>Contact Number</th>
                        <th>Bank Branch</th>
                        <th>Loan Amount</th>
                        <th>Work Type</th>
                        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin'))
                        <th>Assigned Users</th>
                        
                        @endif
                        <th>Status</th>
                        <th>Printed</th>
                        <th>VDN</th>
                        @if(!auth()->user()->roles->contains('name', 'Bank Branch'))
                        <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach($works as $work)
                    @php
                        $statusColors = [
                            'New File' => 'table-primary',
                            'Surveying' => 'table-info',
                            'Reporting' => 'table-warning',
                            'Checking' => 'table-secondary',
                            'Printing' => 'table-warning',
                            'Completed' => 'table-success',
                            'Hold' => 'table-danger',
                            'Canceled' => 'table-dark',
                        ];
                        $rowClass = $statusColors[$work->status] ?? '';
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td>
                            <strong>Created:</strong> {{ $work->created_at->format('d/m/Y H:i') }}<br>
                            @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin'))
                            <strong>Assignment:</strong> 
                            @if($work->assignment_date)
                                @if(is_string($work->assignment_date))
                                    @php
                                        try {
                                            $date = \Carbon\Carbon::parse($work->assignment_date);
                                            echo $date->format('d/m/Y');
                                        } catch (\Exception $e) {
                                            echo $work->assignment_date;
                                        }
                                    @endphp
                                @else
                                    {{ $work->assignment_date->format('d/m/Y') }}
                                @endif
                            @else
                                N/A
                            @endif
                            @endif
                        </td>
                        <td>
                            <strong>ID:</strong> {{ $work->id }}<br>
                            @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin'))
                            <strong>Custom ID:</strong> {{ $work->custom_id ?? 'N/A' }}
                            @endif
                        </td>
                        <td>
                            {{ $work->name_of_applicant }}
                            @if($work->remarks)
                                <br><strong>Remarks:</strong> {{ $work->remarks }}
                            @endif
                        </td>
                        <td>{{ $work->number_of_applicants }}</td>
                        <td>{{ $work->bankBranch?->name ?? 'N/A' }}</td>
                        <td>{{ $work->loan_amount_requested }}</td>
                        <td>{{ $work->work_type }} <br>{{ $work->inspection->property_type ?? 'N/A'}} </td>
                        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin'))
                        <td>
                            <strong>In-Charge:</strong> {{ $work->creator?->name ?? 'N/A' }}<br>
                            <strong>Surveyor:</strong> {{ $work->surveyor?->name ?? 'N/A' }}<br>
                            <strong>Reporter:</strong> {{ $work->reporter?->name ?? 'N/A' }}<br>
                            <strong>Checker:</strong> {{ $work->checker?->name ?? 'N/A' }}<br>
                            <strong>Delivery:</strong> {{ $work->deliveryPerson?->name ?? 'N/A' }}
                        </td>
                        
                        @endif
                        <td>
                            @if(
                                !auth()->user()->roles->contains('name', 'Bank Branch')
                            )
                                <select class="form-control form-control-sm status-select" data-work-id="{{ $work->id }}" style="min-width: 120px;">
                                    <option value="New File" {{ $work->status == 'New File' ? 'selected' : '' }}>New File</option>
                                    <option value="Surveying" {{ $work->status == 'Surveying' ? 'selected' : '' }}>Surveying</option>
                                    <option value="Reporting" {{ $work->status == 'Reporting' ? 'selected' : '' }}>Reporting</option>
                                    <option value="Checking" {{ $work->status == 'Checking' ? 'selected' : '' }}>Checking</option>
                                    <option value="Printing" {{ $work->status == 'Printing' ? 'selected' : '' }}>Printing</option>
                                    <option value="Completed" {{ $work->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                {{-- Done button for New File and Surveying --}}
                                @if(in_array($work->status, ['New File', 'Surveying']))
                                    <button type="button" class="btn btn-success btn-sm mt-1 done-btn" data-work-id="{{ $work->id }}" title="Advance to next status">
                                        Done
                                    </button>
                                @endif
                                {{-- Reporting: Start Reporting (if reporter assigned) or Reporting Done (if started) --}}
                                @if($work->status === 'Reporting')
                                    @if($work->assignee_reporter && !$work->reporting_started_at)
                                        <button type="button" class="btn btn-warning btn-sm mt-1 start-reporting-btn" data-work-id="{{ $work->id }}" title="Start reporting timer">
                                            Start Reporting
                                        </button>
                                    @elseif($work->reporting_started_at && !$work->reporting_ended_at)
                                        <button type="button" class="btn btn-success btn-sm mt-1 end-reporting-btn" data-work-id="{{ $work->id }}" title="Mark reporting done, advance to Checking">
                                            Reporting Done
                                        </button>
                                    @elseif(!$work->assignee_reporter)
                                        <small class="d-block text-muted mt-1" title="Assign reporter to enable">Assign reporter first</small>
                                    @endif
                                @endif
                                {{-- Checking: Start Checking (if checker assigned) or End Checking (if started) --}}
                                @if($work->status === 'Checking')
                                    @if($work->assignee_checker && !$work->checking_started_at)
                                        <button type="button" class="btn btn-secondary btn-sm mt-1 start-checking-btn" data-work-id="{{ $work->id }}" title="Start checking timer">
                                            Start Checking
                                        </button>
                                    @elseif($work->checking_started_at && !$work->checking_ended_at)
                                        <button type="button" class="btn btn-success btn-sm mt-1 end-checking-btn" data-work-id="{{ $work->id }}" title="Mark checking done, advance to Printing">
                                            End Checking
                                        </button>
                                    @elseif(!$work->assignee_checker)
                                        <small class="d-block text-muted mt-1" title="Assign checker to enable">Assign checker first</small>
                                    @endif
                                @endif
                                {{-- Printing: Done advances to Completed --}}
                                @if($work->status === 'Printing')
                                    <button type="button" class="btn btn-success btn-sm mt-1 end-printing-btn" data-work-id="{{ $work->id }}" title="Mark printing done, advance to Completed">
                                        Printing Done
                                    </button>
                                @endif
                            @else
                                {{ $work->status }}
                                
                            @endif
                            @if($work->valuer)
                                <br><strong>Valuer:</strong> {{ strtoupper($work->valuer) }}
                            @endif
                            @if($work->result)
                                <br><strong>Result:</strong> {{ $work->result }}
                            @endif
                        
                            {{-- Download Buttons --}}
                            @if($work->final_report_word)
                                <a href="https://valuerkkda.com/public{{ Storage::url($work->final_report_word) }}" class="btn btn-outline-primary btn-sm mt-2" target="_blank">Download Word</a>
                            @endif
                            @if($work->final_report_pdf)
                                <a href="https://valuerkkda.com/public{{ Storage::url($work->final_report_pdf) }}" class="btn btn-outline-danger btn-sm mt-2" target="_blank">Download PDF</a>
                            @endif
                        
                            {{-- Upload Dropdown --}}
                            @if(auth()->user()->roles->contains('name', 'Reporter') || auth()->user()->roles->contains('name', 'Checker')|| auth()->user()->roles->contains('name', 'KKDA Admin'))
                                <div class="dropdown mt-2">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="uploadDropdown{{ $work->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        Upload Final Report
                                    </button>
                                    <div class="dropdown-menu p-3" aria-labelledby="uploadDropdown{{ $work->id }}" style="min-width: 300px;">
                                        <form action="{{ route('works.uploadDocuments', $work->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-2">
                                                <label for="final_report_word_{{ $work->id }}" class="form-label">Word</label>
                                                <input class="form-control form-control-sm" type="file" name="final_report_word" id="final_report_word_{{ $work->id }}" accept=".doc,.docx">
                                            </div>
                                            <div class="mb-2">
                                                <label for="final_report_pdf_{{ $work->id }}" class="form-label">PDF</label>
                                                <input class="form-control form-control-sm" type="file" name="final_report_pdf" id="final_report_pdf_{{ $work->id }}" accept=".pdf">
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input printed-toggle" type="checkbox" 
                                       data-work-id="{{ $work->id }}" 
                                       {{ $work->is_printed ? 'checked' : '' }}
                                       @if(auth()->user()->roles->contains('name', 'Bank Branch'))
                                       disabled
                                       @endif>
                                <label class="form-check-label">
                                    {{ $work->is_printed ? 'Yes' : 'No' }}
                                </label>
                            </div>
                            <small class="d-block text-muted">Required before Completed</small>
                            <small class="d-block text-muted mt-1">
                                @if($work->report_submit_date)
                                    @php
                                        try {
                                            $rsd = is_string($work->report_submit_date) ? \Carbon\Carbon::parse($work->report_submit_date) : $work->report_submit_date;
                                            echo $rsd->format('d/m/Y');
                                        } catch (\Exception $e) {
                                            echo '—';
                                        }
                                    @endphp
                                @else
                                    —
                                @endif
                            </small>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input vdn-toggle" type="checkbox" 
                                       data-work-id="{{ $work->id }}" 
                                       {{ $work->is_vdn ? 'checked' : '' }}
                                       @if(auth()->user()->roles->contains('name', 'Bank Branch'))
                                       disabled
                                       @endif>
                                <label class="form-check-label">
                                    {{ $work->is_vdn ? 'VD' : '' }}
                                </label>
                            </div>
                        </td>

                        @if(!auth()->user()->roles->contains('name', 'Bank Branch'))
                        <td>
                            @if(auth()->user()->roles->contains('name', 'Super Admin') || 
                                auth()->user()->roles->contains('name', 'KKDA Admin') ||
                                
                                auth()->user()->roles->contains('name', 'In-Charge'))
                                
                                <a href="{{ route('works.edit', $work->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('works.destroy', $work->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            @endif
                            <a href="{{ route('works.show', $work->id) }}" class="btn btn-info btn-sm">View</a>
                            
                            @if(
                            !auth()->user()->roles->contains('name', 'Bank Branch') && !auth()->user()->roles->contains('name', 'Delivery Person'))
                            <a href="{{ route('relatives.create', $work->id) }}" class="btn btn-primary btn-sm">Add Relative</a>
                            @endif
                            @if(
                            !auth()->user()->roles->contains('name', 'Bank Branch') && !auth()->user()->roles->contains('name', 'Delivery Person') && !auth()->user()->roles->contains('name', 'Surveyor'))
                            <a href="{{ route('report.select', ['workId' => $work->id]) }}" class="btn btn-primary btn-sm">Create Report</a>
                            @endif
                            @if(
                            !auth()->user()->roles->contains('name', 'Bank Branch') && !auth()->user()->roles->contains('name', 'Delivery Person') && !auth()->user()->roles->contains('name', 'Reporter'))
                            @if(!$work->inspection?->id)
                            <a href="{{ route('inspections.create', $work->id) }}" class="btn btn-secondary btn-sm">Add Survey</a>
                            @endif
                            @endif
                            @if(auth()->user()->roles->contains('name', 'Super Admin') || 
                                auth()->user()->roles->contains('name', 'KKDA Admin') || 
                                auth()->user()->roles->contains('name', 'In-Charge') ||
                                auth()->user()->roles->contains('name', 'Reporter') ||
                                auth()->user()->roles->contains('name', 'Surveyor')
                                
                                )
                            <a href="{{ route('documents.create', $work->id) }}" class="btn btn-primary btn-sm">Upload New Document</a>
                            @endif
                            @if($work->inspection?->id)
                                <a href="{{ route('inspections.show', $work->inspection->id) }}" class="btn btn-success btn-sm">View Survey Report</a>
                            @endif
                            @if($work->report?->id)
                                <a href="{{ route('report.select_edit', $work->report->id) }}" class="btn btn-warning btn-sm">Edit Report</a>
                            @endif
                            
                        
                            
                        </td>
                        @endif
                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        
        <!-- Pagination Footer -->
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing {{ $works->firstItem() ?? 0 }} to {{ $works->lastItem() ?? 0 }} of {{ $works->total() }} entries
                </div>
                <div>
                    {{ $works->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Styles for Improved UI */
.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
    border-bottom: 1px solid rgba(0,0,0,.125);
}

.form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #495057;
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #ced4da;
}

.btn-group .btn {
    border-radius: 0.375rem;
}

.btn-group .btn:first-child {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.btn-group .btn:last-child {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.9rem;
    color: #495057;
}

.table td {
    vertical-align: middle;
    border-color: #e9ecef;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,.075);
}

.badge {
    font-size: 0.75em;
}

/* Status Colors */
.table-primary { background-color: rgba(13,110,253,.1) !important; }
.table-info { background-color: rgba(13,202,240,.1) !important; }
.table-warning { background-color: rgba(255,193,7,.1) !important; }
.table-secondary { background-color: rgba(108,117,125,.1) !important; }
.table-success { background-color: rgba(25,135,84,.1) !important; }
.table-danger { background-color: rgba(220,53,69,.1) !important; }
.table-dark { background-color: rgba(33,37,41,.1) !important; }

/* Filter Card Styling */
.filter-form .form-control {
    border-radius: 0.375rem;
    border: 1px solid #ced4da;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.filter-form .form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        border-radius: 0.375rem !important;
        margin-bottom: 0.25rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle status dropdown changes
    document.querySelectorAll('.status-select').forEach(function(select) {
        select.addEventListener('change', function() {
            const workId = this.getAttribute('data-work-id');
            const newStatus = this.value;
            
            fetch(`/works/${workId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Optionally show a success message
                    console.log('Status updated successfully');
                    // Reload the page to reflect changes
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                    // Reload to revert change
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating status');
                location.reload();
            });
        });
    });

    // Handle Done button - advance to next status
    document.querySelectorAll('.done-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const workId = this.getAttribute('data-work-id');
            const btn = this;
            btn.disabled = true;
            btn.textContent = '...';

            fetch(`/works/${workId}/advance-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                    btn.disabled = false;
                    btn.textContent = 'Done';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while advancing status');
                btn.disabled = false;
                btn.textContent = 'Done';
            });
        });
    });

    // Helper for time-tracking button fetch
    function postTimeAction(url, btn, originalText) {
        btn.disabled = true;
        btn.textContent = '...';
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
                btn.disabled = false;
                btn.textContent = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
            btn.disabled = false;
            btn.textContent = originalText;
        });
    }

    document.querySelectorAll('.start-reporting-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            postTimeAction(`/works/${this.getAttribute('data-work-id')}/start-reporting`, this, 'Start Reporting');
        });
    });
    document.querySelectorAll('.end-reporting-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            postTimeAction(`/works/${this.getAttribute('data-work-id')}/end-reporting`, this, 'Reporting Done');
        });
    });
    document.querySelectorAll('.start-checking-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            postTimeAction(`/works/${this.getAttribute('data-work-id')}/start-checking`, this, 'Start Checking');
        });
    });
    document.querySelectorAll('.end-checking-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            postTimeAction(`/works/${this.getAttribute('data-work-id')}/end-checking`, this, 'End Checking');
        });
    });
    document.querySelectorAll('.end-printing-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            postTimeAction(`/works/${this.getAttribute('data-work-id')}/end-printing`, this, 'Printing Done');
        });
    });

    // Handle printed checkbox toggle
    document.querySelectorAll('.printed-toggle').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const workId = this.getAttribute('data-work-id');
            const isPrinted = this.checked;
            
            fetch(`/works/${workId}/toggle-printed`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    is_printed: isPrinted
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload to show report_submit_date when printed
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                    // Revert checkbox
                    this.checked = !isPrinted;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating print status');
                // Revert checkbox
                this.checked = !isPrinted;
            });
        });
    });

    // Handle VDN checkbox toggle
    document.querySelectorAll('.vdn-toggle').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const workId = this.getAttribute('data-work-id');
            const isVdn = this.checked;
            
            fetch(`/works/${workId}/toggle-vdn`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    is_vdn: isVdn
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the label text
                    const label = this.nextElementSibling;
                    if (label) {
                        label.textContent = isVdn ? 'VD' : '';
                    }
                } else {
                    alert('Error: ' + data.message);
                    // Revert checkbox
                    this.checked = !isVdn;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating VDN status');
                // Revert checkbox
                this.checked = !isVdn;
            });
        });
    });

    // Handle CSV export button
    const exportBtn = document.getElementById('export-csv-btn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            const form = document.querySelector('.filter-form');
            if (!form) return;
            
            // Collect all form data
            const formData = new FormData(form);
            const params = new URLSearchParams();
            
            // Add all form fields to params
            for (const [key, value] of formData.entries()) {
                if (value) {
                    params.append(key, value);
                }
            }
            
            // Build export URL with all query parameters
            const exportUrl = '{{ route("works.export") }}' + '?' + params.toString();
            
            // Open export URL in new window/tab to trigger download
            window.location.href = exportUrl;
        });
    }
});
</script>
@endsection
