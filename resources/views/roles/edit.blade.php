@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
    <h2 class="text-2xl font-semibold mb-4">Edit Role</h2>
    
    <form action="{{ route('roles.update', $role->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-gray-700 font-medium mb-1">Role Name</label>
            <input type="text" name="name" class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-300 focus:outline-none" value="{{ $role->name }}" required>
        </div>

        <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600  font-semibold py-2 px-4 rounded-lg">
            Update Role
        </button>
    </form>
</div>
@endsection
