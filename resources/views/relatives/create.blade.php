@extends('layouts.app')

@section('content')
<div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
<div class="container">
    <h3 class="my-4">Existing Relatives</h3>
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Relation</th>
                <th>Relative Name</th>
                <th>PAN Number</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($relatives as $relative)
                <tr>
                    <td>{{ $relative->name }}</td>
                    <td>{{ $relative->phone_number }}</td>
                    <td>{{ $relative->relation }}</td>
                    <td>{{ $relative->relative_name }}</td>
                    <td>{{ $relative->pan_number }}</td>
                    <td>{{ $relative->address }}</td>
                    <td>
                        <form action="{{ route('relatives.destroy', $relative->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="my-4">Add Relative for Work: {{ $work->id }}</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('relatives.store', $work->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Relation</label>
            <input type="text" name="relation" class="form-control" value="{{ old('relation') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Relative Name</label>
            <input type="text" name="relative_name" class="form-control" value="{{ old('relative_name') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">PAN Number</label>
            <input type="text" name="pan_number" class="form-control" value="{{ old('pan_number') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control">{{ old('address') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Add Relative</button>
    </form>


</div>
@endsection
