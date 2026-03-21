<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\User;
use App\Models\Relative;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;




class WorkController extends Controller
{
    // public function __construct()
    // {
    //     $this->authorizeResource(Work::class, 'work');
    // }

    
    private function getUsersByRole()
    {
        $roles = [
            'Super Admin', 
            'KKDA Admin', 
            'In-Charge', 
            'Surveyor', 
            'Reporter', 
            'Checker', 
            'Delivery Person', 
            'Bank Branch',
            'Accountant'
        ];

        $usersByRole = [];
        foreach ($roles as $role) {
            $usersByRole[$role] = User::select('id', 'name')
                ->whereHas('roles', function ($query) use ($role) {
                    $query->where('name', $role);
                })
                ->pluck('name', 'id');
        }

        return $usersByRole;
    }
    
    private function getStatusCounts()
    {
        return Work::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }
    
    private function applyFilters($query, Request $request)
    {
        // Search Functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name_of_applicant', 'like', "%{$search}%")
                  ->orWhere('project_name', 'like', "%{$search}%")
                  ->orWhere('source', 'like', "%{$search}%")
                  ->orWhere('pin_code', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('custom_id', 'like', "%{$search}%")
                  ->orWhere('assignment_date', 'like', "%{$search}%")
                  ->orWhere('remarks', 'like', "%{$search}%")
                  ->orWhere('number_of_applicants', 'like', "%{$search}%")
                  ->orWhereHas('relatives', function($qr) use ($search) {
                      $qr->where('name', 'like', "%{$search}%")
                        ->orWhere('relative_name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by Bank Branch
        if ($request->filled('bank_branch')) {
            $query->where('bank_branch', (int)$request->bank_branch);
        }

        // Filter by Work Type
        if ($request->filled('work_type')) {
            $query->where('work_type', $request->work_type);
        }

        // Filter by Payment Status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by Delivery Status
        if ($request->filled('delivery_status')) {
            $query->where('delivery_status', $request->delivery_status);
        }
        
        // Filter by Print Status
        if ($request->filled('is_printed')) {
            $isPrinted = $request->is_printed == '1' ? 1 : 0;
            $query->where('is_printed', $isPrinted);
            // When filtering for "not printed", only show works where checking is done
            if ($isPrinted === 0) {
                $query->whereNotNull('checking_ended_at');
            }
        }
        
        // Filter by Source
        if ($request->filled('source')) {
            $query->where('source', 'like', "%{$request->source}%");
        }

        // Filter by Result
        if ($request->filled('result')) {
            $query->where('result', $request->result);
        }

        // Filter by Valuer
        if ($request->filled('valuer')) {
            $query->where('valuer', $request->valuer);
        }

        // Filter by Created Date (range)
        $from = $request->input('created_date_from');
        $to   = $request->input('created_date_to');
        if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        } elseif ($from) {
            $query->whereDate('created_at', '>=', $from);
        } elseif ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        // Filter by Report Submit Date (range)
        $rsdFrom = $request->input('report_submit_date_from');
        $rsdTo   = $request->input('report_submit_date_to');
        if ($rsdFrom && $rsdTo) {
            $query->whereBetween('report_submit_date', [$rsdFrom, $rsdTo]);
        } elseif ($rsdFrom) {
            $query->whereDate('report_submit_date', '>=', $rsdFrom);
        } elseif ($rsdTo) {
            $query->whereDate('report_submit_date', '<=', $rsdTo);
        }
        
        return $query;
    }
    public function index(Request $request)
    {
        $query = Work::with([
            'creator', 
            'surveyor', 
            'reporter', 
            'checker', 
            'deliveryPerson', 
            'bankBranch', 
            'relatives',
            'inspection',
            'report',
        ])->orderBy('created_at', 'desc');
        
        // Apply Filters
        $query = $this->applyFilters($query, $request);
    
        $works = $query->paginate(10)->withQueryString();

        $usersByRole = $this->getUsersByRole();
        $statusCounts = $this->getStatusCounts();
        
        // Get result counts for navigation buttons
        $resultCounts = Work::selectRaw('result, COUNT(*) as count')
            ->whereNotNull('result')
            ->groupBy('result')
            ->pluck('count', 'result')
            ->toArray();

        // Count works pending printing
        $pendingPrintCount = Work::whereNotNull('checking_ended_at')->where('is_printed', 0)->count();
    
        return view('works.index', compact('works', 'usersByRole', 'statusCounts', 'resultCounts', 'pendingPrintCount'));
    }

    public function export(Request $request)
    {
        // Build the same filtered query as index
        $query = Work::with([
            'creator', 'surveyor', 'reporter', 'checker', 'deliveryPerson', 'bankBranch', 'relatives', 'inspection', 'report'
        ])->orderBy('created_at', 'desc');

        // Apply Filters
        $query = $this->applyFilters($query, $request);
    
        $works = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="works_export.csv"',
        ];

        $callback = function () use ($works) {
            $file = fopen('php://output', 'w');

            // CSV Header based on provided sample and available fields
            fputcsv($file, [
                'SL. NO.', 'DATE', 'REF NO.', 'CUSTOMER NAME', 'CONT. NUMBER', 'BANK', 'BRANCH',
                'DONE BY', 'CHECK BY', 'INSPECTION BY', 'INCHARGE', 'STATUS', 'RESULT', 'ASSIGNMENT DATE',
                'PRINT DATE', 'RSD', 'REGION', 'SUB-BRANCH', 'SOURCER', 'HLST/SSL', 'INVOICE NO.', 'INVOICE DATE',
                'PRINCIPLE AMOUNT', 'GST', 'INVOICE AMOUNT', 'BILL STATUS', 'REMARKS'
            ]);

            $rowNum = 1;
            foreach ($works as $work) {
                fputcsv($file, [
                    $rowNum++,
                    optional($work->created_at)->format('n/j/Y'),
                    $work->custom_id,
                    $work->name_of_applicant,
                    $work->number_of_applicants,
                    $work->bank_name,
                    optional($work->bankBranch)->name,
                    optional($work->reporter)->name,     // DONE BY
                    optional($work->checker)->name,      // CHECK BY
                    optional($work->surveyor)->name,     // INSPECTION BY
                    optional($work->creator)->name,      // INCHARGE
                    $work->status,
                    $work->result,
                    $work->assignment_date ? (is_string($work->assignment_date) ? $work->assignment_date : $work->assignment_date->format('n/j/Y')) : '',
                    $work->report_submit_date ? (is_string($work->report_submit_date) ? $work->report_submit_date : $work->report_submit_date->format('n/j/Y')) : '',
                    '', // RSD (not tracked)
                    '', // REGION (not tracked)
                    '', // SUB-BRANCH (not tracked)
                    $work->source, // SOURCER (closest available)
                    '', // HLST/SSL (not tracked)
                    $work->invoice_number ?? '',
                    $work->invoice_date ? (is_string($work->invoice_date) ? $work->invoice_date : $work->invoice_date->format('n/j/Y')) : '',
                    $work->amount_without_gst ?? $work->loan_amount_requested, // PRINCIPLE AMOUNT
                    $work->gst_amount ?? '',
                    $work->invoice_amount ?? '',
                    $work->payment_status, // BILL STATUS
                    $work->remarks,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    public function myWorks(Request $request)
{
    $userId = auth()->id(); // Get the currently logged-in user ID

    $query = Work::where('created_by', $userId)
        ->with([
            'creator', 
            'surveyor', 
            'reporter', 
            'checker', 
            'deliveryPerson', 
            'bankBranch', 
            'relatives',
            'inspection',
            'report',
        ])->orderBy('created_at', 'desc');
    
    // Apply filters
    $query = $this->applyFilters($query, $request);
    
    $works = $query->paginate(10)->withQueryString();
    
    $usersByRole = $this->getUsersByRole();
    $statusCounts = $this->getStatusCounts();
    
    // Get result counts for navigation buttons
    $resultCounts = Work::selectRaw('result, COUNT(*) as count')
        ->where('created_by', $userId)
        ->whereNotNull('result')
        ->groupBy('result')
        ->pluck('count', 'result')
        ->toArray();

    // Count works pending printing
    $pendingPrintCount = Work::where('created_by', $userId)->whereNotNull('checking_ended_at')->where('is_printed', 0)->count();
        
    return view('works.index', compact('works','usersByRole', 'statusCounts', 'resultCounts', 'pendingPrintCount'));
}

public function worksAsSurveyor(Request $request)
{
    $userId = auth()->id(); // Get the currently logged-in user ID

    $query = Work::where('assignee_surveyor', $userId)
        ->with([
            'creator', 
            'surveyor', 
            'reporter', 
            'checker', 
            'deliveryPerson', 
            'bankBranch', 
            'relatives',
            'inspection',
            'report',
        ])->orderBy('created_at', 'desc');
    
    // Apply filters
    $query = $this->applyFilters($query, $request);
    
    $works = $query->paginate(10)->withQueryString();
    
    $usersByRole = $this->getUsersByRole();
    $statusCounts = $this->getStatusCounts();
    
    // Get result counts for navigation buttons
    $resultCounts = Work::selectRaw('result, COUNT(*) as count')
        ->where('assignee_surveyor', $userId)
        ->whereNotNull('result')
        ->groupBy('result')
        ->pluck('count', 'result')
        ->toArray();

    // Count works pending printing
    $pendingPrintCount = Work::where('assignee_surveyor', $userId)->whereNotNull('checking_ended_at')->where('is_printed', 0)->count();

    return view('works.index', compact('works','usersByRole', 'statusCounts', 'resultCounts', 'pendingPrintCount'));
}

public function worksAsChecking(Request $request)
{
    $userId = auth()->id(); // Get the currently logged-in user ID

    $query = Work::where('assignee_checker', $userId)
        ->with([
            'creator', 
            'surveyor', 
            'reporter', 
            'checker', 
            'deliveryPerson', 
            'bankBranch', 
            'relatives',
            'inspection',
            'report',
        ])->orderBy('created_at', 'desc');
    
    // Apply filters
    $query = $this->applyFilters($query, $request);
    
    $works = $query->paginate(10)->withQueryString();

    $usersByRole = $this->getUsersByRole();
    $statusCounts = $this->getStatusCounts();
    
    // Get result counts for navigation buttons
    $resultCounts = Work::selectRaw('result, COUNT(*) as count')
        ->where('assignee_checker', $userId)
        ->whereNotNull('result')
        ->groupBy('result')
        ->pluck('count', 'result')
        ->toArray();

    // Count works pending printing
    $pendingPrintCount = Work::where('assignee_checker', $userId)->whereNotNull('checking_ended_at')->where('is_printed', 0)->count();

    return view('works.index', compact('works','usersByRole', 'statusCounts', 'resultCounts', 'pendingPrintCount'));
}

public function worksAsDelivery(Request $request)
{
    $userId = auth()->id(); // Get the currently logged-in user ID

    $query = Work::where('assignee_delivery', $userId)
        ->with([
            'creator', 
            'surveyor', 
            'reporter', 
            'checker', 
            'deliveryPerson', 
            'bankBranch', 
            'relatives',
            'inspection',
            'report',
        ])->orderBy('created_at', 'desc');
    
    // Apply filters
    $query = $this->applyFilters($query, $request);
    
    $works = $query->paginate(10)->withQueryString();

    $usersByRole = $this->getUsersByRole();
    $statusCounts = $this->getStatusCounts();
    
    // Get result counts for navigation buttons
    $resultCounts = Work::selectRaw('result, COUNT(*) as count')
        ->where('assignee_delivery', $userId)
        ->whereNotNull('result')
        ->groupBy('result')
        ->pluck('count', 'result')
        ->toArray();

    // Count works pending printing
    $pendingPrintCount = Work::where('assignee_delivery', $userId)->whereNotNull('checking_ended_at')->where('is_printed', 0)->count();

    return view('works.index', compact('works','usersByRole', 'statusCounts', 'resultCounts', 'pendingPrintCount'));
}

public function worksForReporter(Request $request)
{
    $userId = auth()->id(); // Get the logged-in user ID

    $query = Work::where('assignee_reporter', $userId)
        ->with([
            'creator', 
            'surveyor', 
            'reporter', 
            'checker', 
            'deliveryPerson', 
            'bankBranch', 
            'relatives',
            'inspection',
            'report',
        ])->orderBy('created_at', 'desc');
    
    // Apply filters
    $query = $this->applyFilters($query, $request);

    $works = $query->paginate(10)->withQueryString();

    $usersByRole = $this->getUsersByRole();
    $statusCounts = $this->getStatusCounts();
    
    // Get result counts for navigation buttons
    $resultCounts = Work::selectRaw('result, COUNT(*) as count')
        ->where('assignee_reporter', $userId)
        ->whereNotNull('result')
        ->groupBy('result')
        ->pluck('count', 'result')
        ->toArray();

    // Count works pending printing
    $pendingPrintCount = Work::where('assignee_reporter', $userId)->whereNotNull('checking_ended_at')->where('is_printed', 0)->count();

    return view('works.index', compact('works','usersByRole', 'statusCounts', 'resultCounts', 'pendingPrintCount'));
}
public function worksForBankBranch(Request $request)
{
    $userId = auth()->id(); // Get the logged-in user ID

    $query = Work::where('bank_branch', $userId)
        ->with([
            'creator', 
            'surveyor', 
            'reporter', 
            'checker', 
            'deliveryPerson', 
            'bankBranch', 
            'relatives',
            'inspection',
            'report',
        ])->orderBy('created_at', 'desc');
    
    // Apply filters
    $query = $this->applyFilters($query, $request);
    
    $works = $query->paginate(10)->withQueryString();

    $usersByRole = $this->getUsersByRole();
    $statusCounts = $this->getStatusCounts();
    
    // Get result counts for navigation buttons
    $resultCounts = Work::selectRaw('result, COUNT(*) as count')
        ->where('bank_branch', $userId)
        ->whereNotNull('result')
        ->groupBy('result')
        ->pluck('count', 'result')
        ->toArray();

    // Count works pending printing
    $pendingPrintCount = Work::where('bank_branch', $userId)->whereNotNull('checking_ended_at')->where('is_printed', 0)->count();

    return view('works.index', compact('works','usersByRole', 'statusCounts', 'resultCounts', 'pendingPrintCount'));
}


    public function create()
    {
        $roles = ['Super Admin', 'KKDA Admin', 'In-Charge', 'Surveyor', 'Reporter', 'Checker', 'Delivery Person', 'Bank Branch'];
        $usersByRole = [];

        foreach ($roles as $role) {
            $usersByRole[$role] = User::select('id', 'name')->whereHas('roles', function ($query) use ($role) {
                $query->where('name', $role);
            })->pluck('name', 'id');
        }

        return view('works.create', compact('usersByRole'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'custom_id' => 'nullable|string|max:255|unique:works,custom_id',
                'assignment_date' => 'nullable|date',
                'name_of_applicant' => 'required|string|max:255',
                'number_of_applicants' => 'required|string|max:255',
                'bank_name' => 'nullable|string|max:255',
                'bank_branch' => 'nullable|integer|exists:users,id',
                'source' => 'nullable|string|max:255',
                'address_line_1' => 'nullable|string|max:255',
                'address_line_2' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:255',
                'district' => 'nullable|string|max:255',
                'pin_code' => 'nullable|string|max:255',
                'post_office' => 'nullable|string|max:255',
                'police_station' => 'nullable|string|max:255',
                'project_name' => 'nullable|string|max:255',
                'loan_amount_requested' => 'string|max:255',
                'loan_type' => 'nullable|string|max:255',
                'pdf_1' => 'nullable|file|mimes:pdf|max:204800000000000000000000',
                'work_type' => ['required', Rule::in(['Valuation', 'Fair Rent Valuation', 'Estimate', 'Completion Certificate', 'Vetting'])],
                'valuer' => ['nullable', Rule::in(['a', 'b', 'c', 'd'])],
                // Hold/Canceled moved to result; keep status limited
                'status' => [ Rule::in(['New File', 'Surveying', 'Reporting', 'Checking', 'Printing', 'Completed'])],
                'payment_status' => [ Rule::in(['Payment Due', 'Paid'])],
                'delivery_status' => [ Rule::in(['Delivery Due', 'Delivery Done'])],
                'result' => ['nullable', Rule::in(['Positive', 'Negative', 'Hold', 'Canceled', 'Return'])],
                'remarks' => 'nullable|string',
                'assignee_surveyor' => 'nullable|integer|exists:users,id',
                'assignee_reporter' => 'nullable|integer|exists:users,id',
                'assignee_checker' => 'nullable|integer|exists:users,id',
                'assignee_delivery' => 'nullable|integer|exists:users,id',
            ]);

            if ($request->hasFile('pdf_1')) {
                $filePath = $request->file('pdf_1')->store('pdfs', 'public');
                $validatedData['pdf_1'] = $filePath;
            }

            $validatedData['created_by'] = Auth::id();

            $work = Work::create($validatedData);
            
            return redirect()->route('works.myWorks')->with('success', 'Work created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function show($id)
{
    $work = Work::with([
        'creator', 
        'surveyor', 
        'reporter', 
        'checker', 
        'deliveryPerson', 
        'bankBranch', 
        'relatives'
    ])->findOrFail($id);

    return view('works.show', compact('work'));
}


    public function edit($id)
    {
        $roles = ['Super Admin', 'KKDA Admin', 'In-Charge', 'Surveyor', 'Reporter', 'Checker', 'Delivery Person', 'Bank Branch'];
        $usersByRole = [];

        foreach ($roles as $role) {
            $usersByRole[$role] = User::select('id', 'name')->whereHas('roles', function ($query) use ($role) {
                $query->where('name', $role);
            })->pluck('name', 'id');
        }

        $work = Work::findOrFail($id);
        return view('works.edit', compact('work', 'usersByRole'));
    }


    public function update(Request $request, $id)
    {
        try {
            // Validate Input
            $validatedData = $request->validate([
                'custom_id' => 'nullable|string|max:255|unique:works,custom_id,' . $id,
                'assignment_date' => 'nullable|date',
                'name_of_applicant' => 'required|string|max:255',
                'number_of_applicants' => 'nullable|numeric',
                'bank_name' => 'nullable|string|max:255',
                'bank_branch' => 'nullable|integer|exists:users,id',
                'source' => 'nullable|string|max:255',
                'address_line_1' => 'nullable|string|max:255',
                'address_line_2' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:255',
                'district' => 'nullable|string|max:255',
                'pin_code' => 'nullable|string|max:10',
                'post_office' => 'nullable|string|max:255',
                'police_station' => 'nullable|string|max:255',
                'project_name' => 'nullable|string|max:255',
                'loan_amount_requested' => 'string|max:255',
                'loan_type' => 'nullable|string|max:255',
                'pdf_1' => 'nullable|file|mimes:pdf|max:204800000000000000000000', // 5MB Limit
                'work_type' => ['required', Rule::in(['Valuation', 'Fair Rent Valuation', 'Estimate', 'Completion Certificate', 'Vetting'])],
                'valuer' => ['nullable', Rule::in(['a', 'b', 'c', 'd'])],
                'status' => ['required', Rule::in(['New File', 'Surveying', 'Reporting', 'Checking', 'Printing', 'Completed'])],
                'payment_status' => ['required', Rule::in(['Payment Due', 'Paid'])],
                'delivery_status' => ['required', Rule::in(['Delivery Due', 'Delivery Done'])],
                'result' => ['nullable', Rule::in(['Positive', 'Negative', 'Hold', 'Canceled', 'Return'])],
                'remarks' => 'nullable|string',
                'report_submit_date' => 'nullable|date',
                'assignee_surveyor' => 'nullable|integer|exists:users,id',
                'assignee_reporter' => 'nullable|integer|exists:users,id',
                'assignee_checker' => 'nullable|integer|exists:users,id',
                'assignee_delivery' => 'nullable|integer|exists:users,id',
            ]);
    
            // Find Work Entry
            $work = Work::findOrFail($id);

            if (($validatedData['status'] ?? null) === 'Completed') {
                // $guardMessage = $this->completionGuardMessage($work);
                // if ($guardMessage) {
                //     return back()->withInput()->withErrors(['status' => $guardMessage]);
                // }
            }
    
            // Handle PDF Upload
            if ($request->hasFile('pdf_1')) {
                $filePath = $request->file('pdf_1')->store('pdfs', 'public');
                $validatedData['pdf_1'] = $filePath;
            }
    
            // Update Work
            $work->update($validatedData);

            if (($validatedData['status'] ?? null) === 'Surveying') {
                $this->autoAssignRole($work, 'Surveyor', 'Surveying', 'assignee_surveyor');
            } elseif (($validatedData['status'] ?? null) === 'Reporting') {
                $this->autoAssignRole($work, 'Reporter', 'Reporting', 'assignee_reporter');
            } elseif (($validatedData['status'] ?? null) === 'Checking') {
                $this->autoAssignRole($work, 'Checker', 'Checking', 'assignee_checker');
            }
            $work->save();
    
            return redirect()->route('works.myWorks')->with('success', 'Work updated successfully.');
        } catch (\Exception $e) {
            
            return back()->withInput()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }


    public function destroy($id)
    {
        $work = Work::findOrFail($id);
        $work->delete();
        return redirect()->route('works.myWorks')->with('success', 'Work deleted successfully.');
    }

    public function get_work_by_status(Request $request)
        {
            $statuses = $request->input('status', []); // Get the status array from request
        
            // Ensure it's an array (in case a single value is passed)
            if (!is_array($statuses)) {
                $statuses = [$statuses];
            }
        
            $works = Work::whereIn('status', $statuses)->paginate(10);
            
            $usersByRole = $this->getUsersByRole();
            $statusCounts = $this->getStatusCounts();

            return view('works.index', compact('works','usersByRole', 'statusCounts'));
        }

    // public function get_work_by_status(Request $request)
    //     {
    //         $statuses = $request->input('status', []); // Get the status array from request
        
    //         // Ensure it's an array (in case a single value is passed)
    //         if (!is_array($statuses)) {
    //             $statuses = [$statuses];
    //         }
        
    //         $works = Work::whereIn('status', $statuses)->paginate(10);
            
    //         return view('works.index', compact('works'));
    //     }



    public function dashboard()
    {
       // Get total works
       $totalWorks = Work::count();

       // Individual work status counts
       $statusCounts = Work::selectRaw('status, COUNT(*) as count')
           ->groupBy('status')
           ->pluck('count', 'status');

       // Loan amount statistics
       $totalLoanAmount = Work::sum('loan_amount_requested');

       // Payment status
       $paymentCounts = Work::selectRaw('payment_status, COUNT(*) as count')
           ->groupBy('payment_status')
           ->pluck('count', 'payment_status');

       // Delivery status
       $deliveryCounts = Work::selectRaw('delivery_status, COUNT(*) as count')
           ->groupBy('delivery_status')
           ->pluck('count', 'delivery_status');

       // Work type distribution
       $workTypes = Work::selectRaw('work_type, COUNT(*) as count')
           ->groupBy('work_type')
           ->pluck('count', 'work_type');

       // Time analytics (Reporting & Checking duration) - for admin
       $reportingTimeStats = null;
       $checkingTimeStats = null;
       if (Schema::hasColumn('works', 'reporting_started_at')) {
           $reportingTimeStats = Work::selectRaw('
               COUNT(*) as count,
               AVG(TIMESTAMPDIFF(MINUTE, reporting_started_at, reporting_ended_at)) as avg_minutes
           ')
           ->whereNotNull('reporting_started_at')
           ->whereNotNull('reporting_ended_at')
           ->first();
       }
       if (Schema::hasColumn('works', 'checking_started_at')) {
           $checkingTimeStats = Work::selectRaw('
               COUNT(*) as count,
               AVG(TIMESTAMPDIFF(MINUTE, checking_started_at, checking_ended_at)) as avg_minutes
           ')
           ->whereNotNull('checking_started_at')
           ->whereNotNull('checking_ended_at')
           ->first();
       }

       return view('works.dashboard', compact(
           'totalWorks', 'statusCounts',
           'totalLoanAmount', 'paymentCounts', 
           'deliveryCounts', 'workTypes',
           'reportingTimeStats', 'checkingTimeStats'
       ));
   }
   public function uploadFinalReports(Request $request, $id)
    {
        $request->validate([
            'final_report_pdf' => 'nullable|mimes:pdf|max:51200', // Allow only PDFs, max size 2MB
            'final_report_word' => 'nullable|mimes:doc,docx|max:51200', // Allow only Word docs, max size 2MB
        ]);
    
        $work = Work::findOrFail($id);
    
        if ($request->hasFile('final_report_pdf')) {
            $pdfPath = $request->file('final_report_pdf')->store('final_reports/pdf', 'public');
            $work->final_report_pdf = $pdfPath;
        }
    
        if ($request->hasFile('final_report_word')) {
            $wordPath = $request->file('final_report_word')->store('final_reports/word', 'public');
            $work->final_report_word = $wordPath;
        }
    
        $work->save();
    
        return redirect()->back()->with('success', 'Final reports uploaded successfully.');
    }
    
    public function documentUpload(Request $request, $id)
    {
        try {
            // Validate the uploaded files
            $validatedData = $request->validate([
                'final_report_pdf' => 'nullable|file|mimes:pdf|max:51200',   // 5MB
                'final_report_word' => 'nullable|file|mimes:doc,docx|max:51200', // 5MB
            ]);
    
            // Find the work entry
            $work = Work::findOrFail($id);
    
            // Handle PDF upload
            if ($request->hasFile('final_report_pdf')) {
                $pdfPath = $request->file('final_report_pdf')->store('final_reports/pdf', 'public');
                $validatedData['final_report_pdf'] = $pdfPath;
            }
    
            // Handle Word upload
            if ($request->hasFile('final_report_word')) {
                $wordPath = $request->file('final_report_word')->store('final_reports/word', 'public');
                $validatedData['final_report_word'] = $wordPath;
            }
    
            // Role-based status update
            $userRole = auth()->user()->role->name ?? null;
    
            if ($userRole === 'Reporter') {
                $validatedData['status'] = 'Checking';
            } elseif ($userRole === 'Checker') {
                $validatedData['status'] = 'On Delivery';
            }
    
            // Update the work record
            $work->update($validatedData);
    
            return back()->withInput()->with('success', 'Final report files uploaded successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function getPostOfficeByPincode($pincode)
    {
        try {
            // Load the JSON file
            $jsonPath = storage_path('app/public/pin_codes.json');
            
            if (!file_exists($jsonPath)) {
                return response()->json(['error' => 'Pincode data not found'], 404);
            }
            
            $jsonContent = file_get_contents($jsonPath);
            $lines = explode("\n", $jsonContent);
            
            $postOffices = [];
            
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;
                
                $data = json_decode($line, true);
                if ($data && isset($data['PINCODE']) && $data['PINCODE'] == (float)$pincode) {
                    $postOffices[] = $data['OFFICE NAME'];
                }
            }
            
            if (empty($postOffices)) {
                return response()->json(['error' => 'No post office found for this pincode'], 404);
            }
            
            // Remove duplicates and return unique post offices
            $uniquePostOffices = array_unique($postOffices);
            
            return response()->json([
                'success' => true,
                'post_offices' => array_values($uniquePostOffices)
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching post office data'], 500);
        }
    }
    




    

    public function updateStatus(Request $request, $id)
    {
        try {
            $work = Work::findOrFail($id);
            $user = auth()->user();
            
            // Check permissions
            $hasPermission = false;
            
            // Super Admin and KKDA Admin can update any work
            if ($user->roles->contains('name', 'Super Admin') || $user->roles->contains('name', 'KKDA Admin')) {
                $hasPermission = true;
            }
            // In-Charge can update works they created
            elseif ($user->roles->contains('name', 'In-Charge') && $work->created_by == $user->id) {
                $hasPermission = true;
            }
            // Reporter can update works assigned to them
            elseif ($user->roles->contains('name', 'Reporter') && $work->assignee_reporter == $user->id) {
                $hasPermission = true;
            }
            // Surveyor can update works assigned to them
            elseif ($user->roles->contains('name', 'Surveyor') && $work->assignee_surveyor == $user->id) {
                $hasPermission = true;
            }
            // Checker can update works assigned to them
            elseif ($user->roles->contains('name', 'Checker') && $work->assignee_checker == $user->id) {
                $hasPermission = true;
            }
            
            if (!$hasPermission) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to update the status of this work.'
                ], 403);
            }
            
            $validatedData = $request->validate([
                'status' => ['required', Rule::in(['New File', 'Surveying', 'Reporting', 'Checking', 'Printing', 'Completed'])],
            ]);

            if (($validatedData['status'] ?? null) === 'Completed') {
                // $guardMessage = $this->completionGuardMessage($work);
                // if ($guardMessage) {
                //     return response()->json(['success' => false, 'message' => $guardMessage], 422);
                // }
            }

            $work->update($validatedData);

            if (($validatedData['status'] ?? null) === 'Surveying') {
                $this->autoAssignRole($work, 'Surveyor', 'Surveying', 'assignee_surveyor');
            } elseif (($validatedData['status'] ?? null) === 'Reporting') {
                $this->autoAssignRole($work, 'Reporter', 'Reporting', 'assignee_reporter');
            } elseif (($validatedData['status'] ?? null) === 'Checking') {
                $this->autoAssignRole($work, 'Checker', 'Checking', 'assignee_checker');
            }
            $work->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'status' => $work->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function advanceStatus(Request $request, $work)
    {
        try {
            $work = $work instanceof Work ? $work : Work::findOrFail($work);
            $user = auth()->user();

            // Same permission check as updateStatus
            $hasPermission = false;
            if ($user->roles->contains('name', 'Super Admin') || $user->roles->contains('name', 'KKDA Admin')) {
                $hasPermission = true;
            } elseif ($user->roles->contains('name', 'In-Charge') && $work->created_by == $user->id) {
                $hasPermission = true;
            } elseif ($user->roles->contains('name', 'Reporter') && $work->assignee_reporter == $user->id) {
                $hasPermission = true;
            } elseif ($user->roles->contains('name', 'Surveyor') && $work->assignee_surveyor == $user->id) {
                $hasPermission = true;
            } elseif ($user->roles->contains('name', 'Checker') && $work->assignee_checker == $user->id) {
                $hasPermission = true;
            }

            if (!$hasPermission) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to update the status of this work.'
                ], 403);
            }

            $nextStatus = match ($work->status) {
                'New File' => 'Surveying',
                'Surveying' => 'Reporting',
                'Reporting' => 'Checking',
                'Checking' => 'Printing',
                'Printing' => 'Completed',
                default => null,
            };

            if ($nextStatus === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status cannot be advanced further.'
                ], 400);
            }

            if ($nextStatus === 'Completed') {
                // $guardMessage = $this->completionGuardMessage($work);
                // if ($guardMessage) {
                //     return response()->json(['success' => false, 'message' => $guardMessage], 422);
                // }
            }

            $work->update(['status' => $nextStatus]);

            if ($nextStatus === 'Surveying') {
                $this->autoAssignRole($work, 'Surveyor', 'Surveying', 'assignee_surveyor');
            } elseif ($nextStatus === 'Reporting') {
                $this->autoAssignRole($work, 'Reporter', 'Reporting', 'assignee_reporter');
            } elseif ($nextStatus === 'Checking') {
                $this->autoAssignRole($work, 'Checker', 'Checking', 'assignee_checker');
            }
            $work->save();

            return response()->json([
                'success' => true,
                'message' => 'Status advanced to ' . $nextStatus,
                'status' => $work->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    private function hasWorkPermission(Work $work): bool
    {
        $user = auth()->user();
        if ($user->roles->contains('name', 'Super Admin') || $user->roles->contains('name', 'KKDA Admin')) {
            return true;
        }
        if ($user->roles->contains('name', 'In-Charge') && $work->created_by == $user->id) {
            return true;
        }
        if ($user->roles->contains('name', 'Reporter') && $work->assignee_reporter == $user->id) {
            return true;
        }
        if ($user->roles->contains('name', 'Surveyor') && $work->assignee_surveyor == $user->id) {
            return true;
        }
        if ($user->roles->contains('name', 'Checker') && $work->assignee_checker == $user->id) {
            return true;
        }
        return false;
    }

    private function completionBlockedMessage(): string
    {
        return 'Please print this work and mark it as printed before setting status to Completed.';
    }

    /**
     * Returns an error message if work cannot be completed, otherwise null.
     */
    private function completionGuardMessage(Work $work): ?string
    {
        if (!$work->is_printed) {
            return $this->completionBlockedMessage();
        }
        return null;
    }

    public function startReporting(Work $work)
    {
        if (!$this->hasWorkPermission($work)) {
            return response()->json(['success' => false, 'message' => 'You do not have permission.'], 403);
        }
        if ($work->status !== 'Reporting') {
            return response()->json(['success' => false, 'message' => 'Work must be in Reporting status.'], 400);
        }
        if (!$work->assignee_reporter) {
            return response()->json(['success' => false, 'message' => 'Reporter must be assigned first.'], 400);
        }
        if ($work->reporting_started_at) {
            return response()->json(['success' => false, 'message' => 'Reporting already started.'], 400);
        }
        $work->update(['reporting_started_at' => now()]);
        return response()->json(['success' => true, 'message' => 'Reporting started.']);
    }

    public function endReporting(Work $work)
    {
        if (!$this->hasWorkPermission($work)) {
            return response()->json(['success' => false, 'message' => 'You do not have permission.'], 403);
        }
        if ($work->status !== 'Reporting') {
            return response()->json(['success' => false, 'message' => 'Work must be in Reporting status.'], 400);
        }
        if (!$work->reporting_started_at) {
            return response()->json(['success' => false, 'message' => 'Start Reporting first.'], 400);
        }
        $work->update([
            'reporting_ended_at' => now(),
            'status' => 'Checking',
        ]);
        return response()->json(['success' => true, 'message' => 'Reporting done. Status advanced to Checking.', 'status' => 'Checking']);
    }

    public function startChecking(Work $work)
    {
        if (!$this->hasWorkPermission($work)) {
            return response()->json(['success' => false, 'message' => 'You do not have permission.'], 403);
        }
        if ($work->status !== 'Checking') {
            return response()->json(['success' => false, 'message' => 'Work must be in Checking status.'], 400);
        }
        if (!$work->assignee_checker) {
            return response()->json(['success' => false, 'message' => 'Checker must be assigned first.'], 400);
        }
        if ($work->checking_started_at) {
            return response()->json(['success' => false, 'message' => 'Checking already started.'], 400);
        }
        $work->update(['checking_started_at' => now()]);
        return response()->json(['success' => true, 'message' => 'Checking started.']);
    }

    public function endChecking(Work $work)
    {
        if (!$this->hasWorkPermission($work)) {
            return response()->json(['success' => false, 'message' => 'You do not have permission.'], 403);
        }
        if ($work->status !== 'Checking') {
            return response()->json(['success' => false, 'message' => 'Work must be in Checking status.'], 400);
        }
        if (!$work->checking_started_at) {
            return response()->json(['success' => false, 'message' => 'Start Checking first.'], 400);
        }
        // $guardMessage = $this->completionGuardMessage($work);
        // if ($guardMessage) {
        //     return response()->json(['success' => false, 'message' => $guardMessage], 422);
        // }
        $work->update([
            'checking_ended_at' => now(),
            'status' => 'Printing',
        ]);
        return response()->json(['success' => true, 'message' => 'Checking done. Status advanced to Printing.', 'status' => 'Printing']);
    }

    public function endPrinting(Work $work)
    {
        if (!$this->hasWorkPermission($work)) {
            return response()->json(['success' => false, 'message' => 'You do not have permission.'], 403);
        }
        if ($work->status !== 'Printing') {
            return response()->json(['success' => false, 'message' => 'Work must be in Printing status.'], 400);
        }
        
        // $guardMessage = $this->completionGuardMessage($work);
        // if ($guardMessage) {
        //     return response()->json(['success' => false, 'message' => $guardMessage], 422);
        // }
        
        $work->update([
            'status' => 'Completed',
        ]);
        return response()->json(['success' => true, 'message' => 'Printing done. Status advanced to Completed.', 'status' => 'Completed']);
    }

    public function togglePrinted(Request $request, $id)
    {
        try {
            $user = auth()->user();
            
            // Check if user is Bank Branch - deny access
            if ($user->roles->contains('name', 'Bank Branch')) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to update print status.'
                ], 403);
            }
            
            $work = Work::findOrFail($id);
            
            $validatedData = $request->validate([
                'is_printed' => 'required|boolean',
            ]);

            // When print is checked, automatically set report_submit_date to today
            if ($validatedData['is_printed']) {
                $validatedData['report_submit_date'] = now()->toDateString();
            }

            $work->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Print status updated successfully',
                'is_printed' => $work->is_printed
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleVdn(Request $request, $id)
    {
        try {
            $user = auth()->user();
            
            // Check if user is Bank Branch - deny access
            if ($user->roles->contains('name', 'Bank Branch')) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to update VDN status.'
                ], 403);
            }
            
            $work = Work::findOrFail($id);
            
            $validatedData = $request->validate([
                'is_vdn' => 'required|boolean',
            ]);

            $work->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'VDN status updated successfully',
                'is_vdn' => $work->is_vdn
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleResult(Request $request, Work $work)
    {
        try {
            $userRoles = auth()->user()->roles->pluck('name')->toArray();
            $allowedRoles = ['Super Admin', 'KKDA Admin', 'In-Charge', 'Surveyor', 'Reporter', 'Checker'];
            
            if (empty(array_intersect($allowedRoles, $userRoles))) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to update the result.'
                ], 403);
            }
            
            $validatedData = $request->validate([
                'result' => 'required|string|in:Hold,Negative,null',
                'remarks' => 'nullable|string'
            ]);

            $newResult = $validatedData['result'] === 'null' ? null : $validatedData['result'];
            
            $work->result = $newResult;
            if ($request->filled('remarks')) {
                $work->remarks = $validatedData['remarks'];
            }
            
            $work->save();

            return response()->json([
                'success' => true,
                'message' => 'Result updated successfully',
                'result' => $work->result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    private function autoAssignRole($work, $roleName, $status, $assigneeColumn)
    {
        // Only assign if currently null
        if (!empty($work->{$assigneeColumn})) {
            return;
        }

        // Get all users with the given role
        $users = User::whereHas('roles', function($q) use ($roleName) {
            $q->where('name', $roleName);
        })->get();

        if ($users->isEmpty()) {
            return; // No users found with this role
        }

        $minLoad = PHP_INT_MAX;
        $selectedUserId = null;

        foreach ($users as $user) {
            // Count active work items assigned to this user that are currently in this specific stage
            $load = Work::where($assigneeColumn, $user->id)
                        ->where('status', $status)
                        ->count();
                        
            if ($load < $minLoad) {
                $minLoad = $load;
                $selectedUserId = $user->id;
            }
        }

        if ($selectedUserId) {
            $work->{$assigneeColumn} = $selectedUserId;
        }
    }
}
