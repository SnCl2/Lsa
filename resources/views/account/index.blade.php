@extends('layouts.app')

@section('content')
<div class="container-fluid mt-5" style="padding-left: 10px; padding-right: 10px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Account & Billing</h1>
    </div>

    <!-- Tab navigation -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
                @php
                    $params = request()->except('tab');
                @endphp
                <a href="{{ route('account.index', array_merge($params, ['tab' => 'pending'])) }}"
                   class="btn btn-sm {{ $tab === 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="fas fa-clock"></i> Pending Billing
                    <span class="badge badge-light ml-2">{{ $countPending }}</span>
                </a>
                <a href="{{ route('account.index', array_merge($params, ['tab' => 'billed'])) }}"
                   class="btn btn-sm {{ $tab === 'billed' ? 'btn-warning' : 'btn-outline-warning' }}">
                    <i class="fas fa-file-invoice"></i> Billed
                    <span class="badge badge-light ml-2">{{ $countBilled }}</span>
                </a>
                <a href="{{ route('account.index', array_merge($params, ['tab' => 'payment_done'])) }}"
                   class="btn btn-sm {{ $tab === 'payment_done' ? 'btn-success' : 'btn-outline-success' }}">
                    <i class="fas fa-check-circle"></i> Payment Done
                    <span class="badge badge-light ml-2">{{ $countPaymentDone }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary"><i class="fas fa-filter mr-2"></i> Advanced Filters</h5>
            <button class="btn btn-sm btn-link text-muted" type="button" data-toggle="collapse" data-target="#filterCollapse">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        <div id="filterCollapse" class="collapse show">
            <div class="card-body">
                <form method="GET" action="{{ route('account.index') }}">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    
                    <div class="row">
                        <!-- Search & Basic Filters -->
                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold">Search</label>
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Applicant, Project, ID..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold">Bank Branch</label>
                            <select name="bank_branch" class="form-control form-control-sm">
                                <option value="">All Branches</option>
                                @foreach($dropdownData['bank_branches'] as $id => $name)
                                    <option value="{{ $id }}" {{ request('bank_branch') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold">Work Type</label>
                            <select name="work_type" class="form-control form-control-sm">
                                <option value="">All Types</option>
                                @foreach($dropdownData['work_types'] as $type)
                                    <option value="{{ $type }}" {{ request('work_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold">Valuer</label>
                            <select name="valuer" class="form-control form-control-sm">
                                <option value="">All Valuers</option>
                                @foreach($dropdownData['valuers'] as $v)
                                    <option value="{{ $v }}" {{ request('valuer') == $v ? 'selected' : '' }}>{{ strtoupper($v) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date Ranges -->
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold">Created Date</label>
                            <div class="input-group input-group-sm">
                                <input type="date" name="created_date_from" class="form-control" value="{{ request('created_date_from') }}">
                                <div class="input-group-append"><span class="input-group-text">to</span></div>
                                <input type="date" name="created_date_to" class="form-control" value="{{ request('created_date_to') }}">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold">Invoice Date</label>
                            <div class="input-group input-group-sm">
                                <input type="date" name="invoice_date_from" class="form-control" value="{{ request('invoice_date_from') }}">
                                <div class="input-group-append"><span class="input-group-text">to</span></div>
                                <input type="date" name="invoice_date_to" class="form-control" value="{{ request('invoice_date_to') }}">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold">Billing Done Date</label>
                            <div class="input-group input-group-sm">
                                <input type="date" name="billing_date_from" class="form-control" value="{{ request('billing_date_from') }}">
                                <div class="input-group-append"><span class="input-group-text">to</span></div>
                                <input type="date" name="billing_date_to" class="form-control" value="{{ request('billing_date_to') }}">
                            </div>
                        </div>
                    </div>

                    <div class="text-right mt-2">
                        <a href="{{ route('account.index', ['tab' => $tab]) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-undo mr-1"></i> Reset
                        </a>
                        <button type="submit" class="btn btn-sm btn-primary ml-2 px-4">
                            <i class="fas fa-search mr-1"></i> Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Results -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-list"></i>
                @if($tab === 'pending') Pending Billing
                @elseif($tab === 'billed') Billed
                @else Payment Done
                @endif
                <span class="badge badge-light ml-2">{{ $works->total() }} Total</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Date</th>
                            <th>ID / Custom ID</th>
                            <th>Applicant</th>
                            <th>Bank Branch</th>
                            <th>Loan Amount</th>
                            <th>Work Type</th>
                            <th>Result</th>
                            @if($tab !== 'pending')
                            <th>Invoice No.</th>
                            <th>Invoice Amount</th>
                            <th>Payment</th>
                            @endif
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($works as $work)
                        <tr>
                            <td>
                                <strong>Created:</strong> {{ $work->created_at->format('d/m/Y H:i') }}<br>
                                @if($work->assignment_date)
                                <strong>Assignment:</strong>
                                @if(is_string($work->assignment_date))
                                    @php try { echo \Carbon\Carbon::parse($work->assignment_date)->format('d/m/Y'); } catch (\Exception $e) { echo $work->assignment_date; } @endphp
                                @else
                                    {{ $work->assignment_date->format('d/m/Y') }}
                                @endif
                                @endif
                            </td>
                            <td><strong>ID:</strong> {{ $work->id }}<br><strong>Custom ID:</strong> {{ $work->custom_id ?? 'N/A' }}</td>
                            <td>{{ $work->name_of_applicant }}</td>
                            <td>{{ $work->bankBranch?->name ?? 'N/A' }}</td>
                            <td>{{ $work->loan_amount_requested }}</td>
                            <td>{{ $work->work_type }}</td>
                            <td>{{ $work->result ?? '—' }}</td>
                            @if($tab !== 'pending')
                            <td>{{ $work->invoice_number ?? '—' }}</td>
                            <td>{{ $work->invoice_amount !== null ? number_format($work->invoice_amount, 2) : '—' }}</td>
                            <td>
                                <span class="badge {{ $work->payment_status === 'Paid' ? 'badge-success' : 'badge-warning' }}">
                                    {{ $work->payment_status ?? '—' }}
                                </span>
                            </td>
                            @endif
                            <td>
                                <a href="{{ route('works.show', $work->id) }}" class="btn btn-info btn-sm">View</a>
                                @if($tab === 'pending')
                                    <button type="button" class="btn btn-primary btn-sm btn-mark-billing" data-work-id="{{ $work->id }}" data-work-applicant="{{ $work->name_of_applicant }}">
                                        Mark billing done
                                    </button>
                                @else
                                    <button type="button" class="btn btn-warning btn-sm btn-edit-billing" data-work-id="{{ $work->id }}"
                                            data-invoice-number="{{ $work->invoice_number }}"
                                            data-invoice-date="{{ $work->invoice_date ? $work->invoice_date->format('Y-m-d') : '' }}"
                                            data-invoice-amount="{{ $work->invoice_amount }}"
                                            data-amount-without-gst="{{ $work->amount_without_gst }}"
                                            data-gst-amount="{{ $work->gst_amount }}"
                                            data-payment-status="{{ $work->payment_status }}">
                                        Edit billing
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $tab === 'pending' ? 9 : 12 }}" class="text-center text-muted py-4">No works found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing {{ $works->firstItem() ?? 0 }} to {{ $works->lastItem() ?? 0 }} of {{ $works->total() }} entries
                </div>
                <div>{{ $works->links() }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Billing modal -->
<div class="modal fade" id="billingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="billingModalTitle">Mark billing done</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="billingForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="billingWorkId" name="work_id">
                    <input type="hidden" id="billingFormMode" name="_mode" value="mark">
                    <div class="form-group">
                        <label for="billingInvoiceNumber">Invoice number</label>
                        <input type="text" class="form-control" id="billingInvoiceNumber" name="invoice_number" placeholder="Optional">
                    </div>
                    <div class="form-group">
                        <label for="billingInvoiceDate">Invoice date</label>
                        <input type="date" class="form-control" id="billingInvoiceDate" name="invoice_date">
                    </div>
                    <div class="form-group">
                        <label for="billingInvoiceAmount">Invoice amount</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="billingInvoiceAmount" name="invoice_amount" placeholder="Optional">
                    </div>
                    <div class="form-group">
                        <label for="billingAmountWithoutGst">Amount without GST</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="billingAmountWithoutGst" name="amount_without_gst" placeholder="Optional">
                    </div>
                    <div class="form-group">
                        <label for="billingGstAmount">GST amount</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="billingGstAmount" name="gst_amount" placeholder="Optional">
                    </div>
                    <div class="form-group">
                        <label for="billingPaymentStatus">Payment status</label>
                        <select class="form-control" id="billingPaymentStatus" name="payment_status">
                            <option value="Payment Due">Payment Due</option>
                            <option value="Paid">Paid</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="billingSubmitBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card { border: none; border-radius: 10px; }
.card-header { border-radius: 10px 10px 0 0 !important; }
.table th { font-weight: 600; }
.badge { font-size: 0.75em; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('billingModal');
    const form = document.getElementById('billingForm');
    const workIdInput = document.getElementById('billingWorkId');
    const modeInput = document.getElementById('billingFormMode');
    const submitBtn = document.getElementById('billingSubmitBtn');
    const titleEl = document.getElementById('billingModalTitle');

    function getCsrf() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    }

    function openModal(mode, workId, data) {
        data = data || {};
        workIdInput.value = workId;
        modeInput.value = mode;
        document.getElementById('billingInvoiceNumber').value = data.invoice_number || '';
        document.getElementById('billingInvoiceDate').value = data.invoice_date || '';
        document.getElementById('billingInvoiceAmount').value = data.invoice_amount != null ? data.invoice_amount : '';
        document.getElementById('billingAmountWithoutGst').value = data.amount_without_gst != null ? data.amount_without_gst : '';
        document.getElementById('billingGstAmount').value = data.gst_amount != null ? data.gst_amount : '';
        document.getElementById('billingPaymentStatus').value = data.payment_status || 'Payment Due';
        titleEl.textContent = mode === 'mark' ? 'Mark billing done' : 'Edit billing';
        submitBtn.textContent = 'Save';
        $(modal).modal('show');
    }

    document.querySelectorAll('.btn-mark-billing').forEach(function(btn) {
        btn.addEventListener('click', function() {
            openModal('mark', this.getAttribute('data-work-id'));
        });
    });

    document.querySelectorAll('.btn-edit-billing').forEach(function(btn) {
        btn.addEventListener('click', function() {
            openModal('edit', this.getAttribute('data-work-id'), {
                invoice_number: this.getAttribute('data-invoice-number') || '',
                invoice_date: this.getAttribute('data-invoice-date') || '',
                invoice_amount: this.getAttribute('data-invoice-amount') || '',
                amount_without_gst: this.getAttribute('data-amount-without-gst') || '',
                gst_amount: this.getAttribute('data-gst-amount') || '',
                payment_status: this.getAttribute('data-payment-status') || 'Payment Due'
            });
        });
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const workId = workIdInput.value;
        const mode = modeInput.value;
        const formData = new FormData(form);
        formData.delete('_token');
        formData.delete('work_id');
        formData.delete('_mode');
        const payload = {};
        formData.forEach((v, k) => { if (v) payload[k] = v; });

        const url = mode === 'mark'
            ? '{{ url("works") }}/' + workId + '/mark-billing-done'
            : '{{ url("works") }}/' + workId + '/billing';
        const method = mode === 'mark' ? 'POST' : 'PATCH';

        submitBtn.disabled = true;
        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrf(),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        })
        .then(function(r) { return r.json().then(function(j) { return { ok: r.ok, json: j }; }); })
        .then(function(_ref) {
            var ok = _ref.ok, json = _ref.json;
            submitBtn.disabled = false;
            if (ok && json.success) {
                $(modal).modal('hide');
                location.reload();
            } else {
                alert(json.message || 'An error occurred.');
            }
        })
        .catch(function(err) {
            submitBtn.disabled = false;
            alert('An error occurred.');
            console.error(err);
        });
    });
});
</script>
@endsection
