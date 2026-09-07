<?php

namespace App\Http\Controllers;

use App\Models\LoanType;
use Illuminate\Http\Request;

class LoanTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = LoanType::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('name', 'like', "%{$search}%");
        }

        $loanTypes = $query->orderBy('name')->paginate(20)->withQueryString();
        return view('loan_types.index', compact('loanTypes'));
    }

    public function create()
    {
        return view('loan_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:loan_types,name|max:255',
        ]);

        LoanType::create([
            'name' => trim($request->name),
        ]);

        return redirect()->route('loan-types.index')->with('success', 'Loan Type created successfully!');
    }

    public function edit(LoanType $loanType)
    {
        return view('loan_types.edit', compact('loanType'));
    }

    public function update(Request $request, LoanType $loanType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:loan_types,name,' . $loanType->id,
        ]);

        $loanType->update([
            'name' => trim($request->name),
        ]);

        return redirect()->route('loan-types.index')->with('success', 'Loan Type updated successfully!');
    }

    public function destroy(LoanType $loanType)
    {
        $loanType->delete();
        return redirect()->route('loan-types.index')->with('success', 'Loan Type deleted successfully!');
    }
}
