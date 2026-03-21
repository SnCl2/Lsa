@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Dashboard</h2>

    <div class="row">
        <!-- Total Works -->
        

        <div class="col-md-3">
            <div class="card bg-primary text-white mb-3">
                <div class="card-body">
                    <a href="{{ route('works.index') }}" > <h5>Total Works</h5></a>
                    <h3>{{ $totalWorks }}</h3>
                </div>
            </div>
        </div>
        

        <!-- Status Counts -->
        @foreach($statusCounts as $status => $count)
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <a href="{{ route('works.status', ['status' => $status ]) }}" class="mb-3"><h5>{{ $status }}</h5></a>

                    
                    <h3>{{ $count }}</h3>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if((auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin')) && ($reportingTimeStats ?? null))
    <div class="row mt-4">
        <div class="col-12">
            <h5 class="mb-3">Employee Time Analytics</h5>
        </div>
        @if($reportingTimeStats && $reportingTimeStats->count > 0)
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body">
                    <h5 class="card-title">Reporting Time</h5>
                    <p class="mb-0"><strong>{{ $reportingTimeStats->count }}</strong> works with tracked time</p>
                    <p class="mb-0">Avg: <strong>{{ round($reportingTimeStats->avg_minutes) }}</strong> minutes</p>
                </div>
            </div>
        </div>
        @endif
        @if($checkingTimeStats && $checkingTimeStats->count > 0)
        <div class="col-md-4">
            <div class="card border-secondary">
                <div class="card-body">
                    <h5 class="card-title">Checking Time</h5>
                    <p class="mb-0"><strong>{{ $checkingTimeStats->count }}</strong> works with tracked time</p>
                    <p class="mb-0">Avg: <strong>{{ round($checkingTimeStats->avg_minutes) }}</strong> minutes</p>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    <div class="row mt-4">
        <!-- Loan Amount -->
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Total Loan Amount Requested</h5>
                    <h3>₹ {{ number_format($totalLoanAmount, 2) }}</h3>
                </div>
            </div>
        </div>

        <!-- Payment Status -->
        @foreach($paymentCounts as $status => $count)
        <div class="col-md-4">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <h5>{{ $status }}</h5>
                    <h3>{{ $count }}</h3>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row mt-4">
        <!-- Delivery Status -->
        @foreach($deliveryCounts as $status => $count)
        <div class="col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>{{ $status }}</h5>
                    <h3>{{ $count }}</h3>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row mt-4">
        <!-- Work Type Distribution -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Work Type Distribution</h5>
                    <canvas id="workTypeChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Work Status Distribution</h5>
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Payment Status Distribution -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Payment Status Distribution</h5>
                    <canvas id="paymentChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Delivery Status Distribution -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Delivery Status Distribution</h5>
                    <canvas id="deliveryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Chart.js for Pie Charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Work Type Distribution Chart
    var ctx1 = document.getElementById('workTypeChart').getContext('2d');
    new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: {!! json_encode($workTypes->keys()) !!},
            datasets: [{
                data: {!! json_encode($workTypes->values()) !!},
                backgroundColor: ['#007bff', '#28a745', '#dc3545']
            }]
        }
    });

    // Work Status Distribution Chart
    var ctx2 = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: {!! json_encode($statusCounts->keys()) !!},
            datasets: [{
                data: {!! json_encode($statusCounts->values()) !!},
                backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0', '#9966ff', '#ff9f40', '#c9cbcf']
            }]
        }
    });

    // Payment Status Distribution Chart
    var ctx3 = document.getElementById('paymentChart').getContext('2d');
    new Chart(ctx3, {
        type: 'pie',
        data: {
            labels: {!! json_encode($paymentCounts->keys()) !!},
            datasets: [{
                data: {!! json_encode($paymentCounts->values()) !!},
                backgroundColor: ['#198754', '#dc3545']
            }]
        }
    });

    // Delivery Status Distribution Chart
    var ctx4 = document.getElementById('deliveryChart').getContext('2d');
    new Chart(ctx4, {
        type: 'pie',
        data: {
            labels: {!! json_encode($deliveryCounts->keys()) !!},
            datasets: [{
                data: {!! json_encode($deliveryCounts->values()) !!},
                backgroundColor: ['#ffc107', '#20c997']
            }]
        }
    });
    
</script>
@endsection
