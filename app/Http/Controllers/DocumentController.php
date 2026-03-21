<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Work;

class DocumentController extends Controller
{
    public function create($work_id)
    {
        $work = Work::findOrFail($work_id);
        $documents = Document::where('work_id', $work_id)->get();
        return view('documents.create', compact('work', 'documents'));
    }

    public function store(Request $request, $work_id)
    {
        try {
            $validatedData = $request->validate([
                'document_name' => 'required|string|max:255',
                'date_of_issue' => 'date',
                'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // Check if file is uploaded
            if (!$request->hasFile('image')) {
                return back()->withErrors(['error' => 'No file uploaded.']);
            }

            $uploadedFile = $request->file('image');
            if (!$uploadedFile->isValid()) {
                return back()->withErrors(['error' => 'Uploaded file is invalid.']);
            }

            // Upload image
            $imagePath = $uploadedFile->store('documents', 'public');

            // Store in DB
            $document = new Document();
            $document->work_id = $work_id;
            $document->document_name = $validatedData['document_name'];
            $document->date_of_issue = $validatedData['date_of_issue'];
            $document->image = $imagePath;


            $document->save();

            return redirect()->route('documents.create', $work_id)->with('success', 'Document added successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        $work_id = $document->work_id;
        $document->delete();

        return redirect()->route('documents.create', $work_id)->with('success', 'Document deleted successfully.');
    }
}
