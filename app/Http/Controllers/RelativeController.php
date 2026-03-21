<?php

namespace App\Http\Controllers;

use App\Models\Relative;
use App\Models\Work;
use Illuminate\Http\Request;

class RelativeController extends Controller
{
    // Show create form with all relatives related to a specific work
    public function create($workId)
    {
        $work = Work::findOrFail($workId);
        $relatives = Relative::where('work_id', $workId)->get();
        
        return view('relatives.create', compact('work', 'relatives'));
    }

    // Store a new relative
    public function store(Request $request, $workId)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'relation' => 'required|string|max:255',
            'relative_name' => 'required|string|max:255',
            'pan_number' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
        ]);

        $validatedData['work_id'] = $workId;
        Relative::create($validatedData);

        return redirect()->route('relatives.create', $workId)->with('success', 'Relative added successfully.');
    }

    // Delete a relative
    public function destroy($id)
    {
        $relative = Relative::findOrFail($id);
        $workId = $relative->work_id;
        $relative->delete();

        return redirect()->route('relatives.create', $workId)->with('success', 'Relative deleted successfully.');
    }
}
