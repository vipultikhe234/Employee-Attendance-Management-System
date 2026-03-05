<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Attendance History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Filter Form -->
                <form action="{{ route('employee.history') }}" method="GET" class="flex items-end gap-4 mb-6">
                    <div>
                        <x-input-label for="start_date" value="Start Date" />
                        <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block" value="{{ request('start_date') }}" />
                    </div>
                    <div>
                        <x-input-label for="end_date" value="End Date" />
                        <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block" value="{{ request('end_date') }}" />
                    </div>
                    <div>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">Filter</button>
                    </div>
                </form>

                <!-- Table -->
                <div class="overflow-x-auto relative shadow sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3 px-6">Date</th>
                                <th scope="col" class="py-3 px-6">Check In</th>
                                <th scope="col" class="py-3 px-6">Check Out</th>
                                <th scope="col" class="py-3 px-6">Status</th>
                                <th scope="col" class="py-3 px-6">Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="py-4 px-6">{{ \Carbon\Carbon::parse($attendance->created_at)->format('Y-m-d') }}</td>
                                    <td class="py-4 px-6">{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i:s') : '-' }}</td>
                                    <td class="py-4 px-6">{{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i:s') : '-' }}</td>
                                    <td class="py-4 px-6 text-{{ $attendance->status === 'late' ? 'red' : ($attendance->status === 'early' ? 'yellow' : 'green') }}-600 font-semibold">{{ ucfirst($attendance->status) }}</td>
                                    <td class="py-4 px-6">{{ $attendance->reason ?? 'None' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 px-6 text-center text-gray-500">No attendance records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
