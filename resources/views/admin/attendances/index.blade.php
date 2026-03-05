<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Attendance Monitor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <!-- Filter Form -->
                <form action="{{ route('admin.attendances.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    
                    <div>
                        <x-input-label for="start_date" value="Start Date" />
                        <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" value="{{ request('start_date') }}" />
                    </div>
                    
                    <div>
                        <x-input-label for="end_date" value="End Date" />
                        <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" value="{{ request('end_date') }}" />
                    </div>

                    <div>
                        <x-input-label for="employee_id" value="Employee" />
                        <select id="employee_id" name="employee_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            <option value="">All Employees</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->user->name }} ({{ $emp->employee_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="location_id" value="Location" />
                        <select id="location_id" name="location_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            <option value="">All Locations</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                                    {{ $loc->location_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 w-full transition">Filter</button>
                        <a href="{{ route('admin.attendances.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 w-full text-center transition">Clear</a>
                    </div>
                </form>
            </div>

            <!-- Export Button -->
            <div class="mb-4 flex justify-end">
                <a href="{{ route('admin.attendances.export', request()->all()) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 flex items-center gap-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export to Excel
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Table -->
                <div class="overflow-x-auto relative shadow sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3 px-6">Date</th>
                                <th scope="col" class="py-3 px-6">Employee</th>
                                <th scope="col" class="py-3 px-6">Check In</th>
                                <th scope="col" class="py-3 px-6">Check Out</th>
                                <th scope="col" class="py-3 px-6">Status</th>
                                <th scope="col" class="py-3 px-6">Location</th>
                                <th scope="col" class="py-3 px-6">Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="py-4 px-6">{{ \Carbon\Carbon::parse($attendance->created_at)->format('Y-m-d') }}</td>
                                    <td class="py-4 px-6 font-semibold">{{ $attendance->employee->user->name }} ({{ $attendance->employee->employee_code }})</td>
                                    <td class="py-4 px-6">{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i:s') : '-' }}</td>
                                    <td class="py-4 px-6">{{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i:s') : '-' }}</td>
                                    <td class="py-4 px-6 text-{{ $attendance->status === 'late' ? 'red' : ($attendance->status === 'early' ? 'yellow' : 'green') }}-600 font-semibold">{{ ucfirst($attendance->status) }}</td>
                                    <td class="py-4 px-6">{{ $attendance->location ? $attendance->location->location_name : '-' }}</td>
                                    <td class="py-4 px-6">{{ $attendance->reason ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-4 px-6 text-center text-gray-500">No attendance records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $attendances->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
