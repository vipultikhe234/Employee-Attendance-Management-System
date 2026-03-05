<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Location') }}
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

                <form method="POST" action="{{ route('admin.locations.store') }}" class="space-y-4">
                    @csrf
                    
                    <div>
                        <x-input-label for="location_name" value="Location Name" />
                        <x-text-input id="location_name" name="location_name" type="text" class="mt-1 block w-full" value="{{ old('location_name') }}" required />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="latitude" value="Latitude" />
                            <x-text-input id="latitude" name="latitude" type="number" step="any" class="mt-1 block w-full" value="{{ old('latitude') }}" required />
                        </div>
                        <div>
                            <x-input-label for="longitude" value="Longitude" />
                            <x-text-input id="longitude" name="longitude" type="number" step="any" class="mt-1 block w-full" value="{{ old('longitude') }}" required />
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="radius" value="Radius (meters)" />
                            <x-text-input id="radius" name="radius" type="number" class="mt-1 block w-full" value="{{ old('radius', 100) }}" required />
                        </div>
                        <div>
                            <x-input-label for="in_time" value="In Time" />
                            <x-text-input id="in_time" name="in_time" type="time" class="mt-1 block w-full" value="{{ old('in_time', '09:00') }}" required />
                        </div>
                        <div>
                            <x-input-label for="out_time" value="Out Time" />
                            <x-text-input id="out_time" name="out_time" type="time" class="mt-1 block w-full" value="{{ old('out_time', '17:00') }}" required />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="early_buffer" value="Early Buffer (mins)" />
                            <x-text-input id="early_buffer" name="early_buffer" type="number" class="mt-1 block w-full" value="{{ old('early_buffer', 30) }}" required />
                        </div>
                        <div>
                            <x-input-label for="late_buffer" value="Late Buffer (mins)" />
                            <x-text-input id="late_buffer" name="late_buffer" type="number" class="mt-1 block w-full" value="{{ old('late_buffer', 20) }}" required />
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Save Location</button>
                        <a href="{{ route('admin.locations.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
