@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 1100px;">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="h3 font-weight-bold text-dark mb-1">
                <i class="fas fa-share-alt text-primary mr-2"></i>Sources
            </h2>
            <p class="text-muted mb-0 small">Manage source names and sourcers associated with works. Renaming updates all linked works automatically.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('sources.create') }}" class="btn btn-primary px-4 py-2 font-weight-bold shadow-sm" style="border-radius: 8px;">
                <i class="fas fa-plus-circle mr-1"></i> Add New Source
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
            <form method="GET" action="{{ route('sources.index') }}" class="row align-items-center">
                <div class="col-md-9 col-sm-12 mb-2 mb-md-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0" style="border-color: #ced4da;">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                        </div>
                        <input type="text" name="search" class="form-control border-left-0" style="border-color: #ced4da;"
                               placeholder="Search source name..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 col-sm-12 d-flex">
                    <button type="submit" class="btn btn-primary btn-block mr-2 font-weight-bold">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    @if(request('search'))
                        <a href="{{ route('sources.index') }}" class="btn btn-outline-secondary" title="Clear filter">
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
                Sources Directory
            </span>
            <span class="badge badge-primary badge-pill px-3 py-1 font-weight-bold" style="font-size: 0.85rem;">
                Total: {{ $sources->total() }}
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th>Source Name</th>
                        <th class="text-center" style="width: 160px;">Linked Works</th>
                        <th class="text-center" style="width: 170px;">Updated At</th>
                        <th class="text-center" style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sources as $index => $source)
                        <tr>
                            <td class="text-center align-middle font-weight-bold text-muted">
                                {{ $sources->firstItem() + $index }}
                            </td>
                            <td class="align-middle font-weight-bold text-dark" style="font-size: 1rem;">
                                <i class="fas fa-share-alt text-secondary mr-2" style="font-size: 0.9rem;"></i>{{ $source->name }}
                            </td>
                            <td class="text-center align-middle">
                                @if($source->works_count > 0)
                                    <a href="{{ route('works.index', ['source' => $source->name]) }}" 
                                       class="badge badge-info px-2 py-1 font-weight-bold text-white text-decoration-none shadow-sm source-work-link" 
                                       style="font-size: 0.85rem; cursor: pointer; transition: all 0.2s;" 
                                       title="View {{ number_format($source->works_count) }} {{ Str::plural('work', $source->works_count) }} for {{ $source->name }}">
                                        <i class="fas fa-file-alt mr-1"></i>{{ number_format($source->works_count) }}
                                        <i class="fas fa-external-link-alt ml-1" style="font-size: 0.68rem; opacity: 0.85;"></i>
                                    </a>
                                @else
                                    <a href="{{ route('works.index', ['source' => $source->name]) }}" 
                                       class="text-muted font-weight-normal text-decoration-none" 
                                       title="0 works linked. Click to view works page">
                                        —
                                    </a>
                                @endif
                            </td>
                            <td class="text-center align-middle" title="{{ $source->updated_at ? \Carbon\Carbon::parse($source->updated_at)->diffForHumans() : '' }}">
                                @if($source->updated_at)
                                    <span class="text-dark font-weight-bold" style="font-size: 0.88rem;">
                                        {{ \Carbon\Carbon::parse($source->updated_at)->format('d/m/Y') }}
                                    </span>
                                    <br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($source->updated_at)->format('h:i A') }}</small>
                                @else
                                    <span class="text-muted font-weight-normal">—</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center align-items-center">
                                    <a href="{{ route('sources.edit', $source->id) }}" 
                                       class="btn btn-sm btn-info text-white mr-2 shadow-sm font-weight-bold px-3 py-1"
                                       style="border-radius: 6px; background-color: #0284c7; border-color: #0284c7;">
                                       <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('sources.destroy', $source->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete \'{{ addslashes($source->name) }}\'?')">
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
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-share-alt fa-3x mb-3 text-secondary" style="opacity: 0.4;"></i>
                                    <h5 class="font-weight-bold text-dark">No sources found</h5>
                                    @if(request('search'))
                                        <p class="mb-3 small">No sources match "{{ request('search') }}"</p>
                                        <a href="{{ route('sources.index') }}" class="btn btn-sm btn-outline-primary">Clear Filter</a>
                                    @else
                                        <p class="mb-3 small">Start by creating your first source</p>
                                        <a href="{{ route('sources.create') }}" class="btn btn-sm btn-primary">Add your first source</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Footer -->
        @if($sources->hasPages())
            <div class="card-footer bg-white py-3 border-top d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    Showing <strong>{{ $sources->firstItem() }}</strong> to <strong>{{ $sources->lastItem() }}</strong> of <strong>{{ $sources->total() }}</strong> sources
                </div>
                <div>
                    {{ $sources->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.source-work-link:hover {
    background-color: #0284c7 !important;
    transform: scale(1.05);
    text-decoration: none !important;
}
</style>
@endsection
