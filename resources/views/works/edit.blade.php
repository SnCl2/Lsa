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
    <h1 class="mb-4">Edit Work</h1>
    <form action="{{ route('works.update', $work->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin'))
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="custom_id">Custom ID</label>
                <input type="text" class="form-control" id="custom_id" name="custom_id" value="{{ $work->custom_id }}" >
            </div>
            <div class="form-group col-md-6">
                <label for="assignment_date">Assignment Date</label>
                <input type="date" class="form-control" id="assignment_date" name="assignment_date" value="{{ $work->assignment_date ? (is_string($work->assignment_date) ? $work->assignment_date : $work->assignment_date->format('Y-m-d')) : '' }}" >
            </div>
        </div>
        @endif
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name_of_applicant">Name of Applicant</label>
                <input type="text" class="form-control" id="name_of_applicant" name="name_of_applicant" value="{{ $work->name_of_applicant }}" required>
            </div>
            <div class="form-group col-md-6">
                <label for="number_of_applicants">Contact Number</label>
                <input type="text" class="form-control" id="number_of_applicants" name="number_of_applicants" value="{{ $work->number_of_applicants }}" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="bank_name">Bank Name</label>
                <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ $work->bank_name }}">
            </div>
            <div class="form-group col-md-6">
                <label for="bank_branch">Bank Branch</label>
                <select class="form-control" id="bank_branch" name="bank_branch">
                    <option value="">Select Bank Branch</option>
                    @foreach($usersByRole['Bank Branch'] as $id => $name)
                        <option value="{{ $id }}" {{ $work->bank_branch == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="source">Source</label>
                <input type="text" class="form-control" id="source" name="source" value="{{ $work->source }}">
            </div>
            <div class="form-group col-md-6">
                <label for="address_line_1">Address Line 1</label>
                <input type="text" class="form-control" id="address_line_1" name="address_line_1" value="{{ $work->address_line_1 }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="address_line_2">Address Line 2</label>
                <input type="text" class="form-control" id="address_line_2" name="address_line_2" value="{{ $work->address_line_2 }}">
            </div>
            <div class="form-group col-md-6">
                <label for="state">State</label>
                <input type="text" class="form-control" id="state" name="state" value="{{ $work->state }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="district">District</label>
                <input type="text" class="form-control" id="district" name="district" value="{{ $work->district }}">
            </div>
            <div class="form-group col-md-6">
                <label for="pin_code">Pin Code</label>
                <input type="text" class="form-control" id="pin_code" name="pin_code" value="{{ $work->pin_code }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="post_office">Post Office</label>
                <input type="text" class="form-control" id="post_office" name="post_office" value="{{ $work->post_office }}">
            </div>
            <div class="form-group col-md-6">
                <label for="police_station">Police Station</label>
                <input type="text" class="form-control" id="police_station" name="police_station" value="{{ $work->police_station }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="project_name">Project Name</label>
                <input type="text" class="form-control" id="project_name" name="project_name" value="{{ $work->project_name }}">
            </div>
            <div class="form-group col-md-6">
                <label for="loan_amount_requested">Loan Amount Requested</label>
                <input type="text" class="form-control" id="loan_amount_requested" name="loan_amount_requested" value="{{ $work->loan_amount_requested}}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="loan_type">Loan Type</label>
                <input type="text" class="form-control" id="loan_type" name="loan_type" value="{{ $work->loan_type }}">
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
                    <option value="Valuation" {{ $work->work_type == 'Valuation' ? 'selected' : '' }}>Valuation</option>
                    <option value="Fair Rent Valuation" {{ $work->work_type == 'Fair Rent Valuation' ? 'selected' : '' }}>Fair Rent Valuation</option>
                    <option value="Estimate" {{ $work->work_type == 'Estimate' ? 'selected' : '' }}>Estimate</option>
                    <option value="Completion Certificate" {{ $work->work_type == 'Completion Certificate' ? 'selected' : '' }}>Completion Certificate</option>
                    <option value="Vetting" {{ $work->work_type == 'Vetting' ? 'selected' : '' }}>Vetting</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="valuer">Valuer</label>
                <select class="form-control" id="valuer" name="valuer">
                    <option value="">Select Valuer</option>
                    <option value="a" {{ $work->valuer == 'a' ? 'selected' : '' }}>A</option>
                    <option value="b" {{ $work->valuer == 'b' ? 'selected' : '' }}>B</option>
                    <option value="c" {{ $work->valuer == 'c' ? 'selected' : '' }}>C</option>
                    <option value="d" {{ $work->valuer == 'd' ? 'selected' : '' }}>D</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="result">Result</label>
                <select class="form-control" id="result" name="result">
                    <option value="">Select Result</option>
                    <option value="Positive" {{ $work->result == 'Positive' ? 'selected' : '' }}>Positive</option>
                    <option value="Negative" {{ $work->result == 'Negative' ? 'selected' : '' }}>Negative</option>
                    <option value="Hold" {{ $work->result == 'Hold' ? 'selected' : '' }}>Hold</option>
                    <option value="Canceled" {{ $work->result == 'Canceled' ? 'selected' : '' }}>Canceled</option>
                    <option value="Return" {{ $work->result == 'Return' ? 'selected' : '' }}>Return</option>
                </select>
            </div>
            @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin'))
            <div class="form-group col-md-6">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="New File" {{ $work->status == 'New File' ? 'selected' : '' }}>New File</option>
                    <option value="Surveying" {{ $work->status == 'Surveying' ? 'selected' : '' }}>Surveying</option>
                    <option value="Reporting" {{ $work->status == 'Reporting' ? 'selected' : '' }}>Reporting</option>
                    <option value="Checking" {{ $work->status == 'Checking' ? 'selected' : '' }}>Checking</option>
                    <option value="Printing" {{ $work->status == 'Printing' ? 'selected' : '' }}>Printing</option>
                    <option value="Completed" {{ $work->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
                <small class="form-text text-muted">To set Completed, first mark Printed = Yes from the works list.</small>
            </div>
            @endif
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="report_submit_date">Report Submit Date</label>
                <input type="date" class="form-control" id="report_submit_date" name="report_submit_date" value="{{ $work->report_submit_date ? (is_string($work->report_submit_date) ? $work->report_submit_date : $work->report_submit_date->format('Y-m-d')) : '' }}" placeholder="Set when report is printed">
                <small class="form-text text-muted">Auto-set when Print is checked on index, or edit manually here.</small>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="remarks">Remarks</label>
                <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Enter remarks (optional)">{{ $work->remarks }}</textarea>
            </div>
        </div>
        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin'))
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="payment_status">Payment Status</label>
                <select class="form-control" id="payment_status" name="payment_status" required>
                    <option value="Payment Due" {{ $work->payment_status == 'Payment Due' ? 'selected' : '' }}>Payment Due</option>
                    <option value="Paid" {{ $work->payment_status == 'Paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="delivery_status">Delivery Status</label>
                <select class="form-control" id="delivery_status" name="delivery_status" required>
                    <option value="Delivery Due" {{ $work->delivery_status == 'Delivery Due' ? 'selected' : '' }}>Delivery Due</option>
                    <option value="Delivery Done" {{ $work->delivery_status == 'Delivery Done' ? 'selected' : '' }}>Delivery Done</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="assignee_surveyor">Assignee Surveyor</label>
                <select class="form-control" id="assignee_surveyor" name="assignee_surveyor">
                    <option value="">Select Surveyor</option>
                    @foreach($usersByRole['Surveyor'] as $id => $name)
                        <option value="{{ $id }}" {{ $work->assignee_surveyor == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="assignee_reporter">Assignee Reporter</label>
                <select class="form-control" id="assignee_reporter" name="assignee_reporter">
                    <option value="">Select Reporter</option>
                    @foreach($usersByRole['Reporter'] as $id => $name)
                        <option value="{{ $id }}" {{ $work->assignee_reporter == $id ? 'selected' : '' }}>{{ $name }}</option>
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
                        <option value="{{ $id }}" {{ $work->assignee_checker == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="assignee_delivery">Assignee Delivery</label>
                <select class="form-control" id="assignee_delivery" name="assignee_delivery">
                    <option value="">Select Delivery Person</option>
                    @foreach($usersByRole['Delivery Person'] as $id => $name)
                        <option value="{{ $id }}" {{ $work->assignee_delivery == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif
        <button type="submit" class="btn btn-primary mt-3">Update Work</button>
    </form>
    @if($work->pdf_1)
                <div class="mt-4">
                    <label class="form-label">PDF Document:</label>
                    <!--<a>https://valuerkkda.com/public{{ Storage::url($work->pdf_1) }}</p>-->
                    <iframe src="https://valuerkkda.com/public{{ Storage::url($work->pdf_1) }}" width="100%" height="600px"></iframe>


                    

                </div>
            @endif
            
            <form action="{{ route('works.uploadDocuments', $work->id) }}" method="POST" enctype="multipart/form-data" class="p-4 border rounded bg-light">
              @csrf
              <div class="mb-3">
                <label for="final_report_word" class="form-label">Upload Final Report (Word)</label>
                <input class="form-control" type="file" name="final_report_word" id="final_report_word" accept=".doc,.docx">
              </div>
              <div class="mb-3">
                <label for="final_report_pdf" class="form-label">Upload Final Report (PDF)</label>
                <input class="form-control" type="file" name="final_report_pdf" id="final_report_pdf" accept=".pdf">
              </div>
              <button type="submit" class="btn btn-primary">Upload</button>
            </form>
            
            
            
            
            
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
            </script>
            @endsection