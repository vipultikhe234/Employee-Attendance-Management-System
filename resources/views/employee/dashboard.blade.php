<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Employee Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                  {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                  {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-2xl mb-4 font-bold text-gray-800">Welcome, {{ $employee->user->name }}</h3>
                <p class="mb-2 text-gray-600">Assigned Location: <span class="font-semibold">{{ $employee->location->location_name ?? 'None' }}</span></p>

                @if($employee->location)
                    <div class="mt-6 mb-8 bg-gradient-to-br from-indigo-50 to-white p-6 rounded-2xl border border-indigo-100 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10">
                            <svg class="w-24 h-24 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10">
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-[0.2em]">Shift Schedule</p>
                                <div class="flex items-center gap-2">
                                    <span class="text-lg font-black text-gray-800">
                                        {{ \Carbon\Carbon::parse($employee->location->in_time)->format('h:i A') }}
                                    </span>
                                    <span class="text-indigo-300 font-bold">→</span>
                                    <span class="text-lg font-black text-gray-800">
                                        {{ \Carbon\Carbon::parse($employee->location->out_time)->format('h:i A') }}
                                    </span>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-[0.2em]">Buffer Limits</p>
                                <div class="flex gap-4 bg-white/60 p-2 px-3 rounded-xl border border-indigo-50 w-fit backdrop-blur-sm">
                                    <div class="flex flex-col">
                                        <span class="text-[9px] text-green-600 font-black leading-none">EARLY</span>
                                        <span class="text-base font-black text-gray-700">{{ $employee->location->early_buffer }} <span class="text-[10px] font-normal">min</span></span>
                                    </div>
                                    <div class="w-px h-8 bg-indigo-100 self-center"></div>
                                    <div class="flex flex-col">
                                        <span class="text-[9px] text-red-500 font-black leading-none">LATE</span>
                                        <span class="text-base font-black text-gray-700">{{ $employee->location->late_buffer }} <span class="text-[10px] font-normal">min</span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-1 md:border-l md:pl-8 border-indigo-100/50">
                                <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-[0.2em]">Real-time Clock</p>
                                <p class="text-3xl font-black text-indigo-600 tabular-nums drop-shadow-sm" id="live-time">{{ \Carbon\Carbon::now()->format('h:i:s A') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mt-8 border-t pt-6">
                    <h4 class="text-xl font-semibold mb-4 text-gray-700">Today's Attendance</h4>
                    
                    @if(!$todayAuth)
                        <!-- Check In form -->
                        <form action="{{ route('employee.clock_in') }}" method="POST" id="clockInForm" class="space-y-4 max-w-md">
                            @csrf
                            <input type="hidden" name="latitude" id="lat">
                            <input type="hidden" name="longitude" id="lon">
                            
                            <div>
                                <x-input-label for="reason" value="Reason (Mandatory if Early/Late)" />
                                <x-text-input id="reason" name="reason" type="text" class="mt-1 block w-full" value="{{ old('reason') }}" />
                            </div>

                            <button type="button" onclick="getLocationAndSubmit('clockInForm')" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded hover:bg-indigo-700 transition w-full">
                                Clock In
                            </button>
                        </form>
                    @elseif(is_null($todayAuth->check_out_time))
                        <!-- Check Out form -->
                        <div class="text-green-600 font-bold mb-4 flex items-center gap-2">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                             Checked in today at {{ \Carbon\Carbon::parse($todayAuth->check_in_time)->format('h:i A') }} (Status: {{ ucfirst($todayAuth->status) }})
                        </div>
                        <form action="{{ route('employee.clock_out') }}" method="POST" id="clockOutForm" class="max-w-md">
                            @csrf
                            <input type="hidden" name="latitude" id="out_lat">
                            <input type="hidden" name="longitude" id="out_lon">

                            <button type="button" onclick="getLocationAndSubmit('clockOutForm')" class="px-6 py-2 bg-red-600 text-white font-bold rounded hover:bg-red-700 transition w-full">
                                Clock Out
                            </button>
                        </form>
                    @else
                        <!-- Already Checked Out -->
                        <div class="text-gray-600 font-bold">
                            You have completed your attendance for today.
                            <br>
                            Check In: {{ \Carbon\Carbon::parse($todayAuth->check_in_time)->format('h:i A') }}
                            <br>
                            Check Out: {{ \Carbon\Carbon::parse($todayAuth->check_out_time)->format('h:i A') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                 <h4 class="text-lg font-semibold mb-4 text-gray-700">Quick Links</h4>
                 <a href="{{ route('employee.history') }}" class="text-indigo-600 hover:text-indigo-800 underline">View Attendance History</a>
            </div>
        </div>
    </div>

    <!-- JS Scripts -->
    <script>
        // Live Clock Update
        function updateTime() {
            const timeDisplay = document.getElementById('live-time');
            if (timeDisplay) {
                const now = new Date();
                timeDisplay.innerText = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
            }
        }
        setInterval(updateTime, 1000);

        // Geolocation Submission
        function getLocationAndSubmit(formId) {
            let btn = document.querySelector('#' + formId + ' button');
            if (btn) {
                btn.innerHTML = 'Processing...';
                btn.disabled = true;
            }

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        if (formId === 'clockInForm') {
                            document.getElementById('lat').value = position.coords.latitude;
                            document.getElementById('lon').value = position.coords.longitude;
                        } else {
                            document.getElementById('out_lat').value = position.coords.latitude;
                            document.getElementById('out_lon').value = position.coords.longitude;
                        }
                        
                        document.getElementById(formId).submit();
                    },
                    function(error) {
                        if (btn) {
                            btn.innerHTML = formId === 'clockInForm' ? 'Clock In' : 'Clock Out';
                            btn.disabled = false;
                        }
                        alert('Could not get your location. Please enable location services.');
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            } else {
                if (btn) {
                    btn.innerHTML = formId === 'clockInForm' ? 'Clock In' : 'Clock Out';
                    btn.disabled = false;
                }
                alert('Geolocation is not supported by your browser.');
            }
        }
    </script>
</x-app-layout>
