@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="max-width: 1400px;">
    <!-- Welcome Header -->
    <div class="mb-5">
        <h2 class="font-weight-bolder text-dark mb-1" style="letter-spacing: -0.5px;">
            Welcome back, {{ auth()->user()->name }} 👋
        </h2>
        <p class="text-muted mb-0">Select a module below to manage your tasks and workflows.</p>
    </div>

    <!-- Quick Access Grid -->
    <div class="row">
        
        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin'))
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <a href="{{ route('works.dashboard') }}" class="text-decoration-none module-link">
                <div class="premium-card h-100">
                    <div class="card-icon bg-primary-soft text-primary">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h5 class="card-title font-weight-bolder text-dark mb-2">Work Dashboard</h5>
                    <p class="card-text text-muted small mb-0">All KPIs, graphs, and charts overview.</p>
                </div>
            </a>
        </div>
        @endif

        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin') || auth()->user()->roles->contains('name', 'In-Charge'))
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <a href="{{ route('works.create') }}" class="text-decoration-none module-link">
                <div class="premium-card h-100 border-primary-left">
                    <div class="card-icon bg-success-soft text-success">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <h5 class="card-title font-weight-bolder text-dark mb-2">Create Work</h5>
                    <p class="card-text text-muted small mb-0">Start a new evaluation or task entry.</p>
                </div>
            </a>
        </div>
        
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <a href="{{ route('works.myWorks') }}" class="text-decoration-none module-link">
                <div class="premium-card h-100">
                    <div class="card-icon bg-info-soft text-info">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h5 class="card-title font-weight-bolder text-dark mb-2">My Works</h5>
                    <p class="card-text text-muted small mb-0">View and manage tasks assigned to you.</p>
                </div>
            </a>
        </div>
        @endif

        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin'))
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <a href="{{ route('works.index') }}" class="text-decoration-none module-link">
                <div class="premium-card h-100">
                    <div class="card-icon bg-secondary-soft text-secondary">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <h5 class="card-title font-weight-bolder text-dark mb-2">All Works</h5>
                    <p class="card-text text-muted small mb-0">Complete list of all works in the system.</p>
                </div>
            </a>
        </div>
        @endif

        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin') || auth()->user()->roles->contains('name', 'Reporter'))
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <a href="{{ route('works.reporter') }}" class="text-decoration-none module-link">
                <div class="premium-card h-100">
                    <div class="card-icon bg-indigo-soft text-indigo">
                        <i class="fas fa-pen-nib"></i>
                    </div>
                    <h5 class="card-title font-weight-bolder text-dark mb-2">Reporter Works</h5>
                    <p class="card-text text-muted small mb-0">Works assigned for reporting.</p>
                </div>
            </a>
        </div>
        @endif

        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin') || auth()->user()->roles->contains('name', 'Surveyor'))
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <a href="{{ route('works.surveyor') }}" class="text-decoration-none module-link">
                <div class="premium-card h-100">
                    <div class="card-icon bg-teal-soft text-teal">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h5 class="card-title font-weight-bolder text-dark mb-2">Surveyor Works</h5>
                    <p class="card-text text-muted small mb-0">Works assigned for on-site surveying.</p>
                </div>
            </a>
        </div>
        @endif

        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin') || auth()->user()->roles->contains('name', 'Checker'))
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <a href="{{ route('works.checking') }}" class="text-decoration-none module-link">
                <div class="premium-card h-100 border-warning-left">
                    <div class="card-icon bg-warning-soft text-warning">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <h5 class="card-title font-weight-bolder text-dark mb-2">Works for Checking</h5>
                    <p class="card-text text-muted small mb-0">Review and verify completed tasks.</p>
                </div>
            </a>
        </div>
        @endif
        
        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin') || auth()->user()->roles->contains('name', 'Delivery Person'))
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <a href="{{ route('works.delivery') }}" class="text-decoration-none module-link">
                <div class="premium-card h-100">
                    <div class="card-icon bg-purple-soft text-purple">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h5 class="card-title font-weight-bolder text-dark mb-2">Works for Delivery</h5>
                    <p class="card-text text-muted small mb-0">Manage final deliveries.</p>
                </div>
            </a>
        </div>
        @endif
        
        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin') || auth()->user()->roles->contains('name', 'Bank Branch'))
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <a href="{{ route('works.bankBranch') }}" class="text-decoration-none module-link">
                <div class="premium-card h-100">
                    <div class="card-icon bg-orange-soft text-orange">
                        <i class="fas fa-university"></i>
                    </div>
                    <h5 class="card-title font-weight-bolder text-dark mb-2">Bank Branch</h5>
                    <p class="card-text text-muted small mb-0">Works associated with your bank branch.</p>
                </div>
            </a>
        </div>
        @endif

        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin') || auth()->user()->roles->contains('name', 'Accountant'))
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <a href="{{ route('account.index') }}" class="text-decoration-none module-link">
                <div class="premium-card h-100">
                    <div class="card-icon bg-danger-soft text-danger">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <h5 class="card-title font-weight-bolder text-dark mb-2">Account & Billing</h5>
                    <p class="card-text text-muted small mb-0">Manage billing, payments, and accounts.</p>
                </div>
            </a>
        </div>
        @endif

    </div>
</div>

<style>
/* Premium Dashboard UI */
:root {
    --bg-light: #eef2f6;
    --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -1px rgba(0, 0, 0, 0.04);
    --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

body {
    background-color: var(--bg-light) !important;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

.text-muted { color: #475569 !important; }

/* Grid Cards */
.module-link { display: block; height: 100%; }

.premium-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--card-shadow);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #d1d5db;
    position: relative;
    overflow: hidden;
}

.premium-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--card-shadow-hover);
    border-color: #cbd5e1;
}

.card-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 20px;
    transition: transform 0.3s ease;
}

.premium-card:hover .card-icon {
    transform: scale(1.1) rotate(5deg);
}

/* Specific Accent Borders */
.border-primary-left { border-left: 4px solid #0d6efd !important; }
.border-warning-left { border-left: 4px solid #ffc107 !important; }

/* Soft Color Palettes */
.bg-primary-soft { background-color: #cfe2ff; }
.text-primary { color: #084298 !important; }

.bg-success-soft { background-color: #d1e7dd; }
.text-success { color: #0f5132 !important; }

.bg-info-soft { background-color: #cff4fc; }
.text-info { color: #055160 !important; }

.bg-warning-soft { background-color: #fff3cd; }
.text-warning { color: #664d03 !important; }

.bg-danger-soft { background-color: #f8d7da; }
.text-danger { color: #842029 !important; }

.bg-secondary-soft { background-color: #e2e3e5; }
.text-secondary { color: #41464b !important; }

/* Custom Accents */
.bg-indigo-soft { background-color: #e0cffc; }
.text-indigo { color: #310560 !important; }

.bg-teal-soft { background-color: #d2f4ea; }
.text-teal { color: #0c4128 !important; }

.bg-purple-soft { background-color: #e2d9f3; }
.text-purple { color: #432874 !important; }

.bg-orange-soft { background-color: #fce3ce; }
.text-orange { color: #9e4a05 !important; }

</style>
@endsection