<?php

namespace App\Http\Controllers;

use App\Models\Source;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SourceController extends Controller
{
    public function index(Request $request)
    {
        $query = Source::query()->withCount('works');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('name', 'like', "%{$search}%");
        }

        $sources = $query->orderBy('name')->paginate(25)->withQueryString();

        return view('sources.index', compact('sources'));
    }

    public function create()
    {
        return view('sources.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sources,name',
        ]);

        Source::create([
            'name' => trim($request->name),
        ]);

        return redirect()->route('sources.index')->with('success', 'Source created successfully!');
    }

    public function edit(Source $source)
    {
        return view('sources.edit', compact('source'));
    }

    public function update(Request $request, Source $source)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sources,name,' . $source->id,
        ]);

        $oldName = $source->name;
        $newName = trim($request->name);

        $source->update([
            'name' => $newName,
        ]);

        // Keep historical works synchronized when a source name is updated
        $updatedCount = 0;
        if ($oldName !== $newName) {
            $updatedCount = Work::where('source', $oldName)->update(['source' => $newName]);
        }

        $successMessage = 'Source updated successfully!';
        if ($updatedCount > 0) {
            $successMessage .= " ({$updatedCount} linked " . Str::plural('work', $updatedCount) . " updated).";
        }

        return redirect()->route('sources.index')->with('success', $successMessage);
    }

    public function destroy(Source $source)
    {
        $name = $source->name;
        $workCount = $source->works()->count();
        $source->delete();

        $message = "Source '{$name}' deleted successfully!";
        if ($workCount > 0) {
            $message .= " ({$workCount} historical works retain this source in records).";
        }

        return redirect()->route('sources.index')->with('success', $message);
    }
}
