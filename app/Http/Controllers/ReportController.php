<?php

namespace App\Http\Controllers;
use App\Models\Work;
use App\Models\Relative;
use App\Models\Inspection;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\Shared\Html;
use App\Models\Document;

    class ReportController extends Controller
    {
        public function select($workId)
        {
            return view("reports.select_report_type", compact("workId")); // Pass variable name as a string
        }
        public function select_edit($reportId)
        {
            return view("reports.select_report_type_edit", compact("reportId")); // Pass variable name as a string
        }

        public function create($workId)
        {
            $work = Work::with([
                'creator',
                'surveyor',
                'reporter',
                'checker',
                'deliveryPerson',
                'bankBranch',
                'relatives',
                'inspection',
                "documents",
            ])->where('id', $workId)->first();
            // Log::info('Work Data Retrieved:', ['work' => $work]);
            return view('reports.flat_create' , compact('work'));
        }
        
        public function store(Request $request)
        {
            try {
                // Validate request data
                $validatedData = $request->validate([
                    'work_id' => 'required|integer|exists:works,id',
                    'report_data' => 'required|string',
                ]);
                $report = new Report();
                $report->work_id = $validatedData['work_id'];
                $report->data = $validatedData['report_data'];
                $report->save();
                
                
        
                // Decode and validate JSON
                $reportData = json_decode($validatedData['report_data'], true);
                if (json_last_error() !== JSON_ERROR_NONE || empty($reportData)) {
                    return redirect()->back()->withErrors(['error' => 'Invalid JSON format.']);
                }
        
                // Define paths

                $templatePath = storage_path('app/public/word_Document/temp.docx');
                $outputPath = storage_path('app/public/word_Document/flat' . time() . '.docx');
            
                // Ensure template file exists
                if (!file_exists($templatePath)) {
                    return redirect()->back()->withErrors(['error' => 'Template file is missing.']);
                }
        
                // Load the Word template
                $templateProcessor = new TemplateProcessor($templatePath);
        
                // Replace placeholders with report data
                foreach ($reportData as $key => $value) {
                    $cleanKey = strtoupper($key);
                    $finalValue = $value ? $this->sanitizeForWord($value) : '--';
                    $templateProcessor->setValue($cleanKey, $finalValue);
                }
                
                
                


        
                // Fetch associated documents
                $documents = Document::where('work_id', $validatedData['work_id'])->get();

        
                if ($documents->isNotEmpty()) {
                    // Clone the block for each document
                    $templateProcessor->cloneBlock('DOCUMENTS_BLOCK', count($documents), true, true);
        
                    foreach ($documents as $index => $document) {
                        $documentName = $this->sanitizeForWord($document->document_name);
                        $imagePath = public_path('storage/' . $document->image);
        

        
                        // Set document name (use placeholder without #1, #2, etc.)
                        $templateProcessor->setValue("DOCUMENT_NAME#" . ($index + 1), $documentName);
        
                        // Set image if it exists (use placeholder without #1, #2, etc.)
                        if (file_exists($imagePath)) {
                            $templateProcessor->setImageValue("DOCUMENT_IMAGE#" . ($index + 1), [
                                'path' => $imagePath,
                                'width' => 650,
                                'height' => 700,
                                'ratio' => true,
                            ]);
                        } else {
                            Log::warning("Image Not Found:", ['path' => $imagePath]);
                            $templateProcessor->setValue("DOCUMENT_IMAGE#" . ($index + 1), 'Image not found');
                        }
                    }
                }
        
                $work = Work::findOrFail($validatedData['work_id']);
                if ($work->inspection && !empty($work->inspection->uploaded_images)) {
                    $uploadedImages = $work->inspection->uploaded_images;

                    // Add a section for inspection images
                    $templateProcessor->setValue('INSPECTION_IMAGES_TITLE', 'Inspection Uploaded Images');
                
                    // Group images into rows of 3
                    $imageRows = array_chunk($uploadedImages, 3);
                
                    // Clone the block for each row of images
                    $templateProcessor->cloneBlock('INSPECTION_IMAGES_BLOCK', count($imageRows), true, true);
                
                    // Add images to the document
                    foreach ($imageRows as $rowIndex => $imageRow) {
                        foreach ($imageRow as $colIndex => $image) {
                            $imagePath = public_path('storage/' . $image);
                            if (file_exists($imagePath)) {
                                // Dynamically set image placeholders
                                $templateProcessor->setImageValue("INSPECTION_IMAGE_COL{$colIndex}#" . ($rowIndex + 1), [
                                    'path' => $imagePath,
                                    'width' => 200,
                                    'height' => 200,
                                    'ratio' => true,
                                ]);
                            } else {
                                Log::warning("Inspection Image Not Found:", ['path' => $imagePath]);
                                $templateProcessor->setValue("INSPECTION_IMAGE_COL{$colIndex}#" . ($rowIndex + 1), 'Image not found');
                            }
                        }
                    }
                }
                $work = Work::findOrFail($validatedData['work_id']);
                $documents = $work->documents;

        
                // Add a table for documents
                $tableRows = [];
                $count=0;
                foreach ($documents as $index => $document) {
                    
                    if ($document->date_of_issue != '2000-01-01') {
                        
                        $row = [
                            'INDEX' => ($count + 1) . ')', // I), II), III) ...
                            'DOCUMENT_NAME' => 'XEROX COPY OF ' . $this->sanitizeForWord($document->document_name),
                            'DATE_OF_ISSUE' => 'Date of Issue: ' . $document->date_of_issue,
                        ];
                        $count=$count+1;
                        $tableRows[] = $row;
                    }
                }
        
                // Add the table to the document
                if (!empty($tableRows)) {
                    $templateProcessor->cloneRowAndSetValues('INDEX', $tableRows);
                }
        
        
                // Save the document
                $templateProcessor->saveAs($outputPath);
        
                // Update work status
                $work->update(['status' => 'Checking']);
        
                // Return the document for download
                return response()->download($outputPath)->deleteFileAfterSend(true);
        
            } catch (\Exception $e) {
                Log::error('Error generating report:', ['error' => $e->getMessage()]);
                return redirect()->back()->withErrors(['error' => 'An error occurred while generating the report.']);
            }
        }
        
        

        
        
        private function sanitizeForWord($value)
        {
            $value = trim($value);                                  // Remove leading/trailing whitespace
            $value = str_replace('_', ' ', $value);                  // Remove underscores
            $value = preg_replace('/[\r\n]+/', ' ', $value);        // Replace newlines with space
            $value = strtoupper($value);                            // Convert to uppercase
            return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');   // Encode for XML safety
        }


        
        /**
         * Add dynamic image placeholders to the template.
         */
        private function addDynamicImagePlaceholders($templateContent, $numRows)
        {
            $placeholders = '';
            for ($rowIndex = 0; $rowIndex < $numRows; $rowIndex++) {
                for ($colIndex = 0; $colIndex < 3; $colIndex++) {
                    $placeholders .= '${INSPECTION_IMAGE_ROW' . $rowIndex . '_COL' . $colIndex . '} ';
                }
                $placeholders .= "\n"; // Add a newline after each row
            }
        
            // Replace the static placeholders with dynamic ones
            $templateContent = str_replace(
                '${INSPECTION_IMAGE_ROW0_COL0} ${INSPECTION_IMAGE_ROW0_COL1} ${INSPECTION_IMAGE_ROW0_COL2}',
                $placeholders,
                $templateContent
            );
        
            return $templateContent;
        }

                
        public function edit($reportId)
        {
            $report = Report::findOrFail($reportId); // Fetch the report or fail if not found
            $work = Work::with([
                'creator',
                'surveyor',
                'reporter',
                'checker',
                'deliveryPerson',
                'bankBranch',
                'relatives',
                'inspection',
                "documents",
            ])->where('id', $report->work_id)->first();
            
            return view('reports.flat_edit', compact('report','work')); // Return edit view with report data
        }
        
        public function update(Request $request,$id)
        {
            try {
                $report = Report::findOrFail($id);
                // Validate request data
                $validatedData = $request->validate([
                    'work_id' => 'required|integer|exists:works,id',
                    'report_data' => 'required|string',
                ]);
                // dd($validatedData)
                $report->update([
                    'work_id' => $validatedData['work_id'],
                    'data' => $validatedData['report_data'],
                ]);
                
                
        
                // Decode and validate JSON
                $reportData = json_decode($validatedData['report_data'], true);
                if (json_last_error() !== JSON_ERROR_NONE || empty($reportData)) {
                    return redirect()->back()->withErrors(['error' => 'Invalid JSON format.']);
                }
        
                // Define paths
                $templatePath = storage_path('app/public/word_Document/temp.docx');
                $outputPath = storage_path('app/public/word_Document/report_edited' . time() . '.docx');
        
                // Ensure template file exists
                if (!file_exists($templatePath)) {
                    return redirect()->back()->withErrors(['error' => 'Template file is missing.']);
                }
        
                // Load the Word template
                $templateProcessor = new TemplateProcessor($templatePath);
        
                // Replace placeholders with report data
                foreach ($reportData as $key => $value) {
                    $cleanKey = strtoupper($key);
                    $finalValue = $value ? $this->sanitizeForWord($value) : '--';
                    $templateProcessor->setValue($cleanKey, $finalValue);
                }
        
                // Fetch associated documents
                $documents = Document::where('work_id', $validatedData['work_id'])->get();
                Log::info('Retrieved Documents:', ['count' => $documents->count(), 'documents' => $documents->toArray()]);
        
                if ($documents->isNotEmpty()) {
                    // Clone the block for each document
                    $templateProcessor->cloneBlock('DOCUMENTS_BLOCK', count($documents), true, true);
        
                    foreach ($documents as $index => $document) {
                        $documentName = $this->sanitizeForWord($document->document_name);
                        $imagePath = public_path('storage/' . $document->image);
        
                        Log::info("Processing Document {$index}", ['name' => $documentName, 'image' => $document->image]);
                        Log::info("Image Path:", ['path' => $imagePath]);
        
                        // Set document name (use placeholder without #1, #2, etc.)
                        $templateProcessor->setValue("DOCUMENT_NAME#" . ($index + 1), $documentName);
        
                        // Set image if it exists (use placeholder without #1, #2, etc.)
                        if (file_exists($imagePath)) {
                            Log::info("Image Exists:", ['path' => $imagePath]);
                            $templateProcessor->setImageValue("DOCUMENT_IMAGE#" . ($index + 1), [
                                'path' => $imagePath,
                                'width' => 300,
                                'height' => 200,
                                'ratio' => true,
                            ]);
                        } else {
                            Log::warning("Image Not Found:", ['path' => $imagePath]);
                            $templateProcessor->setValue("DOCUMENT_IMAGE#" . ($index + 1), 'Image not found');
                        }
                    }
                }
        
                $work = Work::findOrFail($validatedData['work_id']);
                if ($work->inspection && !empty($work->inspection->uploaded_images)) {
                    $uploadedImages = $work->inspection->uploaded_images;
                    Log::info('Inspection Uploaded Images:', ['images' => $uploadedImages]);
                
                    // Add a section for inspection images
                    $templateProcessor->setValue('INSPECTION_IMAGES_TITLE', 'Inspection Uploaded Images');
                
                    // Group images into rows of 3
                    $imageRows = array_chunk($uploadedImages, 3);
                
                    // Clone the block for each row of images
                    $templateProcessor->cloneBlock('INSPECTION_IMAGES_BLOCK', count($imageRows), true, true);
                
                    // Add images to the document
                    foreach ($imageRows as $rowIndex => $imageRow) {
                        foreach ($imageRow as $colIndex => $image) {
                            $imagePath = public_path('storage/' . $image);
                            if (file_exists($imagePath)) {
                                // Dynamically set image placeholders
                                $templateProcessor->setImageValue("INSPECTION_IMAGE_COL{$colIndex}#" . ($rowIndex + 1), [
                                    'path' => $imagePath,
                                    'width' => 200,
                                    'height' => 200,
                                    'ratio' => true,
                                ]);
                            } else {
                                Log::warning("Inspection Image Not Found:", ['path' => $imagePath]);
                                $templateProcessor->setValue("INSPECTION_IMAGE_COL{$colIndex}#" . ($rowIndex + 1), 'Image not found');
                            }
                        }
                    }
                }
                $work = Work::findOrFail($validatedData['work_id']);
                $documents = $work->documents;
                Log::info('Retrieved Documents:', ['count' => $documents->count(), 'documents' => $documents->toArray()]);
        
                // Add a table for documents
                $tableRows = [];
                $count=0;
                foreach ($documents as $index => $document) {
                    
                    if ($document->date_of_issue != '2000-01-01') {
                        
                        $row = [
                            'INDEX' => ($count + 1) . ')', // I), II), III) ...
                            'DOCUMENT_NAME' => 'XEROX COPY OF ' . $this->sanitizeForWord($document->document_name),
                            'DATE_OF_ISSUE' => 'Date of Issue: ' . $document->date_of_issue,
                        ];
                        $count=$count+1;
                        $tableRows[] = $row;
                    }
                }
        
                // Add the table to the document
                if (!empty($tableRows)) {
                    $templateProcessor->cloneRowAndSetValues('INDEX', $tableRows);
                }
        
        
                // Save the document
                $templateProcessor->saveAs($outputPath);
        
                // Update work status
                $work->update(['status' => 'Checking']);
        
                // Return the document for download
                return response()->download($outputPath)->deleteFileAfterSend(true);
        
            } catch (\Exception $e) {
                Log::error('Error generating report:', ['error' => $e->getMessage()]);
                return redirect()->back()->withErrors(['error' => 'An error occurred while generating the report.']);
            }
        }
        
          
        
        public function lnb_create($workId)
        {
            $work = Work::with([
                'creator',
                'surveyor',
                'reporter',
                'checker',
                'deliveryPerson',
                'bankBranch',
                'relatives',
                'inspection',
                "documents",
            ])->where('id', $workId)->first();
            // Log::info('Work Data Retrieved:', ['work' => $work]);
            return view('reports.lnb_create' , compact('work'));
        }
        
              
        public function lnb_store(Request $request)
        {
            try {
                // Validate request data
                $validatedData = $request->validate([
                    'work_id' => 'required|integer|exists:works,id',
                    'report_data' => 'required|string',
                ]);
                $report = new Report();
                $report->work_id = $validatedData['work_id'];
                $report->data = $validatedData['report_data'];
                $report->save();
                
                
        
                // Decode and validate JSON
                $reportData = json_decode($validatedData['report_data'], true);
                if (json_last_error() !== JSON_ERROR_NONE || empty($reportData)) {
                    return redirect()->back()->withErrors(['error' => 'Invalid JSON format.']);
                }
        
                // Define paths
                $templatePath = storage_path('app/public/word_Document/lnb_temp.docx');
                $outputPath = storage_path('app/public/word_Document/lnb_report_' . time() . '.docx');
        
                // Ensure template file exists
                if (!file_exists($templatePath)) {
                    return redirect()->back()->withErrors(['error' => 'Template file is missing.']);
                }
        
                // Load the Word template
                $templateProcessor = new TemplateProcessor($templatePath);
        
                // Replace placeholders with report data
                foreach ($reportData as $key => $value) {
                    $cleanKey = strtoupper($key);
                    $finalValue = $value ? $this->sanitizeForWord($value) : '--';
                    $templateProcessor->setValue($cleanKey, $finalValue);
                }
        
                // Fetch associated documents
                $documents = Document::where('work_id', $validatedData['work_id'])->get();
                Log::info('Retrieved Documents:', ['count' => $documents->count(), 'documents' => $documents->toArray()]);
        
                if ($documents->isNotEmpty()) {
                    // Clone the block for each document
                    $templateProcessor->cloneBlock('DOCUMENTS_BLOCK', count($documents), true, true);
        
                    foreach ($documents as $index => $document) {
                        $documentName = $this->sanitizeForWord($document->document_name);
                        $imagePath = public_path('storage/' . $document->image);
        
                        Log::info("Processing Document {$index}", ['name' => $documentName, 'image' => $document->image]);
                        Log::info("Image Path:", ['path' => $imagePath]);
        
                        // Set document name (use placeholder without #1, #2, etc.)
                        $templateProcessor->setValue("DOCUMENT_NAME#" . ($index + 1), $documentName);
        
                        // Set image if it exists (use placeholder without #1, #2, etc.)
                        if (file_exists($imagePath)) {
                            Log::info("Image Exists:", ['path' => $imagePath]);
                            $templateProcessor->setImageValue("DOCUMENT_IMAGE#" . ($index + 1), [
                                'path' => $imagePath,
                                'width' => 300,
                                'height' => 200,
                                'ratio' => true,
                            ]);
                        } else {
                            Log::warning("Image Not Found:", ['path' => $imagePath]);
                            $templateProcessor->setValue("DOCUMENT_IMAGE#" . ($index + 1), 'Image not found');
                        }
                    }
                }
        
                $work = Work::findOrFail($validatedData['work_id']);
                if ($work->inspection && !empty($work->inspection->uploaded_images)) {
                    $uploadedImages = $work->inspection->uploaded_images;
                    Log::info('Inspection Uploaded Images:', ['images' => $uploadedImages]);
                
                    // Add a section for inspection images
                    $templateProcessor->setValue('INSPECTION_IMAGES_TITLE', 'Inspection Uploaded Images');
                
                    // Group images into rows of 3
                    $imageRows = array_chunk($uploadedImages, 3);
                
                    // Clone the block for each row of images
                    $templateProcessor->cloneBlock('INSPECTION_IMAGES_BLOCK', count($imageRows), true, true);
                
                    // Add images to the document
                    foreach ($imageRows as $rowIndex => $imageRow) {
                        foreach ($imageRow as $colIndex => $image) {
                            $imagePath = public_path('storage/' . $image);
                            if (file_exists($imagePath)) {
                                // Dynamically set image placeholders
                                $templateProcessor->setImageValue("INSPECTION_IMAGE_COL{$colIndex}#" . ($rowIndex + 1), [
                                    'path' => $imagePath,
                                    'width' => 200,
                                    'height' => 200,
                                    'ratio' => true,
                                ]);
                            } else {
                                Log::warning("Inspection Image Not Found:", ['path' => $imagePath]);
                                $templateProcessor->setValue("INSPECTION_IMAGE_COL{$colIndex}#" . ($rowIndex + 1), 'Image not found');
                            }
                        }
                    }
                }
                $work = Work::findOrFail($validatedData['work_id']);
                $documents = $work->documents;
                Log::info('Retrieved Documents:', ['count' => $documents->count(), 'documents' => $documents->toArray()]);
        
                // Add a table for documents
                $tableRows = [];
                $count=0;
                foreach ($documents as $index => $document) {
                    
                    if ($document->date_of_issue != '2000-01-01') {
                        
                        $row = [
                            'INDEX' => ($count + 1) . ')', // I), II), III) ...
                            'DOCUMENT_NAME' => 'XEROX COPY OF ' . $this->sanitizeForWord($document->document_name),
                            'DATE_OF_ISSUE' => 'Date of Issue: ' . $document->date_of_issue,
                        ];
                        $count=$count+1;
                        $tableRows[] = $row;
                    }
                }
        
                // Add the table to the document
                if (!empty($tableRows)) {
                    $templateProcessor->cloneRowAndSetValues('INDEX', $tableRows);
                }
        
        
                // Save the document
                $templateProcessor->saveAs($outputPath);
        
                // Update work status
                $work->update(['status' => 'Checking']);
        
                // Return the document for download
                return response()->download($outputPath)->deleteFileAfterSend(true);
        
            } catch (\Exception $e) {
                Log::error('Error generating report:', ['error' => $e->getMessage()]);
                return redirect()->back()->withErrors(['error' => 'An error occurred while generating the report.']);
            }
        }
        
        public function lnb_edit($reportId)
        {
            $report = Report::findOrFail($reportId); // Fetch the report or fail if not found
            $work = Work::with([
                'creator',
                'surveyor',
                'reporter',
                'checker',
                'deliveryPerson',
                'bankBranch',
                'relatives',
                'inspection',
                "documents",
            ])->where('id', $report->work_id)->first(); 
            
            return view('reports.lnb_edit', compact('report','work')); // Return edit view with report data
        }
        
        public function lnb_update(Request $request,$id)
        {
            try {
                $report = Report::findOrFail($id);
                // Validate request data
                $validatedData = $request->validate([
                    'work_id' => 'required|integer|exists:works,id',
                    'report_data' => 'required|string',
                ]);
                $report = new Report();
                $report->work_id = $validatedData['work_id'];
                $report->data = $validatedData['report_data'];
                $report->save();
                
                
        
                // Decode and validate JSON
                $reportData = json_decode($validatedData['report_data'], true);
                if (json_last_error() !== JSON_ERROR_NONE || empty($reportData)) {
                    return redirect()->back()->withErrors(['error' => 'Invalid JSON format.']);
                }
        
                // Define paths
                $templatePath = storage_path('app/public/word_Document/lnb_temp.docx');
                $outputPath = storage_path('app/public/word_Document/lnb_report_' . time() . '.docx');
        
                // Ensure template file exists
                if (!file_exists($templatePath)) {
                    return redirect()->back()->withErrors(['error' => 'Template file is missing.']);
                }
        
                // Load the Word template
                $templateProcessor = new TemplateProcessor($templatePath);
        
                // Replace placeholders with report data
                foreach ($reportData as $key => $value) {
                    $cleanKey = strtoupper($key);
                    $finalValue = $value ? $this->sanitizeForWord($value) : '--';
                    $templateProcessor->setValue($cleanKey, $finalValue);
                }
        
                // Fetch associated documents
                $documents = Document::where('work_id', $validatedData['work_id'])->get();
                Log::info('Retrieved Documents:', ['count' => $documents->count(), 'documents' => $documents->toArray()]);
        
                if ($documents->isNotEmpty()) {
                    // Clone the block for each document
                    $templateProcessor->cloneBlock('DOCUMENTS_BLOCK', count($documents), true, true);
        
                    foreach ($documents as $index => $document) {
                        $documentName = $this->sanitizeForWord($document->document_name);
                        $imagePath = public_path('storage/' . $document->image);
        
                        Log::info("Processing Document {$index}", ['name' => $documentName, 'image' => $document->image]);
                        Log::info("Image Path:", ['path' => $imagePath]);
        
                        // Set document name (use placeholder without #1, #2, etc.)
                        $templateProcessor->setValue("DOCUMENT_NAME#" . ($index + 1), $documentName);
        
                        // Set image if it exists (use placeholder without #1, #2, etc.)
                        if (file_exists($imagePath)) {
                            Log::info("Image Exists:", ['path' => $imagePath]);
                            $templateProcessor->setImageValue("DOCUMENT_IMAGE#" . ($index + 1), [
                                'path' => $imagePath,
                                'width' => 300,
                                'height' => 200,
                                'ratio' => true,
                            ]);
                        } else {
                            Log::warning("Image Not Found:", ['path' => $imagePath]);
                            $templateProcessor->setValue("DOCUMENT_IMAGE#" . ($index + 1), 'Image not found');
                        }
                    }
                }
        
                $work = Work::findOrFail($validatedData['work_id']);
                if ($work->inspection && !empty($work->inspection->uploaded_images)) {
                    $uploadedImages = $work->inspection->uploaded_images;
                    Log::info('Inspection Uploaded Images:', ['images' => $uploadedImages]);
                
                    // Add a section for inspection images
                    $templateProcessor->setValue('INSPECTION_IMAGES_TITLE', 'Inspection Uploaded Images');
                
                    // Group images into rows of 3
                    $imageRows = array_chunk($uploadedImages, 3);
                
                    // Clone the block for each row of images
                    $templateProcessor->cloneBlock('INSPECTION_IMAGES_BLOCK', count($imageRows), true, true);
                
                    // Add images to the document
                    foreach ($imageRows as $rowIndex => $imageRow) {
                        foreach ($imageRow as $colIndex => $image) {
                            $imagePath = public_path('storage/' . $image);
                            if (file_exists($imagePath)) {
                                // Dynamically set image placeholders
                                $templateProcessor->setImageValue("INSPECTION_IMAGE_COL{$colIndex}#" . ($rowIndex + 1), [
                                    'path' => $imagePath,
                                    'width' => 200,
                                    'height' => 200,
                                    'ratio' => true,
                                ]);
                            } else {
                                Log::warning("Inspection Image Not Found:", ['path' => $imagePath]);
                                $templateProcessor->setValue("INSPECTION_IMAGE_COL{$colIndex}#" . ($rowIndex + 1), 'Image not found');
                            }
                        }
                    }
                }
                $work = Work::findOrFail($validatedData['work_id']);
                $documents = $work->documents;
                Log::info('Retrieved Documents:', ['count' => $documents->count(), 'documents' => $documents->toArray()]);
        
                // Add a table for documents
                $tableRows = [];
                $count=0;
                foreach ($documents as $index => $document) {
                    
                    if ($document->date_of_issue != '2000-01-01') {
                        
                        $row = [
                            'INDEX' => ($count + 1) . ')', // I), II), III) ...
                            'DOCUMENT_NAME' => 'XEROX COPY OF ' . $this->sanitizeForWord($document->document_name),
                            'DATE_OF_ISSUE' => 'Date of Issue: ' . $document->date_of_issue,
                        ];
                        $count=$count+1;
                        $tableRows[] = $row;
                    }
                }
        
                // Add the table to the document
                if (!empty($tableRows)) {
                    $templateProcessor->cloneRowAndSetValues('INDEX', $tableRows);
                }
        
        
                // Save the document
                $templateProcessor->saveAs($outputPath);
        
                // Update work status
                $work->update(['status' => 'Checking']);
        
                // Return the document for download
                return response()->download($outputPath)->deleteFileAfterSend(true);
        
            } catch (\Exception $e) {
                Log::error('Error generating report:', ['error' => $e->getMessage()]);
                return redirect()->back()->withErrors(['error' => 'An error occurred while generating the report.']);
            }
        }
}
