@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-semibold mb-4">Edit User</h2>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-700">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block text-gray-700 font-medium mb-1">Name</label>
            <input type="text" name="name" value="{{ $user->name }}" 
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ $user->email }}" 
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Roles</label>
            <select name="roles[]" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" multiple required>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" @if($user->roles->contains($role)) selected @endif>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center">
            <input type="checkbox" name="can_login" value="1" id="can_login"
                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                @if(optional($user->userRoleRelations->first())->can_login) checked @endif>
            <label for="can_login" class="ml-2 text-gray-700">Allow Login</label>
        </div>

        <div class="flex space-x-3">
            <button type="submit" class="px-4 py-2 bg-yellow-500  rounded-md shadow-md hover:bg-yellow-600 transition">
                Update User
            </button>
            <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md shadow-md hover:bg-gray-600 transition">
                Cancel
            </a>
        </div>
    </form>

    <!-- Password Reset Section -->
    <div class="mt-8 p-6 bg-gray-50 rounded-lg border-l-4 border-blue-500">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-key text-blue-500"></i> Password Reset
        </h3>
        <p class="text-gray-600 mb-4">Set a new password for this user.</p>
        
        <form action="{{ route('users.reset-password', $user->id) }}" method="POST" id="password-reset-form">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="new_password" class="block text-gray-700 font-medium mb-1">New Password</label>
                    <input type="password" name="new_password" id="new_password" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" 
                        required minlength="6" placeholder="Enter new password">
                </div>
                <div>
                    <label for="confirm_password" class="block text-gray-700 font-medium mb-1">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" 
                        required minlength="6" placeholder="Confirm new password">
                </div>
                <div class="flex items-center space-x-4">
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md shadow-md hover:bg-red-700 transition">
                        <i class="fas fa-key"></i> Reset Password
                    </button>
                    <span class="text-sm text-gray-500">
                        Password will be updated immediately
                    </span>
                </div>
            </div>
        </form>
        
        <!-- Debug Info -->
        <div class="mt-4 p-3 bg-yellow-100 border border-yellow-400 rounded">
            <h4 class="font-semibold text-yellow-800">Debug Info:</h4>
            <p><strong>Form Action:</strong> {{ route('users.reset-password', $user->id) }}</p>
            <p><strong>User ID:</strong> {{ $user->id }}</p>
            <p><strong>Method:</strong> POST</p>
            <p><strong>CSRF Token:</strong> {{ csrf_token() }}</p>
            <p><strong>Current User:</strong> {{ auth()->user()->name ?? 'Not authenticated' }}</p>
            <p><strong>User Roles:</strong> {{ auth()->user()->roles->pluck('name')->join(', ') ?? 'No roles' }}</p>
        </div>
        
        <!-- Test Form -->
        <div class="mt-4 p-3 bg-blue-100 border border-blue-400 rounded">
            <h4 class="font-semibold text-blue-800">Test Form (Simple):</h4>
            <form action="{{ route('users.reset-password', $user->id) }}" method="POST" onsubmit="return confirm('Test password reset?')">
                @csrf
                <input type="hidden" name="new_password" value="test123">
                <input type="hidden" name="confirm_password" value="test123">
                <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded">Test Reset</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');
    const form = document.getElementById('password-reset-form');
    
    if (newPassword && confirmPassword && form) {
        function validatePasswords() {
            if (confirmPassword.value && newPassword.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Passwords do not match');
            } else {
                confirmPassword.setCustomValidity('');
            }
        }
        
        newPassword.addEventListener('input', validatePasswords);
        confirmPassword.addEventListener('input', validatePasswords);
        
        form.addEventListener('submit', function(e) {
            console.log('Form submitted');
            console.log('New password:', newPassword.value);
            console.log('Confirm password:', confirmPassword.value);
            
            if (newPassword.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            
            if (newPassword.value.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long!');
                return false;
            }
        });
    } else {
        console.error('Password reset form elements not found');
    }
});
</script>
@endsection
