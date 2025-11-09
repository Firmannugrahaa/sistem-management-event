<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Role Permission Matrix</h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Role Permission Matrix</h1>
            <p class="text-gray-600">Manage permissions for each role</p>
        </div>

        @if(session('status'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('superuser.permissions.update') }}">
            @csrf

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                @foreach($permissions as $permission)
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" title="{{ $permission->name }}">
                                    <div class="truncate max-w-[100px]">{{ Str::limit($permission->name, 12) }}</div>
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($roles as $role)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $role->name }}
                                </td>
                                @foreach($permissions as $permission)
                                <td class="px-3 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    <input
                                        type="checkbox"
                                        name="{{ $role->name }}[]"
                                        value="{{ $permission->id }}"
                                        {{ in_array($permission->id, $rolePermissions[$role->name] ?? []) ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    Save Permission Mapping
                </button>
            </div>
        </form>
    </div>
</x-app-layout>