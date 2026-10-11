@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 1200px;">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="h3 font-weight-bold text-dark mb-1">
                <i class="fas fa-building text-primary mr-2"></i>Project Names
            </h2>
            <p class="text-muted mb-0 small">These project names appear as autocomplete suggestions when creating or editing works, linked with banks & branches.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('project-names.create') }}" class="btn btn-primary px-4 py-2 font-weight-bold shadow-sm" style="border-radius: 8px;">
                <i class="fas fa-plus-circle mr-1"></i> Add New Project Name
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
            <form method="GET" action="{{ route('project-names.index') }}" class="row align-items-center">
                <div class="col-md-4 col-sm-12 mb-2 mb-md-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0" style="border-color: #ced4da;">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                        </div>
                        <input type="text" name="search" class="form-control border-left-0" style="border-color: #ced4da;"
                               placeholder="Search project or bank..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 col-sm-12 mb-2 mb-md-0">
                    <select name="type" class="form-control" style="border-color: #ced4da;" onchange="this.form.submit()">
                        <option value="">All Project Types</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-sm-12 mb-2 mb-md-0">
                    <select name="bank_name" class="form-control" style="border-color: #ced4da;" onchange="this.form.submit()">
                        <option value="">All Banks</option>
                        @if(isset($bankNames))
                            @foreach($bankNames as $bn)
                                <option value="{{ $bn->name }}" {{ request('bank_name') === $bn->name ? 'selected' : '' }}>
                                    {{ $bn->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-2 col-sm-12 d-flex">
                    <button type="submit" class="btn btn-primary btn-block mr-2 font-weight-bold">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    @if(request('search') || request('type') || request('bank_name') || request('bank_branch'))
                        <a href="{{ route('project-names.index') }}" class="btn btn-outline-secondary" title="Clear filter">
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
                Project Names Directory
            </span>
            <span class="badge badge-primary badge-pill px-3 py-1 font-weight-bold" style="font-size: 0.85rem;">
                Total: {{ $projectNames->total() }}
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th>Project Name</th>
                        <th style="min-width: 170px;">Bank / Branch</th>
                        <th class="text-center" style="width: 130px;">Project Type</th>
                        <th class="text-right" style="width: 130px;">Project Rate</th>
                        <th class="text-center" style="width: 140px;">Linked Works</th>
                        <th class="text-center" style="width: 140px;">Updated At</th>
                        <th class="text-center" style="width: 160px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projectNames as $index => $projectName)
                        <tr>
                            <td class="text-center align-middle font-weight-bold text-muted">
                                {{ $projectNames->firstItem() + $index }}
                            </td>
                            <td class="align-middle font-weight-bold text-dark" style="font-size: 1rem;">
                                {{ $projectName->name }}
                            </td>
                            <td class="align-middle">
                                @if($projectName->bank_name)
                                    <div class="font-weight-bold text-dark" style="font-size: 0.92rem;">
                                        <i class="fas fa-university text-primary mr-1" style="font-size: 0.85rem;"></i>{{ $projectName->bank_name }}
                                    </div>
                                @endif
                                <div class="{{ $projectName->bank_name ? 'small text-muted mt-1' : 'text-muted' }}">
                                    @if($projectName->bankBranch)
                                        <i class="fas fa-code-branch text-info mr-1" style="font-size: 0.75rem;"></i>{{ $projectName->bankBranch->name }}
                                    @else
                                        {{ $projectName->bank_name ? 'No branch' : '—' }}
                                    @endif
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                @if($projectName->project_type === 'Approved')
                                    <span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size: 0.82rem;">
                                        <i class="fas fa-check-circle mr-1"></i>Approved
                                    </span>
                                @elseif($projectName->project_type === 'Screen')
                                    <span class="badge badge-warning px-2 py-1 font-weight-bold text-dark" style="font-size: 0.82rem;">
                                        <i class="fas fa-desktop mr-1"></i>Screen
                                    </span>
                                @else
                                    <span class="badge badge-secondary px-2 py-1 font-weight-bold" style="font-size: 0.82rem;">
                                        {{ $projectName->project_type ?? 'Normal' }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-right align-middle font-weight-bold text-dark">
                                @if(!is_null($projectName->project_rate))
                                    ₹ {{ number_format($projectName->project_rate, 2) }}
                                @else
                                    <span class="text-muted font-weight-normal">—</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                @if($projectName->works_count > 0)
                                    <a href="{{ route('works.index', ['project_name' => $projectName->name]) }}" 
                                       class="badge badge-info px-2 py-1 font-weight-bold text-white text-decoration-none shadow-sm project-work-link" 
                                       style="font-size: 0.85rem; cursor: pointer; transition: all 0.2s;" 
                                       title="View {{ number_format($projectName->works_count) }} {{ Str::plural('work', $projectName->works_count) }} for {{ $projectName->name }}">
                                        <i class="fas fa-file-alt mr-1"></i>{{ number_format($projectName->works_count) }}
                                        <i class="fas fa-external-link-alt ml-1" style="font-size: 0.68rem; opacity: 0.85;"></i>
                                    </a>
                                @else
                                    <a href="{{ route('works.index', ['project_name' => $projectName->name]) }}" 
                                       class="text-muted font-weight-normal text-decoration-none" 
                                       title="0 works linked. Click to view works page">
                                        —
                                    </a>
                                @endif
                            </td>
                            <td class="text-center align-middle" title="{{ $projectName->updated_at ? \Carbon\Carbon::parse($projectName->updated_at)->diffForHumans() : '' }}">
                                @if($projectName->updated_at)
                                    <span class="text-dark font-weight-bold" style="font-size: 0.88rem;">
                                        {{ \Carbon\Carbon::parse($projectName->updated_at)->format('d/m/Y') }}
                                    </span>
                                    <br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($projectName->updated_at)->format('h:i A') }}</small>
                                @else
                                    <span class="text-muted font-weight-normal">—</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center align-items-center">
                                    <a href="{{ route('project-names.edit', $projectName->id) }}" 
                                       class="btn btn-sm btn-info text-white mr-2 shadow-sm font-weight-bold px-3 py-1"
                                       style="border-radius: 6px; background-color: #0284c7; border-color: #0284c7;">
                                       <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('project-names.destroy', $projectName->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete \'{{ addslashes($projectName->name) }}\'?')">
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
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 text-secondary d-block"></i>
                                @if(request('search') || request('type') || request('bank_name'))
                                    <p class="mb-2 font-weight-bold">No project names match your filter.</p>
                                    <a href="{{ route('project-names.index') }}" class="btn btn-sm btn-outline-primary">Clear Filter</a>
                                @else
                                    <p class="mb-2 font-weight-bold">No project names found.</p>
                                    <a href="{{ route('project-names.create') }}" class="btn btn-sm btn-primary">Add your first project name</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($projectNames->hasPages())
            <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center flex-wrap">
                <div class="text-muted small mb-2 mb-md-0">
                    Showing {{ $projectNames->firstItem() }} to {{ $projectNames->lastItem() }} of {{ $projectNames->total() }} entries
                </div>
                <div>
                    {{ $projectNames->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
