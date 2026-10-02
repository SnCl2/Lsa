@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 1100px;">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="h3 font-weight-bold text-dark mb-1">
                <i class="fas fa-university text-primary mr-2"></i>Bank Names
            </h2>
            <p class="text-muted mb-0 small">These bank names appear as autocomplete suggestions when creating or editing works.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('bank-names.create') }}" class="btn btn-primary px-4 py-2 font-weight-bold shadow-sm" style="border-radius: 8px;">
                <i class="fas fa-plus-circle mr-1"></i> Add New Bank Name
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-check-circle mr-2"></i><strong>Success:</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            @foreach($errors->all() as $error)
                <span>{{ $error }}</span>
            @endforeach
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Search Box Card -->
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('bank-names.index') }}" class="row align-items-center">
                <div class="col-md-9 col-sm-12 mb-2 mb-md-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0" style="border-color: #ced4da;">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                        </div>
                        <input type="text" name="search" class="form-control border-left-0" style="border-color: #ced4da;"
                               placeholder="Search bank name..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 col-sm-12 d-flex">
                    <button type="submit" class="btn btn-primary btn-block mr-2 font-weight-bold">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    @if(request('search'))
                        <a href="{{ route('bank-names.index') }}" class="btn btn-outline-secondary" title="Clear filter">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="font-weight-bold text-secondary text-uppercase small">
                Bank Names Directory
            </span>
            <span class="badge badge-primary badge-pill px-3 py-1 font-weight-bold" style="font-size: 0.85rem;">
                Total: {{ $bankNames->total() }}
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th>Bank Name</th>
                        <th class="text-center" style="width: 160px;">Linked Works</th>
                        <th class="text-center" style="width: 170px;">Updated At</th>
                        <th class="text-center" style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bankNames as $index => $bankName)
                        <tr>
                            <td class="text-center align-middle font-weight-bold text-muted">
                                {{ $bankNames->firstItem() + $index }}
                            </td>
                            <td class="align-middle font-weight-bold text-dark" style="font-size: 1rem;">
                                <i class="fas fa-university text-secondary mr-2" style="font-size: 0.9rem;"></i>{{ $bankName->name }}
                            </td>
                            <td class="text-center align-middle">
                                @if($bankName->works_count > 0)
                                    <span class="badge badge-info px-2 py-1 font-weight-bold" style="font-size: 0.82rem;">
                                        <i class="fas fa-file-alt mr-1"></i>{{ number_format($bankName->works_count) }}
                                    </span>
                                @else
                                    <span class="text-muted font-weight-normal">—</span>
                                @endif
                            </td>
                            <td class="text-center align-middle" title="{{ $bankName->updated_at ? \Carbon\Carbon::parse($bankName->updated_at)->diffForHumans() : '' }}">
                                @if($bankName->updated_at)
                                    <span class="text-dark font-weight-bold" style="font-size: 0.88rem;">
                                        {{ \Carbon\Carbon::parse($bankName->updated_at)->format('d/m/Y') }}
                                    </span>
                                    <br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($bankName->updated_at)->format('h:i A') }}</small>
                                @else
                                    <span class="text-muted font-weight-normal">—</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center align-items-center">
                                    <a href="{{ route('bank-names.edit', $bankName->id) }}" 
                                       class="btn btn-sm btn-info text-white mr-2 shadow-sm font-weight-bold px-3 py-1"
                                       style="border-radius: 6px; background-color: #0284c7; border-color: #0284c7;">
                                       <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('bank-names.destroy', $bankName->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete \'{{ addslashes($bankName->name) }}\'?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger shadow-sm font-weight-bold px-3 py-1"
                                                style="border-radius: 6px; background-color: #dc2626; border-color: #dc2626;">
                                            <i class="fas fa-trash-alt mr-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-university fa-3x mb-3 text-secondary d-block"></i>
                                @if(request('search'))
                                    <p class="mb-2 font-weight-bold">No bank names match your search.</p>
                                    <a href="{{ route('bank-names.index') }}" class="btn btn-sm btn-outline-primary">Clear Filter</a>
                                @else
                                    <p class="mb-2 font-weight-bold">No bank names found.</p>
                                    <a href="{{ route('bank-names.create') }}" class="btn btn-sm btn-primary">Add your first bank name</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bankNames->hasPages())
            <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center flex-wrap">
                <div class="text-muted small mb-2 mb-md-0">
                    Showing {{ $bankNames->firstItem() }} to {{ $bankNames->lastItem() }} of {{ $bankNames->total() }} entries
                </div>
                <div>
                    {{ $bankNames->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
