<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    private const POSITIVE_RESULTS = ['Positive', '+ve'];

    public function index(Request $request)
    {
        $tab = $request->get('tab', 'pending');
        if (!in_array($tab, ['pending', 'billed', 'payment_done'])) {
            $tab = 'pending';
        }

        $query = Work::whereIn('result', self::POSITIVE_RESULTS);

        if ($tab === 'pending') {
            $query->where('is_billing_done', false);
        } elseif ($tab === 'billed') {
            $query->where('is_billing_done', true)->where('payment_status', 'Payment Due');
        } else {
            $query->where('payment_status', 'Paid');
        }

        // Apply Filters
        $query = $this->applyFilters($query, $request);

        $works = $query->with([
            'creator',
            'surveyor',
            'reporter',
            'checker',
            'deliveryPerson',
            'bankBranch',
            'relatives',
            'inspection',
            'report',
            'billingDoneBy',
        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $countPending = Work::whereIn('result', self::POSITIVE_RESULTS)->where('is_billing_done', false)->count();
        $countBilled = Work::whereIn('result', self::POSITIVE_RESULTS)->where('is_billing_done', true)->where('payment_status', 'Payment Due')->count();
        $countPaymentDone = Work::whereIn('result', self::POSITIVE_RESULTS)->where('payment_status', 'Paid')->count();

        $dropdownData = $this->getDropdownData();

        return view('account.index', compact('works', 'tab', 'countPending', 'countBilled', 'countPaymentDone', 'dropdownData'));
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name_of_applicant', 'like', "%{$search}%")
                    ->orWhere('project_name', 'like', "%{$search}%")
                    ->orWhere('custom_id', 'like', "%{$search}%")
                    ->orWhere('invoice_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('bank_branch')) {
            $query->where('bank_branch', $request->bank_branch);
        }

        if ($request->filled('work_type')) {
            $query->where('work_type', $request->work_type);
        }

        if ($request->filled('valuer')) {
            $query->where('valuer', $request->valuer);
        }

        // Created Date range
        if ($request->filled('created_date_from')) {
            $query->whereDate('created_at', '>=', $request->created_date_from);
        }
        if ($request->filled('created_date_to')) {
            $query->whereDate('created_at', '<=', $request->created_date_to);
        }

        // Invoice Date range
        if ($request->filled('invoice_date_from')) {
            $query->whereDate('invoice_date', '>=', $request->invoice_date_from);
        }
        if ($request->filled('invoice_date_to')) {
            $query->whereDate('invoice_date', '<=', $request->invoice_date_to);
        }

        // Billing Done Date range
        if ($request->filled('billing_date_from')) {
            $query->whereDate('billing_done_at', '>=', $request->billing_date_from);
        }
        if ($request->filled('billing_date_to')) {
            $query->whereDate('billing_done_at', '<=', $request->billing_date_to);
        }

        return $query;
    }

    private function getDropdownData()
    {
        return [
            'bank_branches' => \App\Models\User::whereHas('roles', function($q) {
                $q->where('name', 'Bank Branch');
            })->pluck('name', 'id'),
            'work_types' => ['Valuation', 'Fair Rent Valuation', 'Estimate', 'Completion Certificate', 'Vetting'],
            'valuers' => ['a', 'b', 'c', 'd'],
        ];
    }

    public function markBillingDone(Request $request, Work $work)
    {
        $user = auth()->user();
        if (!$user->roles->contains('name', 'Super Admin') && !$user->roles->contains('name', 'KKDA Admin') && !$user->roles->contains('name', 'Accountant')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        if (!in_array($work->result, self::POSITIVE_RESULTS)) {
            return response()->json(['success' => false, 'message' => 'Only positive works can be marked as billing done.'], 422);
        }
        if ($work->is_billing_done) {
            return response()->json(['success' => false, 'message' => 'Billing is already marked done for this work.'], 422);
        }

        $validated = $request->validate([
            'invoice_number' => 'nullable|string|max:255',
            'invoice_date' => 'nullable|date',
            'invoice_amount' => 'nullable|numeric|min:0',
            'amount_without_gst' => 'nullable|numeric|min:0',
            'gst_amount' => 'nullable|numeric|min:0',
            'payment_status' => ['nullable', Rule::in(['Payment Due', 'Paid'])],
        ]);

        $work->is_billing_done = true;
        $work->billing_done_at = now();
        $work->billing_done_by = $user->id;
        $work->payment_status = $validated['payment_status'] ?? 'Payment Due';
        foreach (['invoice_number', 'invoice_date', 'invoice_amount', 'amount_without_gst', 'gst_amount'] as $key) {
            if (array_key_exists($key, $validated) && $validated[$key] !== null) {
                $work->$key = $validated[$key];
            }
        }
        $work->save();

        return response()->json(['success' => true, 'message' => 'Billing marked as done.']);
    }

    public function updateBilling(Request $request, Work $work)
    {
        $user = auth()->user();
        if (!$user->roles->contains('name', 'Super Admin') && !$user->roles->contains('name', 'KKDA Admin') && !$user->roles->contains('name', 'Accountant')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        if (!$work->is_billing_done) {
            return response()->json(['success' => false, 'message' => 'Billing has not been marked done for this work.'], 422);
        }

        $validated = $request->validate([
            'invoice_number' => 'nullable|string|max:255',
            'invoice_date' => 'nullable|date',
            'invoice_amount' => 'nullable|numeric|min:0',
            'amount_without_gst' => 'nullable|numeric|min:0',
            'gst_amount' => 'nullable|numeric|min:0',
            'payment_status' => ['nullable', Rule::in(['Payment Due', 'Paid'])],
        ]);

        foreach (['invoice_number', 'invoice_date', 'invoice_amount', 'amount_without_gst', 'gst_amount', 'payment_status'] as $key) {
            if (array_key_exists($key, $validated)) {
                $work->$key = $validated[$key];
            }
        }
        $work->save();

        return response()->json(['success' => true, 'message' => 'Billing updated.']);
    }
}
