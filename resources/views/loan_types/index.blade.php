@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 1000px;">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="h3 font-weight-bold text-dark mb-1">
                <i class="fas fa-money-check-alt text-primary mr-2"></i>Loan Types
            </h2>
            <p class="text-muted mb-0 small">Manage loan types available in create and edit works forms.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('loan-types.create') }}" class="btn btn-primary px-4 py-2 font-weight-bold shadow-sm" style="border-radius: 8px;">
                <i class="fas fa-plus-circle mr-1"></i> Add New Loan Type
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
            <form method="GET" action="{{ route('loan-types.index') }}" class="row align-items-center">
                <div class="col-md-9 col-sm-12 mb-2 mb-md-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0" style="border-color: #ced4da;">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                        </div>
                        <input type="text" name="search" class="form-control border-left-0" style="border-color: #ced4da;"
                               placeholder="Search loan type by name..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 col-sm-12 d-flex">
                    <button type="submit" class="btn btn-primary btn-block mr-2 font-weight-bold">
                        <i class="fas fa-search mr-1"></i> Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('loan-types.index') }}" class="btn btn-outline-secondary" title="Clear search">
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
                Loan Types List
            </span>
            <span class="badge badge-primary badge-pill px-3 py-1 font-weight-bold" style="font-size: 0.85rem;">
                Total: {{ $loanTypes->total() }}
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" style="width: 70px;">#</th>
                        <th>Loan Type Name</th>
                        <th class="text-center" style="width: 220px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loanTypes as $index => $loanType)
                        <tr>
                            <td class="text-center align-middle font-weight-bold text-muted">
                                {{ $loanTypes->firstItem() + $index }}
                            </td>
                            <td class="align-middle font-weight-bold text-dark" style="font-size: 1rem;">
                                {{ $loanType->name }}
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center align-items-center">
                                    <a href="{{ route('loan-types.edit', $loanType->id) }}" 
                                       class="btn btn-sm btn-info text-white mr-2 shadow-sm font-weight-bold px-3 py-1"
                                       style="border-radius: 6px; background-color: #0284c7; border-color: #0284c7;">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('loan-types.destroy', $loanType->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete the loan type \'{{ addslashes($loanType->name) }}\'?')">
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
                            <td colspan="3" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 text-secondary d-block"></i>
                                @if(request('search'))
                                    <p class="mb-2 font-weight-bold">No loan types match "{{ request('search') }}"</p>
                                    <a href="{{ route('loan-types.index') }}" class="btn btn-sm btn-outline-primary">Clear Filter</a>
                                @else
                                    <p class="mb-2 font-weight-bold">No loan types found.</p>
                                    <a href="{{ route('loan-types.create') }}" class="btn btn-sm btn-primary">Add your first loan type</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($loanTypes->hasPages())
            <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center flex-wrap">
                <div class="text-muted small mb-2 mb-md-0">
                    Showing {{ $loanTypes->firstItem() }} to {{ $loanTypes->lastItem() }} of {{ $loanTypes->total() }} entries
                </div>
                <div>
                    {{ $loanTypes->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
