<?php

namespace App\Http\Controllers;

use App\Models\BankName;
use Illuminate\Http\Request;

class BankNameController extends Controller
{
    public function index(Request $request)
    {
        $query = BankName::query()->withCount('works');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('name', 'like', "%{$search}%");
        }

        $bankNames = $query->orderBy('name')->paginate(25)->withQueryString();
        return view('bank_names.index', compact('bankNames'));
    }

    public function create()
    {
        return view('bank_names.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:bank_names,name',
        ]);

        BankName::create([
            'name' => trim($request->name),
        ]);

        return redirect()->route('bank-names.index')->with('success', 'Bank Name created successfully!');
    }

    public function edit(BankName $bankName)
    {
        return view('bank_names.edit', compact('bankName'));
    }

    public function update(Request $request, BankName $bankName)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:bank_names,name,' . $bankName->id,
        ]);

        $bankName->update([
            'name' => trim($request->name),
        ]);

        return redirect()->route('bank-names.index')->with('success', 'Bank Name updated successfully!');
    }

    public function destroy(BankName $bankName)
    {
        $bankName->delete();
        return redirect()->route('bank-names.index')->with('success', 'Bank Name deleted successfully!');
    }
}
