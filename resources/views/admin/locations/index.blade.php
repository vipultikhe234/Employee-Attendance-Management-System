<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Locations') }}
            </h2>
            <a href="{{ route('admin.locations.create') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">+ Add Location</a>
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
                            <th scope="col" class="py-3 px-6">Location Name</th>
                            <th scope="col" class="py-3 px-6">Latitude</th>
                            <th scope="col" class="py-3 px-6">Longitude</th>
                            <th scope="col" class="py-3 px-6">Radius (m)</th>
                            <th scope="col" class="py-3 px-6">Work Schedule</th>
                            <th scope="col" class="py-3 px-6">Buffers</th>
                            <th scope="col" class="py-3 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($locations as $loc)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="py-4 px-6 font-semibold text-gray-900">{{ $loc->location_name }}</td>
                                <td class="py-4 px-6">{{ $loc->latitude }}</td>
                                <td class="py-4 px-6">{{ $loc->longitude }}</td>
                                <td class="py-4 px-6">{{ $loc->radius }}</td>
                                <td class="py-4 px-6">{{ \Carbon\Carbon::parse($loc->in_time)->format('H:i') }} to {{ \Carbon\Carbon::parse($loc->out_time)->format('H:i') }}</td>
                                <td class="py-4 px-6 whitespace-nowrap"><span class="text-green-600">Early: {{ $loc->early_buffer }}m</span> | <span class="text-red-500">Late: {{ $loc->late_buffer }}m</span></td>
                                <td class="py-4 px-6 text-right flex justify-end gap-2">
                                    <a href="{{ route('admin.locations.edit', $loc->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                                    <form action="{{ route('admin.locations.destroy', $loc->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
