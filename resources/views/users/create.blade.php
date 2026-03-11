@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Create New User</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Roles</label>
            <select name="roles[]" class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300" multiple required>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center">
            <input type="checkbox" name="can_login" value="1" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring focus:ring-blue-300">
            <label class="ml-2 text-sm text-gray-700">Allow Login</label>
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="bg-green-500 hover:bg-green-600  px-4 py-2 rounded-lg">Create User</button>
            <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Cancel</a>
        </div>
    </form>
</div>
@endsection
