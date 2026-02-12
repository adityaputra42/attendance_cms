@extends('layouts.admin')

@section('title', 'Attendance Details')

@section('content')
<div class="bg-white shadow rounded-xl p-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
        <h2 class="text-2xl font-bold text-gray-800">
            Attendance Details #{{ $attendance->id }}
        </h2>

        <a href="{{ route('admin.attendances.index') }}"
           class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back
        </a>
    </div>

    <!-- Top Info -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        <!-- Employee Info -->
        <div class="bg-gray-50 rounded-xl p-5 border">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">
                Employee Information
            </h3>

            <div class="flex items-center gap-4">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($attendance->user->name) }}&background=random"
                     class="w-16 h-16 rounded-full">

                <div>
                    <p class="text-xl font-semibold text-gray-800">
                        {{ $attendance->user->name }}
                    </p>
                    <p class="text-gray-600 text-sm">
                        {{ $attendance->user->email }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ $attendance->user->phone ?? 'No Phone Number' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="bg-gray-50 rounded-xl p-5 border">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">
                Summary
            </h3>

            <div class="grid grid-cols-2 gap-4 text-sm">

                <div>
                    <p class="text-gray-500">Date</p>
                    <p class="font-semibold text-gray-800">
                        {{ optional($attendance->attendance_date)->format('l, d M Y') }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Working Hours</p>
                    <p class="font-semibold text-blue-600">
                        {{ $attendance->getFormattedWorkDuration() ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Status</p>
                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                        {{ $attendance->status === 'checked_in'
                            ? 'bg-green-100 text-green-700'
                            : 'bg-gray-200 text-gray-700' }}">
                        {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                    </span>
                </div>

            </div>
        </div>
    </div>

    <!-- Check In & Out -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- CHECK IN -->
        <div class="border rounded-xl overflow-hidden">
            <div class="bg-green-50 px-5 py-3 border-b">
                <h4 class="text-green-700 font-semibold flex items-center gap-2">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    CHECK IN
                </h4>
            </div>

            <div class="p-5">

                <div class="mb-4">
                    <span class="text-3xl font-bold text-gray-800">
                        {{ optional($attendance->check_in_time)->format('H:i') ?? '-' }}
                    </span>
                    <span class="text-gray-500 ml-1">WIB</span>
                </div>

                <div class="mb-4">
                    <p class="text-xs text-gray-500 mb-1">Location Address</p>
                    <p class="text-sm bg-gray-50 p-3 rounded">
                        {{ $attendance->check_in_address ?? 'Address not available' }}
                    </p>
                </div>

                @if($attendance->check_in_photo)
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 mb-1">Check-in Photo</p>
                        <img src="{{ asset('storage/'.$attendance->check_in_photo) }}"
                             class="w-full h-48 object-cover rounded-lg border">
                    </div>
                @endif

                @if($attendance->check_in_latitude && $attendance->check_in_longitude)
                    <div id="map-in"
                         class="w-full h-52 rounded-lg border"></div>
                @endif

            </div>
        </div>

        <!-- CHECK OUT -->
        <div class="border rounded-xl overflow-hidden">
            <div class="bg-red-50 px-5 py-3 border-b">
                <h4 class="text-red-700 font-semibold flex items-center gap-2">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    CHECK OUT
                </h4>
            </div>

            <div class="p-5">

                @if($attendance->check_out_time)

                    <div class="mb-4">
                        <span class="text-3xl font-bold text-gray-800">
                            {{ optional($attendance->check_out_time)->format('H:i') }}
                        </span>
                        <span class="text-gray-500 ml-1">WIB</span>
                    </div>

                    <div class="mb-4">
                        <p class="text-xs text-gray-500 mb-1">Work Description</p>
                        <p class="text-sm bg-gray-50 p-3 rounded italic">
                            {{ $attendance->work_description ?? '-' }}
                        </p>
                    </div>

                    <div class="mb-4">
                        <p class="text-xs text-gray-500 mb-1">Location Address</p>
                        <p class="text-sm bg-gray-50 p-3 rounded">
                            {{ $attendance->check_out_address ?? 'Address not available' }}
                        </p>
                    </div>

                    @if($attendance->check_out_photo)
                        <div class="mb-4">
                            <p class="text-xs text-gray-500 mb-1">Check-out Photo</p>
                            <img src="{{ asset('storage/'.$attendance->check_out_photo) }}"
                                 class="w-full h-48 object-cover rounded-lg border">
                        </div>
                    @endif

                    @if($attendance->check_out_latitude && $attendance->check_out_longitude)
                        <div id="map-out"
                             class="w-full h-52 rounded-lg border"></div>
                    @endif

                @else
                    <div class="flex flex-col items-center justify-center text-gray-400 py-12">
                        <i class="fa-solid fa-hourglass-half text-4xl mb-3"></i>
                        <p>Not checked out yet</p>
                    </div>
                @endif

            </div>
        </div>

    </div>

</div>

<!-- Leaflet -->
<link rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    @if($attendance->check_in_latitude && $attendance->check_in_longitude)
        var mapIn = L.map('map-in')
            .setView([{{ $attendance->check_in_latitude }},
                      {{ $attendance->check_in_longitude }}], 15);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png')
            .addTo(mapIn);

        L.marker([{{ $attendance->check_in_latitude }},
                  {{ $attendance->check_in_longitude }}])
            .addTo(mapIn)
            .bindPopup('Check In Location');
    @endif

    @if($attendance->check_out_latitude && $attendance->check_out_longitude)
        var mapOut = L.map('map-out')
            .setView([{{ $attendance->check_out_latitude }},
                      {{ $attendance->check_out_longitude }}], 15);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png')
            .addTo(mapOut);

        L.marker([{{ $attendance->check_out_latitude }},
                  {{ $attendance->check_out_longitude }}])
            .addTo(mapOut)
            .bindPopup('Check Out Location');
    @endif

});
</script>
@endsection
