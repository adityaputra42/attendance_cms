@extends('layouts.admin')

@section('title', 'Attendance Details')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h3>Details #{{ $attendance->id }}</h3>
        <a href="{{ route('admin.attendances.index') }}" class="btn btn-neutral"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <!-- Employee Info -->
        <div class="p-4 bg-gray-50 rounded-lg">
            <h4 class="text-lg font-bold mb-4">Employee Information</h4>
            <div class="flex items-center gap-4">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($attendance->user->name) }}&background=random" class="w-16 h-16 rounded-full">
                <div>
                    <h5 class="text-xl font-semibold">{{ $attendance->user->name }}</h5>
                    <p class="text-gray-600">{{ $attendance->user->email }}</p>
                    <p class="text-sm text-gray-500">{{ $attendance->user->phone ?? 'No Phone' }}</p>
                </div>
            </div>
        </div>

        <!-- Status Summary -->
        <div class="p-4 bg-gray-50 rounded-lg">
            <h4 class="text-lg font-bold mb-4">Summary</h4>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="block text-sm text-gray-500">Date</span>
                    <span class="text-lg font-semibold">{{ $attendance->attendance_date->format('l, d M Y') }}</span>
                </div>
                <div>
                    <span class="block text-sm text-gray-500">Working Hours</span>
                    <span class="text-lg font-semibold text-blue-600">{{ $attendance->getFormattedWorkDuration() ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-sm text-gray-500">Status</span>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $attendance->status == 'checked_in' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline & Location -->
    <div class="grid grid-cols-2 gap-6 mt-6">
        <!-- Check In -->
        <div class="border rounded-lg overflow-hidden">
            <div class="bg-green-50 px-4 py-3 border-b border-green-100">
                <h5 class="text-green-800 font-bold flex items-center gap-2">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i> CHECK IN
                </h5>
            </div>
            <div class="p-4">
                <div class="mb-4">
                    <span class="text-3xl font-bold text-gray-800">{{ $attendance->check_in_time->format('H:i') }}</span>
                    <span class="text-gray-500 ml-2">WIB</span>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Location Address</label>
                    <p class="text-sm text-gray-700 bg-gray-50 p-2 rounded">{{ $attendance->check_in_address ?? 'Address not available' }}</p>
                </div>

                @if($attendance->check_in_photo)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Check-in Photo</label>
                    <img src="{{ asset('storage/'.$attendance->check_in_photo) }}" class="w-full h-48 object-cover rounded-lg border">
                </div>
                @endif
                
                <!-- Map Container -->
                <div id="map-in" style="height: 200px; width: 100%; border-radius: 8px;"></div>
            </div>
        </div>

        <!-- Check Out -->
        <div class="border rounded-lg overflow-hidden">
            <div class="bg-red-50 px-4 py-3 border-b border-red-100">
                <h5 class="text-red-800 font-bold flex items-center gap-2">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> CHECK OUT
                </h5>
            </div>
            <div class="p-4">
                @if($attendance->check_out_time)
                    <div class="mb-4">
                        <span class="text-3xl font-bold text-gray-800">{{ $attendance->check_out_time->format('H:i') }}</span>
                        <span class="text-gray-500 ml-2">WIB</span>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Work Description</label>
                        <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded italic">"{{ $attendance->work_description ?? '-' }}"</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Location Address</label>
                        <p class="text-sm text-gray-700 bg-gray-50 p-2 rounded">{{ $attendance->check_out_address ?? 'Address not available' }}</p>
                    </div>

                    @if($attendance->check_out_photo)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Check-out Photo</label>
                        <img src="{{ asset('storage/'.$attendance->check_out_photo) }}" class="w-full h-48 object-cover rounded-lg border">
                    </div>
                    @endif

                    <!-- Map Container -->
                    <div id="map-out" style="height: 200px; width: 100%; border-radius: 8px;"></div>
                @else
                    <div class="flex flex-col items-center justify-center h-full text-gray-400 py-10">
                        <i class="fa-solid fa-hourglass-half text-4xl mb-2"></i>
                        <p>Not checked out yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Init Check-in Map
        var mapIn = L.map('map-in').setView([{{ $attendance->check_in_latitude }}, {{ $attendance->check_in_longitude }}], 15);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(mapIn);
        L.marker([{{ $attendance->check_in_latitude }}, {{ $attendance->check_in_longitude }}]).addTo(mapIn)
            .bindPopup('Check In Location').openPopup();

        // Init Check-out Map if exists
        @if($attendance->check_out_latitude && $attendance->check_out_longitude)
            var mapOut = L.map('map-out').setView([{{ $attendance->check_out_latitude }}, {{ $attendance->check_out_longitude }}], 15);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(mapOut);
            L.marker([{{ $attendance->check_out_latitude }}, {{ $attendance->check_out_longitude }}]).addTo(mapOut)
                .bindPopup('Check Out Location').openPopup();
        @endif
    });
</script>
@endsection
