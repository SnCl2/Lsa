<?php

namespace App\Http\Controllers;

use App\Models\ProjectName;
use Illuminate\Http\Request;

class ProjectNameController extends Controller
{
    public function index(Request $request)
    {
        $query = ProjectName::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('type')) {
            $query->where('project_type', $request->type);
        }

        $projectNames = $query->orderBy('name')->paginate(25)->withQueryString();
        $types = ProjectName::TYPES;
        return view('project_names.index', compact('projectNames', 'types'));
    }

    public function create()
    {
        $types = ProjectName::TYPES;
        return view('project_names.create', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:project_names,name|max:255',
            'project_type' => 'required|string|in:' . implode(',', ProjectName::TYPES),
            'project_rate' => 'nullable|numeric|min:0',
        ]);

        ProjectName::create([
            'name' => trim($request->name),
            'project_type' => $request->input('project_type', ProjectName::TYPE_NORMAL),
            'project_rate' => $request->filled('project_rate') ? $request->project_rate : null,
        ]);

        return redirect()->route('project-names.index')->with('success', 'Project Name created successfully!');
    }

    public function edit(ProjectName $projectName)
    {
        $types = ProjectName::TYPES;
        return view('project_names.edit', compact('projectName', 'types'));
    }

    public function update(Request $request, ProjectName $projectName)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:project_names,name,' . $projectName->id,
            'project_type' => 'required|string|in:' . implode(',', ProjectName::TYPES),
            'project_rate' => 'nullable|numeric|min:0',
        ]);

        $projectName->update([
            'name' => trim($request->name),
            'project_type' => $request->input('project_type', ProjectName::TYPE_NORMAL),
            'project_rate' => $request->filled('project_rate') ? $request->project_rate : null,
        ]);

        return redirect()->route('project-names.index')->with('success', 'Project Name updated successfully!');
    }

    public function destroy(ProjectName $projectName)
    {
        $projectName->delete();
        return redirect()->route('project-names.index')->with('success', 'Project Name deleted successfully!');
    }
}
