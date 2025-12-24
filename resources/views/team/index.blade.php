<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manage My Team') }}
            </h2>
            <x-breadcrumb :items="[
                ['name' => 'Manage Team & Vendor', 'url' => route('team-vendor.index')],
                ['name' => 'Team Members']
            ]"/>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <x-inline-alert type="success" :message="session('success')" class="mb-6" />
            @endif
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <x-primary-button tag="a" :href="route('team.create')" class="">
                            Add Team Member
                        </x-primary-button>
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 mt-6">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Username</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($teamMembers as $member)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $member->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $member->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $member->username }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @foreach($member->roles as $role)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $role->name }}
                                        </span>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button
                                            @click="window.dispatchEvent(new CustomEvent('open-confirmation-data', {
                                                detail: {
                                                    formId: 'delete-form-{{ $member->id }}',
                                                    title: 'Remove Team Member',
                                                    message: 'Are you sure you want to remove {{ $member->name }} from your team? This action cannot be undone.'
                                                }
                                            }))"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">
                                            Remove
                                        </button>

                                        <!-- Hidden delete form -->
                                        <form id="delete-form-{{ $member->id }}" action="{{ route('team.destroy', $member) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        You have not added any team members yet.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $teamMembers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>