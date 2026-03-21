@extends('layouts.app')

@section('content')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>


<div class="container mt-4">
    <div class="row">
        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin'))
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Work Dashboard</h5>
                    <p class="card-text">All KPIs, Graphes and Charts.</p>
                    <a href="{{ route('works.dashboard') }}" class="btn btn-primary">Go to Create Work</a>
                </div>
            </div>
            
        </div>
        @endif
        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin') || auth()->user()->roles->contains('name', 'In-Charge'))
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Create Work</h5>
                    <p class="card-text">Create a new work entry.</p>
                    <a href="{{ route('works.create') }}" class="btn btn-primary">Go to Create Work</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">My Works</h5>
                    <p class="card-text">View your works.</p>
                    <a href="{{ route('works.myWorks') }}" class="btn btn-primary">Go to My Works</a>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin') )
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">All Works</h5>
                    <p class="card-text">View all works.</p>
                    <a href="{{ route('works.index') }}" class="btn btn-primary">Go to All Works</a>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin') || auth()->user()->roles->contains('name', 'Reporter'))
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Reporter Works</h5>
                    <p class="card-text">View all works assigned as a reporter.</p>
                    <a href="{{ route('works.reporter') }}" class="btn btn-primary">Go to Reporter Works</a>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin') || auth()->user()->roles->contains('name', 'Surveyor'))
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Surveyor Works</h5>
                    <p class="card-text">View all works assigned as a surveyor.</p>
                    <a href="{{ route('works.surveyor') }}" class="btn btn-primary">Go to Surveyor Works</a>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin') || auth()->user()->roles->contains('name', 'Checker'))
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Works for Checking </h5>
                    <p class="card-text">View all works assigned as a surveyor.</p>
                    <a href="{{ route('works.checking') }}" class="btn btn-primary">Go to Checking Works</a>
                </div>
            </div>
        </div>
        @endif
        
        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin') || auth()->user()->roles->contains('name', 'Delivery Person'))
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Works for Delivery</h5>
                    <p class="card-text">View all works assigned as a Delivery.</p>
                    <a href="{{ route('works.delivery') }}" class="btn btn-primary">Go to Checking Works</a>
                </div>
            </div>
        </div>
        @endif
        
        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin') || auth()->user()->roles->contains('name', 'Bank Branch'))
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Bank Branch </h5>
                    <p class="card-text">View all works associated with your bank branch.</p>
                    <a href="{{ route('works.bankBranch') }}" class="btn btn-primary">Go to Bank Branch Works</a>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->roles->contains('name', 'Super Admin') || auth()->user()->roles->contains('name', 'KKDA Admin') || auth()->user()->roles->contains('name', 'Accountant'))
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Account & Billing</h5>
                    <p class="card-text">Manage billing for positive works. Pending, billed, and payment done.</p>
                    <a href="{{ route('account.index') }}" class="btn btn-primary">Go to Account</a>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection