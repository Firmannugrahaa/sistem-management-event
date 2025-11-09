@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Create New Role</h1>
            <a href="{{ route('superuser.roles.index') }}" class="text-blue-500 hover:text-blue-700">
                ← Back to Roles
            </a>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <form method="POST" action="{{ route('superuser.roles.store') }}">
                @csrf

                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700">Role Name</label>
                    <div class="mt-1">
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="py-2 px-3 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md @error('name') border-red-500 @enderror">
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Permissions</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($permissions as $permission)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2">{{ $permission->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                @error('permissions')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div class="mt-8">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition duration-200">
                        Create Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection