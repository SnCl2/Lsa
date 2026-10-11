<?php

namespace App\Http\Controllers;

use App\Models\ProjectName;
use App\Models\BankName;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectNameController extends Controller
{
    public function index(Request $request)
    {
        $query = ProjectName::query()
            ->with(['bankBranch'])
            ->withCount('works');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('bank_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('project_type', $request->type);
        }

        if ($request->filled('bank_name')) {
            $query->where('bank_name', $request->bank_name);
        }

        if ($request->filled('bank_branch')) {
            $query->where('bank_branch', (int) $request->bank_branch);
        }

        $projectNames = $query->orderBy('name')->paginate(25)->withQueryString();
        $types = ProjectName::TYPES;
        $bankNames = BankName::orderBy('name')->get();
        $bankBranches = User::whereHas('roles', function ($q) {
            $q->where('name', 'Bank Branch');
        })->orderBy('name')->get();

        return view('project_names.index', compact('projectNames', 'types', 'bankNames', 'bankBranches'));
    }

    public function create()
    {
        $types = ProjectName::TYPES;
        $bankNames = BankName::orderBy('name')->get();
        $bankBranches = User::whereHas('roles', function ($q) {
            $q->where('name', 'Bank Branch');
        })->orderBy('name')->get();

        return view('project_names.create', compact('types', 'bankNames', 'bankBranches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:project_names,name|max:255',
            'project_type' => 'required|string|in:' . implode(',', ProjectName::TYPES),
            'project_rate' => 'nullable|numeric|min:0',
            'bank_name' => 'nullable|string|max:255',
            'bank_branch' => 'nullable|exists:users,id',
        ]);

        $bankName = $request->filled('bank_name') ? trim($request->bank_name) : null;
        $bankId = null;
        if ($bankName) {
            $bankModel = BankName::where('name', $bankName)->first();
            $bankId = $bankModel?->id;
        }

        ProjectName::create([
            'name' => trim($request->name),
            'project_type' => $request->input('project_type', ProjectName::TYPE_NORMAL),
            'project_rate' => $request->filled('project_rate') ? $request->project_rate : null,
            'bank_name' => $bankName,
            'bank_id' => $bankId,
            'bank_branch' => $request->filled('bank_branch') ? (int) $request->bank_branch : null,
        ]);

        return redirect()->route('project-names.index')->with('success', 'Project Name created successfully!');
    }

    public function edit(ProjectName $projectName)
    {
        $types = ProjectName::TYPES;
        $bankNames = BankName::orderBy('name')->get();
        $bankBranches = User::whereHas('roles', function ($q) {
            $q->where('name', 'Bank Branch');
        })->orderBy('name')->get();

        return view('project_names.edit', compact('projectName', 'types', 'bankNames', 'bankBranches'));
    }

    public function update(Request $request, ProjectName $projectName)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:project_names,name,' . $projectName->id,
            'project_type' => 'required|string|in:' . implode(',', ProjectName::TYPES),
            'project_rate' => 'nullable|numeric|min:0',
            'bank_name' => 'nullable|string|max:255',
            'bank_branch' => 'nullable|exists:users,id',
        ]);

        $bankName = $request->filled('bank_name') ? trim($request->bank_name) : null;
        $bankId = null;
        if ($bankName) {
            $bankModel = BankName::where('name', $bankName)->first();
            $bankId = $bankModel?->id;
        }

        $projectName->update([
            'name' => trim($request->name),
            'project_type' => $request->input('project_type', ProjectName::TYPE_NORMAL),
            'project_rate' => $request->filled('project_rate') ? $request->project_rate : null,
            'bank_name' => $bankName,
            'bank_id' => $bankId,
            'bank_branch' => $request->filled('bank_branch') ? (int) $request->bank_branch : null,
        ]);

        return redirect()->route('project-names.index')->with('success', 'Project Name updated successfully!');
    }

    public function destroy(ProjectName $projectName)
    {
        $projectName->delete();
        return redirect()->route('project-names.index')->with('success', 'Project Name deleted successfully!');
    }
}
