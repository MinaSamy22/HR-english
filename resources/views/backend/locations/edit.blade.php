@extends('backend.layouts.app')

@push('style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
@endpush

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1>Edit Location</h1>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Edit Location</h3>
                </div>

                <form method="post" action="{{ route('locations.update', $location->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $location->name) }}" required>
                        </div>

                        <div id="map" style="height: 500px;"></div>
                        <input type="hidden" name="polygon" id="polygonInput" value="{{ json_encode($location->polygon) }}">
                    </div>

                    <div class="card-footer">
                        <a href="{{ route('locations.index') }}" class="btn btn-default">Back</a>
                        <button type="submit" class="btn btn-primary float-right">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection

@section('script')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>

<script>
    var map = L.map('map').setView([30.0444, 31.2357], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    var drawControl = new L.Control.Draw({
        draw: {
            polygon: true,
            polyline: false,
            rectangle: true,
            circle: false,
            marker: false,
            circlemarker: false
        },
        edit: {
            featureGroup: drawnItems
        }
    });
    map.addControl(drawControl);

    // Load existing polygon
    var existingPolygon = @json($location->polygon);
    if (existingPolygon && existingPolygon.length > 0) {
        var latlngs = existingPolygon.map(function(p) { return [p.lat, p.lng]; });
        var polygon = L.polygon(latlngs, {color: 'blue'}).addTo(drawnItems);
        map.fitBounds(polygon.getBounds());
    }

    function savePolygon(layer) {
        var coords = layer.getLatLngs()[0].map(function(p) {
            return { lat: p.lat, lng: p.lng };
        });
        document.getElementById('polygonInput').value = JSON.stringify(coords);
    }

    map.on(L.Draw.Event.CREATED, function (event) {
        drawnItems.clearLayers();
        var layer = event.layer;
        drawnItems.addLayer(layer);
        savePolygon(layer);
    });

    map.on(L.Draw.Event.EDITED, function (event) {
        event.layers.eachLayer(function (layer) {
            savePolygon(layer);
        });
    });
</script>
@endsection
