@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card mb-6">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b">
            <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                <i class="fa-solid fa-location-dot text-xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold">Office Location & Radius</h3>
                <p class="text-gray-500 text-sm">Configure where employees can check-in/out.</p>
            </div>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="form-group">
                    <label class="form-label font-semibold">Allowed Radius (Meters)</label>
                    <div class="relative">
                        <input type="number" name="allowed_radius_meters" class="form-control pl-10" value="{{ $settings['allowed_radius_meters']->value ?? 500 }}">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-solid fa-ruler-horizontal"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Maximum distance from office coordinates.</p>
                </div>

                <div class="form-group">
                    <label class="form-label font-semibold">Working Hours</label>
                    <div class="flex items-center gap-2">
                        <input type="time" name="work_start_time" class="form-control" value="{{ $settings['work_start_time']->value ?? '08:00' }}">
                        <span class="text-gray-400">to</span>
                        <input type="time" name="work_end_time" class="form-control" value="{{ $settings['work_end_time']->value ?? '17:00' }}">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="form-group">
                    <label class="form-label font-semibold">Latitude</label>
                    <input type="text" id="lat" name="office_latitude" class="form-control" value="{{ $settings['office_latitude']->value ?? '-6.200000' }}">
                </div>
                <div class="form-group">
                    <label class="form-label font-semibold">Longitude</label>
                    <input type="text" id="lng" name="office_longitude" class="form-control" value="{{ $settings['office_longitude']->value ?? '106.816666' }}">
                </div>
            </div>

            <!-- Map Picker -->
            <div class="mb-6 rounded-lg overflow-hidden border border-gray-200">
                <div id="settings-map" style="height: 400px; width: 100%;"></div>
                <p class="text-sm text-gray-500 p-2 bg-gray-50 text-center"><i class="fa-solid fa-info-circle mr-1"></i> Drag marker to set office location</p>
            </div>

            <div class="flex justify-end pt-4 border-t">
                <button type="submit" class="btn btn-primary px-6 py-2.5">
                    <i class="fa-solid fa-save mr-2"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var lat = parseFloat(document.getElementById('lat').value) || -6.200000;
        var lng = parseFloat(document.getElementById('lng').value) || 106.816666;

        var map = L.map('settings-map').setView([lat, lng], 13);
        
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var marker = L.marker([lat, lng], {draggable: true}).addTo(map);

        marker.on('dragend', function(e) {
            var position = marker.getLatLng();
            document.getElementById('lat').value = position.lat.toFixed(6);
            document.getElementById('lng').value = position.lng.toFixed(6);
        });

        // Click map to move marker
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById('lat').value = e.latlng.lat.toFixed(6);
            document.getElementById('lng').value = e.latlng.lng.toFixed(6);
        });

        // Update map when inputs change
        function updateMap() {
            var inputLat = parseFloat(document.getElementById('lat').value);
            var inputLng = parseFloat(document.getElementById('lng').value);
            if (!isNaN(inputLat) && !isNaN(inputLng)) {
                var newLatLng = new L.LatLng(inputLat, inputLng);
                marker.setLatLng(newLatLng);
                map.panTo(newLatLng);
            }
        }

        document.getElementById('lat').addEventListener('change', updateMap);
        document.getElementById('lng').addEventListener('change', updateMap);
    });
</script>
@endsection
