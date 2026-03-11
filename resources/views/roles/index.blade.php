@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
    <h2 class="text-2xl font-semibold mb-4">Roles</h2>

    <a href="{{ route('roles.create') }}" class="mb-4 inline-block px-4 py-2 bg-blue-500  font-semibold rounded-md shadow-md hover:bg-blue-600 transition">
        + Create New Role
    </a>

    <table class="w-full border-collapse border border-gray-300 mt-4">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
                <tr class="border border-gray-300 hover:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2">{{ $role->name }}</td>
                    <td class="border border-gray-300 px-4 py-2 flex space-x-2">
                        <a href="{{ route('roles.edit', $role->id) }}" class="px-3 py-1 bg-yellow-500  rounded-md shadow-md hover:bg-yellow-600 transition">
                            Edit
                        </a>
                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-600  rounded-md shadow-md hover:bg-red-700 transition">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
