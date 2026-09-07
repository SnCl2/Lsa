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

        $projectNames = $query->orderBy('name')->paginate(25)->withQueryString();
        return view('project_names.index', compact('projectNames'));
    }

    public function create()
    {
        return view('project_names.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:project_names,name|max:255',
        ]);

        ProjectName::create([
            'name' => trim($request->name),
        ]);

        return redirect()->route('project-names.index')->with('success', 'Project Name created successfully!');
    }

    public function edit(ProjectName $projectName)
    {
        return view('project_names.edit', compact('projectName'));
    }

    public function update(Request $request, ProjectName $projectName)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:project_names,name,' . $projectName->id,
        ]);

        $projectName->update([
            'name' => trim($request->name),
        ]);

        return redirect()->route('project-names.index')->with('success', 'Project Name updated successfully!');
    }

    public function destroy(ProjectName $projectName)
    {
        $projectName->delete();
        return redirect()->route('project-names.index')->with('success', 'Project Name deleted successfully!');
    }
}
