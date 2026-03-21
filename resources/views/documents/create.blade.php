@extends('layouts.app')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="container">
    <h2>Upload Document</h2>

    <!-- Document Upload Form -->
    <form action="{{ route('documents.store', $work->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="work_id" value="{{ $work->id }}">

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="document_name" class="form-label">Document Name</label>
                <input type="text" name="document_name" id="document_name" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label for="date_of_issue" class="form-label">Date of Issue</label>
                <input type="date" name="date_of_issue" id="date_of_issue" class="form-control" 
       value="{{ old('date_of_issue', '2000-01-01') }}">
            </div>

            <div class="col-md-4">
                <label for="image" class="form-label">Upload Document Image</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
            </div>
        </div>


        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    <hr>

    <!-- List of Uploaded Documents -->
    <h3>Uploaded Documents</h3>
    @if ($documents->isEmpty())
        <p>No documents uploaded yet.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Document Name</th>
                    <th>Date of Issue</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($documents as $document)
                <tr>
                    <td>{{ $document->document_name }}</td>
                    <td>{{ $document->date_of_issue }}</td>
                    <td>
                        <img src="https://valuerkkda.com/public/storage/{{( $document->image)}}" alt="Document Image" width="100">

                    </td>
                    <td>
                        <form action="{{ route('documents.destroy', $document->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
