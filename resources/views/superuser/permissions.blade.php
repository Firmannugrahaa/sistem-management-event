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

        @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('superuser.permissions.update') }}" id="permissions-form">
            @csrf

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                @foreach($permissions as $permission)
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" title="{{ $permission->name }}">
                                    <div class="truncate max-w-[100px]" data-tooltip="{{ $permission->name }}">{{ Str::limit($permission->name, 12) }}</div>
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
                                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 permission-checkbox">
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    Select permissions for each role. Changes will take effect immediately after saving.
                </div>
                <div class="flex space-x-3">
                    <button type="button" id="select-all" class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        Select All
                    </button>
                    <button type="button" id="clear-all" class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        Clear All
                    </button>
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        Save Permission Mapping
                    </button>
                </div>
            </div>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const checkboxes = document.querySelectorAll('.permission-checkbox');
                const selectAllBtn = document.getElementById('select-all');
                const clearAllBtn = document.getElementById('clear-all');

                // Select all permissions
                selectAllBtn.addEventListener('click', function() {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = true;
                    });
                });

                // Clear all permissions
                clearAllBtn.addEventListener('click', function() {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                });

                // Add hover tooltips for permission names
                const tooltipElements = document.querySelectorAll('[data-tooltip]');
                tooltipElements.forEach(element => {
                    element.addEventListener('mouseenter', function() {
                        const tooltip = document.createElement('div');
                        tooltip.className = 'absolute bg-gray-800 text-white text-xs rounded py-1 px-2 z-50';
                        tooltip.textContent = this.getAttribute('data-tooltip');
                        tooltip.style.top = (this.getBoundingClientRect().bottom + window.scrollY + 5) + 'px';
                        tooltip.style.left = (this.getBoundingClientRect().left + window.scrollX) + 'px';
                        tooltip.id = 'tooltip';
                        document.body.appendChild(tooltip);
                    });

                    element.addEventListener('mouseleave', function() {
                        const existingTooltip = document.getElementById('tooltip');
                        if (existingTooltip) {
                            existingTooltip.remove();
                        }
                    });
                });
            });
        </script>
    </div>
</x-app-layout>