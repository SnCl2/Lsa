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
            <div class="card border-0 shadow-sm rounded-lg h-100 py-3 border-left-primary card-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between pr-4">
                        <div>
                            <div class="text-xs font-weight-bold text-contrast-primary text-uppercase mb-1" style="letter-spacing: 0.8px;">Created</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-900">{{ $createdCount }}</div>
                        </div>
                    </div>
                    <i class="fas fa-folder-plus card-icon text-contrast-primary"></i>
                </div>
            </div>
        </div>

        <!-- Surveyed Card -->
        <div class="col-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm rounded-lg h-100 py-3 border-left-info card-info">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between pr-4">
                        <div>
                            <div class="text-xs font-weight-bold text-contrast-info text-uppercase mb-1" style="letter-spacing: 0.8px;">Surveyed</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-900">{{ $surveyedCount }}</div>
                        </div>
                    </div>
                    <i class="fas fa-map-marked-alt card-icon text-contrast-info"></i>
                </div>
            </div>
        </div>

        <!-- Reported Card -->
        <div class="col-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm rounded-lg h-100 py-3 border-left-warning card-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between pr-4">
                        <div>
                            <div class="text-xs font-weight-bold text-contrast-warning text-uppercase mb-1" style="letter-spacing: 0.8px;">Reported</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-900">{{ $reportedCount }}</div>
                        </div>
                    </div>
                    <i class="fas fa-file-invoice card-icon text-contrast-warning"></i>
                </div>
            </div>
        </div>

        <!-- Checked Card -->
        <div class="col-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm rounded-lg h-100 py-3 border-left-success card-success">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between pr-4">
                        <div>
                            <div class="text-xs font-weight-bold text-contrast-success text-uppercase mb-1" style="letter-spacing: 0.8px;">Checked</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-900">{{ $checkedCount }}</div>
                        </div>
                    </div>
                    <i class="fas fa-user-check card-icon text-contrast-success"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Delivered Card -->
        <div class="col-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm rounded-lg h-100 py-3 border-left-dark card-dark">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between pr-4">
                        <div>
                            <div class="text-xs font-weight-bold text-contrast-dark text-uppercase mb-1" style="letter-spacing: 0.8px;">Delivered</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-900">{{ $deliveredCount }}</div>
                        </div>
                    </div>
                    <i class="fas fa-shipping-fast card-icon text-contrast-dark"></i>
                </div>
            </div>
        </div>

        <!-- Canceled Card -->
        <div class="col-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm rounded-lg h-100 py-3 border-left-danger card-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between pr-4">
                        <div>
                            <div class="text-xs font-weight-bold text-contrast-danger text-uppercase mb-1" style="letter-spacing: 0.8px;">Canceled</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-900">{{ $canceledCount }}</div>
                        </div>
                    </div>
                    <i class="fas fa-ban card-icon text-contrast-danger"></i>
                </div>
            </div>
        </div>

        <!-- Positive Card -->
        <div class="col-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm rounded-lg h-100 py-3 border-left-teal card-teal">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between pr-4">
                        <div>
                            <div class="text-xs font-weight-bold text-contrast-teal text-uppercase mb-1" style="letter-spacing: 0.8px;">Positive Result</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-900">{{ $positiveCount }}</div>
                        </div>
                    </div>
                    <i class="fas fa-check-circle card-icon text-contrast-teal"></i>
                </div>
            </div>
        </div>

        <!-- Negative Card -->
        <div class="col-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm rounded-lg h-100 py-3 border-left-orange card-orange">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between pr-4">
                        <div>
                            <div class="text-xs font-weight-bold text-contrast-orange text-uppercase mb-1" style="letter-spacing: 0.8px;">Negative Result</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-900">{{ $negativeCount }}</div>
                        </div>
                    </div>
                    <i class="fas fa-times-circle card-icon text-contrast-orange"></i>
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
    /* Card Styling & Hover Animations */
    .card {
        position: relative;
        overflow: hidden;
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08) !important;
    }

    /* Left Accent Borders (High Contrast) */
    .border-left-primary { border-left: 5px solid #3f51b5 !important; }
    .border-left-info { border-left: 5px solid #0d9488 !important; }
    .border-left-warning { border-left: 5px solid #b45309 !important; }
    .border-left-success { border-left: 5px solid #047857 !important; }
    .border-left-dark { border-left: 5px solid #1f2937 !important; }
    .border-left-danger { border-left: 5px solid #be123c !important; }
    .border-left-teal { border-left: 5px solid #0f766e !important; }
    .border-left-orange { border-left: 5px solid #c2410c !important; }

    /* Card Background Tints */
    .card-primary { background-color: rgba(63, 81, 181, 0.03) !important; }
    .card-info { background-color: rgba(13, 148, 136, 0.03) !important; }
    .card-warning { background-color: rgba(180, 83, 9, 0.03) !important; }
    .card-success { background-color: rgba(4, 120, 87, 0.03) !important; }
    .card-dark { background-color: rgba(31, 41, 55, 0.03) !important; }
    .card-danger { background-color: rgba(190, 18, 60, 0.03) !important; }
    .card-teal { background-color: rgba(15, 118, 110, 0.03) !important; }
    .card-orange { background-color: rgba(194, 65, 12, 0.03) !important; }

    /* High Contrast Text Labels */
    .text-contrast-primary { color: #1e3a8a !important; }
    .text-contrast-info { color: #0f766e !important; }
    .text-contrast-warning { color: #7c2d12 !important; }
    .text-contrast-success { color: #064e3b !important; }
    .text-contrast-dark { color: #111827 !important; }
    .text-contrast-danger { color: #881337 !important; }
    .text-contrast-teal { color: #115e59 !important; }
    .text-contrast-orange { color: #7c2d12 !important; }

    /* Absolute Watermark Card Icons */
    .card-icon {
        position: absolute;
        right: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 2.25rem;
        opacity: 0.16;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
    }
    
    .card:hover .card-icon {
        transform: translateY(-50%) scale(1.2);
        opacity: 0.28;
    }

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
