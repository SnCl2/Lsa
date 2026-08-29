@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-md-5">
    <!-- Breadcrumbs & Quick Date Nav -->
    <div class="row align-items-center justify-content-between mb-4 no-print">
        <div class="col-12 col-md-auto mb-2 mb-md-0">
            <h1 class="h2 text-primary font-weight-bold mb-0">Daily Activity Report</h1>
            <p class="text-muted mb-0">Detailed operations summary for the selected date.</p>
        </div>
        <div class="col-12 col-md-auto">
            <form action="{{ route('works.daily-report') }}" method="GET" class="form-inline bg-white p-3 rounded-lg shadow-sm border border-light">
                <div class="btn-group mr-2" role="group">
                    <a href="{{ route('works.daily-report', ['date' => \Carbon\Carbon::parse($dateStr)->subDay()->toDateString()]) }}" class="btn btn-outline-secondary btn-sm" title="Previous Day">
                        <i class="fas fa-chevron-left"></i> Prev
                    </a>
                    <a href="{{ route('works.daily-report', ['date' => \Carbon\Carbon::today()->toDateString()]) }}" class="btn btn-outline-secondary btn-sm {{ $dateStr === \Carbon\Carbon::today()->toDateString() ? 'active font-weight-bold' : '' }}">
                        Today
                    </a>
                    <a href="{{ route('works.daily-report', ['date' => \Carbon\Carbon::parse($dateStr)->addDay()->toDateString()]) }}" class="btn btn-outline-secondary btn-sm" title="Next Day">
                        Next <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <div class="form-group mr-2">
                    <input type="date" name="date" class="form-control form-control-sm border-secondary" value="{{ $dateStr }}" onchange="this.form.submit()">
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Go</button>
            </form>
        </div>
    </div>

    <!-- Print Header (only visible on print) -->
    <div class="print-header d-none text-center mb-4">
        <h2>KKDA LSA DAILY OPERATIONS REPORT</h2>
        <h4>Date: {{ \Carbon\Carbon::parse($dateStr)->format('F d, Y') }}</h4>
        <hr class="border-secondary">
    </div>

    <!-- Actions Row -->
    <div class="row mb-4 justify-content-end no-print">
        <div class="col-auto">
            <a href="{{ route('works.daily-report', ['date' => $dateStr, 'action' => 'export']) }}" class="btn btn-success shadow-sm mr-2">
                <i class="fas fa-file-csv mr-1"></i> Export CSV
            </a>
            <button onclick="window.print()" class="btn btn-info shadow-sm text-white">
                <i class="fas fa-print mr-1"></i> Print Report
            </button>
        </div>
    </div>

    <!-- Summary Statistics Grid -->
    <div class="row mb-4">
        <!-- Created Card -->
        <div class="col-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm rounded-lg h-100 py-2 border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Created</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $createdCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder-plus fa-2x text-primary-light"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Surveyed Card -->
        <div class="col-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm rounded-lg h-100 py-2 border-left-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Surveyed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $surveyedCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-map-marked-alt fa-2x text-info-light"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reported Card -->
        <div class="col-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm rounded-lg h-100 py-2 border-left-warning">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Reported</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $reportedCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-warning-light"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checked Card -->
        <div class="col-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm rounded-lg h-100 py-2 border-left-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Checked</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $checkedCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-success-light"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Delivered Card -->
        <div class="col-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm rounded-lg h-100 py-2 border-left-dark">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Delivered</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $deliveredCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shipping-fast fa-2x text-dark-light"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Canceled Card -->
        <div class="col-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm rounded-lg h-100 py-2 border-left-danger">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Canceled</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $canceledCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ban fa-2x text-danger-light"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Positive Card -->
        <div class="col-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm rounded-lg h-100 py-2 border-left-teal">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-teal text-uppercase mb-1">Positive Result</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $positiveCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-teal-light"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Negative Card -->
        <div class="col-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm rounded-lg h-100 py-2 border-left-orange">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-orange text-uppercase mb-1">Negative Result</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $negativeCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-orange-light"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Productivity Leaderboard -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h3 class="h5 text-primary font-weight-bold mb-0">
                        <i class="fas fa-chart-line mr-2"></i> Staff Productivity Leaderboard
                    </h3>
                    <span class="badge badge-pill badge-primary-light no-print">Performance breakdown</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-items-center mb-0">
                            <thead class="bg-light-blue text-primary font-weight-bold">
                                <tr>
                                    <th class="py-3">Staff Name</th>
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
                                    <td class="font-weight-bold py-3">
                                        <i class="fas fa-user-circle mr-2 text-secondary"></i> {{ $activity['name'] }}
                                    </td>
                                    <td>
                                        <span class="badge badge-pill badge-secondary">{{ $activity['role'] }}</span>
                                    </td>
                                    <td class="text-center font-weight-bold text-primary">{{ $activity['created'] ?? 0 }}</td>
                                    <td class="text-center font-weight-bold text-info">{{ $activity['surveyed'] ?? 0 }}</td>
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
    </div>

    <!-- Detailed Activity Logs -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h3 class="h5 text-primary font-weight-bold mb-0">
                        <i class="fas fa-list-alt mr-2"></i> Detailed Activity Logs
                    </h3>
                    <span class="badge badge-success">{{ $detailedWorks->count() }} total files active</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-items-center mb-0">
                            <thead class="bg-light-blue text-primary font-weight-bold">
                                <tr>
                                    <th class="py-3">Custom ID</th>
                                    <th>Applicant</th>
                                    <th>In-Charge</th>
                                    <th>Surveyor & Time</th>
                                    <th>Reporter & Duration</th>
                                    <th>Checker & Duration</th>
                                    <th>Delivery Status</th>
                                    <th>Result / Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($detailedWorks as $work)
                                <tr>
                                    <td class="font-weight-bold py-3 text-nowrap">
                                        <a href="{{ route('works.show', $work->id) }}" class="text-decoration-none text-primary" target="_blank">
                                            <i class="fas fa-external-link-alt mr-1 no-print"></i> {{ $work->custom_id }}
                                        </a>
                                    </td>
                                    <td>{{ $work->name_of_applicant }}</td>
                                    <td>{{ $work->creator->name ?? '-' }}</td>
                                    <td>
                                        @if($work->surveyor)
                                            <div class="font-weight-bold text-dark">{{ $work->surveyor->name }}</div>
                                            @if($work->inspection)
                                                <small class="text-muted"><i class="far fa-clock"></i> Surveyed: {{ $work->inspection->created_at->format('h:i A') }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
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
                                    <td>
                                        @if($work->delivery_status === 'Delivery Done')
                                            <span class="badge badge-success"><i class="fas fa-check mr-1"></i> Delivered</span>
                                            @if($work->deliveryPerson)
                                                <small class="text-muted d-block">{{ $work->deliveryPerson->name }}</small>
                                            @endif
                                        @else
                                            <span class="badge badge-warning"><i class="fas fa-hourglass-half mr-1"></i> {{ $work->delivery_status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($work->result)
                                            <span class="badge badge-pill badge-{{ 
                                                $work->result === 'Positive' ? 'success' : (
                                                $work->result === 'Negative' ? 'danger' : (
                                                $work->result === 'Canceled' ? 'secondary' : 'warning'))
                                            }}">{{ $work->result }}</span>
                                        @endif
                                        @if($work->remarks)
                                            <small class="text-muted d-block mt-1 text-wrap" style="max-width: 200px;">{{ Str::limit($work->remarks, 50) }}</small>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle mr-2"></i> No active works recorded for this date.
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

<style>
    /* Styling tokens */
    .border-left-primary { border-left: 4px solid #3f51b5 !important; }
    .border-left-info { border-left: 4px solid #36b9cc !important; }
    .border-left-warning { border-left: 4px solid #f6c23e !important; }
    .border-left-success { border-left: 4px solid #1cc88a !important; }
    .border-left-dark { border-left: 4px solid #5a5c69 !important; }
    .border-left-danger { border-left: 4px solid #e74a3b !important; }
    .border-left-teal { border-left: 4px solid #20c997 !important; }
    .border-left-orange { border-left: 4px solid #fd7e14 !important; }

    .text-teal { color: #20c997 !important; }
    .text-orange { color: #fd7e14 !important; }

    .text-primary-light { color: #d9e2ec; }
    .text-info-light { color: #e0f2f1; }
    .text-warning-light { color: #fffde7; }
    .text-success-light { color: #e8f5e9; }
    .text-dark-light { color: #eceff1; }
    .text-danger-light { color: #ffebee; }
    .text-teal-light { color: #e0f2f1; }
    .text-orange-light { color: #fff3e0; }

    .badge-primary-light {
        background-color: #e8eaf6;
        color: #3f51b5;
    }
    
    .bg-light-blue {
        background-color: #f0f2f8;
    }

    .form-control-sm {
        height: calc(1.5em + .5rem + 2px);
    }

    /* Print styles */
    @media print {
        .no-print {
            display: none !important;
        }
        body {
            background-color: #ffffff !important;
            color: #000000 !important;
        }
        .container-fluid {
            padding: 0 !important;
            margin: 0 !important;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            margin-bottom: 20px !important;
        }
        .card-header {
            border-bottom: 1px solid #ddd !important;
        }
        .table-responsive {
            overflow: visible !important;
        }
        .table {
            border-collapse: collapse !important;
            width: 100% !important;
        }
        .table th, .table td {
            border: 1px solid #ddd !important;
            padding: 8px !important;
        }
        .print-header {
            display: block !important;
        }
    }
</style>
@endsection
