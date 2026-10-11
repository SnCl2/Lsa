@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 650px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 font-weight-bold text-dark mb-1">
                <i class="fas fa-plus-circle text-primary mr-2"></i>Create Source
            </h2>
            <p class="text-muted small mb-0">Add a source name to the directory for autocomplete and work association.</p>
        </div>
        <a href="{{ route('sources.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Back to List
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <ul class="mb-0 pl-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-body p-4">
            <form action="{{ route('sources.store') }}" method="POST">
                @csrf

                <div class="form-group mb-4">
                    <label class="font-weight-bold text-dark" for="name">Source Name <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" 
                           placeholder="e.g. NARAYAN BANERJEE, COD, HLST, GOPAL MONDAL..." 
                           class="form-control form-control-lg" 
                           style="border-color: #ced4da;"
                           required autofocus>
                    <small class="form-text text-muted">This name will be suggested when users type in the Source field during work entry.</small>
                </div>

                <div class="d-flex align-items-center pt-2">
                    <button type="submit" class="btn btn-primary px-4 py-2 font-weight-bold mr-2 shadow-sm" style="border-radius: 8px;">
                        <i class="fas fa-save mr-1"></i> Save Source
                    </button>
                    <a href="{{ route('sources.index') }}" class="btn btn-outline-secondary px-4 py-2" style="border-radius: 8px;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
