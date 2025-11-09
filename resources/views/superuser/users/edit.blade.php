@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Edit User</h1>
            <a href="{{ route('superuser.users.index') }}" class="text-blue-500 hover:text-blue-700">
                ← Back to Users
            </a>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <form method="POST" action="{{ route('superuser.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="py-2 px-3 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md @error('name') border-red-500 @enderror">
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <div class="mt-1">
                            <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required class="py-2 px-3 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md @error('username') border-red-500 @enderror">
                        </div>
                        @error('username')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-6">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <div class="mt-1">
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="py-2 px-3 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md @error('email') border-red-500 @enderror">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password (optional)</label>
                        <div class="mt-1">
                            <input type="password" name="password" id="password" class="py-2 px-3 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md @error('password') border-red-500 @enderror">
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Leave blank to keep current password</p>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <div class="mt-1">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="py-2 px-3 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <div class="mt-1">
                            <select id="role" name="role" required class="py-2 px-3 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md @error('role') border-red-500 @enderror">
                                <option value="">Select a role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('role')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition duration-200">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection