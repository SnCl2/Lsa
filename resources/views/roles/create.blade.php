@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
    <h2 class="text-2xl font-semibold mb-4">Create Role</h2>
    
    <form action="{{ route('roles.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-gray-700 font-medium mb-1">Role Name</label>
            <input type="text" name="name" class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-300 focus:outline-none" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700  font-semibold py-2 px-4 rounded-lg">
            Create Role
        </button>
    </form>
</div>
@endsection
