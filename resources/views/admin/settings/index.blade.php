@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="bg-base-100 p-6 rounded-xl shadow">

        <!-- Header -->
        <div class="flex items-center gap-4 mb-8 pb-4 border-b">
            <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                <i class="fa-solid fa-location-dot text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold">Office Location & Radius</h2>
                <p class="text-sm text-gray-500">
                    Configure where employees can check-in/out.
                </p>
            </div>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf

            <!-- Radius & Working Hours -->
            <div class="grid md:grid-cols-2 gap-6 mb-8">

                <!-- Allowed Radius -->
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">
                            Allowed Radius (Meters)
                        </span>
                    </label>

                    <div class="relative">
                        <input type="number"
                               name="allowed_radius_meters"
                               class="input input-bordered w-full pl-10"
                               value="{{ $settings['allowed_radius_meters']->value ?? 500 }}">

                        <div class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <i class="fa-solid fa-ruler-horizontal"></i>
                        </div>
                    </div>

                    <p class="text-xs text-gray-500 mt-1">
                        Maximum distance from office coordinates.
                    </p>
                </div>

                <!-- Working Hours -->
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">
                            Working Hours
                        </span>
                    </label>

                    <div class="flex items-center gap-3">
                        <input type="time"
                               name="work_start_time"
                               class="input input-bordered w-full"
                               value="{{ $settings['work_start_time']->value ?? '08:00' }}">

                        <span class="text-gray-400">to</span>

                        <input type="time"
                               name="work_end_time"
                               class="input input-bordered w-full"
                               value="{{ $settings['work_end_time']->value ?? '17:00' }}">
                    </div>
                </div>

            </div>

            <!-- Coordinates -->
            <div class="grid md:grid-cols-2 gap-6 mb-6">

                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Latitude</span>
                    </label>
                    <input type="text"
                           id="lat"
                           name="office_latitude"
                           class="input input-bordered w-full"
                           value="{{ $settings['office_latitude']->value ?? '-6.200000' }}">
                </div>

                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Longitude</span>
                    </label>
                    <input type="text"
                           id="lng"
                           name="office_longitude"
                           class="input input-bordered w-full"
                           value="{{ $settings['office_longitude']->value ?? '106.816666' }}">
                </div>

            </div>

            <!-- Map Picker -->
            <div class="mb-8 rounded-xl overflow-hidden border">
                <div id="settings-map" class="w-full h-100"></div>

                <div class="text-sm text-gray-500 text-center py-2 bg-gray-50">
                    <i class="fa-solid fa-info-circle mr-1"></i>
                    Drag marker or click map to set office location
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end pt-6 border-t">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save mr-2"></i>
                    Save Changes
                </button>
            </div>

        </form>

    </div>

</div>

<!-- Leaflet -->
<link rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      crossorigin=""/>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    let latInput = document.getElementById('lat');
    let lngInput = document.getElementById('lng');

    let lat = parseFloat(latInput.value) || -6.200000;
    let lng = parseFloat(lngInput.value) || 106.816666;

    let map = L.map('settings-map').setView([lat, lng], 13);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    let marker = L.marker([lat, lng], { draggable: true }).addTo(map);

    function updateInputs(position) {
        latInput.value = position.lat.toFixed(6);
        lngInput.value = position.lng.toFixed(6);
    }

    marker.on('dragend', function() {
        updateInputs(marker.getLatLng());
    });

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateInputs(e.latlng);
    });

    function updateMapFromInput() {
        let newLat = parseFloat(latInput.value);
        let newLng = parseFloat(lngInput.value);

        if (!isNaN(newLat) && !isNaN(newLng)) {
            let newLatLng = new L.LatLng(newLat, newLng);
            marker.setLatLng(newLatLng);
            map.panTo(newLatLng);
        }
    }

    latInput.addEventListener('change', updateMapFromInput);
    lngInput.addEventListener('change', updateMapFromInput);
});
</script>
@endsection

