<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Employee') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Errors -->
                @if ($errors->any())
                    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.employees.update', $employee->id) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <x-input-label for="name" value="Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $employee->user->name) }}" required />
                    </div>

                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email', $employee->user->email) }}" required />
                    </div>

                    <div>
                        <x-input-label for="password" value="Password (leave blank to keep current)" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
                    </div>

                    <div>
                        <x-input-label for="employee_code" value="Employee Code" />
                        <x-text-input id="employee_code" name="employee_code" type="text" class="mt-1 block w-full" value="{{ old('employee_code', $employee->employee_code) }}" required />
                    </div>

                    <div>
                        <x-input-label for="designation" value="Designation" />
                        <x-text-input id="designation" name="designation" type="text" class="mt-1 block w-full" value="{{ old('designation', $employee->designation) }}" required />
                    </div>

                    <div>
                        <x-input-label for="location_id" value="Assigned Location" />
                        <select id="location_id" name="location_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ old('location_id', $employee->location_id) == $loc->id ? 'selected' : '' }}>
                                    {{ $loc->location_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="status" value="Employee Status" />
                        <select id="status" name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                            <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">Update</button>
                        <a href="{{ route('admin.employees.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
