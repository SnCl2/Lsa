@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid py-5" style="max-width: 1400px;">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5">
        <div>
            <h2 class="font-weight-bolder text-dark mb-1" style="letter-spacing: -0.5px;">Analytics Dashboard</h2>
            <p class="text-muted mb-0">Overview of operational workflow and financial metrics.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm font-weight-bold">
                <i class="fas fa-arrow-left mr-1"></i> Back to Hub
            </a>
        </div>
    </div>

    <!-- Top KPI Row -->
    <div class="row mb-4">
        <!-- Work Volume -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="premium-card h-100 border-left-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-uppercase text-muted font-weight-bold mb-0">Total Work Volume</h6>
                        <div class="icon-circle bg-primary-soft text-primary">
                            <i class="fas fa-briefcase"></i>
                        </div>
                    </div>
                    <h3 class="font-weight-bolder text-dark mb-1">{{ number_format($thisMonthCount) }}</h3>
                    <p class="small mb-0 {{ $volumeTrend >= 0 ? 'text-success' : 'text-danger' }}">
                        <i class="fas {{ $volumeTrend >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                        <strong>{{ abs($volumeTrend) }}%</strong> from last month
                    </p>
                </div>
            </div>
        </div>

        <!-- Financial Overview -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="premium-card h-100 border-left-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-uppercase text-muted font-weight-bold mb-0">Invoiced (This Mth)</h6>
                        <div class="icon-circle bg-success-soft text-success">
                            <i class="fas fa-rupee-sign"></i>
                        </div>
                    </div>
                    <h3 class="font-weight-bolder text-dark mb-1">₹{{ number_format($invoicedThisMonth, 2) }}</h3>
                    <p class="small mb-0 text-warning font-weight-bold">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $pendingPaymentCount }} works pending payment
                    </p>
                </div>
            </div>
        </div>

        <!-- Operational Efficiency -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="premium-card h-100 border-left-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-uppercase text-muted font-weight-bold mb-0">Avg Turnaround</h6>
                        <div class="icon-circle bg-info-soft text-info">
                            <i class="fas fa-stopwatch"></i>
                        </div>
                    </div>
                    <h3 class="font-weight-bolder text-dark mb-1">{{ $avgTurnaroundHours }} <span style="font-size: 1rem;">hrs</span></h3>
                    <p class="small text-muted mb-0">Avg time reporting & checking</p>
                </div>
            </div>
        </div>

        <!-- Pending Deliverables -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="premium-card h-100 border-left-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-uppercase text-muted font-weight-bold mb-0">Pending Deliveries</h6>
                        <div class="icon-circle bg-danger-soft text-danger">
                            <i class="fas fa-truck-loading"></i>
                        </div>
                    </div>
                    <h3 class="font-weight-bolder text-dark mb-1">{{ number_format($pendingDeliverables) }}</h3>
                    <p class="small text-muted mb-0">Requires final delivery/printing</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4">
            <div class="premium-card h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h6 class="text-uppercase text-dark font-weight-bold mb-0">Work Inflow Trend (Last 6 Months)</h6>
                </div>
                <div class="card-body">
                    <canvas id="inflowChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="premium-card h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h6 class="text-uppercase text-dark font-weight-bold mb-0">Pipeline Status</h6>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="premium-card h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h6 class="text-uppercase text-dark font-weight-bold mb-0">Top 5 Bank Branches Requesting Work</h6>
                </div>
                <div class="card-body">
                    <canvas id="branchesChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="premium-card h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h6 class="text-uppercase text-dark font-weight-bold mb-0">Properties Evaluated by Type</h6>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="propertyChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Premium Aesthetic Variables & Base */
:root {
    --bg-light: #eef2f6;
    --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -1px rgba(0, 0, 0, 0.04);
}

body {
    background-color: var(--bg-light) !important;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

.text-muted { color: #475569 !important; }

/* Cards */
.premium-card {
    border-radius: 16px;
    border: 1px solid #d1d5db;
    background: #ffffff;
    box-shadow: var(--card-shadow);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.premium-card:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Specific Accent Borders */
.border-left-primary { border-left: 4px solid #0d6efd !important; }
.border-left-success { border-left: 4px solid #198754 !important; }
.border-left-info { border-left: 4px solid #0dcaf0 !important; }
.border-left-danger { border-left: 4px solid #dc3545 !important; }

/* Soft Colors */
.bg-primary-soft { background-color: #cfe2ff; }
.text-primary { color: #084298 !important; }

.bg-success-soft { background-color: #d1e7dd; }
.text-success { color: #0f5132 !important; }

.bg-info-soft { background-color: #cff4fc; }
.text-info { color: #055160 !important; }

.bg-danger-soft { background-color: #f8d7da; }
.text-danger { color: #842029 !important; }

/* Icons */
.icon-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Inflow Line Chart
    const inflowCtx = document.getElementById('inflowChart').getContext('2d');
    
    // Create gradient
    let gradient = inflowCtx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(13, 110, 253, 0.5)'); // primary color fading out
    gradient.addColorStop(1, 'rgba(13, 110, 253, 0.0)');

    new Chart(inflowCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_reverse($months)) !!},
            datasets: [{
                label: 'Works Assigned',
                data: {!! json_encode(array_reverse($inflowData)) !!},
                borderColor: '#0d6efd',
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#0d6efd',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#e2e8f0' } },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. Status Doughnut Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusDataRaw = {!! json_encode($statusDistribution) !!};
    
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusDataRaw),
            datasets: [{
                data: Object.values(statusDataRaw),
                backgroundColor: [
                    '#0d6efd', '#198754', '#ffc107', '#dc3545', '#0dcaf0', '#6f42c1', '#fd7e14'
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            cutout: '75%',
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true } }
            }
        }
    });

    // 3. Top Branches Bar Chart
    const branchCtx = document.getElementById('branchesChart').getContext('2d');
    const branchDataRaw = {!! json_encode($topBranches) !!};

    new Chart(branchCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(branchDataRaw),
            datasets: [{
                label: 'Works Requested',
                data: Object.values(branchDataRaw),
                backgroundColor: '#6f42c1',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#e2e8f0' } },
                x: { grid: { display: false } }
            }
        }
    });

    // 4. Property Types Polar/Doughnut Chart
    const propertyCtx = document.getElementById('propertyChart').getContext('2d');
    const propertyDataRaw = {!! json_encode($propertyTypes) !!};

    new Chart(propertyCtx, {
        type: 'pie',
        data: {
            labels: Object.keys(propertyDataRaw),
            datasets: [{
                data: Object.values(propertyDataRaw),
                backgroundColor: [
                    '#20c997', '#fd7e14', '#e83e8c', '#6610f2', '#0dcaf0'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'right', labels: { boxWidth: 12, usePointStyle: true } }
            }
        }
    });
});
</script>
@endsection
