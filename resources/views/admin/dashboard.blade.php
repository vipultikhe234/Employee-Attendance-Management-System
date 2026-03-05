<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Employees -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col items-center justify-center">
                    <h3 class="text-lg font-bold text-gray-700">Total Employees</h3>
                    <p class="text-4xl font-extrabold text-indigo-600 mt-2">{{ $totalEmployees }}</p>
                </div>
                
                <!-- Present Today -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col items-center justify-center">
                    <h3 class="text-lg font-bold text-gray-700">Present Today</h3>
                    <p class="text-4xl font-extrabold text-green-500 mt-2">{{ $presentCount }}</p>
                </div>

                <!-- Absent Today -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col items-center justify-center">
                    <h3 class="text-lg font-bold text-gray-700">Absent Today</h3>
                    <p class="text-4xl font-extrabold text-red-500 mt-2">{{ $absentCount }}</p>
                </div>

                <!-- Late Today -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col items-center justify-center">
                    <h3 class="text-lg font-bold text-gray-700">Late Today</h3>
                    <p class="text-4xl font-extrabold text-yellow-500 mt-2">{{ $lateCount }}</p>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-4">
                <h3 class="text-xl font-bold border-b pb-2 mb-4">Quick Links</h3>
                <div class="flex gap-4">
                    <a href="{{ route('admin.employees.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">Manage Employees</a>
                    <a href="{{ route('admin.locations.index') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Manage Locations</a>
                    <a href="{{ route('admin.attendances.index') }}" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition">Attendance Monitor</a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
