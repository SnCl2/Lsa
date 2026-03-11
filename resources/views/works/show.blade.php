@extends('layouts.app')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Work Details</h2>
        </div>
        <div class="card-body">
            <!-- Applicant Details Section -->
            <h5 class="mb-3">Applicant Details</h5>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Name of Applicant:</label>
                    <p class="form-control-plaintext">{{ $work->name_of_applicant ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Number of Applicants:</label>
                    <p class="form-control-plaintext">{{ $work->number_of_applicants ?? 'N/A' }}</p>
                </div>
            </div>
            
            <!-- Relatives Section -->
            <h5 class="mt-4 mb-3">Other Applicants</h5>
            @if($work->relatives->isNotEmpty())
                <ul>
                    @foreach($work->relatives as $relative)
                        <li>{{ $relative->name }}  - {{ $relative->phone_number }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">No relatives added.</p>
            @endif

            <!-- Bank Details Section -->
            <h5 class="mt-4 mb-3">Bank Details</h5>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Bank Name:</label>
                    <p class="form-control-plaintext">{{ $work->bank_name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bank Branch:</label>
                    <p class="form-control-plaintext">{{ optional($work->bankBranch)->name ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Address Section -->
            <h5 class="mt-4 mb-3">Address</h5>
            <div class="row">
                @foreach(['address_line_1', 'address_line_2', 'state', 'district', 'pin_code', 'post_office', 'police_station'] as $field)
                    <div class="col-md-6">
                        <label class="form-label">{{ ucwords(str_replace('_', ' ', $field)) }}:</label>
                        <p class="form-control-plaintext">{{ $work->$field ?? 'N/A' }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Loan Details -->
            <h5 class="mt-4 mb-3">Loan Details</h5>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Project Name:</label>
                    <p class="form-control-plaintext">{{ $work->project_name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Loan Amount Requested:</label>
                    <p class="form-control-plaintext">₹{{ $work->loan_amount_requested ?? '--' }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Loan Type:</label>
                    <p class="form-control-plaintext">{{ $work->loan_type ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Status Information -->
            <h5 class="mt-4 mb-3">Work & Payment Status</h5>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Status:</label>
                    <p class="form-control-plaintext"><span class="badge bg-info">{{ $work->status ?? 'Pending' }}</span></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Work Type:</label>
                    <p class="form-control-plaintext">{{ $work->work_type ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Payment Status:</label>
                    <p class="form-control-plaintext">
                        <span class="badge {{ $work->payment_status == 'Paid' ? 'bg-success' : 'bg-danger' }}">
                            {{ $work->payment_status ?? 'Unpaid' }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Delivery Status:</label>
                    <p class="form-control-plaintext">
                        <span class="badge {{ $work->delivery_status == 'Delivered' ? 'bg-success' : 'bg-warning' }}">
                            {{ $work->delivery_status ?? 'Pending' }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Assignee Information -->
            <h5 class="mt-4 mb-3">Assignee Information</h5>
            <div class="row">
                @foreach([
                    'surveyor' => 'Surveyor',
                    'reporter' => 'Reporter',
                    'checker' => 'Checker',
                    'deliveryPerson' => 'Delivery Person'
                ] as $relation => $label)
                    <div class="col-md-6">
                        <label class="form-label">Assignee {{ $label }}:</label>
                        <p class="form-control-plaintext">{{ optional($work->$relation)->name ?? 'Not Assigned' }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Relatives Section -->
            <h5 class="mt-4 mb-3">Other Applicants</h5>
            @if($work->relatives->isNotEmpty())
                <ul>
                    @foreach($work->relatives as $relative)
                        <li>{{ $relative->name }}  - {{ $relative->phone_number }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">No relatives added.</p>
            @endif

            <!-- PDF Section -->
            @if($work->pdf_1)
                <div class="mt-4">
                    <label class="form-label">PDF Document:</label>
                    <!--<a>https://valuerkkda.com/public{{ Storage::url($work->pdf_1) }}</p>-->
                    <iframe src="https://valuerkkda.com/public{{ Storage::url($work->pdf_1) }}" width="100%" height="600px"></iframe>


                    

                </div>
            @endif

        </div>
    </div>
</div>

@endsection
