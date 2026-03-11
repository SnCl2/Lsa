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
    <h1 class="mb-4">Create Work</h1>
    <form action="{{ route('works.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin'))
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="custom_id">Custom ID</label>
                <input type="text" class="form-control" id="custom_id" name="custom_id" >
            </div>
            <div class="form-group col-md-6">
                <label for="assignment_date">Assignment Date</label>
                <input type="date" class="form-control" id="assignment_date" name="assignment_date" value="{{ date('Y-m-d') }}" >
            </div>
        </div>
        @endif
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name_of_applicant">Name of Applicant</label>
                <input type="text" class="form-control" id="name_of_applicant" name="name_of_applicant" required>
            </div>
            <div class="form-group col-md-6">
                <label for="number_of_applicants">Contact Number</label>
                <input type="number" class="form-control" id="number_of_applicants" name="number_of_applicants" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="bank_name">Bank Name</label>
                <input type="text" class="form-control" id="bank_name" name="bank_name" required>
            </div>
            <div class="form-group col-md-6">
                <label for="bank_branch">Bank Branch</label>
                <select class="form-control" id="bank_branch" name="bank_branch">
                    <option value="">Select Bank Branch</option>
                    @foreach($usersByRole['Bank Branch'] as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="source">Source</label>
                <input type="text" class="form-control" id="source" name="source" required>
            </div>
            <div class="form-group col-md-6">
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
                    <input type="text" class="form-control" id="address_line_1" name="address_line_1" required>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="address_line_2">Road Facility</label>
                <input type="text" class="form-control" id="address_line_2" name="address_line_2" required>
            </div>
            <div class="form-group col-md-6">
                <label for="state">State</label>
                <input type="text" class="form-control" id="state" name="state" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="district">District</label>
                <input type="text" class="form-control" id="district" name="district" required>
            </div>
            <div class="form-group col-md-6">
                <label for="pin_code">Pin Code</label>
                <input type="text" class="form-control" id="pin_code" name="pin_code" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="post_office">Post Office</label>
                <input type="text" class="form-control" id="post_office" name="post_office" required>
            </div>
            <div class="form-group col-md-6">
                <label for="police_station">Police Station</label>
                <input type="text" class="form-control" id="police_station" name="police_station" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="project_name">Project Name</label>
                <input type="text" class="form-control" id="project_name" name="project_name" required>
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
                    <input type="text" class="form-control" id="loan_amount_requested" name="loan_amount_requested" required>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="loan_type">Loan Type</label>
                <select class="form-control" id="loan_type" name="loan_type" required>
                    <option value="">Select Loan Type</option>
                    <option value="NHBL">NHBL</option>
                    <option value="TAKEOVER">TAKEOVER</option>
                    <option value="TOP-UP">TOP-UP</option>
                    <option value="HBL">HBL</option>
                    <option value="COMBO">COMBO</option>
                    <option value="CONSTRUCTION">CONSTRUCTION</option>
                    <option value="REALITY">REALITY</option>
                    <option value="RESALE">RESALE</option>
                    <option value="NPA">NPA</option>
                    <option value="ADDITIONAL">ADDITIONAL</option>
                    <option value="OTHER">OTHER</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="pdf_1">Upload PDF</label>
                <input type="file" class="form-control-file" id="pdf_1" name="pdf_1">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="work_type">Work Type</label>
                <select class="form-control" id="work_type" name="work_type" required>
                    <option value="">Select Work Type</option>
                    <option value="Valuation">Valuation</option>
                    <option value="Fair Rent Valuation">Fair Rent Valuation</option>
                    <option value="Estimate">Estimate</option>
                    <option value="Completion Certificate">Completion Certificate</option>
                    <option value="Vetting">Vetting</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="valuer">Valuer</label>
                <select class="form-control" id="valuer" name="valuer">
                    <option value="">Select Valuer</option>
                    <option value="a">A</option>
                    <option value="b">B</option>
                    <option value="c">C</option>
                    <option value="d">D</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="result">Result</label>
                <select class="form-control" id="result" name="result">
                    <option value="">Select Result</option>
                    <option value="Positive">Positive</option>
                    <option value="Negative">Negative</option>
                    <option value="Hold">Hold</option>
                    <option value="Canceled">Canceled</option>
                    <option value="Return">Return</option>
                </select>
            </div>
            @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin'))

            <div class="form-group col-md-6">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="New File">New File</option>
                    <option value="Surveying">Surveying</option>
                    <option value="Reporting">Reporting</option>
                    <option value="Checking">Checking</option>
                    <option value="Printing">Printing</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
            @endif
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="remarks">Remarks</label>
                <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Enter remarks (optional)"></textarea>
            </div>
        </div>
        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin'))
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="payment_status">Payment Status</label>
                <select class="form-control" id="payment_status" name="payment_status" required>
                    <option value="Payment Due">Payment Due</option>
                    <option value="Paid">Paid</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="delivery_status">Delivery Status</label>
                <select class="form-control" id="delivery_status" name="delivery_status" required>
                    <option value="Delivery Due">Delivery Due</option>
                    <option value="Delivery Done">Delivery Done</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="assignee_surveyor">Assignee Surveyor</label>
                <select class="form-control" id="assignee_surveyor" name="assignee_surveyor">
                    <option value="">Select Surveyor</option>
                    @foreach($usersByRole['Surveyor'] as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="assignee_reporter">Assignee Reporter</label>
                <select class="form-control" id="assignee_reporter" name="assignee_reporter">
                    <option value="">Select Reporter</option>
                    @foreach($usersByRole['Reporter'] as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="assignee_checker">Assignee Checker</label>
                <select class="form-control" id="assignee_checker" name="assignee_checker">
                    <option value="">Select Checker</option>
                    @foreach($usersByRole['Checker'] as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="assignee_delivery">Assignee Delivery</label>
                <select class="form-control" id="assignee_delivery" name="assignee_delivery">
                    <option value="">Select Delivery Person</option>
                    @foreach($usersByRole['Delivery Person'] as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>
        <button type="submit" class="btn btn-primary mt-3">Create Work</button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let oldValues = @json(old());

        document.querySelectorAll("input[type='text'], input[type='number'], input[type='date']").forEach(input => {
            let inputName = input.getAttribute("name");
            if (inputName && oldValues[inputName] !== undefined) {
                input.value = oldValues[inputName];
            }
        });

        document.querySelectorAll("select").forEach(select => {
            let selectName = select.getAttribute("name");
            if (selectName && oldValues[selectName] !== undefined) {
                select.value = oldValues[selectName];
            }
        });
    });
    
    document.getElementById("unit").addEventListener("change", function() {
        let unit = this.value;
        let inputField = document.getElementById("loan_amount_requested");
    
        if (unit) {
            inputField.value = inputField.value.replace(/ Lakh| Cr/g, '') + " " + unit; 
        }
    });

    // Pincode to Post Office lookup functionality
    document.getElementById("pin_code").addEventListener("input", function() {
        const pincode = this.value.trim();
        const postOfficeField = document.getElementById("post_office");
        
        // Clear post office field when pincode is cleared
        if (pincode.length === 0) {
            postOfficeField.value = '';
            return;
        }
        
        // Only make request if pincode is 6 digits
        if (pincode.length === 6 && /^\d{6}$/.test(pincode)) {
            // Show loading state
            postOfficeField.placeholder = "Loading...";
            postOfficeField.disabled = true;
            
            fetch(`/api/pincode/${pincode}`)
                .then(response => response.json())
                .then(data => {
                    postOfficeField.disabled = false;
                    postOfficeField.placeholder = "";
                    
                    if (data.success && data.post_offices && data.post_offices.length > 0) {
                        // If multiple post offices, show the first one or create a dropdown
                        if (data.post_offices.length === 1) {
                            postOfficeField.value = data.post_offices[0];
                        } else {
                            // Multiple post offices - show first one but allow user to change
                            postOfficeField.value = data.post_offices[0];
                            postOfficeField.title = `Multiple post offices found: ${data.post_offices.join(', ')}`;
                        }
                    } else {
                        postOfficeField.value = '';
                        postOfficeField.placeholder = "No post office found for this pincode";
                    }
                })
                .catch(error => {
                    postOfficeField.disabled = false;
                    postOfficeField.placeholder = "";
                    postOfficeField.value = '';
                    console.error('Error fetching post office:', error);
                });
        } else if (pincode.length > 0) {
            // Invalid pincode format
            postOfficeField.value = '';
            postOfficeField.placeholder = "Enter a valid 6-digit pincode";
        }
    });
</script>
<script>
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