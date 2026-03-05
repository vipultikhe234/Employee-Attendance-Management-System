<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Employees') }}
            </h2>
            <a href="{{ route('admin.employees.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">+ Add Employee</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                 @if(session('success'))
                    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                      {{ session('success') }}
                    </div>
                @endif
                
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3 px-6">Emp Code</th>
                            <th scope="col" class="py-3 px-6">Name</th>
                            <th scope="col" class="py-3 px-6">Email</th>
                            <th scope="col" class="py-3 px-6">Designation</th>
                            <th scope="col" class="py-3 px-6">Location</th>
                            <th scope="col" class="py-3 px-6">Status</th>
                            <th scope="col" class="py-3 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $emp)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="py-4 px-6">{{ $emp->employee_code }}</td>
                                <td class="py-4 px-6 font-semibold text-gray-900">{{ $emp->user->name }}</td>
                                <td class="py-4 px-6">{{ $emp->user->email }}</td>
                                <td class="py-4 px-6">{{ $emp->designation }}</td>
                                <td class="py-4 px-6">{{ $emp->location ? $emp->location->location_name : 'NA' }}</td>
                                <td class="py-4 px-6">
                                    @if($emp->status === 'active')
                                        <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Active</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Inactive</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right flex justify-end gap-2">
                                    <a href="{{ route('admin.employees.edit', $emp->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                                    <form action="{{ route('admin.employees.destroy', $emp->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
