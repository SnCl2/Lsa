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
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Create Work</h1>
        <a href="{{ route('works.myWorks') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Back to Works
        </a>
    </div>

    <!-- 2-Part Form Navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex border rounded p-2 bg-light shadow-sm">
                <button type="button" class="btn btn-primary flex-fill mr-2 py-2 font-weight-bold" id="tabBtnPart1">
                    <span class="badge badge-light text-primary mr-1">Part 1</span> Lead Information
                </button>
                <button type="button" class="btn btn-outline-secondary flex-fill py-2 font-weight-bold" id="tabBtnPart2">
                    <span class="badge badge-secondary mr-1">Part 2</span> Work & Valuation Details
                </button>
            </div>
        </div>
    </div>

    <form id="workCreateForm" action="{{ route('works.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="form_step" id="form_step" value="full">

        <!-- ================= PART 1: LEAD INFORMATION ================= -->
        <div id="part1Section">
            <div class="card mb-4 border-primary shadow-sm">
                <div class="card-header bg-primary text-white font-weight-bold">
                    <i class="fas fa-user-tag mr-2"></i>Part 1: Lead Information
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="custom_id">Custom ID</label>
                            <input type="text" class="form-control" id="custom_id" name="custom_id" placeholder="Auto-generated on save" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="assignment_date">Assignment Date</label>
                            <input type="date" class="form-control" id="assignment_date" name="assignment_date" value="{{ old('assignment_date', date('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="rmls_number">RMLS Number</label>
                            <input type="text" class="form-control" id="rmls_number" name="rmls_number" value="{{ old('rmls_number') }}" placeholder="Enter RMLS Number">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="source">Source</label>
                            <input type="text" class="form-control" id="source" name="source" value="{{ old('source') }}" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name_of_applicant">Name of Applicant <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name_of_applicant" name="name_of_applicant" value="{{ old('name_of_applicant') }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="number_of_applicants">Contact Number <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="number_of_applicants" name="number_of_applicants" value="{{ old('number_of_applicants') }}" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="bank_name">Bank Name</label>
                            <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="bank_branch">Bank Branch</label>
                            <select class="form-control" id="bank_branch" name="bank_branch">
                                <option value="">Select Bank Branch</option>
                                @foreach($usersByRole['Bank Branch'] as $id => $name)
                                    <option value="{{ $id }}" {{ old('bank_branch') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="address_line_1">HOLDING NO. / PREMISES NO.</label>
                            <div class="d-flex">
                                <!-- Dropdown -->
                                <select id="prefixSelect" class="form-control mr-2" style="max-width: 200px;">
                                    <option value="">-- Select --</option>
                                    <option value="Holding No">Holding No</option>
                                    <option value="Premises No">Premises No</option>
                                    <option value="Khatian No">Khatian No</option>
                                </select>
                        
                                <!-- Input -->
                                <input type="text" class="form-control" id="address_line_1" name="address_line_1" value="{{ old('address_line_1') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="project_name">Project Name</label>
                            <input type="text" class="form-control" id="project_name" name="project_name" value="{{ old('project_name') }}" list="project_name_list" autocomplete="off">
                            <datalist id="project_name_list">
                                @foreach($projectNames as $pn)
                                    <option value="{{ $pn->name }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="loan_amount_requested">Loan Amount Requested</label>
                            <div class="d-flex">
                                <!-- Unit dropdown -->
                                <select class="form-control mr-2" id="unit" style="max-width: 200px;">
                                    <option value="">Select Unit</option>
                                    <option value="Lakh">Lakh</option>
                                    <option value="Cr">Cr</option>
                                </select>
                        
                                <!-- Loan amount input -->
                                <input type="text" class="form-control" id="loan_amount_requested" name="loan_amount_requested" value="{{ old('loan_amount_requested') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="loan_type">Loan Type</label>
                            <select class="form-control" id="loan_type" name="loan_type">
                                <option value="">Select Loan Type</option>
                                @foreach($loanTypes as $lt)
                                    <option value="{{ $lt->name }}" {{ old('loan_type') == $lt->name ? 'selected' : '' }}>{{ $lt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="work_type">Work Type</label>
                            <select class="form-control" id="work_type" name="work_type">
                                <option value="">Select Work Type</option>
                                <option value="Valuation" {{ old('work_type', 'Valuation') == 'Valuation' ? 'selected' : '' }}>Valuation</option>
                                <option value="Fair Rent Valuation" {{ old('work_type') == 'Fair Rent Valuation' ? 'selected' : '' }}>Fair Rent Valuation</option>
                                <option value="Estimate" {{ old('work_type') == 'Estimate' ? 'selected' : '' }}>Estimate</option>
                                <option value="Completion Certificate" {{ old('work_type') == 'Completion Certificate' ? 'selected' : '' }}>Completion Certificate</option>
                                <option value="Vetting" {{ old('work_type') == 'Vetting' ? 'selected' : '' }}>Vetting</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="valuer">Valuer</label>
                            <select class="form-control" id="valuer" name="valuer">
                                <option value="">Select Valuer</option>
                                <option value="a" {{ old('valuer') == 'a' ? 'selected' : '' }}>A</option>
                                <option value="b" {{ old('valuer') == 'b' ? 'selected' : '' }}>B</option>
                                <option value="c" {{ old('valuer') == 'c' ? 'selected' : '' }}>C</option>
                                <option value="d" {{ old('valuer') == 'd' ? 'selected' : '' }}>D</option>
                            </select>
                        </div>
                    </div>

                    <!-- Part 1 Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <div>
                            <button type="submit" class="btn btn-outline-primary font-weight-bold px-4 py-2" id="saveLeadBtn">
                                <i class="fas fa-bookmark mr-1"></i> Save as Lead
                            </button>
                            <small class="text-muted d-block mt-1">Submit only Part 1 to create this record as a lead.</small>
                        </div>
                        <button type="button" class="btn btn-primary font-weight-bold px-4 py-2" id="goToPart2Btn">
                            Continue to Part 2 <i class="fas fa-arrow-right ml-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= PART 2: WORK & VALUATION DETAILS ================= -->
        <div id="part2Section" style="display: none;">
            <div class="card mb-4 border-info shadow-sm">
                <div class="card-header bg-info text-white font-weight-bold">
                    <i class="fas fa-clipboard-list mr-2"></i>Part 2: Work & Valuation Details
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="pin_code">Pin Code</label>
                            <input type="text" class="form-control part2-field" id="pin_code" name="pin_code" value="{{ old('pin_code') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="police_station">Police Station</label>
                            <input type="text" class="form-control part2-field" id="police_station" name="police_station" value="{{ old('police_station') }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="pdf_1">Upload PDF</label>
                            <input type="file" class="form-control-file part2-field" id="pdf_1" name="pdf_1">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="actual_value">FMV</label>
                            <input type="number" step="0.01" class="form-control part2-field" id="actual_value" name="actual_value" value="{{ old('actual_value') }}" placeholder="Enter FMV">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="realised_value">RV</label>
                            <input type="number" step="0.01" class="form-control part2-field" id="realised_value" name="realised_value" value="{{ old('realised_value') }}" placeholder="Enter RV">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="fair_market_value">DV</label>
                            <input type="number" step="0.01" class="form-control part2-field" id="fair_market_value" name="fair_market_value" value="{{ old('fair_market_value') }}" placeholder="Enter DV">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="remarks">Remarks</label>
                            <textarea class="form-control part2-field" id="remarks" name="remarks" rows="3" placeholder="Enter remarks (optional)">{{ old('remarks') }}</textarea>
                        </div>
                    </div>
                    @php
                        $canManageAdminFields = (auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin')) && !auth()->user()->roles->contains('name', 'In-Charge');
                    @endphp
                    @if($canManageAdminFields)
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="result">Result</label>
                            <select class="form-control part2-field" id="result" name="result">
                                <option value="">Select Result</option>
                                <option value="Positive" {{ old('result') == 'Positive' ? 'selected' : '' }}>Positive</option>
                                <option value="Negative" {{ old('result') == 'Negative' ? 'selected' : '' }}>Negative</option>
                                <option value="Canceled" {{ old('result') == 'Canceled' ? 'selected' : '' }}>Canceled</option>
                                <option value="Return" {{ old('result') == 'Return' ? 'selected' : '' }}>Return</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="status">Status</label>
                            <select class="form-control part2-field" id="status" name="status">
                                <option value="New File" {{ old('status', 'New File') == 'New File' ? 'selected' : '' }}>New File</option>
                                <option value="Surveying" {{ old('status') == 'Surveying' ? 'selected' : '' }}>Surveying</option>
                                <option value="Reporting" {{ old('status') == 'Reporting' ? 'selected' : '' }}>Reporting</option>
                                <option value="Checking" {{ old('status') == 'Checking' ? 'selected' : '' }}>Checking</option>
                                <option value="Printing" {{ old('status') == 'Printing' ? 'selected' : '' }}>Printing</option>
                                <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="is_hold">Hold Status</label>
                            <div class="custom-control custom-switch mt-2">
                                <input type="hidden" name="is_hold" value="0">
                                <input type="checkbox" class="custom-control-input part2-field" id="is_hold" name="is_hold" value="1" {{ old('is_hold') ? 'checked' : '' }}>
                                <label class="custom-control-label text-danger font-weight-bold" for="is_hold">
                                    <i class="fas fa-pause-circle"></i> Put this work On Hold
                                </label>
                            </div>
                            <small class="form-text text-muted">Hold can be toggled without changing current workflow stage.</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="payment_status">Payment Status</label>
                            <select class="form-control part2-field" id="payment_status" name="payment_status">
                                <option value="Payment Due" {{ old('payment_status', 'Payment Due') == 'Payment Due' ? 'selected' : '' }}>Payment Due</option>
                                <option value="Paid" {{ old('payment_status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="delivery_status">Delivery Status</label>
                            <select class="form-control part2-field" id="delivery_status" name="delivery_status">
                                <option value="Delivery Due" {{ old('delivery_status', 'Delivery Due') == 'Delivery Due' ? 'selected' : '' }}>Delivery Due</option>
                                <option value="Delivery Done" {{ old('delivery_status') == 'Delivery Done' ? 'selected' : '' }}>Delivery Done</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="assignee_surveyor">Assignee Surveyor</label>
                            <select class="form-control part2-field" id="assignee_surveyor" name="assignee_surveyor">
                                <option value="">Select Surveyor</option>
                                @foreach($usersByRole['Surveyor'] as $id => $name)
                                    <option value="{{ $id }}" {{ old('assignee_surveyor') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="assignee_reporter">Assignee Reporter</label>
                            <select class="form-control part2-field" id="assignee_reporter" name="assignee_reporter">
                                <option value="">Select Reporter</option>
                                @foreach($usersByRole['Reporter'] as $id => $name)
                                    <option value="{{ $id }}" {{ old('assignee_reporter') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="assignee_checker">Assignee Checker</label>
                            <select class="form-control part2-field" id="assignee_checker" name="assignee_checker">
                                <option value="">Select Checker</option>
                                @foreach($usersByRole['Checker'] as $id => $name)
                                    <option value="{{ $id }}" {{ old('assignee_checker') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="assignee_delivery">Assignee Delivery</label>
                            <select class="form-control part2-field" id="assignee_delivery" name="assignee_delivery">
                                <option value="">Select Delivery Person</option>
                                @foreach($usersByRole['Delivery Person'] as $id => $name)
                                    <option value="{{ $id }}" {{ old('assignee_delivery') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    <!-- Part 2 Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <button type="button" class="btn btn-secondary font-weight-bold px-4 py-2" id="backToPart1Btn">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Part 1
                        </button>
                        <button type="submit" class="btn btn-success font-weight-bold px-4 py-2" id="createWorkBtn">
                            <i class="fas fa-check-circle mr-1"></i> Create Work
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const part1Section = document.getElementById("part1Section");
        const part2Section = document.getElementById("part2Section");
        const tabBtnPart1 = document.getElementById("tabBtnPart1");
        const tabBtnPart2 = document.getElementById("tabBtnPart2");
        const goToPart2Btn = document.getElementById("goToPart2Btn");
        const backToPart1Btn = document.getElementById("backToPart1Btn");
        const saveLeadBtn = document.getElementById("saveLeadBtn");
        const createWorkBtn = document.getElementById("createWorkBtn");
        const formStepInput = document.getElementById("form_step");

        function showPart(partNumber) {
            if (partNumber === 1) {
                part1Section.style.display = "block";
                part2Section.style.display = "none";

                tabBtnPart1.className = "btn btn-primary flex-fill mr-2 py-2 font-weight-bold";
                tabBtnPart1.querySelector(".badge").className = "badge badge-light text-primary mr-1";

                tabBtnPart2.className = "btn btn-outline-secondary flex-fill py-2 font-weight-bold";
                tabBtnPart2.querySelector(".badge").className = "badge badge-secondary mr-1";
            } else {
                part1Section.style.display = "none";
                part2Section.style.display = "block";

                tabBtnPart2.className = "btn btn-primary flex-fill py-2 font-weight-bold";
                tabBtnPart2.querySelector(".badge").className = "badge badge-light text-primary mr-1";

                tabBtnPart1.className = "btn btn-outline-secondary flex-fill mr-2 py-2 font-weight-bold";
                tabBtnPart1.querySelector(".badge").className = "badge badge-secondary mr-1";
            }
        }

        // Validate Part 1 required fields
        function isPart1Valid() {
            const part1Inputs = part1Section.querySelectorAll("input[required], select[required], textarea[required]");
            for (let input of part1Inputs) {
                if (!input.checkValidity()) {
                    showPart(1);
                    input.reportValidity();
                    return false;
                }
            }
            return true;
        }

        tabBtnPart1.addEventListener("click", function () {
            showPart(1);
        });

        tabBtnPart2.addEventListener("click", function () {
            showPart(2);
        });

        goToPart2Btn.addEventListener("click", function () {
            if (isPart1Valid()) {
                showPart(2);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });

        backToPart1Btn.addEventListener("click", function () {
            showPart(1);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Submit only Part 1 as Lead
        saveLeadBtn.addEventListener("click", function (e) {
            formStepInput.value = "lead";
            if (!isPart1Valid()) {
                e.preventDefault();
            }
        });

        // Submit full work
        createWorkBtn.addEventListener("click", function (e) {
            formStepInput.value = "full";
            if (!isPart1Valid()) {
                e.preventDefault();
            }
        });

        @php
            $part2Fields = ['pin_code', 'police_station', 'pdf_1', 'actual_value', 'realised_value', 'fair_market_value', 'remarks', 'result', 'status', 'is_hold', 'payment_status', 'delivery_status', 'assignee_surveyor', 'assignee_reporter', 'assignee_checker', 'assignee_delivery'];
            $hasPart2Errors = false;
            if ($errors->any()) {
                foreach ($part2Fields as $p2f) {
                    if ($errors->has($p2f)) {
                        $hasPart2Errors = true;
                        break;
                    }
                }
            }
        @endphp
        @if ($hasPart2Errors)
            showPart(2);
        @else
            showPart(1);
        @endif
    });

    document.getElementById("unit").addEventListener("change", function() {
        let unit = this.value;
        let inputField = document.getElementById("loan_amount_requested");
    
        if (unit) {
            inputField.value = inputField.value.replace(/ Lakh| Cr/g, '') + " " + unit; 
        }
    });

    const prefixSelect = document.getElementById('prefixSelect');
    const addressInput = document.getElementById('address_line_1');

    prefixSelect.addEventListener('change', function () {
        const prefix = this.value;
        const currentValue = addressInput.value;

        const cleanedValue = currentValue.replace(/^(Holding No|Premises No|Khatian No):\s*/i, '');
        addressInput.value = prefix ? `${prefix}: ${cleanedValue}` : cleanedValue;
    });
</script>
@endsection