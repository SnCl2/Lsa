@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
       
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Flat</h5>
                    <p class="card-text">Edit report for Flat.</p>
                    <a href="{{ route('reports.edit', ['id' => $reportId]) }}" class="btn btn-primary">Edit report</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Land and Buielding</h5>
                    <p class="card-text">Edit report for Land and Buielding.</p>
                    <a href="{{ route('report.lnb_edit', ['id' => $reportId]) }}" class="btn btn-primary">Edit report</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
